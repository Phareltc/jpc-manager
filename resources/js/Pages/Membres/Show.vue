<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3'; // Ajout de useForm
import { ref } from 'vue'; // Ajout de ref pour la modale
import Modal from '@/Components/Modal.vue'; // Import du composant Modale de Breeze

const props = defineProps({ 
    membre: Object 
});

// Logique pour la modale
const showModal = ref(false);

// Formulaire réactif pour la scolarité
const form = useForm({
    membre_id: props.membre.id,
    annee_academique: '',
    etablissement: '',
    niveau_etude: '',
    resultat: 'En cours',
});

const submitScolarite = () => {
    form.post(route('scolarites.store'), {
        onSuccess: () => {
            showModal.value = false;
            form.reset('annee_academique', 'etablissement', 'niveau_etude', 'resultat');
        },
    });
};
</script>

<template>
    <Head :title="membre.nom + ' ' + membre.prenom" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold text-gray-800">Fiche Membre</h2>
                <Link :href="route('membres.index')" class="bg-gray-100 px-3 py-1 rounded text-sm font-bold text-gray-600 hover:bg-gray-200">
                    ← Retour à la liste
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:px-6 bg-blue-600 flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-bold text-white">{{ membre.nom }} {{ membre.prenom }}</h3>
                            <p class="text-sm text-blue-100">{{ membre.structure_mouvement }} - {{ membre.role_spirituel }}</p>
                        </div>
                        <Link :href="route('membres.edit', membre.id)" class="text-xs bg-white text-blue-600 px-3 py-1 rounded font-bold uppercase shadow">
                            Modifier le profil
                        </Link>
                    </div>
                    
                    <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                        <dl class="sm:divide-y sm:divide-gray-200">
                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                                <dt class="text-sm font-bold text-gray-500">Téléphone / Profession</dt>
                                <dd class="text-sm text-gray-900 sm:col-span-2">
                                    {{ membre.telephone || 'N/A' }} — {{ membre.statut_professionnel }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="bg-white shadow sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Trajectoire Académique</h3>
                        <button @click="showModal = true" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow text-sm font-bold uppercase transition">
                            + Ajouter une année
                        </button>
                    </div>

                    <!-- <div v-if="form.errors" class="text-red-600 text-sm">
                        {{ form.errors }}
                    </div> -->

                    <div v-if="membre.scolarites && membre.scolarites.length > 0" class="overflow-hidden border border-gray-100 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Année</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Établissement</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Niveau</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Résultat</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="scolarite in membre.scolarites" :key="scolarite.id">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ scolarite.annee_academique }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ scolarite.etablissement }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ scolarite.niveau_etude }}</td>
                                    <td class="px-6 py-4 text-sm font-bold">
                                        <span :class="{
                                            'text-green-600': scolarite.resultat === 'Admis',
                                            'text-red-600': scolarite.resultat === 'Échec',
                                            'text-blue-600': scolarite.resultat === 'En cours'
                                        }">{{ scolarite.resultat }}</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="text-center py-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200 text-gray-400">
                        Aucun historique scolaire pour le moment.
                    </div>
                </div>

            </div>
        </div>

        <Modal :show="showModal" @close="showModal = false">
            <form @submit.prevent="submitScolarite" class="p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 border-b pb-2">Nouveau suivi scolaire</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-bold text-gray-700">Année Académique</label>
                        <input v-model="form.annee_academique" type="text" placeholder="ex: 2024-2025" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-bold text-gray-700">Résultat</label>
                        <select v-model="form.resultat" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="En cours">En cours</option>
                            <option value="Admis">Admis</option>
                            <option value="Échec">Échec</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700">Établissement</label>
                        <input v-model="form.etablissement" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700">Niveau d'étude (Classe / Filière)</label>
                        <input v-model="form.niveau_etude" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="showModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md font-bold">Annuler</button>
                    <button type="submit" :disabled="form.processing" class="px-4 py-2 bg-blue-600 text-white rounded-md font-bold hover:bg-blue-700">
                        {{ form.processing ? 'Enregistrement...' : 'Enregistrer les données' }}
                    </button>
                </div>
            </form>
        </Modal>
    </AuthenticatedLayout>
</template>