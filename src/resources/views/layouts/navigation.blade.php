<nav class="navbar" x-data="{ open: false }">
    <div class="navbar-inner">
        <!-- Logo y marca -->
        <a href="{{ route('dashboard') }}" class="navbar-brand">
            <img
                src="{{ asset('build/assets/Logo.jpg') }}"
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
        </ul>

        <!-- Acciones: Perfil y Menú Móvil -->
        <div class="navbar-actions">
            <!-- Menú de usuario -->
            <div class="navbar-user">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="user-menu-btn" aria-haspopup="true" aria-expanded="false" title="Menú de usuario">
                            <!-- Icono de usuario para móvil -->
                            <svg class="user-icon-mobile" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                            <span class="user-name-text">{{ Auth::user()->name }}</span>
                            <svg class="user-chevron" aria-hidden="true" width="16" height="16" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>
                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="dropdown-item">
                            {{ __('Perfil') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();"
                                class="dropdown-item">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Botón menú móvil -->
            <button
                class="navbar-toggle"
                @click="open = !open"
                :aria-expanded="open.toString()"
                aria-controls="mobile-menu"
                aria-label="Abrir menú de navegación">
                <svg aria-hidden="true" width="20" height="20" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex"
                          stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M4 6h16M4 12h16M4 18h16" />
                    <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden"
                          stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Menú móvil -->
    <div id="mobile-menu"
         class="navbar-mobile"
         :class="{'open': open}"
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