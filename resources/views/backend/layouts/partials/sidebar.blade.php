 <!-- sidebar menu area start -->
 @php
     $usr = Auth::guard('admin')->user();
 @endphp
 <div class="sidebar-menu">
    <div class="sidebar-header">
        <div class="logo">
            <a href="{{ route('admin.dashboard') }}">
                <h2 class="text-white">Admin</h2> 
            </a>
        </div>
    </div>
    <div class="main-menu">
        <div class="menu-inner">
            <nav>
                <ul class="metismenu" id="menu">

                    @if ($usr->can('dashboard.view'))
                    <li class="active">
                        <a href="javascript:void(0)" aria-expanded="true"><i class="ti-dashboard"></i><span>dashboard</span></a>
                        <ul class="collapse">
                            <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('role.create') || $usr->can('role.view') ||  $usr->can('role.edit') ||  $usr->can('role.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-tasks"></i><span>
                            Roles & Permisos
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.roles.create') || Route::is('admin.roles.index') || Route::is('admin.roles.edit') || Route::is('admin.roles.show') ? 'in' : '' }}">
                            @if ($usr->can('role.view'))
                                <li class="{{ Route::is('admin.roles.index')  || Route::is('admin.roles.edit') ? 'active' : '' }}"><a href="{{ route('admin.roles.index') }}">Todos los Roles</a></li>
                            @endif
                            @if ($usr->can('role.create'))
                                <li class="{{ Route::is('admin.roles.create')  ? 'active' : '' }}"><a href="{{ route('admin.roles.create') }}">Crear Role</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    
                    @if ($usr->can('admin.create') || $usr->can('admin.view') ||  $usr->can('admin.edit') ||  $usr->can('admin.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-user"></i><span>
                            Admins
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.admins.create') || Route::is('admin.admins.index') || Route::is('admin.admins.edit') || Route::is('admin.admins.show') ? 'in' : '' }}">
                            
                            @if ($usr->can('admin.view'))
                                <li class="{{ Route::is('admin.admins.index')  || Route::is('admin.admins.edit') ? 'active' : '' }}"><a href="{{ route('admin.admins.index') }}">Todos los Admins</a></li>
                            @endif

                            @if ($usr->can('admin.create'))
                                <li class="{{ Route::is('admin.admins.create')  ? 'active' : '' }}"><a href="{{ route('admin.admins.create') }}">Crear Admin</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('configuracion.view'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                            Configuraciones
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.configuraciones.create') || Route::is('admin.configuraciones.index') || Route::is('admin.configuraciones.edit') || Route::is('admin.configuraciones.show') ? 'in' : '' }}">
                             @if ($usr->can('tipoFormato.create') || $usr->can('tipoFormato.view') ||  $usr->can('tipoFormato.edit') ||  $usr->can('tipoFormato.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Tipos Formato
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.tiposFormato.create') || Route::is('admin.tiposFormato.index') || Route::is('admin.tiposFormato.edit') || Route::is('admin.tiposFormato.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('tipoFormato.view'))
                                        <li class="{{ Route::is('admin.tiposFormato.index')  || Route::is('admin.tiposFormato.edit') ? 'active' : '' }}"><a href="{{ route('admin.tiposFormato.index') }}">Todos los Tipos Formato</a></li>
                                    @endif

                                    @if ($usr->can('tipoFormato.create'))
                                        <li class="{{ Route::is('admin.tiposFormato.create')  ? 'active' : '' }}"><a href="{{ route('admin.tiposFormato.create') }}">Crear Tipo Respuesta</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if ($usr->can('estructuraFormatoPago.create') || $usr->can('estructuraFormatoPago.create') || $usr->can('estructuraFormatoPago.view') ||  $usr->can('estructuraFormatoPago.edit') ||  $usr->can('estructuraFormatoPago.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Estructura Formato Pago
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.estructurasFormatoPago.create') || Route::is('admin.estructurasFormatoPago.index') || Route::is('admin.estructurasFormatoPago.edit') || Route::is('admin.estructurasFormatoPago.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('estructuraFormatoPago.view'))
                                        <li class="{{ Route::is('admin.estructurasFormatoPago.index')  || Route::is('admin.estructurasFormatoPago.edit') ? 'active' : '' }}"><a href="{{ route('admin.estructurasFormatoPago.index') }}">Todos las Estructuras Formato Pago</a></li>
                                    @endif

                                    @if ($usr->can('estructuraFormatoPago.create'))
                                        <li class="{{ Route::is('admin.estructurasFormatoPago.create')  ? 'active' : '' }}"><a href="{{ route('admin.estructurasFormatoPago.create') }}">Crear Estructura Formato Pago</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if ($usr->can('estructuraDocumentosHabilitantes.create') || $usr->can('estructuraDocumentosHabilitantes.view') ||  $usr->can('estructuraDocumentosHabilitantes.edit') ||  $usr->can('estructuraDocumentosHabilitantes.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Estructura Documentos Habilitantes
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.estructurasDocumentosHabilitantes.create') || Route::is('admin.estructurasDocumentosHabilitantes.index') || Route::is('admin.estructurasDocumentosHabilitantes.edit') || Route::is('admin.estructurasDocumentosHabilitantes.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('estructuraDocumentosHabilitantes.view'))
                                        <li class="{{ Route::is('admin.estructurasDocumentosHabilitantes.index')  || Route::is('admin.estructurasDocumentosHabilitantes.edit') ? 'active' : '' }}"><a href="{{ route('admin.estructurasDocumentosHabilitantes.index') }}">Todos las Estructuras Documentos Habilitantes</a></li>
                                    @endif

                                    @if ($usr->can('estructuraDocumentosHabilitantes.create'))
                                        <li class="{{ Route::is('admin.estructurasDocumentosHabilitantes.create')  ? 'active' : '' }}"><a href="{{ route('admin.estructurasDocumentosHabilitantes.create') }}">Crear Estructura Documentos Habilitantes</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if ($usr->can('estructuraResumenRemesa.create') || $usr->can('estructuraResumenRemesa.create') || $usr->can('estructuraResumenRemesa.view') ||  $usr->can('estructuraResumenRemesa.edit') ||  $usr->can('estructuraResumenRemesa.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Estructura Resumen Remesa
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.estructurasResumenRemesa.create') || Route::is('admin.estructurasResumenRemesa.index') || Route::is('admin.estructurasResumenRemesa.edit') || Route::is('admin.estructurasResumenRemesa.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('estructuraResumenRemesa.view'))
                                        <li class="{{ Route::is('admin.estructurasResumenRemesa.index')  || Route::is('admin.estructurasResumenRemesa.edit') ? 'active' : '' }}"><a href="{{ route('admin.estructurasResumenRemesa.index') }}">Todos las Estructuras Resumen Remesa</a></li>
                                    @endif

                                    @if ($usr->can('estructuraResumenRemesa.create'))
                                        <li class="{{ Route::is('admin.estructurasResumenRemesa.create')  ? 'active' : '' }}"><a href="{{ route('admin.estructurasResumenRemesa.create') }}">Crear Estructura Resumen Remesa</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if ($usr->can('estructuraLiquidacionEconomica.create') || $usr->can('estructuraLiquidacionEconomica.create') || $usr->can('estructuraLiquidacionEconomica.view') ||  $usr->can('estructuraLiquidacionEconomica.edit') ||  $usr->can('estructuraLiquidacionEconomica.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Estructura Liquidación Económica
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.estructurasLiquidacionEconomica.create') || Route::is('admin.estructurasLiquidacionEconomica.index') || Route::is('admin.estructurasLiquidacionEconomica.edit') || Route::is('admin.estructurasLiquidacionEconomica.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('estructuraLiquidacionEconomica.view'))
                                        <li class="{{ Route::is('admin.estructurasLiquidacionEconomica.index')  || Route::is('admin.estructurasLiquidacionEconomica.edit') ? 'active' : '' }}"><a href="{{ route('admin.estructurasLiquidacionEconomica.index') }}">Todos las Estructuras Liquidación Económica</a></li>
                                    @endif

                                    @if ($usr->can('estructuraLiquidacionEconomica.create'))
                                        <li class="{{ Route::is('admin.estructurasLiquidacionEconomica.create')  ? 'active' : '' }}"><a href="{{ route('admin.estructurasLiquidacionEconomica.create') }}">Crear Estructura Liquidación Económica</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('controlPrevio.create') || $usr->can('controlPrevio.view') ||  $usr->can('controlPrevio.edit') ||  $usr->can('controlPrevio.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                            Control Previo
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.controlesPrevios.create') || Route::is('admin.controlesPrevios.index') || Route::is('admin.controlesPrevios.edit') || Route::is('admin.controlesPrevios.show') ? 'in' : '' }}">
                            
                            @if ($usr->can('controlPrevio.view'))
                                <li class="{{ Route::is('admin.controlesPrevios.index')  || Route::is('admin.controlesPrevios.edit') ? 'active' : '' }}"><a href="{{ route('admin.controlesPrevios.index') }}">Todas los Controles Previos</a></li>
                            @endif

                            @if ($usr->can('controlPrevio.create'))
                                <li class="{{ Route::is('admin.controlesPrevios.create')  ? 'active' : '' }}"><a href="{{ route('admin.controlesPrevios.create') }}">Crear Control Previo</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('reporte.view') || $usr->can('reporteTramites.view'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-download"></i><span>
                            Reportes
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.oficios.index') || Route::is('admin.rezagados.index') || Route::is('admin.rezagadosLevantamientoObjeciones.index') || Route::is('admin.extemporaneos.index') ? 'in' : '' }}">
                            @if ($usr->can('reporte.view'))
                                <li class="{{ Route::is('admin.reportes.index')  ? 'active' : '' }}"><a href="{{ route('admin.reportes.index') }}">Reporte Etiquetado Caja</a></li>
                            @endif
                            @if ($usr->can('reporteTramites.view'))
                                <li class="{{ Route::is('admin.reportes.create')  ? 'active' : '' }}"><a href="{{ route('admin.reportes.create') }}">Generar Reporte</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                </ul>
            </nav>
        </div>
    </div>
</div>
<!-- sidebar menu area end -->