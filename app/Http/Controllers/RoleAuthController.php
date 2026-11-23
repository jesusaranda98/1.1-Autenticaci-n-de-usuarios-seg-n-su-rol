<?php

namespace App\Http\Controllers;

use App\Mail\AdminVerificationCodeMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class RoleAuthController extends Controller
{
    private const CODE_TTL_MINUTES = 5;

    public function showLoginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $request->session()->forget(['2fa_pending_user_id', '2fa_issued_at']);

        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Las credenciales ingresadas no son válidas.']);
        }

        if ($user->isEmpleado()) {
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->route('empleado.dashboard')
                ->with('success', 'Bienvenido, ' . $user->name . '. Acceso concedido como Empleado.');
        }

        if ($user->isAdmin()) {
            return $this->issueAdminVerificationCode($request, $user);
        }

        return back()->withErrors(['email' => 'Rol de usuario no reconocido.']);
    }

    public function showVerifyForm(Request $request): View|RedirectResponse
    {
        $pendingUser = $this->getValidPendingAdmin($request);

        if (! $pendingUser) {
            $request->session()->forget(['2fa_pending_user_id', '2fa_issued_at']);

            return redirect()->route('login')
                ->withErrors(['email' => 'Debes iniciar sesión como administrador primero o tu sesión de verificación expiró.']);
        }

        $remainingSeconds = 0;
        if ($pendingUser->two_factor_expires_at && now()->lt($pendingUser->two_factor_expires_at)) {
            $remainingSeconds = now()->diffInSeconds($pendingUser->two_factor_expires_at);
        }

        return view('auth.verify-admin-code', [
            'email' => $pendingUser->email,
            'remainingSeconds' => $remainingSeconds,
        ]);
    }

    public function verifyCode(Request $request): RedirectResponse
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ], [
            'code.required' => 'Ingresa el código de 6 dígitos.',
            'code.digits' => 'El código debe contener exactamente 6 números.',
        ]);

        $pendingUser = $this->getValidPendingAdmin($request);

        if (! $pendingUser) {
            $request->session()->forget(['2fa_pending_user_id', '2fa_issued_at']);

            return redirect()->route('login')
                ->withErrors(['email' => 'El proceso de verificación no está activo o venció. Inicia sesión nuevamente.']);
        }

        if (
            ! $pendingUser->two_factor_code ||
            ! $pendingUser->two_factor_expires_at ||
            now()->gte($pendingUser->two_factor_expires_at)
        ) {
            $pendingUser->clearTwoFactorCode();
            $request->session()->forget(['2fa_pending_user_id', '2fa_issued_at']);

            return redirect()->route('login')
                ->withErrors(['email' => 'El código de verificación ha expirado (vigencia máxima de 5 minutos). Vuelve a iniciar sesión.']);
        }

        if (! hash_equals($pendingUser->two_factor_code, $request->input('code'))) {
            return back()->withErrors([
                'code' => 'El código ingresado es incorrecto.',
            ]);
        }

        $pendingUser->clearTwoFactorCode();
        $request->session()->forget(['2fa_pending_user_id', '2fa_issued_at']);

        Auth::login($pendingUser);
        $request->session()->regenerate();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Autenticación en 3 etapas completada. Bienvenido, ' . $pendingUser->name . '.');
    }

    public function resendCode(Request $request): RedirectResponse
    {
        $pendingUser = $this->getValidPendingAdmin($request);

        if (! $pendingUser) {
            $request->session()->forget(['2fa_pending_user_id', '2fa_issued_at']);

            return redirect()->route('login')
                ->withErrors(['email' => 'Tu sesión de verificación expiró. Inicia sesión de nuevo.']);
        }

        return $this->issueAdminVerificationCode($request, $pendingUser, true);
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['2fa_pending_user_id', '2fa_issued_at']);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')
            ->with('success', 'Has cerrado sesión correctamente.');
    }

    private function issueAdminVerificationCode(Request $request, User $admin, bool $isResend = false): RedirectResponse
    {
        $code = (string) random_int(100000, 999999);
        $expiresAt = now()->addMinutes(self::CODE_TTL_MINUTES);

        $admin->forceFill([
            'two_factor_code' => $code,
            'two_factor_expires_at' => $expiresAt,
        ])->save();

        $request->session()->put('2fa_pending_user_id', $admin->id);
        $request->session()->put('2fa_issued_at', now()->timestamp);

        Mail::to($admin->email)->send(
            new AdminVerificationCodeMail($admin, $code, self::CODE_TTL_MINUTES)
        );

        $message = $isResend
            ? 'Se envió un nuevo código de verificación a tu correo (vigente por 5 minutos).'
            : 'Credenciales válidas. Enviamos un código de 6 dígitos a tu correo (vigente por 5 minutos).';

        return redirect()->route('admin.2fa.challenge')
            ->with('status', $message);
    }

    private function getValidPendingAdmin(Request $request): ?User
    {
        $userId = $request->session()->get('2fa_pending_user_id');

        if (! $userId) {
            return null;
        }

        $user = User::find($userId);

        if (! $user || ! $user->isAdmin()) {
            return null;
        }

        return $user;
    }

    private function redirectByRole(User $user): RedirectResponse
    {
        return $user->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('empleado.dashboard');
    }
}