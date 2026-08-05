<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Modal from "@/Components/Modal.vue";

const props = defineProps({
  matrice: Object,
  typesDisponibles: Array, // Liste d'objets [{ id, libelle, montant_defaut, ... }]
  filters: Object,
  stats: Object,
});

const moisListe = [
  { id: 1, nom: "Jan" },
  { id: 2, nom: "Fév" },
  { id: 3, nom: "Mar" },
  { id: 4, nom: "Avr" },
  { id: 5, nom: "Mai" },
  { id: 6, nom: "Juin" },
  { id: 7, nom: "Juil" },
  { id: 8, nom: "Août" },
  { id: 9, nom: "Sept" },
  { id: 10, nom: "Oct" },
  { id: 11, nom: "Nov" },
  { id: 12, nom: "Déc" },
];

/* --- FILTRES --- */
const search = ref(props.filters.search || "");
const annee = ref(props.filters.annee || new Date().getFullYear().toString());
const type_id = ref(
  props.filters.type_id || (props.typesDisponibles[0]?.id ?? "")
);

// Libellé et Objet du type actuellement sélectionné
const selectedType = computed(() => {
  return props.typesDisponibles.find((t) => t.id === Number(type_id.value));
});

const selectedTypeLibelle = computed(() => {
  return selectedType.value ? selectedType.value.libelle : "Contribution";
});

const applyFilters = () => {
  router.get(
    route("contributions.index"),
    {
      search: search.value,
      annee: annee.value,
      type_id: type_id.value,
    },
    { preserveState: true, replace: true }
  );
};

const resetFilters = () => {
  search.value = "";
  annee.value = new Date().getFullYear().toString();
  type_id.value = props.typesDisponibles[0]?.id ?? "";
  applyFilters();
};

const formatMoney = (amount) => {
  return new Intl.NumberFormat("fr-FR").format(amount || 0) + " FCFA";
};

/* --- MODALE CRÉATION D'UN NOUVEAU TYPE DE CONTRIBUTION --- */
const showCreateTypeModal = ref(false);

const typeForm = useForm({
  libelle: "",
  categorie: "evenement",
  montant_defaut: null,
  date_debut: "",
  date_fin: "",
});

const submitCreateType = () => {
  typeForm.post(route("type-contributions.store"), {
    onSuccess: () => {
      showCreateTypeModal.value = false;
      typeForm.reset();
    },
  });
};

/* --- MODALE ENREGISTREMENT / ÉDITION D'UN PAIEMENT --- */
const showPaymentModal = ref(false);
const modalTitle = ref("");

const form = useForm({
  id: null,
  membre_id: "",
  type_contribution_id: "",
  montant: 5000,
  date_paiement: new Date().toISOString().substring(0, 10),
  annee_concernee: "",
  mois_concerne: "",
  mode_paiement: "Espèces",
  observation: "",
});

// Clic sur une case vide (Nouveau paiement)
const openNewPaymentModal = (membre, moisId) => {
  form.reset();
  form.clearErrors();

  form.id = null; // Important pour forcer le POST
  form.membre_id = membre.id;
  form.annee_concernee = annee.value;
  form.mois_concerne = moisId;
  form.type_contribution_id = type_id.value;

  // Récupérer le montant par défaut du type sélectionné s'il existe
  const defaultAmount = selectedType.value?.montant_defaut ?? 5000;
  form.montant = defaultAmount;

  form.date_paiement = new Date().toISOString().substring(0, 10);
  form.mode_paiement = "Espèces";

  const moisNom = moisListe.find((m) => m.id === moisId)?.nom;
  modalTitle.value = `Enregistrer : ${selectedTypeLibelle.value} - ${membre.nom} ${membre.prenom} (${moisNom} ${annee.value})`;
  showPaymentModal.value = true;
};

