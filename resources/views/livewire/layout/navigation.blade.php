<?php
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    // Componente Livewire vacío (se usa el formulario tradicional para logout)
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- ... (parte superior igual) ... -->

    <x-slot name="content">
        <!-- Debug: Verificar usuario y roles -->
        @php
            $user = auth()->user();
            $isSuperAdmin = $user && $user->hasRole('SuperAdmin');
            $isAdmin = $user && $user->hasRole('Administrador');
            $isCompanyAdmin = $user && $user->hasRole('Administrador de Empresa');
        @endphp

        <!-- Menú Cuenta -->
        <x-dropdown-link :href="route('perfil.index')">
            {{ __('Administrar Cuenta') }}
        </x-dropdown-link>

        <div class="border-t border-gray-200"></div>

        <!-- Sección Administración -->
        @if($isSuperAdmin || $isAdmin || $isCompanyAdmin)
        <div class="block px-4 py-2 text-xs text-gray-400">
            {{ __('Administración') }}
            <div class="text-xs text-gray-500">
                @if($isSuperAdmin) SuperAdmin @endif
                @if($isAdmin) | Administrador @endif
                @if($isCompanyAdmin) | Admin Empresa @endif
            </div>
        </div>

        <!-- Empresas -->
        @if($isSuperAdmin)
        <x-dropdown-link :href="route('companies.index')">
            {{ __('Empresas') }}
        </x-dropdown-link>
        @endif

        <!-- Usuarios -->
        @if($user->can('view_users'))
        <x-dropdown-link :href="route('admin.users.index')">
            {{ __('Usuarios') }}
        </x-dropdown-link>
        @endif

        <!-- Roles -->
        @if($user->can('view_roles'))
        <x-dropdown-link :href="route('admin.roles.index')">
            {{ __('Roles y permisos') }}
        </x-dropdown-link>
        @endif

        <!-- Auditoría -->
        @if($user->can('view_logs'))
        <x-dropdown-link :href="route('admin.logs')">
            {{ __('Auditoría') }}
        </x-dropdown-link>
        @endif

        <!-- Configuración -->
        @if($isSuperAdmin)
        <x-dropdown-link :href="route('admin.settings')">
            {{ __('Configuración') }}
        </x-dropdown-link>
        @endif

        <div class="border-t border-gray-200"></div>
        @endif

        <!-- Cerrar Sesión -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-dropdown-link :href="route('logout')"
                    onclick="event.preventDefault();
                                this.closest('form').submit();">
                {{ __('Cerrar Sesión') }}
            </x-dropdown-link>
        </form>
    </x-slot>
</nav>