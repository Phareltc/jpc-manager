<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    nom: '',
    prenom: '',
    sexe: 'M',
    date_naissance: '',
    date_bapteme: '', // Initialisé à vide pour le champ date
    telephone: '',
    niveau_etude: 'Université',
    statut_professionnel: '',
    role_spirituel: 'Membre',
    statut_matrimonial: 'Célibataire',
    structure_mouvement: 'JPC',
});

const submit = () => {
    form.post(route('membres.store'));
};
</script>

<template>
    <Head title="Ajouter un membre" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">Nouveau Membre</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg border border-gray-200">
                    <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="md:col-span-2 border-b pb-2">
                            <h3 class="text-lg font-medium text-gray-900">Identification</h3>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nom</label>
                            <input v-model="form.nom" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            <div v-if="form.errors.nom" class="text-red-500 text-xs mt-1">{{ form.errors.nom }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Prénom</label>
                            <input v-model="form.prenom" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Sexe</label>
                            <select v-model="form.sexe" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="M">Masculin</option>
                                <option value="F">Féminin</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de naissance</label>
                            <input v-model="form.date_naissance" type="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                            <input v-model="form.telephone" type="text" placeholder="Ex: +241..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Statut Matrimonial</label>
                            <select v-model="form.statut_matrimonial" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="Célibataire">Célibataire</option>
                                <option value="Marié(e)">Marié(e)</option>
                                <option value="Divorcé(e)">Divorcé(e)</option>
                                <option value="Veuf(ve)">Veuf(ve)</option>
                            </select>
                        </div>

                        <div class="md:col-span-2 border-b pb-2 mt-4">
                            <h3 class="text-lg font-medium text-gray-900">Parcours & Engagement</h3>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Niveau d'étude</label>
                            <select v-model="form.niveau_etude" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="Primaire">Primaire</option>
                                <option value="Secondaire">Secondaire</option>
                                <option value="Université">Université</option>
                                <option value="Aucun">Aucun</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Statut Professionnel</label>
                            <input v-model="form.statut_professionnel" type="text" placeholder="Ex: Étudiant, Agent Public..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Structure / Mouvement</label>
                            <input v-model="form.structure_mouvement" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Rôle Spirituel</label>
                            <select v-model="form.role_spirituel" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="Membre">Membre</option>
                                <option value="Responsable">Responsable</option>
                                <option value="Ancien">Ancien</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de Baptême (si applicable)</label>
                            <input v-model="form.date_bapteme" type="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div class="md:col-span-2 mt-6">
                            <button 
                                type="submit" 
                                :disabled="form.processing" 
                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition duration-200 shadow-md disabled:opacity-50"
                            >
                                <span v-if="form.processing">Traitement en cours...</span>
                                <span v-else>Enregistrer le membre</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>