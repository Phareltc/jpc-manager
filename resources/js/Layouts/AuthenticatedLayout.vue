<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="flex min-h-screen bg-gray-100">
        <aside class="hidden md:flex w-64 bg-white border-r border-gray-200 flex-col sticky top-0 h-screen">
            <div class="h-16 flex items-center px-6 border-b border-gray-100">
                <Link :href="route('dashboard')" class="flex items-center gap-2">
                    <ApplicationLogo class="h-8 w-auto fill-current text-blue-600" />
                    <span class="text-lg font-bold text-gray-800 tracking-tight">Admin JPC</span>
                </Link>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-8 overflow-y-auto">
                <div>
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Général</p>
                    <div class="space-y-1">
                        <Link :href="route('dashboard')" 
                            :class="route().current('dashboard') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-50'"
                            class="flex items-center px-3 py-2.5 text-sm rounded-lg transition-all group">
                            <svg class="w-5 h-5 mr-3 transition-colors" :class="route().current('dashboard') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Accueil
                        </Link>
                    </div>
                </div>

                <div>
                    <p class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-4">Ressources Humaines</p>
                    <div class="space-y-1">
                        <Link :href="route('membres.index')" 
                            :class="route().current('membres.*') ? 'bg-blue-50 text-blue-700 font-bold' : 'text-gray-600 hover:bg-gray-50'"
                            class="flex items-center px-3 py-2.5 text-sm rounded-lg transition-all group">
                            <svg class="w-5 h-5 mr-3 transition-colors" :class="route().current('membres.*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                            Membres
                        </Link>
                    </div>
                </div>
            </nav>

            <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                <div class="flex items-center px-2 py-2">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ $page.props.auth.user.name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $page.props.auth.user.email }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <div class="flex-1 flex flex-col">
            <nav class="bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between items-center">
                        <div class="flex md:hidden">
                            <ApplicationLogo class="h-8 w-auto fill-current text-blue-600" />
                        </div>

                        <div class="ml-auto flex items-center">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none transition">
                                        {{ $page.props.auth.user.name }}
                                        <svg class="ml-2 h-4 w-4 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                                    </button>
                                </template>
                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">Profil</DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">Déconnexion</DropdownLink>
                                </template>
                            </Dropdown>

                            <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="md:hidden ml-4 p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none transition">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{'hidden': showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{'hidden': !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div :class="{'block': showingNavigationDropdown, 'hidden': !showingNavigationDropdown}" class="md:hidden border-t border-gray-200 bg-white shadow-lg">
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">Accueil</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('membres.index')" :active="route().current('membres.*')">Membres</ResponsiveNavLink>
                    </div>
                </div>
            </nav>

            <header class="bg-white border-b border-gray-100" v-if="$slots.header">
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <main class="flex-1 p-6 md:p-8">
                <slot />
            </main>

            <footer class="bg-white border-t border-gray-200 py-6">
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-500 gap-4">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-700">Jeunesse Pour Christ de Montalier</span>
                            <span>&copy; 2026 Tous droits réservés.</span>
                        </div>
                        <div class="flex items-center gap-6">
                            <span class="text-xs px-2 py-1 bg-gray-100 rounded text-gray-400 uppercase tracking-tighter">v1.2.0</span>
                            <a href="#" class="hover:text-blue-600 transition">Assistance technique</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
</template>