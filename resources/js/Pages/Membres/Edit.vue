<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const props = defineProps({
    membre: Object
});

const form = useForm({
    nom: props.membre.nom ?? '',
    prenom: props.membre.prenom ?? '',
    sexe: props.membre.sexe ?? '',
    date_naissance: props.membre.date_naissance ?? '',
    date_bapteme: props.membre.date_bapteme ?? '',
    telephone: props.membre.telephone ?? '',
    niveau_etude: props.membre.niveau_etude ?? '',
    statut_professionnel: props.membre.statut_professionnel ?? '',
    role_spirituel: props.membre.role_spirituel ?? '',
    statut_matrimonial: props.membre.statut_matrimonial ?? '',
    structure_mouvement: props.membre.structure_mouvement ?? '',
});

const submit = () => {
    // IMPORTANT : On utilise put() pour la mise à jour
    form.put(route('membres.update', props.membre.id));
};
</script>

<template>
    <Head title="Modifier un membre" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Modifier le membre : {{ props.membre.nom }} {{ props.membre.prenom }}
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-5xl sm:px-6 lg:px-8">
                <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="md:col-span-2 border-b pb-2">
                            <h3 class="text-lg font-medium text-gray-900">État Civil</h3>
                        </div>

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
                            <label class="block text-sm font-medium text-gray-700">Téléphone</label>
                            <input v-model="form.telephone" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Statut Matrimonial</label>
                            <select v-model="form.statut_matrimonial" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                <option value="Célibataire">Célibataire</option>
                                <option value="Marié(e)">Marié(e)</option>
                                <option value="Veuf(ve)">Veuf(ve)</option>
                                <option value="Divorcé(e)">Divorcé(e)</option>
                            </select>
                        </div>

                        <div class="md:col-span-2 border-b pb-2 mt-4">
                            <h3 class="text-lg font-medium text-gray-900">Parcours Académique & Pro</h3>
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
                            <input v-model="form.statut_professionnel" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Ex: Étudiant, Agent public...">
                        </div>

                        <div class="md:col-span-2 border-b pb-2 mt-4">
                            <h3 class="text-lg font-medium text-gray-900">Engagement JPC</h3>
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
                            <label class="block text-sm font-medium text-gray-700">Structure / Mouvement</label>
                            <input v-model="form.structure_mouvement" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Date de Baptême</label>
                            <input v-model="form.date_bapteme" type="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div class="md:col-span-2 flex justify-end space-x-3 mt-6 border-t pt-4">
                            <Link :href="route('membres.index')" class="text-gray-600 px-4 py-2 hover:underline">Annuler</Link>
                            <button 
                                type="submit" 
                                :disabled="form.processing" 
                                class="bg-indigo-600 text-white py-2 px-6 rounded-md hover:bg-indigo-700 shadow flex items-center"
                            >
                                <span v-if="form.processing" class="mr-2 italic">Enregistrement...</span>
                                <span v-else>Sauvegarder les modifications</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>