@extends('errors.errors_layout')

@section('title')
    403 - Access Denied
@endsection

@section('error-content')
    <h2>403</h2>
    <p>Acceso no autorizado o denegado</p>
    <hr>
    <p class="mt-2">
        {{ $exception->getMessage() }}
    </p>
    <a href="{{ route('admin.dashboard') }}">Regresar al Dashboard</a>
    <a href="{{ route('admin.login') }}">Iniciar sesión nuevamente !</a>
@endsection