// Clic sur une case payée (Édition)
const openEditPaymentModal = (membre, moisId, contrib) => {
  form.reset();
  form.clearErrors();

  form.id = contrib.id;
  form.membre_id = membre.id;
  form.annee_concernee = annee.value;
  form.mois_concerne = moisId;
  form.type_contribution_id = type_id.value;
  form.montant = contrib.montant;
  form.date_paiement = contrib.date_paiement;
  form.mode_paiement = contrib.mode_paiement || "Espèces";

  const moisNom = moisListe.find((m) => m.id === moisId)?.nom;
  modalTitle.value = `Modifier : ${selectedTypeLibelle.value} - ${membre.nom} ${membre.prenom} (${moisNom} ${annee.value})`;
  showPaymentModal.value = true;
};

const submitPayment = () => {
  if (form.id) {
    form.put(route("contributions.update", form.id), {
      onSuccess: () => {
        showPaymentModal.value = false;
        form.reset();
      },
    });
  } else {
    form.post(route("contributions.store"), {
      onSuccess: () => {
        showPaymentModal.value = false;
        form.reset();
      },
    });
  }
};
</script>

<template>
  <Head title="Matrice des Contributions" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold text-gray-800">
        📊 Matrice des Contributions :
        <span class="text-emerald-600">{{ selectedTypeLibelle }}</span>
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- CARTE DE STATISTIQUE -->
        <div
          class="bg-white p-6 rounded-lg shadow border-l-4 border-emerald-500 flex justify-between items-center"
        >
          <div>
            <p class="text-xs font-bold uppercase text-gray-500">
              Total Récolté pour {{ selectedTypeLibelle }} ({{ annee }})
            </p>
            <p class="text-2xl font-black text-emerald-600 mt-1">
              {{ formatMoney(stats?.total_general) }}
            </p>
          </div>
        </div>

        <!-- BARRE DE FILTRES -->
        <div
          class="bg-white p-4 rounded-lg shadow flex flex-col md:flex-row gap-4 items-center"
        >
          <!-- Filtre Recherche -->
          <div class="flex-1 w-full">
            <input
              v-model="search"
              @keyup.enter="applyFilters"
              type="text"
              placeholder="Rechercher un membre par nom ou prénom..."
              class="w-full border-gray-300 rounded-md shadow-sm text-sm"
            />
          </div>

          <!-- Filtre Type de Contribution + Bouton Création -->
          <div class="w-full md:w-auto flex items-center gap-2">
            <select
              v-model="type_id"
              @change="applyFilters"
              class="w-full md:w-56 border-gray-300 rounded-md shadow-sm text-sm font-semibold text-emerald-700"
            >
              <option v-for="t in typesDisponibles" :key="t.id" :value="t.id">
                {{ t.libelle }}
              </option>
            </select>

            <button
              type="button"
              @click="showCreateTypeModal = true"
              class="bg-gray-800 hover:bg-gray-700 text-white px-3 py-2 rounded text-sm font-bold whitespace-nowrap transition"
              title="Créer un nouveau type ou projet"
            >
              + Type
            </button>
          </div>

          <!-- Filtre Année -->
          <div class="w-full md:w-32">
            <input
              v-model="annee"
              @keyup.enter="applyFilters"
              type="number"
              placeholder="Année"
              class="w-full border-gray-300 rounded-md shadow-sm text-sm"
            />
          </div>

          <!-- Boutons Actions -->
          <div class="flex gap-2">
            <button
              @click="applyFilters"
              class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded text-sm font-bold"
            >
              Filtrer
            </button>
            <button
              @click="resetFilters"
              class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded text-sm font-bold"
            >
              Réinitialiser
            </button>
          </div>
        </div>

        <!-- MATRICE DES CONTRIBUTIONS -->
        <div class="bg-white shadow sm:rounded-lg overflow-x-auto">
          <table
            class="min-w-full divide-y divide-gray-200 text-center text-xs"
          >
            <thead class="bg-gray-800 text-white font-bold uppercase">
              <tr>
                <th class="px-4 py-3 text-left">Membre</th>
                <th v-for="m in moisListe" :key="m.id" class="px-2 py-3 w-16">
                  {{ m.nom }}
                </th>
                <th class="px-4 py-3 text-right">Total Versé</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr
                v-for="row in matrice.data"
                :key="row.id"
                class="hover:bg-gray-50 transition"
              >
                <td
                  class="px-4 py-3 text-left font-bold text-gray-900 whitespace-nowrap"
                >
                  <Link
                    :href="route('membres.show', row.id)"
                    class="text-blue-600 hover:underline"
                  >
                    {{ row.nom }} {{ row.prenom }}
                  </Link>
                </td>

                <td
                  v-for="m in moisListe"
                  :key="m.id"
                  class="px-1 py-3 border-r border-gray-100"
                >
                  <button
                    v-if="row.cotisations && row.cotisations[m.id]"
                    @click="
                      openEditPaymentModal(row, m.id, row.cotisations[m.id])
                    "
                    :title="`Payé: ${formatMoney(
                      row.cotisations[m.id].montant
                    )}`"
                    class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-700 font-bold hover:bg-emerald-200 inline-flex items-center justify-center transition shadow-sm"
                  >
                    ✓
                  </button>

                  <button
                    v-else
                    @click="openNewPaymentModal(row, m.id)"
                    title="Ajouter un versement"
                    class="w-8 h-8 rounded-full bg-gray-50 text-gray-300 hover:bg-emerald-50 hover:text-emerald-500 font-bold inline-flex items-center justify-center transition border border-dashed border-gray-200"
                  >
                    +
                  </button>
                </td>

                <td
                  class="px-4 py-3 text-right font-black text-emerald-600 whitespace-nowrap"
                >
                  {{ formatMoney(row.total_paye) }}
                </td>
              </tr>
            </tbody>
          </table>

          <!-- PAGINATION -->
          <div
            class="p-4 border-t border-gray-100 flex justify-between items-center text-sm"
          >
            <p class="text-gray-500">
              Affichage de {{ matrice.from || 0 }} à {{ matrice.to || 0 }} sur
              {{ matrice.total || 0 }} membres
            </p>
            <div class="flex gap-1">
              <template v-for="(link, k) in matrice.links" :key="k">
                <Link
                  v-if="link.url"
                  :href="link.url"
                  class="px-3 py-1 border rounded text-xs transition"
                  :class="{
                    'bg-emerald-600 text-white font-bold border-emerald-600':
                      link.active,
                    'bg-white text-gray-700 border-gray-300 hover:bg-gray-100':
                      !link.active,
                  }"
                >
                  <span v-html="link.label"></span>
                </Link>
                <span
                  v-else
                  v-html="link.label"
                  class="px-3 py-1 border rounded text-xs text-gray-400 border-gray-200 cursor-not-allowed"
                ></span>
              </template>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- MODALE CRÉATION TYPE CONTRIBUTION / ÉVÉNEMENT -->
    <Modal :show="showCreateTypeModal" @close="showCreateTypeModal = false">
      <form @submit.prevent="submitCreateType" class="p-6">
        <h2 class="text-base font-bold text-gray-900 mb-4 border-b pb-2">
          Créer un nouveau type de contribution ou événement
        </h2>

        <div class="space-y-4">
          <!-- Libellé -->
          <div>
            <label class="block text-sm font-bold text-gray-700"
              >Libellé / Intitulé *</label
            >
            <input
              v-model="typeForm.libelle"
              type="text"
              placeholder="Ex: Cotisation Annuelle, Levée de fonds Gala..."
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
              required
            />
            <span
              v-if="typeForm.errors.libelle"
              class="text-red-500 text-xs mt-1 block"
            >
              {{ typeForm.errors.libelle }}
            </span>
          </div>

          <!-- Catégorie -->
          <div>
            <label class="block text-sm font-bold text-gray-700"
              >Catégorie *</label
            >
            <select
              v-model="typeForm.categorie"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
              required
            >
              <option value="mensuelle">Cotisation Mensuelle Régulière</option>
              <option value="exceptionnelle">
                Contribution Exceptionnelle
              </option>
              <option value="evenement">Événement Spécial</option>
            </select>
          </div>

          <!-- Montant -->
          <div>
            <label class="block text-sm font-bold text-gray-700"
              >Montant par défaut / Objectif (FCFA)</label
            >
            <input
              v-model="typeForm.montant_defaut"
              type="number"
              placeholder="0 si libre"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
            />
          </div>

          <!-- Dates spécifiques événement -->
          <div
            v-if="typeForm.categorie === 'evenement'"
            class="grid grid-cols-1 md:grid-cols-2 gap-4 border-t pt-3"
          >
            <div>
              <label class="block text-sm font-bold text-gray-700"
                >Date de début</label
              >
              <input
                v-model="typeForm.date_debut"
                type="date"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
              />
            </div>
            <div>
              <label class="block text-sm font-bold text-gray-700"
                >Date de fin</label
              >
              <input
                v-model="typeForm.date_fin"
                type="date"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
              />
            </div>
          </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
          <button
            type="button"
            @click="showCreateTypeModal = false"
            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md font-bold text-sm transition"
          >
            Annuler
          </button>
          <button
            type="submit"
            :disabled="typeForm.processing"
            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md font-bold text-sm disabled:opacity-50 transition"
          >
            {{ typeForm.processing ? "Enregistrement..." : "Créer le type" }}
          </button>
        </div>
      </form>
    </Modal>

    <!-- MODALE DE PAIEMENT / ÉDITION -->
    <Modal :show="showPaymentModal" @close="showPaymentModal = false">
      <form @submit.prevent="submitPayment" class="p-6">
        <h2 class="text-base font-bold text-gray-900 mb-4 border-b pb-2">
          {{ modalTitle }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-bold text-gray-700"
              >Type de contribution</label
            >
            <select
              v-model="form.type_contribution_id"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
              required
            >
              <option v-for="t in typesDisponibles" :key="t.id" :value="t.id">
                {{ t.libelle }}
              </option>
            </select>
            <span
              v-if="form.errors.type_contribution_id"
              class="text-red-500 text-xs mt-1 block"
            >
              {{ form.errors.type_contribution_id }}
            </span>
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700"
              >Montant (FCFA)</label
            >
            <input
              v-model="form.montant"
              type="number"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
              required
            />
            <span
              v-if="form.errors.montant"
              class="text-red-500 text-xs mt-1 block"
            >
              {{ form.errors.montant }}
            </span>
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700"
              >Date de paiement</label
            >
            <input
              v-model="form.date_paiement"
              type="date"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
              required
            />
            <span
              v-if="form.errors.date_paiement"
              class="text-red-500 text-xs mt-1 block"
            >
              {{ form.errors.date_paiement }}
            </span>
          </div>

          <div>
            <label class="block text-sm font-bold text-gray-700"
              >Mode de paiement</label
            >
            <select
              v-model="form.mode_paiement"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
            >
              <option value="Espèces">Espèces</option>
              <option value="Mobile Money">Mobile Money</option>
              <option value="Virement">Virement</option>
              <option value="Chèque">Chèque</option>
            </select>
          </div>
        </div>

        <div class="mt-6 flex justify-end gap-3">
          <button
            type="button"
            @click="showPaymentModal = false"
            class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md font-bold text-sm transition"
          >
            Annuler
          </button>
          <button
            type="submit"
            :disabled="form.processing"
            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md font-bold text-sm disabled:opacity-50 transition"
          >
            {{ form.processing ? "Enregistrement..." : "Enregistrer" }}
          </button>
        </div>
      </form>
    </Modal>
  </AuthenticatedLayout>
</template>