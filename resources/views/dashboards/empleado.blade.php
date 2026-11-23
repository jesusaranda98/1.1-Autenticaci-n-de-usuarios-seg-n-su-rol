@extends('layouts.app')

@section('title', 'Panel Empleado')

@section('content')
<div class="card auth-card bg-white p-4 p-md-5">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 pb-3 mb-4 border-bottom">
        <div>
            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1 mb-2">
                Rol: Empleado · Acceso Directo
            </span>
            <h2 class="fw-bold text-dark mb-0">Panel de Empleado</h2>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn btn-outline-secondary">Cerrar sesión</button>
        </form>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="p-4 rounded-3 bg-light border">
        <h5 class="fw-bold">Bienvenido al espacio de trabajo</h5>
        <p class="mb-1"><strong>Nombre:</strong> {{ auth()->user()->name }}</p>
        <p class="mb-0"><strong>Correo:</strong> {{ auth()->user()->email }}</p>
    </div>
</div>
@endsection