<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

// On récupère les stats envoyées par le controller
const props = defineProps({
    stats: Object
});
</script>

<template>
    <Head title="Tableau de bord" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Tableau de bord JPC</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                        <div class="text-gray-500 text-sm">Total Membres</div>
                        <div class="text-3xl font-bold">{{ stats.total_membres }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                        <div class="text-gray-500 text-sm">Frères (Hommes)</div>
                        <div class="text-3xl font-bold">{{ stats.hommes }}</div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-pink-500">
                        <div class="text-gray-500 text-sm">Sœurs (Femmes)</div>
                        <div class="text-3xl font-bold">{{ stats.femmes }}</div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>