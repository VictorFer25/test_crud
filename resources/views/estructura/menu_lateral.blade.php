<div class="sne-siderbar-back sne-sidebarback-hide" id="sne-back-sidebar" sne-sidebar-status="show"
    style="z-index: 2000;"></div>

<nav class="d-md-block sidebar sne-sidebar-background shadow-lg sne-sidebar-hide" style="z-index: 2001;"
    id="sne-sidebar">
    <div class="sidebar-sticky">

        <div class="text-center sne-text-option text-secondary font-weight-bolder">
            MENÚ
            <hr class="border-dorado">
        </div>

        <ul class="nav flex-column">
            <li class="nav-item {{ request()->is('/') ? 'opened' : '' }}">
                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                    href="{{ route('producto.create') }}">
                    REGISTRAR PRODUCTOS
                </a>
            </li>

            <li class="nav-item {{ request()->is('lista-productos') ? 'opened' : '' }}">
                <a class="nav-link {{ request()->is('/lista-productos') ? 'active' : '' }}"
                    href="{{ route('producto.show') }}">
                    VER PRODUCTOS
                </a>
            </li>

        </ul>
    </div>
</nav>
