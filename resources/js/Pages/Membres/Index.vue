<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import { watch } from 'vue';

defineProps({ 
    membres: Array 
});

const page = usePage();

const deleteMembre = (id) => {
    if (confirm("Es-tu sûr de vouloir supprimer ce membre ? Cette action est irréversible.")) {
        router.delete(route('membres.destroy', id));
    }
};

// Gestion de la disparition automatique
// On définit les clés à surveiller
const flashKeys = ['success', 'warning', 'danger'];

flashKeys.forEach(key => {
    watch(() => page.props.flash[key], (newMessage) => {
        if (newMessage) {
            // On utilise un timer pour effacer le message après 3 secondes
            setTimeout(() => {
                page.props.flash[key] = null;
            }, 3000);
        }
    }, { immediate: true }); // On ajoute 'immediate' pour capter le message dès le chargement
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
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-blue-700 transition shadow-sm"
                >
                    + Ajouter un membre
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                
                <div v-if="$page.props.flash.success" 
                    class="mb-4 p-4 text-green-800 bg-green-50 border-l-4 border-green-600 rounded shadow-sm" 
                    role="alert">
                    <span class="font-bold">✅ Succès :</span> {{ $page.props.flash.success }}
                </div>

                <div v-if="$page.props.flash.warning" 
                    class="mb-4 p-4 text-orange-800 bg-orange-50 border-l-4 border-orange-500 rounded shadow-sm" 
                    role="alert">
                    <span class="font-bold">⚠️ Modification :</span> {{ $page.props.flash.warning }}
                </div>

                <div v-if="$page.props.flash.danger" 
                    class="mb-4 p-4 text-red-800 bg-red-50 border-l-4 border-red-600 rounded shadow-sm" 
                    role="alert">
                    <span class="font-bold">🗑️ Suppression :</span> {{ $page.props.flash.danger }}
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
                            <tr v-for="membre in membres" :key="membre.id" class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    {{ membre.nom }} {{ membre.prenom }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ membre.sexe === 'M' ? 'Masculin' : 'Féminin' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ membre.niveau_etude }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link :href="route('membres.edit', membre.id)" class="text-indigo-600 hover:text-indigo-900 mr-4 font-bold">Modifier</Link>
                                    <button @click="deleteMembre(membre.id)" class="text-red-600 hover:text-red-900 font-bold">Supprimer</button>
                                </td>
                            </tr>
                            <tr v-if="membres.length === 0">
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic">Aucun membre enregistré.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>