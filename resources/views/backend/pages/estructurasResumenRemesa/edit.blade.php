
@extends('backend.layouts.master')

@section('title')
Editar Estructura Resumen Remesa - Panel Estructura Resumen Remesa
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
                <h4 class="page-title pull-left">Editar Estructura Resumen Remesa</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.estructurasResumenRemesa.index') }}">Todos las Estructuras Resumen Remesa</a></li>
                    <li><span>Editar Estructura Resumen Remesa - {{ $estructuraResumenRemesa->descripcion }}</span></li>
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
                    <h4 class="header-title">Editar Estructura Resumen Remesa - {{ $estructuraResumenRemesa->descripcion }}</h4>
                    @include('backend.layouts.partials.messages')

                    <form action="{{ route('admin.estructurasResumenRemesa.update', $estructuraResumenRemesa->id) }}" method="POST">
                        @method('PUT')
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="nombre">Descripción</label>
                                <input type="text" class="form-control @error('descripcion') is-invalid @enderror" id="descripcion" name="descripcion" placeholder="Descripción" value="{{old('descripcion', $estructuraResumenRemesa->descripcion)}}" required autofocus>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="tipo_formato_id">Seleccione un Tipo Formato:</label>
                                <select id="tipo_formato_id" name="tipo_formato_id" class="form-control selectpicker @error('tipo_formato_id') is-invalid @enderror" data-live-search="true" required>
                                    <option value="">Seleccione un Tipo Formato</option>
                                    @foreach ($tiposFormato as $key => $value)
                                        <option value="{{ $key }}" {{ old('tipo_formato_id', $estructuraResumenRemesa->tipo_formato_id) == $key ? 'selected' : '' }}>{{ $value }}</option>
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
                                    <textarea class="form-control @error('estructura') is-invalid @enderror" id="estructura" name="estructura" maxlength="5000" rows="3" required>{{old('estructura', $estructuraResumenRemesa->estructura)}}</textarea>
                                    @error('estructura')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Guardar</button>
                        <a href="{{ route('admin.estructurasResumenRemesa.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancelar</a>
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