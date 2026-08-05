<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({ 
    membre: Object 
});

/* --- SCOLARITÉS --- */
const showScolariteModal = ref(false);
const isEditingScolarite = ref(false);
const editingScolariteId = ref(null);

const showDeleteScolariteModal = ref(false);
const scolariteToDeleteId = ref(null);

const scolariteForm = useForm({
    membre_id: props.membre.id,
    annee_academique: '',
    etablissement: '',
    niveau_etude: '',
    resultat: 'En cours',
});

const openCreateScolariteModal = () => {
    isEditingScolarite.value = false;
    editingScolariteId.value = null;
    scolariteForm.reset();
    scolariteForm.membre_id = props.membre.id;
    scolariteForm.resultat = 'En cours';
    showScolariteModal.value = true;
};

const openEditScolariteModal = (scolarite) => {
    isEditingScolarite.value = true;
    editingScolariteId.value = scolarite.id;
    scolariteForm.membre_id = props.membre.id;
    scolariteForm.annee_academique = scolarite.annee_academique;
    scolariteForm.etablissement = scolarite.etablissement;
    scolariteForm.niveau_etude = scolarite.niveau_etude;
    scolariteForm.resultat = scolarite.resultat;
    showScolariteModal.value = true;
};

const submitScolarite = () => {
    if (isEditingScolarite.value) {
        scolariteForm.put(route('scolarites.update', editingScolariteId.value), {
            onSuccess: () => {
                showScolariteModal.value = false;
                scolariteForm.reset();
            },
        });
    } else {
        scolariteForm.post(route('scolarites.store'), {
            onSuccess: () => {
                showScolariteModal.value = false;
                scolariteForm.reset();
            },
        });
    }
};

const confirmDeleteScolarite = (scolariteId) => {
    scolariteToDeleteId.value = scolariteId;
    showDeleteScolariteModal.value = true;
};

const deleteScolarite = () => {
    if (scolariteToDeleteId.value) {
        scolariteForm.delete(route('scolarites.destroy', scolariteToDeleteId.value), {
            onSuccess: () => {
                showDeleteScolariteModal.value = false;
                scolariteToDeleteId.value = null;
            },
        });
    }
};

/* --- CONTRIBUTIONS --- */
const showContributionModal = ref(false);
const isEditingContribution = ref(false);
const editingContributionId = ref(null);

const showDeleteContributionModal = ref(false);
const contributionToDeleteId = ref(null);

const contributionForm = useForm({
    membre_id: props.membre.id,
    type_contribution: 'Cotisation mensuelle',
    montant: '',
    date_paiement: new Date().toISOString().split('T')[0],
    annee_concernee: new Date().getFullYear().toString(),
    mois_concerne: '',
    mode_paiement: 'Espèces',
    observation: '',
});

const openCreateContributionModal = () => {
    isEditingContribution.value = false;
    editingContributionId.value = null;
    contributionForm.reset();
    contributionForm.membre_id = props.membre.id;
    contributionForm.type_contribution = 'Cotisation mensuelle';
    contributionForm.mode_paiement = 'Espèces';
    contributionForm.date_paiement = new Date().toISOString().split('T')[0];
    contributionForm.annee_concernee = new Date().getFullYear().toString();
    showContributionModal.value = true;
};

const openEditContributionModal = (contribution) => {
    isEditingContribution.value = true;
    editingContributionId.value = contribution.id;
    contributionForm.membre_id = props.membre.id;
    contributionForm.type_contribution = contribution.type_contribution;
    contributionForm.montant = contribution.montant;
    contributionForm.date_paiement = contribution.date_paiement;
    contributionForm.annee_concernee = contribution.annee_concernee;
    contributionForm.mois_concerne = contribution.mois_concerne;
    contributionForm.mode_paiement = contribution.mode_paiement;
    contributionForm.observation = contribution.observation;
    showContributionModal.value = true;
};

const submitContribution = () => {
    if (isEditingContribution.value) {
        contributionForm.put(route('contributions.update', editingContributionId.value), {
            onSuccess: () => {
                showContributionModal.value = false;
                contributionForm.reset();
            },
        });
    } else {
        contributionForm.post(route('contributions.store'), {
            onSuccess: () => {
                showContributionModal.value = false;
                contributionForm.reset();
            },
        });
    }
};

const confirmDeleteContribution = (contributionId) => {
    contributionToDeleteId.value = contributionId;
    showDeleteContributionModal.value = true;
};

