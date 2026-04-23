<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    nom: '',
    prenom: '',
    sexe: 'M', // Valeur par défaut
    date_naissance: '',
    date_bapteme: null, // Plus propre pour une date vide
    telephone: '',
    niveau_etude: 'Université', // Valeur par défaut
    statut_professionnel: '',
    role_spirituel: 'Membre', // On donne une valeur par défaut pour tester
    statut_matrimonial: 'Célibataire', // Valeur par défaut
    structure_mouvement: 'JPC', // AJOUTE CE CHAMP qui est dans ton modèle !
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
            <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nom</label>
                            <input v-model="form.nom" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
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
                            <input v-model="form.statut_professionnel" type="text" placeholder="Ex: Étudiant, Employé..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Rôle Spirituel</label>
                            <select v-model="form.role_spirituel" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Sélectionnez un rôle</option>
                                <option value="Membre">Membre</option>
                                <option value="Responsable">Responsable</option>
                                <option value="Ancien">Ancien</option>
                            </select>
                            <div v-if="form.errors.role_spirituel" class="text-red-500 text-xs mt-1">{{ form.errors.role_spirituel }}</div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Statut Matrimonial</label>
                            <select v-model="form.statut_matrimonial" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="Célibataire">Célibataire</option>
                                <option value="Marié(e)">Marié(e)</option>
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <button type="submit" :disabled="form.processing" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">
                                Enregistrer le membre
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        
    </AuthenticatedLayout>
</template>