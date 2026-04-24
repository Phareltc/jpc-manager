<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({ 
    membres: Array,
    filters: Object 
});

const page = usePage();
const showFilters = ref(false); 

// On utilise UNIQUEMENT formFilters
const formFilters = ref({
    search: props.filters.search || '',
    sexe: props.filters.sexe || '',
    niveau_etude: props.filters.niveau_etude || '',
    statut_matrimonial: props.filters.statut_matrimonial || '',
    date_naissance: props.filters.date_naissance || '',
});

watch(formFilters, (newFilters) => {
    router.get(route('membres.index'), newFilters, {
        preserveState: true,
        replace: true,
        preserveScroll: true
    });
}, { deep: true });

const resetFilters = () => {
    formFilters.value = {
        search: '',
        sexe: '',
        niveau_etude: '',
        statut_matrimonial: '',
        date_naissance: '',
    };
};

const deleteMembre = (id) => {
    if (confirm("Es-tu sûr de vouloir supprimer ce membre ?")) {
        router.delete(route('membres.destroy', id));
    }
};

const flashKeys = ['success', 'warning', 'danger'];
flashKeys.forEach(key => {
    watch(() => page.props.flash[key], (msg) => {
        if (msg) setTimeout(() => { page.props.flash[key] = null; }, 3000);
    }, { immediate: true });
});
</script>

<template>
    <Head title="Liste des membres" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center bg-white p-4 rounded-lg shadow-sm border border-gray-100">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Gestion des Membres</h2>
                
                <div class="flex items-center gap-3">
                    <a :href="route('membres.export.pdf', formFilters)" 
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-bold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition shadow-sm">
                        <svg class="w-4 h-4 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        PDF Filtré
                    </a>

                    <a :href="route('membres.export')" 
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-bold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-50 transition shadow-sm">
                        <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Excel
                    </a>

                    <Link :href="route('membres.create')" 
                        class="inline-flex items-center bg-blue-600 text-white px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-widest hover:bg-blue-700 transition shadow-sm">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Nouveau
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                
                <div v-if="$page.props.flash.success" class="mb-4 p-4 text-green-800 bg-green-50 border-l-4 border-green-600 rounded shadow-sm">
                    <span class="font-bold">✅ Succès :</span> {{ $page.props.flash.success }}
                </div>
                <div class="mb-6 bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input 
                                v-model="formFilters.search" 
                                type="text" 
                                placeholder="Rechercher un nom ou prénom..." 
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            />
                        </div>

                        <button 
                            @click="showFilters = !showFilters"
                            class="flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition font-medium text-sm border border-transparent"
                            :class="{'bg-blue-50 text-blue-700 border-blue-200': showFilters}"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            {{ showFilters ? 'Masquer' : 'Filtres' }}
                        </button>
                    </div>

                    <div v-if="showFilters" class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-1 md:grid-cols-4 gap-4 animate-fade-in-down">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Sexe</label>
                            <select v-model="formFilters.sexe" class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                                <option value="">Tous</option>
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Études</label>
                            <select v-model="formFilters.niveau_etude" class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                                <option value="">Tous</option>
                                <option value="Université">Université</option>
                                <option value="Secondaire">Secondaire</option>
                                <option value="Primaire">Primaire</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Matrimonial</label>
                            <select v-model="formFilters.statut_matrimonial" class="w-full text-sm border-gray-300 rounded-md shadow-sm">
                                <option value="">Tous</option>
                                <option value="Célibataire">Célibataire</option>
                                <option value="Marié(e)">Marié(e)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Né(e) le</label>
                            <input v-model="formFilters.date_naissance" type="date" class="w-full text-sm border-gray-300 rounded-md shadow-sm" />
                        </div>
                        <div class="md:col-span-4 flex justify-end">
                            <button @click="resetFilters" class="text-xs text-red-600 hover:underline font-bold uppercase">
                                Réinitialiser les filtres
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Nom & Prénom</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Sexe</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Niveau d'étude</th>
                                <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="membre in membres" :key="membre.id" 
                                @click="router.get(route('membres.show', membre.id))"
                                class="hover:bg-blue-50 transition cursor-pointer group">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 group-hover:text-blue-700">
                                    {{ membre.nom }} {{ membre.prenom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ membre.sexe === 'M' ? 'Masculin' : 'Féminin' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ membre.niveau_etude }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium" @click.stop>
                                    <Link :href="route('membres.edit', membre.id)" class="text-indigo-600 hover:text-indigo-900 mr-4 font-bold">Modifier</Link>
                                    <button @click="deleteMembre(membre.id)" class="text-red-600 hover:text-red-900 font-bold">Supprimer</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>