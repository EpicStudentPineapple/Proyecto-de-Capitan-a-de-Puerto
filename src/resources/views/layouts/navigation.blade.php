<nav class="navbar" x-data="{ mobileOpen: false }">
    <div class="navbar-inner">
        <!-- Logo y marca -->
        <a href="{{ route('dashboard') }}" class="navbar-brand">
            <img
                src="{{ asset('assets/Logo.jpg') }}"
                alt="Gestorinaitor 3000 — Dashboard"
                class="navbar-logo"
            >
        </a>

        <!-- Navegación escritorio -->
        <ul class="navbar-nav" role="list">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                   class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   {{ request()->routeIs('dashboard') ? 'aria-current=page' : '' }}>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('dashboard.trafico') }}"
                   class="nav-link {{ request()->routeIs('dashboard.trafico') ? 'active' : '' }}"
                   {{ request()->routeIs('dashboard.trafico') ? 'aria-current=page' : '' }}>
                    Tráfico
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('dashboard.clima') }}"
                   class="nav-link {{ request()->routeIs('dashboard.clima') ? 'active' : '' }}"
                   {{ request()->routeIs('dashboard.clima') ? 'aria-current=page' : '' }}>
                    Clima
                </a>
            </li>
            @if(Auth::user()->isAdmin())
            <li class="nav-item">
                <a href="{{ route('admin.buques.gestion-atraques') }}"
                   class="nav-link {{ request()->routeIs('admin.buques.gestion-atraques') ? 'active' : '' }}"
                   {{ request()->routeIs('admin.buques.gestion-atraques') ? 'aria-current=page' : '' }}>
                    Atraques
                </a>
            </li>
            @endif
            <li class="nav-item">
                <a href="{{ route('servicios.index') }}"
                   class="nav-link {{ request()->routeIs('servicios.*') ? 'active' : '' }}"
                   {{ request()->routeIs('servicios.*') ? 'aria-current=page' : '' }}>
                    Servicios
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('resenas.index') }}"
                   class="nav-link {{ request()->routeIs('resenas.*') ? 'active' : '' }}"
                   {{ request()->routeIs('resenas.*') ? 'aria-current=page' : '' }}>
                    Reseñas
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('dashboard.tutorial') }}"
                   class="nav-link {{ request()->routeIs('dashboard.tutorial') ? 'active' : '' }}"
                   {{ request()->routeIs('dashboard.tutorial') ? 'aria-current=page' : '' }}>
                    Tutorial
                </a>
            </li>
        </ul>

        <!-- Acciones: Perfil y Menú Móvil -->
        <div class="navbar-actions">

            <!-- Menú de usuario: INLINE Alpine con su propia variable, sin conflicto -->
            <div class="navbar-user"
                 x-data="{ userOpen: false }"
                 @click.outside="userOpen = false">

                <button
                    class="user-menu-btn"
                    @click="userOpen = !userOpen"
                    :aria-expanded="userOpen.toString()"
                    aria-haspopup="true"
                    title="Menú de usuario">
                    <!-- Icono para móvil (oculto en desktop via CSS) -->
                    <svg class="user-icon-mobile" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <span class="user-name-text">{{ Auth::user()->name }}</span>
                    <svg class="user-chevron" aria-hidden="true" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <!-- Panel dropdown -->
                <div
                    x-show="userOpen"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    @click="userOpen = false"
                    class="user-dropdown-panel"
                    x-cloak
                    style="display:none;">

                    <a href="{{ route('profile.edit') }}" class="dropdown-item">
                        {{ __('Perfil') }}
                    </a>

                    <div class="dropdown-divider"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            {{ __('Cerrar Sesión') }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Botón hamburger menú móvil -->
            <button
                class="navbar-toggle"
                @click="mobileOpen = !mobileOpen"
                :aria-expanded="mobileOpen.toString()"
                aria-controls="mobile-menu"
                aria-label="Abrir menú de navegación">
                <svg aria-hidden="true" width="20" height="20" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': mobileOpen, 'inline-flex': !mobileOpen}" class="inline-flex"
                          stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': !mobileOpen, 'inline-flex': mobileOpen}" class="hidden"
                          stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Menú móvil -->
    <div id="mobile-menu"
         class="navbar-mobile"
         :class="{'open': mobileOpen}"
         role="navigation"
         aria-label="Menú móvil">
        <a href="{{ route('dashboard') }}"
           class="mobile-nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            Dashboard
        </a>
        <a href="{{ route('dashboard.trafico') }}"
           class="mobile-nav-link {{ request()->routeIs('dashboard.trafico') ? 'active' : '' }}">
            Tráfico
        </a>
        <a href="{{ route('dashboard.clima') }}"
           class="mobile-nav-link {{ request()->routeIs('dashboard.clima') ? 'active' : '' }}">
            Clima
        </a>
        @if(Auth::user()->isAdmin())
        <a href="{{ route('admin.buques.gestion-atraques') }}"
           class="mobile-nav-link {{ request()->routeIs('admin.buques.gestion-atraques') ? 'active' : '' }}">
            Atraques
        </a>
        @endif
        <a href="{{ route('servicios.index') }}"
           class="mobile-nav-link {{ request()->routeIs('servicios.*') ? 'active' : '' }}">
            Servicios
        </a>
        <a href="{{ route('resenas.index') }}"
           class="mobile-nav-link {{ request()->routeIs('resenas.*') ? 'active' : '' }}">
            Reseñas
        </a>
        <a href="{{ route('dashboard.tutorial') }}"
           class="mobile-nav-link {{ request()->routeIs('dashboard.tutorial') ? 'active' : '' }}">
            Tutorial
        </a>

        <div class="mobile-user-info">
            <div class="mobile-user-name">{{ Auth::user()->name }}</div>
            <div class="mobile-user-email">{{ Auth::user()->email }}</div>
            <div style="margin-top: 0.75rem; display: flex; flex-direction: column; gap: 0.25rem;">
                <a href="{{ route('profile.edit') }}" class="mobile-nav-link">Perfil</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="mobile-nav-link" style="background:none;border:none;cursor:pointer;width:100%;text-align:left;font-family:inherit;">
                        Cerrar Sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>