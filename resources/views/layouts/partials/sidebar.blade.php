<nav class="sidebar" id="sidebar">
    <a href="{{ route('home') }}" class="menu-item {{ Request::routeIs('home') ? 'active' : '' }}">
        <i class="fas fa-home"></i>
        <span>Dashboard</span>
    </a>
    
    <a href="{{ route('proyecto.index') }}" class="menu-item {{ Request::routeIs('proyecto.*') ? 'active' : '' }}">
        <i class="fas fa-project-diagram"></i>
        <span>Proyectos</span>
    </a>
    
    <a href="{{ route('cliente.index') }}" class="menu-item {{ Request::routeIs('cliente.*') ? 'active' : '' }}">
        <i class="fas fa-users"></i>
        <span>Clientes</span>
    </a>
    
    <a href="{{ route('tarea.index') }}" class="menu-item {{ Request::routeIs('tarea.*') ? 'active' : '' }}">
        <i class="fas fa-tasks"></i>
        <span>Tareas</span>
    </a>
    
    <a href="{{ route('empleado.index') }}" class="menu-item {{ Request::routeIs('empleado.*') ? 'active' : '' }}">
        <i class="fas fa-user-tie"></i>
        <span>Empleados</span>
    </a>
    
    <a href="{{ route('equipo_trabajo.index') }}" class="menu-item {{ Request::routeIs('equipo_trabajo.*') ? 'active' : '' }}">
        <i class="fas fa-users-cog"></i>
        <span>Equipos</span>
    </a>
    
    <a href="{{ route('recurso.index') }}" class="menu-item {{ Request::routeIs('recurso.*') ? 'active' : '' }}">
        <i class="fas fa-box-open"></i>
        <span>Recursos</span>
    </a>
    
    <a href="{{ route('factura.index') }}" class="menu-item {{ Request::routeIs('factura.*') ? 'active' : '' }}">
        <i class="fas fa-file-invoice-dollar"></i>
        <span>Facturas</span>
    </a>

    <div class="sidebar-footer">
        <a href="{{ route('profile.edit') }}" class="menu-item {{ Request::routeIs('profile.edit') ? 'active' : '' }}">
            <i class="fas fa-user-cog"></i>
            <span>Mi Perfil</span>
        </a>
    </div>
</nav>