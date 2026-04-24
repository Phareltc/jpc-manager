<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';

defineProps({ membres: Array });

const deleteMembre = (id) => {
    if (confirm("Es-tu sûr de vouloir supprimer ce membre ? Cette action est irréversible.")) {
        router.delete(route('membres.destroy', id));
    }
};

</script>

<template>
    <Head title="Liste des membres" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Gestion des Membres</h2>
                <Link :href="route('membres.create')" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                    + Ajouter un membre
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom & Prénom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Sexe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Niveau d'étude</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="membre in membres" :key="membre.id">
                                <td class="px-6 py-4 whitespace-nowrap">{{ membre.nom }} {{ membre.prenom }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ membre.sexe }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">{{ membre.niveau_etude }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <Link 
                                        :href="route('membres.edit', membre.id)" 
                                        class="text-indigo-600 hover:text-indigo-900 mr-3"
                                    >
                                        Modifier
                                    </Link>
                                    <button 
                                        @click="deleteMembre(membre.id)"
                                        class="text-red-600 hover:text-red-900 font-medium"
                                    >
                                        Supprimer
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>