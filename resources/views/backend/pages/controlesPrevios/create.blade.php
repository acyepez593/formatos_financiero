
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
    .card-header{
        font-size: larger;
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
                                <label for="contrato">Contrato</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('contrato') is-invalid @enderror" id="contrato" name="contrato" placeholder="" value="{{ old('contrato') }}" maxlength="100">
                                    @error('contrato')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="objeto">Objeto</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('objeto') is-invalid @enderror" id="objeto" name="objeto" placeholder="" value="{{ old('objeto') }}" maxlength="1000" required>
                                    @error('objeto')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="beneficiario">Beneficiario</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('beneficiario') is-invalid @enderror" id="beneficiario" name="beneficiario" placeholder="" value="{{ old('beneficiario') }}" maxlength="1000">
                                    @error('beneficiario')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="ruc">RUC</label>
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control @error('ruc') is-invalid @enderror" id="ruc" name="ruc" placeholder="" value="{{ old('ruc') }}" minlength="13" maxlength="13">
                                    @error('ruc')
                                        <div class="alert alert-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
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
                        </div>
                        <div class="form-row">
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
                        <div class="form-group col-md-12 col-sm-12">
                            <div class="card bg-light col-md-12">
                                <div class="card-header">Forma de Pago</div>
                                <div class="card-body">
                                    <button type="button" style="margin-left:10px; magin-right:10px;" id="addRow" onclick="addRowFormaPago()" class="btn btn-primary">Agregar</button>
                                    <div class="form-row">
                                        
                                        <table id="dataTableFormaPago" class="text-center" style="width: 100%;">
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
                        <div class="form-group col-md-12 col-sm-12">
                            <div class="card bg-light col-md-12" style="margin-top: 15px;">
                                <div class="card-header">Documentos Habilitantes</div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <table id="dataTableDocumentosHabilitantes" class="text-center" style="width: 100%;">
                                            <thead class="bg-light text-capitalize">
                                                
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12">
                            <div class="card bg-light col-md-12" style="margin-top: 15px;">
                                <div class="card-header">Resumen de Remesa</div>
                                <div class="card-body">
                                    <div id="dataResumenRemesa" style="width: 100%;"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group col-md-12 col-sm-12">
                            <div class="card bg-light col-md-12" style="margin-top: 15px;">
                                <div class="card-header">Liquidación Económica</div>
                                <div class="card-body">
                                    <button type="button" style="margin-left:10px; magin-right:10px;" id="addRowLiquidacionEconomica" onclick="addRowLiquidacionEconomica()" class="btn btn-primary">Agregar</button>
                                    <div class="form-row">
                                        <table id="dataTableLiquidacionEconomica" class="text-center" style="width: 100%;">
                                            <thead class="bg-light text-capitalize">
                                                
                                            </thead>
                                            <tbody>
                                            
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <input type="hidden" id="forma_pago" name="forma_pago" value="">
                        <input type="hidden" id="documentos_habilitantes" name="documentos_habilitantes" value="">
                        <input type="hidden" id="resumen_remesa" name="resumen_remesa" value="">
                        <input type="hidden" id="liquidacion_economica" name="liquidacion_economica" value="">
                        <button type="submit" onclick="buildObject()" class="btn btn-primary mt-4 pr-4 pl-4">Guardar</button>
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
    let tableRefDocH = "";
    let tableFotter = "";
    let tableHeaderRef = "";
    let tableHeaderRefDocHab = "";
    let tableHeaderRefLiqEco = "";
    let primerReg = false;
    let estructurasFormatoPago = [];
    let estructurasDocumentosHabilitantes = [];
    let contador = 0;
    let contadorDocH = 0;
    let contadorColumnas = 0;

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

        $('#mes').datepicker( {
            language: 'es', 
            changeMonth: true,
            changeYear: true,
            showButtonPanel: true,
            format: 'MM-yyyy',
            viewMode: "months",
            minViewMode: "months",
            todayHighlight: true,
            autoclose: true,
            onClose: function(dateText, inst) { 
                $(this).datepicker('setDate', new Date(inst.selectedYear, inst.selectedMonth, 1));
            }
        });

        $( "#tipo_formato_id" ).on( "change", function() {
            $("#overlay").fadeIn(300);
            $('#dataTableFormaPago').empty();
            $('#dataTableDocumentosHabilitantes').empty();
            $('#dataResumenRemesa').empty();
            $('#dataTableLiquidacionEconomica').empty();

            var tabla = $('#dataTableFormaPago');
            var thead = $('<thead></thead>').appendTo(tabla);
            var tbody = $('<tbody><tbody/>').appendTo(tabla);
            table = "";

            tabla = $('#dataTableDocumentosHabilitantes');
            var thead = $('<thead></thead>').appendTo(tabla);
            var tbody = $('<tbody><tbody/>').appendTo(tabla);
            table = "";

            tabla = $('#dataTableLiquidacionEconomica');
            var thead = $('<thead></thead>').appendTo(tabla);
            var tbody = $('<tbody><tbody/>').appendTo(tabla);
            table = "";

            loadDataTable();
        });

        //Restringir solo numeros enteros
        $(document).on("input", ".int-number", function (e) {
            this.value = this.value.replace(/[^0-9]/g, '');
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
                    let headerDocumentosHabilitantes = "";
                    let innerHTMLDocumentosHabilitantes = "";
                    let headerLiqEco = "";
                    let innerHTMLLiqEco = "";

                    estructurasFormatoPago = JSON.parse(response.estructurasFormatoPago[0].estructura);
                    estructurasDocumentosHabilitantes = JSON.parse(response.estructurasDocumentosHabilitantes[0].estructura);
                    estructurasResumenRemesa = JSON.parse(response.estructurasResumenRemesa[0].estructura);
                    estructurasLiquidacionEconomica = JSON.parse(response.estructurasLiquidacionEconomica[0].estructura);
                    tableHeaderRef = document.getElementById('dataTableFormaPago').getElementsByTagName('thead')[0];
                    
                    //Formato Pago
                    for (let estructuraFormatoPago of estructurasFormatoPago) {
                        header += "<th>" + estructuraFormatoPago.texto + "</th>";
                    }
                    header += "<th>Acciones</th>";
                    tableHeaderRef.insertRow().innerHTML = header;
                    tableRef = document.getElementById('dataTableFormaPago').getElementsByTagName('tbody')[0];

                    //Documentos Habilitantes
                    if(estructurasDocumentosHabilitantes.mostrarHeader){
                        tableHeaderRefDocHab = document.getElementById('dataTableDocumentosHabilitantes').getElementsByTagName('thead')[0];
                        for (let estructuraDocumentosHabilitantes of estructurasDocumentosHabilitantes.estructura) {
                            headerDocumentosHabilitantes += "<th>" + estructuraDocumentosHabilitantes.texto + "</th>";
                        }
                        tableHeaderRefDocHab.insertRow().innerHTML = headerDocumentosHabilitantes;
                    }
                    addRowDocumentosHabilitantes(estructurasDocumentosHabilitantes);

                    //Resumen Remesa
                    addFieldsResumenRemesa(estructurasResumenRemesa);

                    //Liquidación Económica
                    if(estructurasLiquidacionEconomica.direccion_header == "horizontal"){
                        for (let estructuraLiquidacionEconomica of estructurasLiquidacionEconomica.estructura) {
                            headerLiqEco += "<th>" + estructuraLiquidacionEconomica.texto + "</th>";
                        }
                        headerLiqEco += "<th>Acciones</th>";
                        tableHeaderRefLiqEco.insertRow().innerHTML = headerLiqEco;
                        tableRefLiqEco = document.getElementById('dataTableLiquidacionEconomica').getElementsByTagName('tbody')[0];
                    }else if(estructurasLiquidacionEconomica.direccion_header == "vertical"){
                        tableRefLiqEco = document.getElementById('dataTableLiquidacionEconomica').getElementsByTagName('tbody')[0];
                        $('#addRowLiquidacionEconomica').hide();
                        for (let estructuraLiquidacionEconomica of estructurasLiquidacionEconomica.estructura) {
                            innerHTMLLiqEco += "<th>" + estructuraLiquidacionEconomica.texto + "</th>";
                            for(let i = 0; i < estructurasLiquidacionEconomica.numero_columnas; i++){
                                innerHTMLLiqEco += addColLiquidacionEconomica(estructuraLiquidacionEconomica);
                            }
                            tableRefLiqEco.insertRow().innerHTML = innerHTMLLiqEco;
                            innerHTMLLiqEco = "";
                        }
                    }
                    
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
        document.getElementById("dataTableFormaPago").deleteRow(i);
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

        $('#dataTableFormaPago tr').each(function(){
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

    function addRowDocumentosHabilitantes(estructurasDocumentosHabilitantes){
        
        tableRefDocH = document.getElementById('dataTableDocumentosHabilitantes').getElementsByTagName('tbody')[0];

        for (let documento of estructurasDocumentosHabilitantes.documentos) {
            let innerHTML = "";
            innerHTML += '<td width="50%">' + documento + '</td>';
            for (let estructuraDocumentosHabilitantes of estructurasDocumentosHabilitantes.estructura) {
                if(estructuraDocumentosHabilitantes.campo_id != "documento" ){
                    let id = estructuraDocumentosHabilitantes.campo_id + "_"+contadorDocH;
                    switch(estructuraDocumentosHabilitantes.type) {
                    case "text":
                        innerHTML += '<td><input type="text" class="form-control ' + estructuraDocumentosHabilitantes.class + '" id="' + id  + '" name="' + id + '" placeholder="" value="" onchange="' + estructuraDocumentosHabilitantes.onchange + '" maxlength="300" ' + estructuraDocumentosHabilitantes.required + ' ' + estructuraDocumentosHabilitantes.readonly + '></td>';
                        break;
                    case "number":
                        innerHTML += '<td><input type="number" class="form-control ' + estructuraDocumentosHabilitantes.class + '" id="' + id + '" name="' + id + '" placeholder="" value="" onchange="' + estructuraDocumentosHabilitantes.onchange + '" maxlength="300" ' + estructuraDocumentosHabilitantes.required + ' ' + estructuraDocumentosHabilitantes.readonly + '></td>';
                        break;
                    case "date":
                        innerHTML += '<td><input type="date" class="form-control ' + estructuraDocumentosHabilitantes.class + '" id="' + id + '" name="' + id + '" placeholder="" value="" ' + estructuraDocumentosHabilitantes.required + ' ' + estructuraDocumentosHabilitantes.readonly + '></td>';
                        break;
                    default:
                        innerHTML += '<td><input type="text" class="form-control ' + estructuraDocumentosHabilitantes.class + '" id="' + id + '" name="' + id + '" placeholder="" value="" ' + estructuraDocumentosHabilitantes.required + ' ' + estructuraDocumentosHabilitantes.readonly + '></td>';
                    }
                }
            }
            tableRefDocH.insertRow().innerHTML = innerHTML;
            contadorDocH ++;
        }

    }

    function addFieldsResumenRemesa(estructurasResumenRemesa){
        let innerHTML = '<div class="form-row">';
        let long = estructurasResumenRemesa.length;
        let count = 1;

        for(let e of estructurasResumenRemesa){
            innerHTML += 
            '<div class="form-group col-md-6 col-sm-12">'+
            '<label for="' + e.campo_id + '">' + e.texto + '</label>'+
                '<div class="input-group mb-3">'+
                    addFieldResumenRemesa(e)+
                '</div>'+
            '</div>';
            if(long == count){
                innerHTML +='</div>';
            }else{
                if(count % 2 === 0){
                    innerHTML +='</div><div class="form-row">';
                }
            }
            count ++;
        }

        $("#dataResumenRemesa").append(innerHTML);
    }

    function addFieldResumenRemesa(e){

        let innerHTML = "";
        let id = e.campo_id;

        switch(e.type) {
        case "text":
            innerHTML += '<input type="text" class="form-control ' + e.class + '" id="' + id  + '" name="' + id + '" placeholder="" value="" onchange="' + e.onchange + '" maxlength="300" ' + e.required + ' ' + e.readonly + '>';
            break;
        case "number":
            innerHTML += '<input type="number" class="form-control ' + e.class + '" id="' + id + '" name="' + id + '" placeholder="" value="" onchange="' + e.onchange + '" maxlength="300" ' + e.required + ' ' + e.readonly + '>';
            break;
        case "date":
            innerHTML += '<input type="date" class="form-control ' + e.class + '" id="' + id + '" name="' + id + '" placeholder="" value="" ' + e.required + ' ' + e.readonly + '>';
            break;
        default:
            innerHTML += '<input type="text" class="form-control ' + e.class + '" id="' + id + '" name="' + id + '" placeholder="" value="" ' + e.required + ' ' + e.readonly + '>';
        }
        
        return innerHTML;
    }

    function addColLiquidacionEconomica(estructuraLiquidacionEconomica){

        let innerHTML = "";

        let id = estructuraLiquidacionEconomica.campo_id + "_"+contadorColumnas;
        switch(estructuraLiquidacionEconomica.type) {
        case "text":
            innerHTML += '<td><input type="text" class="form-control ' + estructuraLiquidacionEconomica.class + '" id="' + id  + '" name="' + id + '" placeholder="" value="" onchange="' + estructuraLiquidacionEconomica.onchange + '" maxlength="300" ' + estructuraLiquidacionEconomica.required + ' ' + estructuraLiquidacionEconomica.readonly + '></td>';
            break;
        case "number":
            innerHTML += '<td><input type="number" class="form-control ' + estructuraLiquidacionEconomica.class + '" id="' + id + '" name="' + id + '" placeholder="" value="" onchange="' + estructuraLiquidacionEconomica.onchange + '" maxlength="300" ' + estructuraLiquidacionEconomica.required + ' ' + estructuraLiquidacionEconomica.readonly + '></td>';
            break;
        case "date":
            innerHTML += '<td><input type="date" class="form-control ' + estructuraLiquidacionEconomica.class + '" id="' + id + '" name="' + id + '" placeholder="" value="" ' + estructuraLiquidacionEconomica.required + ' ' + estructuraLiquidacionEconomica.readonly + '></td>';
            break;
        default:
            innerHTML += '<td><input type="text" class="form-control ' + estructuraLiquidacionEconomica.class + '" id="' + id + '" name="' + id + '" placeholder="" value="" ' + estructuraLiquidacionEconomica.required + ' ' + estructuraLiquidacionEconomica.readonly + '></td>';
        }
        
        contadorColumnas ++;

        return innerHTML;

    }

    function buildObject(){
        
        let rowFormaPago = 0;
        let rowDocumentosHabilitantes = 0;
        let rowLiquidacionEconomica = 0;
        let headerFormaPago = [];
        let headerDocumentosHabilitantes = [];
        let headerResumenRemesa = [];
        let headerLiquidacionEconomica = [];
        let jsonArrObjFormaPago = [];
        let jsonArrObjDocumentosHabilitantes = [];
        let jsonArrObjResumenRemesa = {};
        let jsonArrObjLiquidacionEconomica = [];
        let jsonColLiquidacionEconomica = [];
        let formaPago = {};
        let documentosHabilitantes = {};
        let resumenRemesa = [];
        let liquidacionEconomica = {};

        //Forma Pago
        for (let estructuraFormatoPago of estructurasFormatoPago) {
            headerFormaPago.push(estructuraFormatoPago.campo_id);
        }

        $('#dataTableFormaPago tr').each(function(){
            let jsonObj = {};
            let column = 0;
            if(rowFormaPago != 0){
                jsonObj[headerFormaPago[rowFormaPago-1]] = "";
                $(this).find('td').each(function(){
                    if(headerFormaPago[column] !== undefined){
                        jsonObj[headerFormaPago[column]] = $($(this).context.children).val();
                    }
                    column ++;
                })
                jsonArrObjFormaPago.push(jsonObj);
            }
            rowFormaPago ++;
        })
        formaPago = jsonArrObjFormaPago;
        $('#forma_pago').val(JSON.stringify(formaPago));
        console.log(formaPago);

        //Documentos Habilitantes
        for (let estructuraDocumentosHabilitantes of estructurasDocumentosHabilitantes.estructura) {
            headerDocumentosHabilitantes.push(estructuraDocumentosHabilitantes.campo_id);
        }

        $('#dataTableDocumentosHabilitantes tr').each(function(){
            if($(this).find('td').length > 0){
                let jsonObj = {};
                let column = 0;
                
                jsonObj[headerDocumentosHabilitantes[column]] = "";
                $(this).find('td').each(function(){
                    if(headerDocumentosHabilitantes[column] !== undefined){
                        if(column == 0){
                            jsonObj[headerDocumentosHabilitantes[column]] = this.textContent;
                        }else{
                            jsonObj[headerDocumentosHabilitantes[column]] = $($(this).context.children).val();
                        }
                    }
                    column ++;
                })
                jsonArrObjDocumentosHabilitantes.push(jsonObj);
                //rowDocumentosHabilitantes ++;
            }
        })
        documentosHabilitantes = jsonArrObjDocumentosHabilitantes;
        console.log(documentosHabilitantes);
        $('#documentos_habilitantes').val(JSON.stringify(documentosHabilitantes));


        //Resumen Remesa
        for (let estructuraResumenRemesa of estructurasResumenRemesa) {
            jsonArrObjResumenRemesa[estructuraResumenRemesa.campo_id] = $('#'+estructuraResumenRemesa.campo_id).val();
        }
        resumenRemesa.push(jsonArrObjResumenRemesa);
        console.log(resumenRemesa);
        $('#resumen_remesa').val(JSON.stringify(resumenRemesa));
        
        //Liquidación Económica
        let arrObjTemp = [];
        
        for (let estructuraLiquidacionEconomica of estructurasLiquidacionEconomica.estructura) {
            headerLiquidacionEconomica.push(estructuraLiquidacionEconomica.campo_id);
        }

        for (let i=0;i<estructurasLiquidacionEconomica.numero_columnas;i++) {
            arrObjTemp[i] = {};
        }

        $('#dataTableLiquidacionEconomica tr').each(function(){
            let jsonObj = {};
            let column = 0;

            if(estructurasLiquidacionEconomica.direccion_header == "horizontal"){
                if(rowLiquidacionEconomica != 0){
                    jsonObj[headerLiquidacionEconomica[rowLiquidacionEconomica]] = "";
                    $(this).find('td').each(function(){
                        if(headerLiquidacionEconomica[column] !== undefined){
                            if(column == 0){
                                jsonObj[headerLiquidacionEconomica[column]] = this.textContent;
                            }else{
                                jsonObj[headerLiquidacionEconomica[column]] = $($(this).context.children).val();
                            }
                        }
                        column ++;
                    })
                    jsonArrObjLiquidacionEconomica.push(jsonObj);
                }
                rowLiquidacionEconomica ++;
            }else if(estructurasLiquidacionEconomica.direccion_header == "vertical"){
                
                $(this).find('td').each(function(){
                    if(headerLiquidacionEconomica[rowLiquidacionEconomica] !== undefined){
                        arrObjTemp[column][headerLiquidacionEconomica[rowLiquidacionEconomica]] = $($(this).context.children).val();
                    }
                    column ++;
                })
                rowLiquidacionEconomica ++;
            }
            
        })

        if(estructurasLiquidacionEconomica.direccion_header == "horizontal"){
            liquidacionEconomica = jsonArrObjLiquidacionEconomica;
        }else if(estructurasLiquidacionEconomica.direccion_header == "vertical"){
            liquidacionEconomica = arrObjTemp;
        }
        
        console.log(liquidacionEconomica);
        $('#liquidacion_economica').val(JSON.stringify(liquidacionEconomica));
    }

</script>
@endsection