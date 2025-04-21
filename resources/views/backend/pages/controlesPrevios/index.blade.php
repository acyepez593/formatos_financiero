@extends('backend.layouts.master')

@section('title')
    {{ __('Expedientes - Panel de Expediente') }}
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
                <h4 class="page-title pull-left">{{ __('Expedientes') }}</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><span>{{ __('Todos los Expedientes') }}</span></li>
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
                                    <form>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="victima_search">Víctima</label>
                                                <input type="text" class="form-control" id="victima_search" name="victima_search" placeholder="">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="id_de_proteccion_search">Buscar por Id de Protección</label>
                                                <input type="text" class="form-control" id="id_de_proteccion_search" name="id_de_proteccion_search" placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="proteccion_id_search">Buscar por Protección:</label>
                                                <select id="proteccion_id_search" name="proteccion_id_search" class="form-control selectpicker" data-live-search="true" multiple required>
                                                    <option value="">Seleccione una Protección</option>
                                                    @foreach ($protecciones as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="peticionario_notificado_search">Buscar por Peticionario Notificado</label>
                                                <input type="text" class="form-control" id="peticionario_notificado_search" name="peticionario_notificado_search" placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="nro_oficio_notificacion_search">Buscar por Nro. Oficio Notificación</label>
                                                <input type="text" class="form-control" id="nro_oficio_notificacion_search" name="nro_oficio_notificacion_search" placeholder="">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="fecha_notificacion_search">Buscar por Fecha de Notificación</label>
                                                <div class="datepicker date input-group">
                                                    <input type="text" class="form-control" id="fecha_notificacion_search" name="fecha_notificacion_search" placeholder="">
                                                    <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="responsables_ids_search">Buscar por Responsable:</label>
                                                <select id="responsables_ids_search" name="responsables_ids_search" class="form-control selectpicker" data-live-search="true" multiple required>
                                                    <option value="">Seleccione una Responsable</option>
                                                    @foreach ($responsables as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="fecha_maxima_respuesta_search">Buscar por Fecha Máxima de Respuesta</label>
                                                <div class="datepicker date input-group">
                                                    <input type="text" class="form-control" id="fecha_maxima_respuesta_search" name="fecha_maxima_respuesta_search" placeholder="">
                                                    <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="documentacion_solicitada_search">Buscar por Documentación Solicitada</label>
                                                <input type="text" class="form-control" id="documentacion_solicitada_search" name="documentacion_solicitada_search" placeholder="">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="observaciones_search">Buscar por Observaciones</label>
                                                <input type="text" class="form-control" id="observaciones_search" name="observaciones_search" placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="tipo_respuesta_id_search">Buscar por Respuesta:</label>
                                                <select id="tipo_respuesta_id_search" name="tipo_respuesta_id_search" class="form-control selectpicker" data-live-search="true" multiple required>
                                                    <option value="">Seleccione una Respuesta</option>
                                                    @foreach ($tiposRespuesta as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="estado_id_search">Buscar por Estado:</label>
                                                <select id="estado_id_search" name="estado_id_search" class="form-control selectpicker" data-live-search="true" multiple required>
                                                    <option value="">Seleccione un Estado</option>
                                                    @foreach ($estados as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="creado_por_id_search">Buscar por Creador por:</label>
                                                <select id="creado_por_id_search" name="creado_por_id_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="">Seleccione un Creado por</option>
                                                    @foreach ($responsables as $key => $value)
                                                        <option value="{{ $value->id }}" {{ Auth::user()->id == $value->id ? 'selected' : ''}}>{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <button type="button" id="buscarExpedientes" class="btn btn-primary mt-4 pr-4 pl-4">Buscar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                Expedientes
                                </button>
                            </h5>
                            </div>

                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    <h4 class="header-title float-left">{{ __('Expedientes') }}</h4>
                                    <p class="float-right mb-2" style="padding: 5px;">
                                        @if (auth()->user()->can('expediente.create'))
                                            <a class="btn btn-primary text-white" href="{{ route('admin.expedientes.create') }}">
                                                {{ __('Crear Nuevo') }}
                                            </a>
                                        @endif
                                    </p>
                                    <div class="clearfix"></div>
                                    <div class="data-tables">
                                        <div class="col-6 mt-6">
                                            <table class="table table-striped">
                                                <tbody>
                                                    <tr>
                                                        <td><b>Total Registros</b></td>
                                                        <td id="totalRegistros"></td>
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
            totalRegistros : 0
        };
        let tableRef = "";
        let tableHeaderRef = "";
        let expedientes = [];
        let protecciones = [];
        let estados = [];
        let tiposRespuesta = [];
        let semaforos = [];
        let responsables = [];

        $(document).ready(function() {

            /*$('#prestador1_search, #prestador2_search, #prestador_salud_search, #responsable_planillaje_search').on('keyup', function () {
                this.value = this.value.toUpperCase();
            });*/

            $( "#buscarExpedientes" ).on( "click", function() {
                $("#overlay").fadeIn(300);
                $('#dataTable').empty();

                var tabla = $('#dataTable');
                var thead = $('<thead></thead>').appendTo(tabla);
                var tbody = $('<tbody><tbody/>').appendTo(tabla);
                table = "";
    
                loadDataTable();
            });

            $('.datepicker').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd"
            });

        });

        let cajas_abiertas = [];

        function loadDataTable(){
            $.ajax({
                url: "{{url('/getExpedientesByFilters')}}",
                method: "POST",
                data: {
                    victima_search: $('#victima_search').val(),
                    id_de_proteccion_search: $('#id_de_proteccion_search').val(),
                    proteccion_id_search: JSON.stringify($('#proteccion_id_search').val()),
                    peticionario_notificado_search: $('#peticionario_notificado_search').val(),
                    nro_oficio_notificacion_search: $('#nro_oficio_notificacion_search').val(),
                    fecha_notificacion_search: $('#fecha_notificacion_search').val(),
                    responsables_ids_search: JSON.stringify($('#responsables_ids_search').val()),
                    fecha_maxima_respuesta_search: $('#fecha_maxima_respuesta_search').val(),
                    documentacion_solicitada_search: $('#documentacion_solicitada_search').val(),
                    observaciones_search: $('#observaciones_search').val(),
                    tipo_respuesta_id_search: JSON.stringify($('#tipo_respuesta_id_search').val()),
                    estado_id_search: JSON.stringify($('#estado_id_search').val()),
                    creado_por_id_search: JSON.stringify($('#creado_por_id_search').val()),
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (response) {
                    $("#overlay").fadeOut(300);

                    $("#collapseTwo").collapse('show');

                    expedientes = response.expedientes;
                    protecciones = response.protecciones;
                    estados = response.estados;
                    tiposRespuesta = response.tiposRespuesta;
                    semaforos = response.semaforos;
                    responsables = response.responsables;

                    dataTableData.totalRegistros = 0;

                    tableHeaderRef = document.getElementById('dataTable').getElementsByTagName('thead')[0];

                    tableHeaderRef.insertRow().innerHTML = 
                        "<th>#</th>"+
                        "<th>Víctima</th>"+
                        "<th>Id de Protección</th>"+
                        "<th>Protección</th>"+
                        "<th>Peticionario Notificado</th>"+
                        "<th>Nro. Oficio Notificación</th>"+
                        "<th>Fecha de Notificación</th>"+
                        "<th>Responsable</th>"+
                        "<th>Fecha Máxima de Respuesta</th>"+
                        "<th>Documentación Solicitada</th>"+
                        "<th>Observaciones</th>"+
                        "<th>Respuesta</th>"+
                        "<th>Estado</th>"+
                        "<th>Estado Semárofo</th>"+
                        "<th>Creado Por</th>"+
                        "<th>Acción</th>";

                    tableRef = document.getElementById('dataTable').getElementsByTagName('tbody')[0];

                    let contador = 1;
                    let meses = [{"id":"01","nombre":"Enero"},{"id":"02","nombre":"Febrero"},{"id":"03","nombre":"Marzo"},{"id":"04","nombre":"Abril"},{"id":"05","nombre":"Mayo"},{"id":"06","nombre":"Junio"},{"id":"07","nombre":"Julio"},{"id":"08","nombre":"Agosto"},{"id":"09","nombre":"Septiembre"},{"id":"10","nombre":"Octubre"},{"id":"11","nombre":"Noviembre"},{"id":"12","nombre":"Diciembre"}];
                    for (let expediente of expedientes) {
                        
                        let rutaEdit = "{{url()->current()}}"+"/"+expediente.id+"/edit";
                        let rutaDelete = "{{url()->current()}}"+"/"+expediente.id;

                        let innerHTML = "";
                        let htmlEdit = "";
                        let htmlDelete = "";
                        let mes = "";
                        let anio = "";
                        htmlEdit +=@if (auth()->user()->can('expediente.edit')) '<a class="btn btn-success text-white" href="'+rutaEdit+'">Editar</a>' @else '' @endif;
                        htmlDelete += @if (auth()->user()->can('expediente.delete')) '<a class="btn btn-danger text-white" href="javascript:void(0);" onclick="event.preventDefault(); deleteDialog('+expediente.id+')">Borrar</a> <form id="delete-form-'+expediente.id+'" action="'+rutaDelete+'" method="POST" style="display: none;">@method('DELETE')@csrf</form>' @else '' @endif;

                        innerHTML += 
                            "<td>"+ contador+ "</td>"+
                            "<td>"+ expediente.victima+ "</td>"+
                            "<td>"+ expediente.id_de_proteccion+ "</td>"+
                            "<td>"+ expediente.proteccion_nombre+ "</td>"+
                            "<td>"+ expediente.peticionario_notificado+ "</td>"+
                            "<td>"+ expediente.nro_oficio_notificacion+ "</td>"+
                            "<td>"+ expediente.fecha_notificacion+ "</td>"+
                            "<td>"+ expediente.responsables_nombres+ "</td>"+
                            "<td>"+ expediente.fecha_maxima_respuesta+ "</td>"+
                            "<td>"+ expediente.documentacion_solicitada+ "</td>"+
                            "<td>"+ expediente.observaciones+ "</td>"+
                            "<td>"+ expediente.tipo_respuesta_nombre+ "</td>"+
                            "<td>"+ expediente.estado_nombre+ "</td>"+
                            "<td>"+ expediente.semaforo_estado+ "</td>"+
                            "<td>"+ expediente.creado_por_nombre+ "</td>";
                            if(expediente.esCreadorRegistro){
                                innerHTML +="<td>" + htmlEdit + htmlDelete + "</td>";
                            }else{
                                innerHTML += "<td></td>";
                            }

                            tableRef.insertRow().innerHTML = innerHTML;
                            let semaforo = semaforos.find(semaforo => semaforo.id === expediente.semaforo_id);
                            if(semaforo){
                                tableRef.children[contador-1].style.backgroundColor = semaforo.color;
                            }
                            
                            contador += 1;
                    }
                    
                    //if ($('#dataTable').length) {

                        
                        $('#dataTable thead tr').clone(true).appendTo( '#dataTable thead' );
                        $('#dataTable thead tr:eq(1) th').each( function (i) {
                            
                            var title = $(this).text();
                            if(title !== '#' && title !== 'Acción'){
                                $(this).html( '<input type="text" placeholder="Buscar por: '+title+'" />' );

                                $( 'input', this ).on( 'keyup change', function () {
                                    if ( table.column(i).search() !== this.value ) {
                                        table
                                            .column(i)
                                            .search( this.value )
                                            .draw();
                                    }
                                    dataTableData.totalRegistros = 0;
                                    
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

        function deleteDialog(id){
            $.confirm({
                title: 'Eliminar',
                content: '¡Esta seguro de borrar este registro!. </br>¡Esta acción será irreversible!',
                buttons: {
                    confirm: function () {
                        $("#overlay").fadeIn(300);
                        $.ajax({
                            url: "{{url()->current()}}"+"/"+id,
                            method: "POST",
                            data: {
                                _method: 'DELETE',
                                _token: '{{csrf_token()}}'
                            },
                            dataType: 'json',
                            success: function (response) {
                                $( "#buscarExpedientes" ).trigger( "click" );
                            }
                        });
                    },
                    cancel: function () {
                        //$.alert('Canceled!');
                    }
                }
            });
        }

        function getTotales(dataTable){
            dataTableArray = dataTable.toArray();
            dataTableData.totalRegistros = dataTableArray.length;
            $('#totalRegistros').html(dataTableData.totalRegistros);
        }
        
     </script>
@endsection