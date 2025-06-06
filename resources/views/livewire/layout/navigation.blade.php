<?php

use Illuminate\Support\Facades\Auth;
use Livewire\Volt\Component;

new class extends Component {
    // Eliminado el método logout ya que usaremos el formulario tradicional
}; ?>

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Logo + Dashboard -->
            <div class="flex items-center">
                <div class="shrink-0">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>
                <div class="hidden sm:flex sm:items-center sm:ml-6">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- User Menu Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 hover:text-gray-700 focus:outline-none transition">
                            {{ Auth::user()->name }}
                            <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Account Management -->
                        <x-dropdown-link :href="route('perfil.index')">
                            {{ __('Administrar Cuenta') }}
                        </x-dropdown-link>

                        <div class="border-t border-gray-200"></div>

                        <!-- Administration Section -->
                        @canany(['view_users', 'view_roles', 'view_companies', 'view_logs'])
                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Administración') }}
                        </div>

                        @can('view_companies')
                        <x-dropdown-link :href="route('companies.index')">
                            {{ __('Empresas') }}
                        </x-dropdown-link>
                        @endcan

                        @can('view_users')
                        <x-dropdown-link :href="route('admin.users.index')">
                            {{ __('Usuarios') }}
                        </x-dropdown-link>
                        @endcan

                        @can('view_roles')
                        <x-dropdown-link :href="route('admin.roles.index')">
                            {{ __('Roles y permisos') }}
                        </x-dropdown-link>
                        @endcan

                        @can('view_logs')
                        <x-dropdown-link :href="route('admin.logs')">
                            {{ __('Auditoría') }}
                        </x-dropdown-link>
                        @endcan

                        @role('SuperAdmin')
                        <x-dropdown-link :href="route('admin.settings')">
                            {{ __('Configuración') }}
                        </x-dropdown-link>
                        @endrole

                        <div class="border-t border-gray-200"></div>
                        @endcanany

                        <!-- Logout -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Cerrar Sesión') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('perfil.index')">
                    {{ __('Administrar Cuenta') }}
                </x-responsive-nav-link>

                @canany(['view_users', 'view_roles', 'view_companies', 'view_logs'])
                <x-responsive-nav-link :href="route('companies.index')">
                    {{ __('Empresas') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.users.index')">
                    {{ __('Usuarios') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.roles.index')">
                    {{ __('Roles y permisos') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.logs')">
                    {{ __('Auditoría') }}
                </x-responsive-nav-link>

                @role('SuperAdmin')
                <x-responsive-nav-link :href="route('admin.settings')">
                    {{ __('Configuración') }}
                </x-responsive-nav-link>
                @endrole
                @endcanany

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Cerrar Sesión') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>