@extends('layouts.app')

@section('title', 'Verificación de Administrador')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card auth-card bg-white p-4 p-md-5">
            <div class="text-center mb-4">
                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill px-3 py-2 mb-2">
                    Etapa 2 y 3 · Seguridad Administrador
                </span>
                <h3 class="fw-bold text-dark mb-1">Código de Verificación</h3>
                <p class="text-muted small mb-0">
                    Enviamos un código de 6 dígitos a <strong>{{ $email }}</strong>
                </p>
            </div>

            @if (session('status'))
                <div class="alert alert-info small" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger small" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="alert alert-light border text-center py-2 mb-3 small">
                Tiempo restante de vigencia:
                <strong id="countdown" class="text-danger">05:00</strong>
            </div>

            <form method="POST" action="{{ route('admin.2fa.verify') }}">
                @csrf

                <div class="mb-4">
                    <label for="code" class="form-label fw-semibold text-center d-block">
                        Ingresa el código de 6 dígitos
                    </label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        class="form-control code-input @error('code') is-invalid @enderror"
                        placeholder="000000"
                        required
                        autofocus
                    >
                </div>

                <button type="submit" id="btnVerify" class="btn btn-dark btn-lg w-100 fw-semibold">
                    Verificar y Entrar
                </button>
            </form>

            <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                <form method="POST" action="{{ route('admin.2fa.resend') }}">
                    @csrf
                    <button type="submit" class="btn btn-link btn-sm text-decoration-none p-0">
                        Reenviar código
                    </button>
                </form>

                <a href="{{ route('login') }}" class="btn btn-link btn-sm text-secondary text-decoration-none p-0">
                    Volver al login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    (function () {
        let totalSeconds = {{ (int) $remainingSeconds }};
        const countdownEl = document.getElementById('countdown');
        const btnVerify = document.getElementById('btnVerify');

        function renderTimer() {
            if (totalSeconds <= 0) {
                countdownEl.textContent = '00:00 (Expirado)';
                btnVerify.disabled = true;
                return;
            }
            const mins = String(Math.floor(totalSeconds / 60)).padStart(2, '0');
            const secs = String(totalSeconds % 60).padStart(2, '0');
            countdownEl.textContent = `${mins}:${secs}`;
            totalSeconds--;
        }

        renderTimer();
        setInterval(renderTimer, 1000);
    })();
</script>
@endpush