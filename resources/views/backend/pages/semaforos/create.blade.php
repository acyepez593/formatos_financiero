
@extends('backend.layouts.master')

@section('title')
Crear Semáforo - Admin Panel
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
                <h4 class="page-title pull-left">Crear Semáforo</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.estados.index') }}">Todos los Semaforos</a></li>
                    <li><span>Crear Semaforo</span></li>
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
                    <h4 class="header-title">Crear Nuevo Semáforo</h4>
                    @include('backend.layouts.partials.messages')
                    
                    <form action="{{ route('admin.semaforos.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="color">Color</label>
                                <input type="color" class="form-control" id="color" name="color" style="height: 40px;" placeholder="Color" required value="{{ old('color') }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="estado">Estado</label>
                                <input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" id="estado" name="estado" placeholder="Estado" required autofocus value="{{ old('estado') }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="rango_inicial">Rango inicial (días)</label>
                                <input type="text" class="form-control int-number" id="rango_inicial" name="rango_inicial" placeholder="Rango Inicial" required value="{{ old('rango_inicial') }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="rango_final">Rango final (días)</label>
                                <input type="text" class="form-control int-number" id="rango_final" name="rango_final" placeholder="Rango Final" required value="{{ old('rango_final') }}">
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

        //Restringir solo numeros enteros
        $(document).on("input", ".int-number", function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    })
</script>
@endsection