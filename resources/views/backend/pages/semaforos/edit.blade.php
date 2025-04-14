
@extends('backend.layouts.master')

@section('title')
Editar Semaforo - Panel Semaforo
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />

<style>
    .form-check-label {
        text-transform: capitalize;
    }
</style>
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">Editar Semáforo</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.estados.index') }}">Todos los Semáforos</a></li>
                    <li><span>Editar Semaforo - {{ $semaforo->estado }}</span></li>
                </ul>
            </div>
        </div>
        <div class="col-sm-6 clearfix">
            @include('backend.layouts.partials.logout')
        </div>
    </div>
</div>
<!-- page title area end -->

<div class="main-content-inner">
    <div class="row">
        <!-- data table start -->
        <div class="col-12 mt-5">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Editar Semáforo - {{ $semaforo->estado }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.semaforos.update', $semaforo->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="nombre">Color</label>
                                <input type="color" class="form-control @error('color') is-invalid @enderror" id="color" name="color" style="height: 40px;" placeholder="Color" value="{{old('color', $semaforo->color)}}" required>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="nombre">Estado</label>
                                <input type="text" class="form-control @error('estado') is-invalid @enderror" id="estado" name="estado" placeholder="Estado" value="{{old('estado', $semaforo->estado)}}" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="nombre">Rango inicial (días)</label>
                                <input type="text" class="form-control @error('rango_inicial') is-invalid @enderror" id="rango_inicial" name="rango_inicial" placeholder="Rango inicial" value="{{old('rango_inicial', $semaforo->rango_inicial)}}" required>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="nombre">Rango final (días)</label>
                                <input type="text" class="form-control @error('rango_final') is-invalid @enderror" id="rango_final" name="rango_final" placeholder="Rango final" value="{{old('rango_final', $semaforo->rango_final)}}" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Guardar</button>
                        <a href="{{ route('admin.semaforos.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
        <!-- data table end -->

    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    })
</script>
@endsection