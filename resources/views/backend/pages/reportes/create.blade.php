@extends('backend.layouts.master')

@section('title')
    {{ __('Reportes - Panel de Reporte') }}
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">

    <style>
        #overlay{	
            position: fixed;
            top: 0;
            z-index: 100;
            width: 100%;
            height:100%;
            display: none;
            background: rgba(0,0,0,0.6);
        }
        .cv-spinner {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;  
        }
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px #ddd solid;
            border-top: 4px #2e93e6 solid;
            border-radius: 50%;
            animation: sp-anime 0.8s infinite linear;
        }
        @keyframes sp-anime {
            100% { 
                transform: rotate(360deg); 
            }
        }
        .is-hide{
            display:none;
        }
    </style>
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">{{ __('Reportes') }}</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
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
                @include('backend.layouts.partials.messages')
                <div class="accordion" id="accordion">
                        <div class="card">
                            <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Búsqueda
                                </button>
                            </h5>
                            </div>
                            <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                                <div class="card-body">
                                    <form action="" method="POST" id="reporte">
                                        @csrf
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="tipo_reporte_search">Seleccione un Tipo de Reporte:</label>
                                                <select id="tipo_reporte_search" name="tipo_reporte_search" class="form-control selectpicker" data-live-search="true" required>
                                                    @foreach ($tiposReporte as $key => $value)
                                                        <option value="{{ $value['id'] }}" {{ $value['id'] == 'oficio' ? 'selected' : '' }}>{{ $value['name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-3 col-sm-6">
                                                <label for="fecha_registro_desde_search">Buscar por Fecha Registro Desde</label>
                                                <div class="datepicker date input-group">
                                                    <input type="text" class="form-control" id="fecha_registro_desde_search" name="fecha_registro_desde_search" placeholder="">
                                                    <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-sm-6">
                                                <label for="fecha_registro_hasta_search">Buscar por Fecha Registro Hasta</label>
                                                <div class="datepicker date input-group">
                                                    <input type="text" class="form-control" id="fecha_registro_hasta_search" name="fecha_registro_hasta_search" placeholder="">
                                                    <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="tipo_id_search">Buscar por Buscar por Tipo:</label>
                                                <select id="tipo_id_search" name="tipo_id_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="">Seleccione un Tipo</option>
                                                    @foreach ($tipos as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="ruc_search">Buscar por RUC</label>
                                                <input type="text" class="form-control" id="ruc_search" name="ruc_search" placeholder="">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="numero_establecimiento_search">Buscar por Número de Establecimiento</label>
                                                <input type="text" class="form-control" id="numero_establecimiento_search" name="numero_establecimiento_search" placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="razon_social_search">Buscar por Razón Social</label>
                                                <input type="text" class="form-control" id="razon_social_search" name="razon_social_search" placeholder="">
                                            </div>
                                            <div class="form-group col-md-3 col-sm-6">
                                                <label for="fecha_recepcion_desde_search">Buscar por Fecha Recepción Desde</label>
                                                <div class="datepicker date input-group">
                                                    <input type="text" class="form-control" id="fecha_recepcion_desde_search" name="fecha_recepcion_desde_search" placeholder="">
                                                    <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-sm-6">
                                                <label for="fecha_recepcion_hasta_search">Buscar por Fecha Recepción Hasta</label>
                                                <div class="datepicker date input-group">
                                                    <input type="text" class="form-control" id="fecha_recepcion_hasta_search" name="fecha_recepcion_hasta_search" placeholder="">
                                                    <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="tipo_atencion_id_search">Buscar por Tipo Atención:</label>
                                                <select id="tipo_atencion_id_search" name="tipo_atencion_id_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="">Seleccione un Tipo Atención</option>
                                                    @foreach ($tiposAtencion as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="provincia_id_search">Buscar por Provincia:</label>
                                                <select id="provincia_id_search" name="provincia_id_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="">Seleccione una Provincia</option>
                                                    @foreach ($provincias as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-3 col-sm-6">
                                                <label for="fecha_servicio_desde_search">Buscar por Mes y Año de Servicio Desde</label>
                                                <div class="datepicker date input-group">
                                                    <input type="text" class="form-control" id="fecha_servicio_desde_search" name="fecha_servicio_desde_search" placeholder="">
                                                    <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-sm-6">
                                                <label for="fecha_servicio_hasta_search">Buscar por Mes y Año de Servicio Hasta</label>
                                                <div class="datepicker date input-group">
                                                    <input type="text" class="form-control" id="fecha_servicio_hasta_search" name="fecha_servicio_hasta_search" placeholder="">
                                                    <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="numero_casos_search">Buscar por Número de Casos </label>
                                                <input type="text" class="form-control" id="numero_casos_search" name="numero_casos_search" placeholder="">
                                            </div>
                                        </div>  
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="monto_planilla_search">Buscar por Monto Planilla</label>
                                                <input type="text" class="form-control" id="monto_planilla_search" name="monto_planilla_search" placeholder="">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="numero_caja_ant_search">Buscar por Número Caja Anterior</label>
                                                <input type="text" class="form-control" id="numero_caja_ant_search" name="numero_caja_ant_search" placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="numero_caja_search">Buscar por Número Caja</label>
                                                <input type="text" class="form-control" id="numero_caja_search" name="numero_caja_search" placeholder="">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="tipo_estado_caja_id_search">Buscar por Estado Caja:</label>
                                                <select id="tipo_estado_caja_id_search" name="tipo_estado_caja_id_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="">Seleccione un Estado Caja</option>
                                                    @foreach ($tiposEstadoCaja as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="numero_caja_auditoria_search">Buscar por Número Caja Auditoría</label>
                                                <input type="text" class="form-control" id="numero_caja_auditoria_search" name="numero_caja_auditoria_search" placeholder="">
                                            </div>
                                            <div class="form-group col-md-3 col-sm-6">
                                                <label for="fecha_envio_auditoria_desde_search">Buscar por Fecha Envio Auditoría Desde</label>
                                                <div class="datepicker date input-group">
                                                    <input type="text" class="form-control" id="fecha_envio_auditoria_desde_search" name="fecha_envio_auditoria_desde_search" placeholder="">
                                                    <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-3 col-sm-6">
                                                <label for="fecha_envio_auditoria_search">Buscar por Fecha Envio Auditoría Hasta</label>
                                                <div class="datepicker date input-group">
                                                    <input type="text" class="form-control" id="fecha_envio_auditoria_hasta_search" name="fecha_envio_auditoria_hasta_search" placeholder="">
                                                    <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="institucion_id_search">Buscar por Institución:</label>
                                                <select id="institucion_id_search" name="institucion_id_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="">Seleccione una Institución</option>
                                                    @foreach ($instituciones as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="documento_externo_search">Buscar por Documento Externo</label>
                                                <input type="text" class="form-control" id="documento_externo_search" name="documento_externo_search" placeholder="">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="tipo_firma_search">Buscar por Tipo de Firma:</label>
                                                <select id="tipo_firma_search" name="tipo_firma_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="">Seleccione un Tipo de Firma</option>
                                                    @foreach ($tiposFirma as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="observacion_search">Buscar por Observaciones</label>
                                                <input type="text" class="form-control" id="observacion_search" name="observacion_search" placeholder="">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="numero_quipux_search">Buscar por Número de Quipux</label>
                                                <input type="text" class="form-control" id="numero_quipux_search" name="numero_quipux_search" placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="responsable_id_search">Buscar por Responsable:</label>
                                                <select id="responsable_id_search" name="responsable_id_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="">Seleccione un Responsable</option>
                                                    @foreach ($responsables as $key => $value)
                                                        <option value="{{ $value->id }}" {{ Auth::user()->id == $value->id ? 'selected' : ''}}>{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12" id="periodo">
                                                <label for="periodo_search">Buscar por Periodo</label>
                                                <input type="text" class="form-control" id="periodo_search" name="periodo_search" placeholder="">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <button type="button" id="buscarReporte" class="btn btn-primary mt-4 pr-4 pl-4">Buscar</button>
                                        </div>
                                            @if (auth()->user()->can('reporteTramites.download'))
                                        <div class="col-sm-2">
                                            <button id="generarReporte" type="button" class="btn btn-success mt-4 pr-4 pl-4">Generar Reporte</button>
                                        </div>
                                        @endif
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                Reportes
                                </button>
                            </h5>
                            </div>

                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    <h4 class="header-title float-left">{{ __('Reportes') }}</h4>
                                    <div class="clearfix"></div>
                                    <div class="data-tables">
                                        <div class="col-6 mt-6">
                                            <table class="table table-striped">
                                                <tbody>
                                                    <tr>
                                                        <td><b>Total Registros</b></td>
                                                        <td id="totalRegistros"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Total Número de Casos</b></td>
                                                        <td id="totalNumCasos"></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Total Monto Planilla</b></td>
                                                        <td id="totalMontoPlanilla"></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <div class="data-tables">
                                        
                                        <table id="dataTable" class="text-center">
                                            <thead class="bg-light text-capitalize">
                                                
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- data table end -->
    </div>
    <div id="overlay">
        <div class="cv-spinner">
            <span class="spinner"></span>
        </div>
    </div>
</div>
@endsection

@section('scripts')
     <!-- Start datatable js -->
     <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/jquery.dataTables.min.js"></script>
     <script src="https://cdn.datatables.net/1.10.18/js/dataTables.bootstrap4.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
     <script src="https://cdn.datatables.net/responsive/2.2.3/js/responsive.bootstrap.min.js"></script>
     
     <script>
        let table = "";
        let dataTableData = {
            totalRegistros : 0,
            totalNumCasos : 0,
            totalMontoPlanilla : 0
        };
        let tableRef = "";
        let tableHeaderRef = "";
        let registros = [];
        let tipos = [];
        let tipos_atencion = [];
        let provincias = [];
        let instituciones = [];
        let tipos_firma = [];
        let responsables = [];
        let selected_table_items = [];

        $(document).ready(function() {

            /*$('#prestador1_search, #prestador2_search, #prestador_salud_search, #responsable_planillaje_search').on('keyup', function () {
                this.value = this.value.toUpperCase();
            });*/

            $( "#buscarReporte" ).on( "click", function() {
                $("#overlay").fadeIn(300);
                $('#dataTable').empty();

                var tabla = $('#dataTable');
                var thead = $('<thead></thead>').appendTo(tabla);
                var tbody = $('<tbody><tbody/>').appendTo(tabla);
                table = "";
    
                loadDataTable();
            });

            $( "#tipo_reporte_search" ).on( "change", function() {
                if($( "#tipo_reporte_search" ).val() == "extemporaneo"){
                    $('#periodo').show();
                }else{
                    $('#periodo').hide();
                }
            });

            $( "#generarReporte" ).on( "click", function() {
                if(document.getElementById('reporte').reportValidity()){
                    $("#overlay").fadeIn(300);
                    $.ajax({
                        url: "{{url('/generarReporteByTipoReporte')}}",
                        method: "POST",
                        data: {
                            tipo_reporte_search: $('#tipo_reporte_search').val(),
                            fecha_registro_desde_search: $('#fecha_registro_desde_search').val(),
                            fecha_registro_hasta_search: $('#fecha_registro_hasta_search').val(),
                            tipo_id_search: JSON.stringify($('#tipo_id_search').val()),
                            ruc_search: $('#ruc_search').val(),
                            numero_establecimiento_search: $('#numero_establecimiento_search').val(),
                            razon_social_search: $('#razon_social_search').val(),
                            fecha_recepcion_desde_search: $('#fecha_recepcion_desde_search').val(),
                            fecha_recepcion_hasta_search: $('#fecha_recepcion_hasta_search').val(),
                            tipo_atencion_id_search: JSON.stringify($('#tipo_atencion_id_search').val()),
                            provincia_id_search: JSON.stringify($('#provincia_id_search').val()),
                            fecha_servicio_desde_search: $('#fecha_servicio_desde_search').val(),
                            fecha_servicio_hasta_search: $('#fecha_servicio_hasta_search').val(),
                            numero_casos_search: $('#numero_casos_search').val(),
                            monto_planilla_search: $('#monto_planilla_search').val(),
                            numero_caja_ant_search: $('#numero_caja_ant_search').val(),
                            numero_caja_search: $('#numero_caja_search').val(),
                            tipo_estado_caja_id_search: JSON.stringify($('#tipo_estado_caja_id_search').val()),
                            numero_caja_auditoria_search: $('#numero_caja_auditoria_search').val(),
                            fecha_envio_auditoria_desde_search: $('#fecha_envio_auditoria_desde_search').val(),
                            fecha_envio_auditoria_hasta_search: $('#fecha_envio_auditoria_hasta_search').val(),
                            institucion_id_search: JSON.stringify($('#institucion_id_search').val()),
                            documento_externo_search: $('#documento_externo_search').val(),
                            tipo_firma_search: JSON.stringify($('#tipo_firma_search').val()),
                            observacion_search: $('#observacion_search').val(),
                            numero_quipux_search: $('#numero_quipux_search').val(),
                            periodo_search: $('#periodo_search').val(),
                            responsable_id_search: JSON.stringify($('#responsable_id_search').val()),
                            _token: '{{csrf_token()}}'
                        },
                        xhrFields: {
                            responseType: 'blob'
                        },
                        success: function (response) {
                            $("#overlay").fadeOut(300);

                            var a = document.createElement('a');
                            var url = window.URL.createObjectURL(response);
                            a.href = url;
                            a.download = 'reporte.xlsx';
                            document.body.append(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(url);
                        }
                    });
                }
            });

            $('.datepicker').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd"
            });

            $('#periodo').hide();

        });

        function loadDataTable(){
            $.ajax({
                url: "{{url('/getReporteByFilters')}}",
                method: "POST",
                data: {
                    tipo_reporte_search: $('#tipo_reporte_search').val(),
                    fecha_registro_desde_search: $('#fecha_registro_desde_search').val(),
                    fecha_registro_hasta_search: $('#fecha_registro_hasta_search').val(),
                    tipo_id_search: JSON.stringify($('#tipo_id_search').val()),
                    ruc_search: $('#ruc_search').val(),
                    numero_establecimiento_search: $('#numero_establecimiento_search').val(),
                    razon_social_search: $('#razon_social_search').val(),
                    fecha_recepcion_desde_search: $('#fecha_recepcion_desde_search').val(),
                    fecha_recepcion_hasta_search: $('#fecha_recepcion_hasta_search').val(),
                    tipo_atencion_id_search: JSON.stringify($('#tipo_atencion_id_search').val()),
                    provincia_id_search: JSON.stringify($('#provincia_id_search').val()),
                    fecha_servicio_desde_search: $('#fecha_servicio_desde_search').val(),
                    fecha_servicio_hasta_search: $('#fecha_servicio_hasta_search').val(),
                    numero_casos_search: $('#numero_casos_search').val(),
                    monto_planilla_search: $('#monto_planilla_search').val(),
                    numero_caja_ant_search: $('#numero_caja_ant_search').val(),
                    numero_caja_search: $('#numero_caja_search').val(),
                    tipo_estado_caja_id_search: JSON.stringify($('#tipo_estado_caja_id_search').val()),
                    numero_caja_auditoria_search: $('#numero_caja_auditoria_search').val(),
                    fecha_envio_auditoria_desde_search: $('#fecha_envio_auditoria_desde_search').val(),
                    fecha_envio_auditoria_hasta_search: $('#fecha_envio_auditoria_hasta_search').val(),
                    institucion_id_search: JSON.stringify($('#institucion_id_search').val()),
                    documento_externo_search: $('#documento_externo_search').val(),
                    tipo_firma_search: JSON.stringify($('#tipo_firma_search').val()),
                    observacion_search: $('#observacion_search').val(),
                    numero_quipux_search: $('#numero_quipux_search').val(),
                    periodo_search: $('#periodo_search').val(),
                    responsable_id_search: JSON.stringify($('#responsable_id_search').val()),
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (response) {
                    $("#overlay").fadeOut(300);

                    $("#collapseTwo").collapse('show');
                    
                    registros = response.registros;
                    tipos = response.tipos;
                    tipos_atencion = response.tipos_atencion;
                    provincias = response.provincias;
                    instituciones = response.instituciones;
                    tipos_firma = response.tipos_firma;
                    responsables = response.responsables;

                    dataTableData.totalRegistros = 0;
                    dataTableData.totalNumCasos = 0;
                    dataTableData.totalMontoPlanilla = 0;

                    tableHeaderRef = document.getElementById('dataTable').getElementsByTagName('thead')[0];

                    tableHeaderRef.insertRow().innerHTML = 
                        "<th>#</th>"+
                        "<th>Fecha Registro</th>"+
                        "<th>Tipo</th>"+
                        "<th>Ruc</th>"+
                        "<th># Establecimiento</th>"+
                        "<th>Razón Social</th>"+
                        "<th>Fecha Recepción</th>"+
                        "<th>Tipo de Atención</th>"+
                        "<th>Provincia</th>"+
                        "<th>Mes y Año del Servicio</th>"+
                        "<th>Número de Expedientes</th>"+
                        "<th>Monto Planilla</th>"+
                        "<th>Número de Caja Anterior</th>"+
                        "<th>Número de Caja</th>"+
                        "<th>Estado Caja</th>"+
                        "<th>Número de Caja Auditoría</th>"+
                        "<th>Fecha Envio Auditoría</th>"+
                        "<th>Tipo de Institución</th>"+
                        "<th>Documento Externo</th>"+
                        "<th>Tipo de Firma del Documento</th>"+
                        "<th>Observaciones</th>"+
                        "<th># de Quipux</th>"+
                        "<th>Periodo</th>"+
                        "<th>Responsable</th>"+
                        "<th>Archivos</th>";
                        

                    tableRef = document.getElementById('dataTable').getElementsByTagName('tbody')[0];

                    let contador = 1;
                    let meses = [{"id":"01","nombre":"Enero"},{"id":"02","nombre":"Febrero"},{"id":"03","nombre":"Marzo"},{"id":"04","nombre":"Abril"},{"id":"05","nombre":"Mayo"},{"id":"06","nombre":"Junio"},{"id":"07","nombre":"Julio"},{"id":"08","nombre":"Agosto"},{"id":"09","nombre":"Septiembre"},{"id":"10","nombre":"Octubre"},{"id":"11","nombre":"Noviembre"},{"id":"12","nombre":"Diciembre"}];
                    for (let registro of registros) {
                        
                        
                        let rutaDownloadFiles = "{{url('/files')}}"+"/";

                        let files_name = [];
                        let html_files = "";
                        let innerHTML = "";
                        let fs = [];
                        let mes = "";
                        let anio = "";

                        registro.files.forEach((file) => files_name.push(file.name));

                        if(files_name.length > 0){ 
                            files_name.forEach((f_name) => html_files += '<a href="'+rutaDownloadFiles+f_name+'" target="_blank" download> <i class="fa fa-file-pdf-o" aria-hidden="true"></i>'+f_name+'</a>');
                        }

                        fs = registro.fecha_servicio.split("-");
                        if(fs.length > 0){
                            if(parseInt(fs[1]) > 0){
                                mes = meses.find(ms => ms.id === fs[1]);
                                mes = mes.nombre;
                                anio = fs[0];
                            }else{
                                mes = "";
                                anio = "";
                            }
                        }else{
                            mes = "";
                            anio = "";
                        }

                        innerHTML += 
                            "<td>"+ contador+ "</td>"+
                            "<td>"+ registro.fecha_registro+ "</td>"+
                            "<td>"+ registro.tipo_nombre+ "</td>"+
                            "<td>"+ registro.ruc+ "</td>"+
                            "<td>"+ registro.numero_establecimiento+ "</td>"+
                            "<td>"+ registro.razon_social+ "</td>"+
                            "<td>"+ registro.fecha_recepcion+ "</td>"+
                            "<td>"+ registro.tipo_atencion_nombre+ "</td>"+
                            "<td>"+ registro.provincia_nombre+ "</td>"+
                            "<td>"+ mes + "-" + anio + "</td>"+
                            "<td>"+ registro.numero_casos+ "</td>"+
                            "<td>"+ registro.monto_planilla+ "</td>"+
                            "<td>"+ registro.numero_caja_ant+ "</td>"+
                            "<td>"+ registro.numero_caja+ "</td>"+
                            "<td>"+ registro.tipo_estado_caja_nombre+ "</td>"+
                            "<td>"+ registro.numero_caja_auditoria+ "</td>"+
                            "<td>"+ registro.fecha_envio_auditoria+ "</td>"+
                            "<td>"+ registro.institucion_nombre+ "</td>"+
                            "<td>"+ registro.documento_externo+ "</td>"+
                            "<td>"+ registro.tipo_firma_nombre+ "</td>"+
                            "<td>"+ registro.observaciones+ "</td>"+
                            "<td>"+ registro.numero_quipux+ "</td>"+
                            "<td>"+ registro.periodo+ "</td>"+
                            "<td>"+ registro.responsable_nombre+ "</td>"+
                            "<td>"+ html_files + "</td>";

                            tableRef.insertRow().innerHTML = innerHTML;
                            contador += 1;
                    }
                    
                    //if ($('#dataTable').length) {

                        
                        $('#dataTable thead tr').clone(true).appendTo( '#dataTable thead' );
                        $('#dataTable thead tr:eq(1) th').each( function (i) {
                            
                            var title = $(this).text();
                            if(title !== '#' && title !== 'Archivos'){
                                $(this).html( '<input type="text" placeholder="Buscar por: '+title+'" />' );

                                $( 'input', this ).on( 'keyup change', function () {
                                    if ( table.column(i).search() !== this.value ) {
                                        table
                                            .column(i)
                                            .search( this.value )
                                            .draw();
                                    }
                                    dataTableData.totalRegistros = 0;
                                    dataTableData.totalNumCasos = 0;
                                    dataTableData.totalMontoPlanilla = 0;
                                    
                                    getTotales(table.rows( { filter : 'applied'} ).data());
                                } );
                            }
                            
                        } );

                        table = $('#dataTable').DataTable( {
                            scrollX: true,
                            orderCellsTop: true,
                            fixedHeader: true,
                            destroy: true,
                            paging: true,
                            searching: true,
                            autoWidth: true,
                            responsive: false,
                        });

                        getTotales(table.rows().data());
                    //}
                }
            });
        }

        function getTotales(dataTable){
            dataTableArray = dataTable.toArray();
            dataTableData.totalRegistros = dataTableArray.length;
            if (dataTableArray.length > 0) {
                dataTableData.totalNumCasos = table.column(10,{ search: "applied" }).data().reduce( function (a, b) { return parseInt(a) + parseInt(b); } );
                dataTableData.totalMontoPlanilla = table.column(11,{ search: "applied" }).data().reduce( function (a, b) { return parseFloat(a) + parseFloat(b); } );
            }
            
            $('#totalRegistros').html(dataTableData.totalRegistros);
            $('#totalNumCasos').html(dataTableData.totalNumCasos);
            $('#totalMontoPlanilla').html('$' + dataTableData.totalMontoPlanilla.toFixed(2));
        }
        
     </script>
@endsection