
@extends('backend.layouts.master')

@section('title')
Crear Expediente - Admin Panel
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
                <h4 class="page-title pull-left">Crear Expediente</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.expedientes.index') }}">Todos los Expedientes</a></li>
                    <li><span>Crear Expediente</span></li>
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
                    <h4 class="header-title">Crear Nuevo Expediente</h4>
                    @include('backend.layouts.partials.messages')
                    
                    <form action="{{ route('admin.expedientes.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="victima">Víctima</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('victima') is-invalid @enderror" id="victima" name="victima" placeholder="Víctima" value="{{ old('victima') }}" maxlength="100" required>
                                    @error('victima')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="id_de_proteccion">Id de Protección</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('id_de_proteccion') is-invalid @enderror" id="id_de_proteccion" name="id_de_proteccion" placeholder="Id de Protección" value="{{ old('id_de_proteccion') }}" maxlength="50" required>
                                    @error('id_de_proteccion')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="proteccion_id">Seleccione una Protección:</label>
                                <select id="proteccion_id" name="proteccion_id" class="form-control selectpicker @error('proteccion_id') is-invalid @enderror" data-live-search="true" required>
                                    <option value="">Seleccione una Protección</option>
                                    @foreach ($protecciones as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('proteccion_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="peticionario_notificado">Peticionario Notificado</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('peticionario_notificado') is-invalid @enderror" id="peticionario_notificado" name="peticionario_notificado" placeholder="Peticionario Notificado" value="{{ old('peticionario_notificado') }}" maxlength="100" required>
                                    @error('peticionario_notificado')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="nro_oficio_notificacion">Nro. Oficio Notificación</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('nro_oficio_notificacion') is-invalid @enderror" id="nro_oficio_notificacion" name="nro_oficio_notificacion" placeholder="Nro. Oficio Notificación" value="{{ old('nro_oficio_notificacion') }}" maxlength="50" required>
                                    @error('nro_oficio_notificacion')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="fecha_notificacion">Fecha de Notificación</label>
                                <div class="datepicker date input-group">
                                    <input type="text" placeholder="Fecha de Notificación" class="form-control @error('fecha_notificacion') is-invalid @enderror" id="fecha_notificacion" name="fecha_notificacion" value="{{ old('fecha_notificacion') }}" required>
                                    <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                                @error('fecha_notificacion')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="responsables_ids">Seleccione un Responsable:</label>
                                <select id="responsables_ids" name="responsables_ids" class="form-control selectpicker @error('responsables_ids') is-invalid @enderror" data-live-search="true" multiple required>
                                    <option value="">Seleccione un Responsable</option>
                                    @foreach ($responsables as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('proteccion_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="fecha_maxima_respuesta">Fecha Máxima de Respuesta</label>
                                <div class="datepicker date input-group">
                                    <input type="text" placeholder="Fecha Máxima de Respuesta" class="form-control @error('fecha_maxima_respuesta') is-invalid @enderror" id="fecha_maxima_respuesta" name="fecha_maxima_respuesta" value="{{ old('fecha_maxima_respuesta') }}" required>
                                    <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                                @error('fecha_maxima_respuesta')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="documentacion_solicitada">Documentación Solicitada</label>
                                <div class="input-group mb-3">
                                    <textarea class="form-control @error('documentacion_solicitada') is-invalid @enderror" id="documentacion_solicitada" name="documentacion_solicitada" value="{{ old('documentacion_solicitada') }}" maxlength="5000" rows="3" required></textarea>
                                    @error('documentacion_solicitada')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="observaciones">Observaciones</label>
                                <div class="input-group mb-3">
                                    <textarea class="form-control @error('observaciones') is-invalid @enderror" id="observaciones" name="observaciones" value="{{ old('observaciones') }}" maxlength="5000" rows="3"></textarea>
                                    @error('observaciones')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="tipo_respuesta_id">Seleccione una Respuesta:</label>
                                <select id="tipo_respuesta_id" name="tipo_respuesta_id" class="form-control selectpicker @error('tipo_respuesta_id') is-invalid @enderror" data-live-search="true">
                                    <option value="">Seleccione una Respuesta</option>
                                    @foreach ($tiposRespuesta as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('tipo_respuesta_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="estado_id">Seleccione un Estado:</label>
                                <select id="estado_id" name="estado_id" class="form-control selectpicker @error('estado_id') is-invalid @enderror" data-live-search="true">
                                    <option value="">Seleccione un Estado</option>
                                    @foreach ($estados as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                @error('estado_id')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <input type="hidden" id="responsables" name="responsables" value="">
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Guardar</button>
                        <a href="{{ route('admin.expedientes.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancelar</a>
                    </form>
                </div>
            </div>
        </div>
        <!-- data table end -->
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        
	    $.fn.datepicker.dates['es'] = {
			days: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
		    daysShort: ['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],
		    daysMin: ['Do','Lu','Ma','Mi','Ju','Vi','Sá'],
		    months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
		    monthsShort: ['Ene','Feb','Mar','Abr', 'May','Jun','Jul','Ago','Sep', 'Oct','Nov','Dic'],
		    today: 'Hoy',
		    clear: 'Limpiar',
		    format: 'yyyy-mm-dd',
		    titleFormat: "MM yyyy", 
		    weekStart: 1
		};

        $('.datepicker').datepicker({
            language: 'es',
            autoclose: true,
            format: "yyyy-mm-dd",
            todayHighlight: true,
        });

        $( "#responsables_ids" ).on( "change", function() {
            $('#responsables').val($( "#responsables_ids" ).val());
        });
        
    })

</script>
@endsection