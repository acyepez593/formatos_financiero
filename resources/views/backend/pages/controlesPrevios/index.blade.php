@extends('backend.layouts.master')

@section('title')
    {{ __('Controles Previos - Panel de Control Previo') }}
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
                <h4 class="page-title pull-left">{{ __('Controles Previos') }}</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><span>{{ __('Todos los Controles Previos') }}</span></li>
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
                                                <label for="tipo_formato_id_search">Buscar por Tipo Formato:</label>
                                                <select id="tipo_formato_id_search" name="proteccion_id_search" class="form-control selectpicker" data-live-search="true" multiple required>
                                                    <option value="">Seleccione un Tipo Formato</option>
                                                    @foreach ($tiposFormato as $key => $value)
                                                        <option value="{{ $value->id }}">{{ $value->nombre }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="nro_control_previo_y_concurrente_search">Buscar por Informe de Control Previo y Control Concurrente No.</label>
                                                <input type="text" class="form-control" id="nro_control_previo_y_concurrente_search" name="nro_control_previo_y_concurrente_search" placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="fecha_tramite_search">Buscar por Fecha de Trámite</label>
                                                <div class="datepicker date input-group">
                                                    <input type="text" class="form-control" id="fecha_tramite_search" name="fecha_tramite_search" placeholder="">
                                                    <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="solicitud_pago_search">Buscar por Solicitud Pago</label>
                                                <input type="text" class="form-control" id="solicitud_pago_search" name="solicitud_pago_search" placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="contrato_search">Buscar por contrato</label>
                                                <input type="text" class="form-control" id="contrato_search" name="contrato_search" placeholder="">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="objeto_search">Buscar por Objeto</label>
                                                <input type="text" class="form-control" id="objeto_search" name="objeto_search" placeholder="">
                                            </div>    
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="beneficiario_search">Buscar por Beneficiario</label>
                                                <input type="text" class="form-control" id="beneficiario_search" name="beneficiario_search" placeholder="">
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="ruc_search">Buscar por RUC</label>
                                                <input type="text" class="form-control" id="ruc_search" name="ruc_search" placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="mes_search">Buscar por Mes</label>
                                                <div class="datepicker date input-group">
                                                    <input type="text" class="form-control" id="mes_search" name="mes_search" placeholder="">
                                                    <div class="input-group-append">
                                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="valor_search">Buscar por Valor</label>
                                                <input type="text" class="form-control" id="valor_search" name="valor_search" placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-row">
                                            <div class="form-group col-md-6 col-sm-12">
                                                <label for="creado_por_id_search">Buscar por Creador por:</label>
                                                <select id="creado_por_id_search" name="creado_por_id_search" class="form-control selectpicker" data-live-search="true" multiple>
                                                    <option value="">Seleccione un Creado por</option>
                                                    @foreach ($servidoresPublicos as $key => $value)
                                                        <option value="{{ $value->id }}" {{ Auth::user()->id == $value->id ? 'selected' : ''}}>{{ $value->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <button type="button" id="buscarControlesPrevios" class="btn btn-primary mt-4 pr-4 pl-4">Buscar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
                                Controles Previos
                                </button>
                            </h5>
                            </div>

                            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                                <div class="card-body">
                                    <h4 class="header-title float-left">{{ __('Controles Previos') }}</h4>
                                    <p class="float-right mb-2" style="padding: 5px;">
                                        @if (auth()->user()->can('controlPrevio.create'))
                                            <a class="btn btn-primary text-white" href="{{ route('admin.controlesPrevios.create') }}">
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
        let controlesPrevios = [];
        let tiposFormato = [];
        let formatosPago = [];
        let documentosHabilitantes = [];
        let resumenesRemesa = [];
        let liquidacionesEconomicas = [];
        let servidoresPublicos = [];

        $(document).ready(function() {

            /*$('#prestador1_search, #prestador2_search, #prestador_salud_search, #responsable_planillaje_search').on('keyup', function () {
                this.value = this.value.toUpperCase();
            });*/

            $( "#buscarControlesPrevios" ).on( "click", function() {
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
                url: "{{url('/getControlesPreviosByFilters')}}",
                method: "POST",
                data: {
                    tipo_formato_id_search: JSON.stringify($('#tipo_formato_id_search').val()),
                    nro_control_previo_y_concurrente_search: $('#nro_control_previo_y_concurrente_search').val(),
                    fecha_tramite_search: $('#fecha_tramite_search').val(),
                    solicitud_pago_search: $('#solicitud_pago_search').val(),
                    contrato_search: $('#contrato_search').val(),
                    objeto_search: $('#objeto_search').val(),
                    beneficiario_search: $('#beneficiario_search').val(),
                    ruc_search: $('#ruc_search').val(),
                    mes_search: $('#mes_search').val(),
                    valor_search: $('#valor_search').val(),
                    creado_por_id_search: JSON.stringify($('#creado_por_id_search').val()),
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (response) {
                    $("#overlay").fadeOut(300);

                    $("#collapseTwo").collapse('show');

                    controlesPrevios = response.controlesPrevios;
                    tiposFormato = response.tiposFormato;
                    formatosPago = response.formatosPago;
                    documentosHabilitantes = response.documentosHabilitantes;
                    resumenesRemesa = response.resumenesRemesa;
                    liquidacionesEconomicas = response.liquidacionesEconomicas;

                    dataTableData.totalRegistros = 0;

                    tableHeaderRef = document.getElementById('dataTable').getElementsByTagName('thead')[0];

                    tableHeaderRef.insertRow().innerHTML = 
                        "<th>#</th>"+
                        "<th>Tipo Formato</th>"+    
                        "<th># Control Previo</th>"+
                        "<th>Fecha Trámite</th>"+
                        "<th>Solicitud de Pago</th>"+
                        "<th>Contrato</th>"+
                        "<th>Objeto</th>"+
                        "<th>Beneficiario</th>"+
                        "<th>RUC</th>"+
                        "<th>Mes</th>"+
                        "<th>Valor</th>"+
                        "<th>Servidor Público</th>"+
                        "<th>Acción</th>";

                    tableRef = document.getElementById('dataTable').getElementsByTagName('tbody')[0];

                    let contador = 1;
                    let meses = [{"id":"01","nombre":"Enero"},{"id":"02","nombre":"Febrero"},{"id":"03","nombre":"Marzo"},{"id":"04","nombre":"Abril"},{"id":"05","nombre":"Mayo"},{"id":"06","nombre":"Junio"},{"id":"07","nombre":"Julio"},{"id":"08","nombre":"Agosto"},{"id":"09","nombre":"Septiembre"},{"id":"10","nombre":"Octubre"},{"id":"11","nombre":"Noviembre"},{"id":"12","nombre":"Diciembre"}];
                    for (let registro of controlesPrevios) {
                        
                        let rutaEdit = "{{url()->current()}}"+"/"+registro.id+"/edit";
                        let rutaDelete = "{{url()->current()}}"+"/"+registro.id;

                        let innerHTML = "";
                        let htmlEdit = "";
                        let htmlDelete = "";
                        let mes = "";
                        let anio = "";
                        htmlEdit +=@if (auth()->user()->can('controlPrevio.edit')) '<a class="btn btn-success text-white" href="'+rutaEdit+'">Editar</a>' @else '' @endif;
                        htmlDelete += @if (auth()->user()->can('controlPrevio.delete')) '<a class="btn btn-danger text-white" href="javascript:void(0);" onclick="event.preventDefault(); deleteDialog('+registro.id+')">Borrar</a> <form id="delete-form-'+registro.id+'" action="'+rutaDelete+'" method="POST" style="display: none;">@method('DELETE')@csrf</form>' @else '' @endif;

                        innerHTML += 
                            "<td>"+ contador+ "</td>"+
                            "<td>"+ registro.tipo_formato_nombre+ "</td>"+
                            "<td>"+ registro.nro_control_previo_y_concurrente+ "</td>"+
                            "<td>"+ registro.fecha_tramite+ "</td>"+
                            "<td>"+ registro.solicitud_pago+ "</td>"+
                            "<td>"+ registro.contrato+ "</td>"+
                            "<td>"+ registro.objeto+ "</td>"+
                            "<td>"+ registro.beneficiario+ "</td>"+
                            "<td>"+ registro.ruc+ "</td>"+
                            "<td>"+ registro.mes+ "</td>"+
                            "<td>"+ registro.valor+ "</td>"+
                            "<td>"+ registro.creado_por_nombre+ "</td>";
                            if(registro.esCreadorRegistro){
                                innerHTML +="<td>" + htmlEdit + htmlDelete + "</td>";
                            }else{
                                innerHTML += "<td></td>";
                            }

                            tableRef.insertRow().innerHTML = innerHTML;
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
                                $( "#buscarControlesPrevios" ).trigger( "click" );
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