const deleteContribution = () => {
    if (contributionToDeleteId.value) {
        contributionForm.delete(route('contributions.destroy', contributionToDeleteId.value), {
            onSuccess: () => {
                showDeleteContributionModal.value = false;
                contributionToDeleteId.value = null;
            },
        });
    }
};

// Formateur de devise FCFA
const formatMoney = (amount) => {
    return new Intl.NumberFormat('fr-FR').format(amount) + ' FCFA';
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
                
                <!-- BLOC 1 : INFOS PERSONNELLES -->
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

                <!-- BLOC 2 : CONTRIBUTIONS FINANCIÈRES -->
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Historique des Contributions</h3>
                            <p class="text-xs text-gray-500">Suivi des versements, cotisations et dons</p>
                        </div>
                        <button @click="openCreateContributionModal" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded shadow text-sm font-bold uppercase transition">
                            + Enregistrer un versement
                        </button>
                    </div>

                    <!-- Tableau des contributions -->
                    <div v-if="membre.contributions && membre.contributions.length > 0" class="overflow-hidden border border-gray-100 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Période</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Montant</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Mode</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="contrib in membre.contributions" :key="contrib.id">
                                    <td class="px-6 py-4 text-sm text-gray-600">{{ contrib.date_paiement }}</td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">{{ contrib.type_contribution }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ contrib.mois_concerne ? contrib.mois_concerne + ' ' : '' }}{{ contrib.annee_concernee }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-emerald-600">
                                        {{ formatMoney(contrib.montant) }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        <span class="px-2 py-1 text-xs rounded bg-gray-100 text-gray-800 font-semibold">
                                            {{ contrib.mode_paiement }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-right space-x-2">
                                        <button @click="openEditContributionModal(contrib)" class="text-indigo-600 hover:text-indigo-900 font-bold">
                                            Éditer
                                        </button>
                                        <button @click="confirmDeleteContribution(contrib.id)" class="text-red-600 hover:text-red-900 font-bold">
                                            Supprimer
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div v-else class="text-center py-6 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200 text-gray-400">
                        Aucune contribution enregistrée pour ce membre.
                    </div>
                </div>

                <!-- BLOC 3 : HISTORIQUE SCOLAIRE -->
                <div class="bg-white shadow sm:rounded-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Trajectoire Académique</h3>
                        <button @click="openCreateScolariteModal" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow text-sm font-bold uppercase transition">
                            + Ajouter une année
                        </button>
                    </div>

                    <!-- Tableau des données scolarité -->
                    <div v-if="membre.scolarites && membre.scolarites.length > 0" class="overflow-hidden border border-gray-100 rounded-lg">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Année</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Établissement</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Niveau</th>
                                    <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase">Résultat</th>
                                    <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase">Actions</th>
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
                                    <td class="px-6 py-4 text-sm text-right space-x-2">
                                        <button @click="openEditScolariteModal(scolarite)" class="text-indigo-600 hover:text-indigo-900 font-bold">
                                            Éditer
                                        </button>
                                        <button @click="confirmDeleteScolarite(scolarite.id)" class="text-red-600 hover:text-red-900 font-bold">
                                            Supprimer
                                        </button>
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

        <!-- MODALES SCOLARITÉ -->
        <Modal :show="showScolariteModal" @close="showScolariteModal = false">
            <form @submit.prevent="submitScolarite" class="p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 border-b pb-2">
                    {{ isEditingScolarite ? 'Modifier la scolarité' : 'Nouveau suivi scolaire' }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-bold text-gray-700">Année Académique</label>
                        <input v-model="scolariteForm.annee_academique" type="text" placeholder="ex: 2024-2025" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-bold text-gray-700">Résultat</label>
                        <select v-model="scolariteForm.resultat" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="En cours">En cours</option>
                            <option value="Admis">Admis</option>
                            <option value="Échec">Échec</option>
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700">Établissement</label>
                        <input v-model="scolariteForm.etablissement" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700">Niveau d'étude (Classe / Filière)</label>
                        <input v-model="scolariteForm.niveau_etude" type="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="showScolariteModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md font-bold">Annuler</button>
                    <button type="submit" :disabled="scolariteForm.processing" class="px-4 py-2 bg-blue-600 text-white rounded-md font-bold hover:bg-blue-700">
                        {{ scolariteForm.processing ? 'Traitement...' : (isEditingScolarite ? 'Mettre à jour' : 'Enregistrer') }}
                    </button>
                </div>
            </form>
        </Modal>

        <Modal :show="showDeleteScolariteModal" @close="showDeleteScolariteModal = false">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold text-xl">
                        ⚠️
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Confirmer la suppression</h3>
                        <p class="text-sm text-gray-500">Es-tu sûr de vouloir supprimer cette ligne d'historique scolaire ?</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="showDeleteScolariteModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md font-bold hover:bg-gray-200 transition">
                        Annuler
                    </button>
                    <button type="button" @click="deleteScolarite" :disabled="scolariteForm.processing" class="px-4 py-2 bg-red-600 text-white rounded-md font-bold hover:bg-red-700 transition">
                        {{ scolariteForm.processing ? 'Suppression...' : 'Oui, supprimer' }}
                    </button>
                </div>
            </div>
        </Modal>

        <!-- MODALES CONTRIBUTION -->
        <Modal :show="showContributionModal" @close="showContributionModal = false">
            <form @submit.prevent="submitContribution" class="p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6 border-b pb-2">
                    {{ isEditingContribution ? 'Modifier la contribution' : 'Enregistrer une contribution' }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-bold text-gray-700">Type de contribution</label>
                        <select v-model="contributionForm.type_contribution" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="Cotisation mensuelle">Cotisation mensuelle</option>
                            <option value="Droit d'adhésion">Droit d'adhésion</option>
                            <option value="Don">Don</option>
                            <option value="Levée de fonds">Levée de fonds</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-bold text-gray-700">Montant (FCFA)</label>
                        <input v-model="contributionForm.montant" type="number" step="0.01" min="0" placeholder="ex: 5000" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-bold text-gray-700">Date du paiement</label>
                        <input v-model="contributionForm.date_paiement" type="date" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-bold text-gray-700">Mode de paiement</label>
                        <select v-model="contributionForm.mode_paiement" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="Espèces">Espèces</option>
                            <option value="Mobile Money">Mobile Money</option>
                            <option value="Virement">Virement</option>
                            <option value="Chèque">Chèque</option>
                        </select>
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-bold text-gray-700">Année concernée</label>
                        <input v-model="contributionForm.annee_concernee" type="text" placeholder="ex: 2025" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                    </div>

                    <div class="col-span-2 md:col-span-1">
                        <label class="block text-sm font-bold text-gray-700">Mois concerné (optionnel)</label>
                        <select v-model="contributionForm.mois_concerne" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Aucun</option>
                            <option value="Janvier">Janvier</option>
                            <option value="Février">Février</option>
                            <option value="Mars">Mars</option>
                            <option value="Avril">Avril</option>
                            <option value="Mai">Mai</option>
                            <option value="Juin">Juin</option>
                            <option value="Juillet">Juillet</option>
                            <option value="Août">Août</option>
                            <option value="Septembre">Septembre</option>
                            <option value="Octobre">Octobre</option>
                            <option value="Novembre">Novembre</option>
                            <option value="Décembre">Décembre</option>
                        </select>
                    </div>

                    <div class="col-span-2">
                        <label class="block text-sm font-bold text-gray-700">Observation / Note (optionnel)</label>
                        <textarea v-model="contributionForm.observation" rows="2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" placeholder="Remarque ou référence de transaction..."></textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button type="button" @click="showContributionModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md font-bold">Annuler</button>
                    <button type="submit" :disabled="contributionForm.processing" class="px-4 py-2 bg-emerald-600 text-white rounded-md font-bold hover:bg-emerald-700">
                        {{ contributionForm.processing ? 'Enregistrement...' : (isEditingContribution ? 'Mettre à jour' : 'Valider la contribution') }}
                    </button>
                </div>
            </form>
        </Modal>

        <Modal :show="showDeleteContributionModal" @close="showDeleteContributionModal = false">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold text-xl">
                        ⚠️
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Confirmer la suppression</h3>
                        <p class="text-sm text-gray-500">Es-tu sûr de vouloir supprimer cet enregistrement de contribution ? Cette action impactera les calculs financiers.</p>
                    </div>
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" @click="showDeleteContributionModal = false" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md font-bold hover:bg-gray-200 transition">
                        Annuler
                    </button>
                    <button type="button" @click="deleteContribution" :disabled="contributionForm.processing" class="px-4 py-2 bg-red-600 text-white rounded-md font-bold hover:bg-red-700 transition">
                        {{ contributionForm.processing ? 'Suppression...' : 'Oui, supprimer' }}
                    </button>
                </div>
            </div>
        </Modal>

    </AuthenticatedLayout>
</template>