
@extends('backend.layouts.master')

@section('title')
Crear Estructura Liquidación Económica - Admin Panel
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
                <h4 class="page-title pull-left">Crear Estructura Liquidación Económica</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.estructurasLiquidacionEconomica.index') }}">Todas las Estructuras Liquidación Económica</a></li>
                    <li><span>Crear Estructura Liquidación Económica</span></li>
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
                    <h4 class="header-title">Crear Estructura Liquidación Económica</h4>
                    @include('backend.layouts.partials.messages')
                    
                    <form action="{{ route('admin.estructurasLiquidacionEconomica.store') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="nombre">Desripción</label>
                                <input type="text" onkeyup="this.value = this.value.toUpperCase();" class="form-control" id="descripcion" name="descripcion" placeholder="Descripcion" required autofocus value="{{ old('descripcion') }}">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="tipo_formato_id">Seleccione un Tipo Formato:</label>
                                <select id="tipo_formato_id" name="tipo_formato_id" class="form-control selectpicker @error('tipo_formato_id') is-invalid @enderror" data-live-search="true" required>
                                    <option value="">Seleccione un Tipo Formato</option>
                                    @foreach ($tiposFormato as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('tipo_formato_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="estructura">Estructura</label>
                                <div class="input-group mb-3">
                                    <textarea class="form-control @error('estructura') is-invalid @enderror" id="estructura" name="estructura" value="{{ old('estructura') }}" maxlength="5000" rows="3" required></textarea>
                                    @error('estructura')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Guardar</button>
                        <a href="{{ route('admin.estructurasLiquidacionEconomica.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancelar</a>
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