<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { watch } from 'vue';

// Récupération des membres depuis le contrôleur
defineProps({ 
    membres: Array 
});

// Initialisation de usePage pour accéder aux props globales (flash)
const page = usePage();

// Fonction de suppression avec confirmation
const deleteMembre = (id) => {
    if (confirm("Es-tu sûr de vouloir supprimer ce membre ? Cette action est irréversible.")) {
        router.delete(route('membres.destroy', id));
    }
};

// Surveillance du message flash pour le faire disparaître après 3 secondes
watch(() => page.props.flash.success, (message) => {
    if (message) {
        setTimeout(() => {
            page.props.flash.success = null;
        }, 3000);
    }
});
</script>

<template>
    <Head title="Liste des membres" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">Gestion des Membres</h2>
                <Link 
                    :href="route('membres.create')" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition"
                >
                    + Ajouter un membre
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                
                <div v-if="$page.props.flash.success" 
                     class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg shadow-sm border border-green-200" 
                     role="alert">
                    <span class="font-medium">Succès !</span> {{ $page.props.flash.success }}
                </div>

                <div v-if="$page.props.flash.error" 
                     class="mb-4 p-4 text-sm text-red-700 bg-red-100 rounded-lg shadow-sm border border-red-200" 
                     role="alert">
                    <span class="font-medium">Erreur !</span> {{ $page.props.flash.error }}
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom & Prénom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sexe</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Niveau d'étude</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="membre in membres" :key="membre.id" class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ membre.nom }} {{ membre.prenom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ membre.sexe === 'M' ? 'Masculin' : 'Féminin' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ membre.niveau_etude }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link 
                                        :href="route('membres.edit', membre.id)" 
                                        class="text-indigo-600 hover:text-indigo-900 mr-4"
                                    >
                                        Modifier
                                    </Link>
                                    <button 
                                        @click="deleteMembre(membre.id)"
                                        class="text-red-600 hover:text-red-900"
                                    >
                                        Supprimer
                                    </button>
                                </td>
                            </tr>
                            
                            <tr v-if="membres.length === 0">
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">
                                    Aucun membre enregistré pour le moment.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>