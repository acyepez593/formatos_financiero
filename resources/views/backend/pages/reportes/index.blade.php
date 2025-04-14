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
                        <form action="" method="POST" id="reporteCajas">
                            @csrf
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="tipo_reporte">Seleccione una Tipo de Reporte:</label>
                                    <select id="tipo_reporte" name="tipo_reporte" class="form-control selectpicker" data-live-search="true" required>
                                        <option value="">Seleccione una Tipo de Reporte</option>
                                        @foreach ($tiposReporte as $key => $value)
                                            <option value="{{ $value['id'] }}" {{ $value['id'] == 'oficio' ? 'selected' : '' }}>{{ $value['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="numero_caja">Número Caja</label>
                                    <input type="text" class="form-control @error('numero_caja') is-invalid @enderror" id="numero_caja" name="numero_caja" placeholder="Número Caja" required autofocus value="{{ old('numero_caja') }}">
                                    @error('numero_caja')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6 col-sm-12">
                                    <label for="etiqueta">Etiqueta</label>
                                    <input type="text" class="form-control @error('etiqueta') is-invalid @enderror" id="etiqueta" name="etiqueta" placeholder="Etiqueta" required value="{{ old('etiqueta') }}">
                                    @error('etiqueta')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-2">
                                    <button id="consutarReporte" type="button" class="btn btn-primary mt-4 pr-4 pl-4">Consultar</button>
                                </div>
                                @if (auth()->user()->can('reporte.download'))
                                <div class="col-sm-2">
                                    <button id="generarReporte" type="button" class="btn btn-success mt-4 pr-4 pl-4">Generar Reporte</button>
                                </div>
                                @endif
                            </div>
                        
                    </p>
                        </form>
                    </div>
                    <p></p>
                    <div class="data-tables" style="margin-top: 15px;">
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
                    <div class="col-md-12">
                        <div class="data-tables" style="width:100%; margin-top: 20px;">
                            <table id="dataTable" class="text-center">
                                <thead class="bg-light text-capitalize">
                                    <tr>
                                        <th style="width: 20%;">{{ __('Razón Social') }}</th>
                                        <th style="width: 15%;">{{ __('Ruc') }}</th>
                                        <th style="width: 10%;">{{ __('Fecha Recepción') }}</th>
                                        <th style="width: 15%;">{{ __('Tipo de Atención') }}</th>
                                        <th style="width: 10%;">{{ __('Mes y Año del Servicio') }}</th>
                                        <th style="width: 15%;">{{ __('Número de Casos') }}</th>
                                        <th style="width: 15%;">{{ __('Monto Planilla') }}</th>
                                    </tr>
                                </thead>
                                
                            </table>
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
        let table = [];
        let dataTableData = {
            totalRegistros : 0,
            totalNumCasos : 0,
            totalMontoPlanilla : 0
        };

        $(document).ready(function() {
            let table = [];

            $('#numero_caja, #etiqueta').on('keyup', function () {
                this.value = this.value.toUpperCase();
            });

            $( "#consutarReporte" ).on( "click", function() {
                if(document.getElementById('reporteCajas').reportValidity()){
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
                if(document.getElementById('reporteCajas').reportValidity()){
                    $("#overlay").fadeIn(300);
                    $.ajax({
                        url: "{{url('/generarReporteByNumeroCaja')}}",
                        method: "POST",
                        data: {
                            tipo_reporte: $('#tipo_reporte').val(),
                            numero_caja: $('#numero_caja').val(),
                            etiqueta: $('#etiqueta').val(),
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
                            a.download = 'test.xlsx';
                            document.body.append(a);
                            a.click();
                            a.remove();
                            window.URL.revokeObjectURL(url);
                        }
                    });
                }
            });

        });
     </script>
@endsection