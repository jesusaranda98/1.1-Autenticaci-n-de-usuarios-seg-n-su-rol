@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card auth-card bg-white p-4 p-md-5">
            <div class="text-center mb-4">
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2 mb-2">
                    Práctica 1.1 · Control de Acceso
                </span>
                <h3 class="fw-bold text-dark mb-1">Iniciar Sesión</h3>
                <p class="text-muted small mb-0">
                    Acceso directo para <strong>Empleados</strong> · Verificación en 3 pasos para <strong>Administradores</strong>
                </p>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show small" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Correo electrónico</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                        placeholder="correo@empresa.com"
                        required
                        autofocus
                    >
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Contraseña</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-control form-control-lg @error('password') is-invalid @enderror"
                        placeholder="••••••••"
                        required
                    >
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small text-muted" for="remember">
                        Recordar sesión (empleados)
                    </label>
                </div>

                <button type="submit" class="btn btn-primary btn-lg w-100 fw-semibold shadow-sm">
                    Continuar
                </button>
            </form>

            <hr class="my-4">
            <div class="bg-light rounded-3 p-3 small text-muted">
                <div class="fw-semibold text-dark mb-1">Usuarios de prueba (Seeder):</div>
                <div><strong>Admin:</strong> admin@empresa.com / <code>Admin1234!</code></div>
                <div><strong>Empleado:</strong> empleado@empresa.com / <code>Empleado1234!</code></div>
            </div>
        </div>
    </div>
</div>
@endsection