@extends('layouts.app')

@section('title', 'Panel Administrador')

@section('content')
<div class="card auth-card bg-white p-4 p-md-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pb-3 mb-4 border-bottom">
        <div>
            <span class="badge bg-danger-subtle text-danger border border-danger-subtle rounded-pill px-3 py-1 mb-2">
                Rol: Administrador · 2FA Verificado
            </span>
            <h2 class="fw-bold text-dark mb-0">Panel de Control Administrativo</h2>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Cerrar sesión</button>
        </form>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-3">
        <div class="col-md-6">
            <div class="p-4 rounded-3 bg-light border h-100">
                <h5 class="fw-bold">Usuario autenticado</h5>
                <p class="mb-1"><strong>Nombre:</strong> {{ auth()->user()->name }}</p>
                <p class="mb-0"><strong>Correo:</strong> {{ auth()->user()->email }}</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-4 rounded-3 bg-light border h-100">
                <h5 class="fw-bold">Seguridad aplicada</h5>
                <p class="small text-muted mb-0">
                    Autenticación en 3 etapas: credenciales, envío de código por correo con vigencia máxima de 5 minutos y validación en ruta segura protegida por sesión.
                </p>
            </div>
        </div>
    </div>
</div>
@endsection