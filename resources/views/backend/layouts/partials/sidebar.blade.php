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

                    @if ($usr->can('catalogo.view'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                            Catálogos
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.catalogos.create') || Route::is('admin.catalogos.index') || Route::is('admin.catalogos.edit') || Route::is('admin.catalogos.show') ? 'in' : '' }}">
                            @if ($usr->can('proteccion.create') || $usr->can('proteccion.view') ||  $usr->can('proteccion.edit') ||  $usr->can('proteccion.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Protecciones
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.protecciones.create') || Route::is('admin.protecciones.index') || Route::is('admin.protecciones.edit') || Route::is('admin.protecciones.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('proteccion.view'))
                                        <li class="{{ Route::is('admin.protecciones.index')  || Route::is('admin.protecciones.edit') ? 'active' : '' }}"><a href="{{ route('admin.protecciones.index') }}">Todos las Protecciones</a></li>
                                    @endif

                                    @if ($usr->can('proteccion.create'))
                                        <li class="{{ Route::is('admin.protecciones.create')  ? 'active' : '' }}"><a href="{{ route('admin.protecciones.create') }}">Crear Protección</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if ($usr->can('estado.create') || $usr->can('estado.create') || $usr->can('estado.view') ||  $usr->can('estado.edit') ||  $usr->can('estado.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Estados
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.estados.create') || Route::is('admin.estados.index') || Route::is('admin.estados.edit') || Route::is('admin.estados.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('estado.view'))
                                        <li class="{{ Route::is('admin.estados.index')  || Route::is('admin.estados.edit') ? 'active' : '' }}"><a href="{{ route('admin.estados.index') }}">Todos los Estados</a></li>
                                    @endif

                                    @if ($usr->can('estado.create'))
                                        <li class="{{ Route::is('admin.estados.create')  ? 'active' : '' }}"><a href="{{ route('admin.estados.create') }}">Crear Estados</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                            @if ($usr->can('tipoRespuesta.create') || $usr->can('tipoRespuesta.view') ||  $usr->can('tipoRespuesta.edit') ||  $usr->can('tipoRespuesta.delete'))
                            <li>
                                <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                                    Tipos Respuesta
                                </span></a>
                                <ul class="collapse {{ Route::is('admin.tiposRespuesta.create') || Route::is('admin.tiposRespuesta.index') || Route::is('admin.tiposRespuesta.edit') || Route::is('admin.tiposRespuesta.show') ? 'in' : '' }}">
                                    
                                    @if ($usr->can('tipoRespuesta.view'))
                                        <li class="{{ Route::is('admin.tiposRespuesta.index')  || Route::is('admin.tiposRespuesta.edit') ? 'active' : '' }}"><a href="{{ route('admin.tiposRespuesta.index') }}">Todos los Tipos Repuesta</a></li>
                                    @endif

                                    @if ($usr->can('tipoRespuesta.create'))
                                        <li class="{{ Route::is('admin.tiposRespuesta.create')  ? 'active' : '' }}"><a href="{{ route('admin.tiposRespuesta.create') }}">Crear Tipo Respuesta</a></li>
                                    @endif
                                </ul>
                            </li>
                            @endif

                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('semaforo.create') || $usr->can('semaforo.view') ||  $usr->can('semaforo.edit') ||  $usr->can('semaforo.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-list"></i><span>
                            Cofiguración Semaforo
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.semaforos.create') || Route::is('admin.semaforos.index') || Route::is('admin.semaforos.edit') || Route::is('admin.semaforos.show') ? 'in' : '' }}">
                            
                            @if ($usr->can('semaforo.view'))
                                <li class="{{ Route::is('admin.semaforos.index')  || Route::is('admin.semaforos.edit') ? 'active' : '' }}"><a href="{{ route('admin.semaforos.index') }}">Todas las Semaforos</a></li>
                            @endif

                            @if ($usr->can('semaforo.create'))
                                <li class="{{ Route::is('admin.semaforos.create')  ? 'active' : '' }}"><a href="{{ route('admin.semaforos.create') }}">Crear Semaforo</a></li>
                            @endif
                        </ul>
                    </li>
                    @endif

                    @if ($usr->can('expediente.create') || $usr->can('expediente.view') ||  $usr->can('expediente.edit') ||  $usr->can('expediente.delete'))
                    <li>
                        <a href="javascript:void(0)" aria-expanded="true"><i class="fa fa-file-text"></i><span>
                            Expedientes
                        </span></a>
                        <ul class="collapse {{ Route::is('admin.expedientes.create') || Route::is('admin.expedientes.index') || Route::is('admin.expedientes.edit') || Route::is('admin.expedientes.show') ? 'in' : '' }}">
                            
                            @if ($usr->can('expediente.view'))
                                <li class="{{ Route::is('admin.expedientes.index')  || Route::is('admin.expedientes.edit') ? 'active' : '' }}"><a href="{{ route('admin.expedientes.index') }}">Todos los Expedientes</a></li>
                            @endif

                            @if ($usr->can('expediente.create'))
                                <li class="{{ Route::is('admin.expedientes.create')  ? 'active' : '' }}"><a href="{{ route('admin.expedientes.create') }}">Crear Registro</a></li>
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