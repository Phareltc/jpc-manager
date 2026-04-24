<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    stats: Object,
    anniversaires: Array,
    recents: Array
});
</script>

<template>
    <Head title="Tableau de bord" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tableau de Bord</h2>
        </template>

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Membres</p>
                    <p class="text-3xl font-bold text-blue-600">{{ stats.total_membres }}</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 uppercase">Hommes / Femmes</p>
                    <p class="text-3xl font-bold text-gray-800">
                        <span class="text-blue-500">{{ stats.hommes }}</span> 
                        <span class="text-gray-300 mx-2">/</span> 
                        <span class="text-pink-500">{{ stats.femmes }}</span>
                    </p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-sm font-medium text-gray-500 uppercase">Anniversaires ({{ new Date().toLocaleString('default', { month: 'long' }) }})</p>
                    <p class="text-3xl font-bold text-orange-500">{{ anniversaires.length }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 border-b border-gray-50 bg-gray-50/50">
                        <h3 class="font-bold text-gray-700">Dernières inscriptions</h3>
                    </div>
                    <ul class="divide-y divide-gray-100">
                        <li v-for="membre in recents" :key="membre.id" class="p-4 flex justify-between items-center hover:bg-gray-50 transition">
                            <span class="text-sm text-gray-700 font-medium">{{ membre.nom }} {{ membre.prenom }}</span>
                            <span class="text-xs text-gray-400 italic">Inscrit le {{ new Date(membre.created_at).toLocaleDateString() }}</span>
                        </li>
                    </ul>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-4 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                        <h3 class="font-bold text-gray-700 text-orange-600">🎉 Anniversaires du mois</h3>
                    </div>
                    <div v-if="anniversaires.length === 0" class="p-8 text-center text-gray-400 text-sm">
                        Aucun anniversaire ce mois-ci.
                    </div>
                    <ul v-else class="divide-y divide-gray-100">
                        <li v-for="anniv in anniversaires" :key="anniv.id" class="p-4 flex justify-between items-center">
                            <span class="text-sm text-gray-700">{{ anniv.nom }} {{ anniv.prenom }}</span>
                            <span class="bg-orange-100 text-orange-700 px-3 py-1 rounded-full text-xs font-bold">
                                {{ new Date(anniv.date_naissance).getDate() }} {{ new Date(anniv.date_naissance).toLocaleString('default', { month: 'short' }) }}
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>