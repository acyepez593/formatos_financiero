
@extends('backend.layouts.master')

@section('title')
Crear Control Previo - Admin Panel
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
                <h4 class="page-title pull-left">Crear Control Previo</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li><a href="{{ route('admin.controlesPrevios.index') }}">Todos los Controles Previos</a></li>
                    <li><span>Crear Control Previo</span></li>
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
                    <h4 class="header-title">Crear Nuevo Control Previo</h4>
                    @include('backend.layouts.partials.messages')
                    
                    <form action="{{ route('admin.controlesPrevios.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="nro_control_previo_y_concurrente">Informe de Control Previo y Control Concurrente No.</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('nro_control_previo_y_concurrente') is-invalid @enderror" id="nro_control_previo_y_concurrente" name="nro_control_previo_y_concurrente" placeholder="" value="{{ old('nro_control_previo_y_concurrente') }}" maxlength="100" required>
                                    @error('nro_control_previo_y_concurrente')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="fecha_tramite">Fecha</label>
                                <div class="datepicker date input-group">
                                    <input type="text" placeholder="" class="form-control @error('fecha_tramite') is-invalid @enderror" id="fecha_tramite" name="fecha_tramite" value="{{ old('fecha_tramite') }}" required>
                                    <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                                @error('fecha_tramite')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="solicitud_pago">Solicitud de Pago</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('solicitud_pago') is-invalid @enderror" id="solicitud_pago" name="solicitud_pago" placeholder="" value="{{ old('solicitud_pago') }}" maxlength="100" required>
                                    @error('solicitud_pago')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="solicitud_pago">Objeto</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('objeto') is-invalid @enderror" id="objeto" name="objeto" placeholder="" value="{{ old('objeto') }}" maxlength="1000" required>
                                    @error('objeto')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="beneficiario">Beneficiario</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('beneficiario') is-invalid @enderror" id="beneficiario" name="beneficiario" placeholder="" value="{{ old('beneficiario') }}" maxlength="1000" required>
                                    @error('beneficiario')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="ruc">RUC</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('ruc') is-invalid @enderror" id="ruc" name="ruc" placeholder="" value="{{ old('ruc') }}" minlength="13" maxlength="13" required>
                                    @error('ruc')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="mes">Mes</label>
                                <div class="datepicker date input-group">
                                    <input type="text" placeholder="" class="form-control @error('mes') is-invalid @enderror" id="mes" name="mes" value="{{ old('mes') }}" required>
                                    <div class="input-group-append">
                                    <span class="input-group-text"><i class="fa fa-calendar"></i></span>
                                    </div>
                                </div>
                                @error('mes')
                                    <div class="alert alert-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="valor">Valor</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('valor') is-invalid @enderror" id="valor" name="valor" placeholder="" value="{{ old('valor') }}" readonly required>
                                    @error('valor')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="tipo_formato_id">Seleccione un Formato:</label>
                                <select id="tipo_formato_id" name="tipo_formato_id" class="form-control selectpicker @error('tipo_formato_id') is-invalid @enderror" data-live-search="true">
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
                            <div class="card bg-light col-md-12">
                                <div class="card-header">Forma de Pago</div>
                                <div class="card-body">
                                <button type="button" style="margin-left:10px; magin-right:10px;" id="addRow" onclick="addRowFormaPago()" class="btn btn-primary">Agregar</button>
                                    <div class="form-row">
                                        
                                        <table id="dataTable" class="text-center" style="width: 100%;">
                                            <thead class="bg-light text-capitalize">
                                                
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="form-row">
                                        <h6 id="subtotal"><b>Subtotal: </b></h5>
                                        
                                    </div>
                                    <div class="form-row">
                                        <h6 id="iva"><b>IVA: </b></h5>
                                        
                                    </div>
                                    <div class="form-row">
                                        <h5 id=total><b>Total: </b></h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="card bg-light col-md-12">
                                <div class="card-header">Documentos Habilitantes</div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <table id="dataTable" class="text-center" style="width: 100%;">
                                            <thead class="bg-light text-capitalize">
                                                
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary mt-4 pr-4 pl-4">Guardar</button>
                        <a href="{{ route('admin.controlesPrevios.index') }}" class="btn btn-secondary mt-4 pr-4 pl-4">Cancelar</a>
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
    let table = "";
    let tableRef = "";
    let tableFotter = "";
    let tableHeaderRef = "";
    let primerReg = false;
    let estructurasFormatoPago = [];
    let estructurasDocumentosHabilitantes = [];
    let contador = 0;

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

        $( "#tipo_formato_id" ).on( "change", function() {
            $("#overlay").fadeIn(300);
            $('#dataTable').empty();

            var tabla = $('#dataTable');
            var thead = $('<thead></thead>').appendTo(tabla);
            var tbody = $('<tbody><tbody/>').appendTo(tabla);
            table = "";

            loadDataTable();
        });

        //Restringir solo numeros decimales
        $(document).on("input", ".decimal-number", function (e) {
            this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');
        });

        loadDataTable();
        
    })

    function loadDataTable(){
        
        $.ajax({
                url: "{{url('/getFormatoByTipoFormato')}}",
                method: "POST",
                data: {
                    tipo_formato_id: $('#tipo_formato_id').val(),
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (response) {

                    let header = "";
                    let innerHTML = "";
                    let htmlDelete = "";

                    estructurasFormatoPago = JSON.parse(response.estructurasFormatoPago[0].estructura);
                    estructurasDocumentosHabilitantes = JSON.parse(response.estructurasDocumentosHabilitantes[0].estructura);
                    console.log(estructurasFormatoPago);
                    tableHeaderRef = document.getElementById('dataTable').getElementsByTagName('thead')[0];
                    
                    for (let estructuraFormatoPago of estructurasFormatoPago) {
                        header += "<th>" + estructuraFormatoPago.texto + "</th>";
                    }
                    header += "<th>Acciones</th>";
                    tableHeaderRef.insertRow().innerHTML = header;
                    tableRef = document.getElementById('dataTable').getElementsByTagName('tbody')[0];
                    
                }
            });
        
    }

    function addRowFormaPago(){

        let header = "";
        let innerHTML = "";
        let htmlDelete = '<button type="button" class="btn btn-danger text-white" style="margin-left:10px; magin-right:10px;" id="addRow_"'+contador+' onclick="deleteRowFormaPago(this)" class="btn btn-primary">Borrar</button>';

        for (let estructuraFormatoPago of estructurasFormatoPago) {
            let id = estructuraFormatoPago.campo_id + "_"+contador;
            switch(estructuraFormatoPago.type) {
            case "text":
                innerHTML += '<td><input type="text" class="form-control ' + estructuraFormatoPago.class + '" id="' + id  + '" name="' + id + '" placeholder="" value="" onchange="' + estructuraFormatoPago.onchange + '" maxlength="300" ' + estructuraFormatoPago.required + ' ' + estructuraFormatoPago.readonly + '></td>';
                break;
            case "number":
                innerHTML += '<td><input type="number" class="form-control ' + estructuraFormatoPago.class + '" id="' + id + '" name="' + id + '" placeholder="" value="" onchange="' + estructuraFormatoPago.onchange + '" maxlength="300" ' + estructuraFormatoPago.required + ' ' + estructuraFormatoPago.readonly + '></td>';
                break;
            case "date":
                innerHTML += '<td><input type="date" class="form-control ' + estructuraFormatoPago.class + '" id="' + id + '" name="' + id + '" placeholder="" value="" ' + estructuraFormatoPago.required + ' ' + estructuraFormatoPago.readonly + '></td>';
                break;
            default:
                innerHTML += '<td><input type="text" class="form-control ' + estructuraFormatoPago.class + '" id="' + id + '" name="' + id + '" placeholder="" value="" ' + estructuraFormatoPago.required + ' ' + estructuraFormatoPago.readonly + '></td>';
            }
        }

        innerHTML += '<td>'+htmlDelete+'</td>';
        tableRef.insertRow().innerHTML = innerHTML;

        contador ++;
        
    }

    function deleteRowFormaPago(r){
        var i = r.parentNode.parentNode.rowIndex;
        document.getElementById("dataTable").deleteRow(i);
        calcularTotales();
    }

    function calcularTotalPorFila(campo){

        let id = campo.split("_")[1];
        if($("#subtotal_"+id).val() != "" && $("#iva_"+id).val() != ""){
            $("#total_"+id).val(parseFloat($("#subtotal_"+id).val()) + parseFloat($("#iva_"+id).val()));
        }else{
            $("#total_"+id).val("");
        }

        calcularTotales();
        
    }

    function calcularTotales(){
        let row = 0;
        let subtotal = 0;
        let iva = 0;
        let total = 0;
        let header = [];

        for (let estructuraFormatoPago of estructurasFormatoPago) {
            header.push(estructuraFormatoPago.campo_id);
        }

        $('#dataTable tr').each(function(){
            let column = 0;
            if(row != 0){
                $(this).find('td').each(function(){
                    if(header[column] !== undefined){
                        if(header[column] == "subtotal" && $($(this).context.children).val() != ""){
                            subtotal += parseFloat($($(this).context.children).val()); 
                        }
                        if(header[column] == "iva" && $($(this).context.children).val() != ""){
                            iva += parseFloat($($(this).context.children).val()); 
                        }
                        if(header[column] == "total" && $($(this).context.children).val() != ""){
                            total += parseFloat($($(this).context.children).val()); 
                        }
                    }
                    column ++;
                });
            }
            row ++;
        });
        $('#subtotal').html("<b>Subtotal: </b> $" + subtotal);
        $('#iva').html("<b>IVA: </b> $" + iva);
        $('#total').html("<b>Total: </b> $" + total);
        $('#valor').val(total);
    }

    function buildObject(){
        
        let row = 0;
        let header = [];
        let jsonArrObj = [];

        for (let estructuraFormatoPago of estructurasFormatoPago) {
            header.push(estructuraFormatoPago.campo_id);
        }

        $('#dataTable tr').each(function(){
            let jsonObj = {};
            let column = 0;
            if(row != 0){
                jsonObj[header[row-1]] = "";
                $(this).find('td').each(function(){
                    if(header[column] !== undefined){
                        jsonObj[header[column]] = $($(this).context.children).val();
                    }
                    column ++;
                })
                jsonArrObj.push(jsonObj);
            }
            row ++;
        })
        console.log(jsonArrObj);
    }

</script>
@endsection