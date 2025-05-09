@extends('backend.layouts.master')

@section('title')
    {{ __('Estructura Liquidación Económica - Panel de Estructura Liquidación Económica') }}
@endsection

@section('styles')
    <!-- Start datatable css -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.18/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.jqueryui.min.css">
@endsection

@section('admin-content')

<!-- page title area start -->
<div class="page-title-area">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <div class="breadcrumbs-area clearfix">
                <h4 class="page-title pull-left">{{ __('Estructuras Liquidación Económica') }}</h4>
                <ul class="breadcrumbs pull-left">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><span>{{ __('Todos las Estructuras Liquidación Económica') }}</span></li>
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
                    <h4 class="header-title float-left">{{ __('Estructuras Liquidación Económica') }}</h4>
                    <p class="float-right mb-2">
                        @if (auth()->user()->can('estructuraLiquidacionEconomica.create'))
                            <a class="btn btn-primary text-white" href="{{ route('admin.estructurasLiquidacionEconomica.create') }}">
                                {{ __('Crear Nueva Estructura Liquidación Económica') }}
                            </a>
                        @endif
                    </p>
                    <div class="clearfix"></div>
                    <div class="data-tables">
                        @include('backend.layouts.partials.messages')
                        <table id="dataTable" class="text-center">
                            <thead class="bg-light text-capitalize">
                                <tr>
                                    <th>{{ __('#') }}</th>
                                    <th>{{ __('Descripción') }}</th>
                                    <th>{{ __('Tipo Formato') }}</th>
                                    <th>{{ __('Estructura') }}</th>
                                    <th>{{ __('Acción') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                               @foreach ($estructurasLiquidacionEconomica as $estructuraLiquidacionEconomica)
                               <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{ $estructuraLiquidacionEconomica->descripcion }}</td>
                                    <td>{{ $estructuraLiquidacionEconomica->nombre_formato_pago }}</td>
                                    <td>{{ $estructuraLiquidacionEconomica->estructura }}</td>
                                    <td>
                                        @if (auth()->user()->can('estructuraLiquidacionEconomica.edit'))
                                            <a class="btn btn-success text-white" href="{{ route('admin.estructurasLiquidacionEconomica.edit', $estructuraLiquidacionEconomica->id) }}">Editar</a>
                                        @endif
                                        
                                        @if (auth()->user()->can('estructuraLiquidacionEconomica.delete'))
                                        <a class="btn btn-danger text-white" href="javascript:void(0);"
                                        onclick="event.preventDefault(); if(confirm('Esta seguro de que quiere borrar este registro?')) { document.getElementById('delete-form-{{ $estructuraLiquidacionEconomica->id }}').submit(); }">
                                            {{ __('Borrar') }}
                                        </a>

                                        <form id="delete-form-{{ $estructuraLiquidacionEconomica->id }}" action="{{ route('admin.estructurasLiquidacionEconomica.destroy', $estructuraLiquidacionEconomica->id) }}" method="POST" style="display: none;">
                                            @method('DELETE')
                                            @csrf
                                        </form>
                                        @endif
                                    </td>
                                    
                                </tr>
                               @endforeach
                            </tbody>
                        </table>
                    </div>
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
     
     <script>
        if ($('#dataTable').length) {
            $('#dataTable').DataTable({
                responsive: true
            });
        }
     </script>
@endsection