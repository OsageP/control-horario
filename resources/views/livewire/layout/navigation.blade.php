<?php
use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {}; // Mantenemos el componente vacío ya que usamos formulario tradicional
?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- ... (parte superior del nav igual que antes) ... -->

    <!-- Settings Dropdown actualizado -->
    <div class="hidden sm:flex sm:items-center sm:ml-6">
        @auth
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                    <div>{{ Auth::user()->name }}</div>
                    <div class="ml-1">
                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </button>
            </x-slot>

            <x-slot name="content">
                <!-- Sección de Usuario -->
                <div class="block px-4 py-2 text-xs text-gray-400">
                    {{ __('Administrar Cuenta') }}
                </div>
                <x-dropdown-link href="{{ route('profile') }}">
                    {{ __('Mi Perfil') }}
                </x-dropdown-link>

                <!-- Sección de Configuración -->
                @canany(['view users', 'view roles'])
                <div class="border-t border-gray-200"></div>
                <div class="block px-4 py-2 text-xs text-gray-400">
                    {{ __('Configuración') }}
                </div>
                
                @can('view users')
                <x-dropdown-link href="{{ route('admin.users') }}">
                    {{ __('Usuarios') }}
                </x-dropdown-link>
                @endcan
                
                @can('view roles')
                <x-dropdown-link href="{{ route('admin.roles') }}">
                    {{ __('Roles') }}
                </x-dropdown-link>
                @endcan
                @endcanany

                <!-- Cerrar Sesión -->
                <div class="border-t border-gray-200"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100">
                        {{ __('Cerrar Sesión') }}
                    </button>
                </form>
            </x-slot>
        </x-dropdown>
        @endauth
    </div>

    <!-- Menú responsive (versión móvil) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <!-- ... (otros elementos del menú móvil) ... -->
        
        @auth
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                <div class="ml-3">
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Sección Usuario -->
                <x-responsive-nav-link href="{{ route('profile') }}">
                    {{ __('Mi Perfil') }}
                </x-responsive-nav-link>

                <!-- Sección Configuración -->
                @can('view users')
                <x-responsive-nav-link href="{{ route('admin.users') }}">
                    {{ __('Usuarios') }}
                </x-responsive-nav-link>
                @endcan
                
                @can('view roles')
                <x-responsive-nav-link href="{{ route('admin.roles') }}">
                    {{ __('Roles') }}
                </x-responsive-nav-link>
                @endcan

                <!-- Cerrar Sesión -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link href="{{ route('logout') }}"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
        @endauth
    </div>
</nav>