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
                    <li><span>{{ __('Todos los Reportes') }}</span></li>
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
                    
                    <h4 class="header-title float-left">{{ __('Reportes') }}</h4>
                    
                    <div class="clearfix"></div>
                    @include('backend.layouts.partials.messages')
                    <div class="col-md-12">
                        <form action="" method="POST" id="reporteControlPrevio">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="tipo_formato_id">Seleccione un Tipo de Formato:</label>
                                    <select id="tipo_formato_id" name="tipo_formato_id" class="form-control selectpicker" data-live-search="true" required>
                                        @foreach ($tiposFormato as $key => $value)
                                            <option value="{{ $value->id }}">{{ $value->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="nro_control_previo_y_concurrente">Seleccione un Control Previo:</label>
                                    <select id="nro_control_previo_y_concurrente" name="nro_control_previo_y_concurrente" class="form-control selectpicker" data-live-search="true" required>
                                        
                                    </select>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <!--<div class="col-sm-2">
                                    <button id="consutarReporte" type="button" class="btn btn-primary mt-4 pr-4 pl-4">Consultar</button>
                                </div>-->
                                @if (auth()->user()->can('reporteControlPrevio.download'))
                                <div class="col-sm-2">
                                    <button id="generarReporte" type="button" class="btn btn-success mt-4 pr-4 pl-4">Generar Reporte</button>
                                </div>
                                @endif
                            </div>
                        
                        </form>
                    </div>
                    
                    <div class="clearfix"></div>
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
        let table = [];

        $(document).ready(function() {
            let table = [];

            $('#tipo_formato_id').on('change', function () {
                let tipo_formato_id = this.value;
                $("#nro_control_previo_y_concurrente").html('');
                $.ajax({
                    url: "{{url('/getControlesPreviosByTipoFormato')}}",
                    type: "POST",
                    data: {
                        tipo_formato_id: tipo_formato_id,
                        _token: '{{csrf_token()}}'
                    },
                    dataType: 'json',
                    success: function (response) {
                        console.log(response);
                        $('#nro_control_previo_y_concurrente').html('<option value="">Seleccione un Control Previo:</option>');
                        $.each(response.controlesPreviosByTipoFormato, function (key, value) {
                            $("#nro_control_previo_y_concurrente").append('<option value="' + value
                                .id + '">' + value.nro_control_previo_y_concurrente + '</option>');
                        });
                        $('#nro_control_previo_y_concurrente.selectpicker').selectpicker('refresh');
                    }
                });
            });

            $( "#consutarReporte" ).on( "click", function() {
                if(document.getElementById('reporteControlPrevio').reportValidity()){
                    $("#overlay").fadeIn(300);
                    
                    $.ajax({
                        url: "{{url('/getReporteByNumeroCaja')}}",
                        method: "POST",
                        data: {
                            tipo_reporte: $('#tipo_reporte').val(),
                            numero_caja: $('#numero_caja').val(),
                            _token: '{{csrf_token()}}'
                        },
                        dataType: 'json',
                        success: function (response) {
                            $("#overlay").fadeOut(300);
                            
                            table = $('#dataTable').DataTable ( {
                                data:response.reportes,
                                destroy: true,
                                paging: true,
                                searching: true,
                                scrollX: false,
                                autoWidth: true,
                                responsive: false,
                                columns: [
                                    { data: "razon_social" },
                                    { data: "ruc" },
                                    { data: "fecha_recepcion" },
                                    { data: "tipo_atencion_id" },
                                    { data: "fecha_servicio" },
                                    { data: "numero_casos" },
                                    { data: "monto_planilla" }
                                ]
                            });
                            $('.data-tables').show();
                            getTotales(table.rows().data());
                        }
                    });
                }
            });

            function getTotales(dataTable) {
                dataTableArray = dataTable.toArray();
                dataTableData.totalRegistros = dataTableArray.length;
                var totalNumCasos = 0;
                var totalMontoPlanilla = 0;
                if (dataTableArray.length > 0) {
                    dataTableData.totalNumCasos = table.column(5,{ search: "applied" }).data().reduce( function (a, b) { return parseInt(a) + parseInt(b); } );
                    dataTableData.totalMontoPlanilla = table.column(6,{ search: "applied" }).data().reduce( function (a, b) { return parseFloat(a) + parseFloat(b); } );

                    totalNumCasos = dataTableData.totalNumCasos;
                    totalMontoPlanilla = dataTableData.totalMontoPlanilla;
                }
                
                $('#totalRegistros').html(dataTableData.totalRegistros);
                $('#totalNumCasos').html(totalNumCasos);
                $('#totalMontoPlanilla').html('$' + totalMontoPlanilla.toFixed(2));
            }

            $( "#generarReporte" ).on( "click", function() {
                if(document.getElementById('reporteControlPrevio').reportValidity()){
                    $("#overlay").fadeIn(300);
                    $.ajax({
                        url: "{{url('/generarReporteById')}}",
                        method: "POST",
                        data: {
                            tipo_formato_id: $('#tipo_formato_id').val(),
                            id_control_previo: $('#nro_control_previo_y_concurrente').val(),
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
                            a.download = 'controlPrevio.xlsx';
                            document.body.append(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(url);
                        }
                    });
                }
            });

            $('#tipo_formato_id').trigger('change');

        });
     </script>
@endsection