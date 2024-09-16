<template>
  <div class="container mx-auto px-5 my-6 flex flex-col">
    <div v-if="hasAction">
      <button
        @click="callFormAnimal()"
        class="bg-blue-500 text-white px-5 py-2 border-blue-300 border-2 rounded-md hover:bg-blue-600 hover:border-blue-500 hover:transition-colors float-end"
      >
        Ajouter un animal
      </button>
    </div>
    <table class="my-8">
      <thead class="text-white bg-gray-700 border-black border-solid border-2">
        <tr>
          <th scope="col" class="p-2 cursor-pointer" @click="sortBy('id')">
            ID
          </th>
          <th
            scope="col"
            class="p-2 text-start cursor-pointer"
            @click="sortBy('nom')"
          >
            Nom
          </th>
          <th
            scope="col"
            class="p-2 text-start cursor-pointer"
            @click="sortBy('age')"
          >
            Age
          </th>
          <th
            scope="col"
            class="p-2 text-start cursor-pointer"
            @click="sortBy('description')"
          >
            Description
          </th>
          <th
            scope="col"
            class="p-2 text-start cursor-pointer"
            @click="sortBy('type')"
          >
            Type
          </th>
          <th
            scope="col"
            class="p-2 text-start cursor-pointer"
            @click="sortBy('race')"
          >
            Race
          </th>
          <th
            scope="col"
            class="p-2 text-start cursor-pointer"
            @click="sortBy('prixTTC')"
          >
            Prix TTC
          </th>
          <th
            scope="col"
            class="p-2 text-start cursor-pointer"
            @click="sortBy('statut')"
          >
            Statut
          </th>
          <th scope="col" class="p-2" v-if="hasAction">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="animal of sortedAnimaux"
          class="border-black border-solid border-b-2 border-l-2 border-r-2"
        >
          <th scope="row" class="p-2">
            {{ animal.id }}
          </th>
          <td class="p-2">{{ animal.nom }}</td>
          <td class="p-2">{{ animal.age }}</td>
          <td class="p-2">
            {{ animal.description }}
          </td>
          <td class="p-2">
            {{ animal.type.nom }}
          </td>
          <td class="p-2">
            {{ animal.race.nom }}
          </td>
          <td class="p-2">
            {{ animal.prixTTC }}
          </td>
          <td class="p-2">
            {{ animal.statut.nom }}
          </td>
          <td class="p-2 flex flex-col gap-1" v-if="hasAction">
            <button
              @click="callFormAnimal(animal.id)"
              class="bg-green-500 text-white px-5 py-2 border-green-300 border-2 rounded-md hover:bg-green-600 hover:border-green-500 hover:transition-colors w-full"
            >
              Modifier
            </button>
            <button
              @click="callModalSuppression(animal.id)"
              class="bg-red-500 text-white px-5 py-2 border-red-300 border-2 rounded-md hover:bg-red-600 hover:border-red-500 hover:transition-colors w-full"
            >
              Supprimer
            </button>
          </td>
        </tr>
      </tbody>
    </table>
    <FormAnimal ref="FormAnimal" @animalUpdated="updateAnimaux"></FormAnimal>
    <ModalSuppression
      ref="ModalSuppression"
      @animalDeleted="deleteAnimal"
    ></ModalSuppression>
  </div>
</template>

<script>
import FormAnimal from "../../../backoffice/components/forms/FormAnimal.vue";
import ModalSuppression from "../../../backoffice/components/modals/ModalSuppression.vue";

export default {
  props: {
    hasAction: false,
  },
  data() {
    return { animaux: [], currentSort: "id", currentSortDir: "asc" };
  },
  async mounted() {
    const animaux = await this.getAnimaux();
    this.animaux = animaux;
  },
  computed: {
    sortedAnimaux() {
      return this.animaux.sort((a, b) => {
        let modifier = 1;
        if (this.currentSortDir === "desc") modifier = -1;

        if (this.currentSort === "type" || this.currentSort === "race") {
          if (a[this.currentSort].nom < b[this.currentSort].nom)
            return -1 * modifier;
          if (a[this.currentSort].nom > b[this.currentSort].nom)
            return 1 * modifier;
          return 0;
        } else {
          if (a[this.currentSort] < b[this.currentSort]) return -1 * modifier;
          if (a[this.currentSort] > b[this.currentSort]) return 1 * modifier;
          return 0;
        }
      });
    },
  },
  methods: {
    async getAnimaux() {
      const response = await fetch("/api/animal/all", {
        method: "POST",
      });

      if (!response.ok) {
        throw new Error(`Erreur HTTP : ${response.status}`);
      }

      return response.json();
    },
    addAnimal(animal) {
      this.animaux.push(animal);
    },
    editAnimal(animal) {
      this.animaux.find((item, index) => {
        if (item.id === animal.id) {
          this.animaux[index] = {
            id: animal.id,
            nom: animal.nom,
            age: animal.age,
            description: animal.description,
            prixTTC: animal.prixTTC,
            type: {
              id: animal.type.id,
              nom: animal.type.nom,
            },
            race: {
              id: animal.race.id,
              nom: animal.race.nom,
            },
            statut: {
              id: animal.statut.id,
              nom: animal.statut.nom,
            },
          };
        }
      });
    },
    updateAnimaux(animal, isEdited) {
      if (isEdited) {
        this.animaux.find((item, index) => {
          if (item.id === animal.id) {
            this.animaux[index] = {
              id: animal.id,
              nom: animal.nom,
              age: animal.age,
              description: animal.description,
              prixTTC: animal.prixTTC,
              type: {
                id: animal.type.id,
                nom: animal.type.nom,
              },
              race: {
                id: animal.race.id,
                nom: animal.race.nom,
              },
              statut: {
                id: animal.statut.id,
                nom: animal.statut.nom,
              },
            };
          }
        });
      } else {
        this.animaux.push(animal);
      }
    },
    deleteAnimal(animalID) {
      this.animaux = this.animaux.filter((item) => item.id !== animalID);
    },
    sortBy(column) {
      if (this.currentSort === column) {
        this.currentSortDir = this.currentSortDir === "asc" ? "desc" : "asc";
      } else {
        this.currentSort = column;
        this.currentSortDir = "asc";
      }
    },
    callFormAnimal(animalID = null) {
      this.$refs.FormAnimal.initForm(animalID);
    },
    callAlertSuccess(title, text) {
      this.$refs.AlertSuccess.initAlert(title, text);
    },
    callModalSuppression(animalID) {
      this.$refs.ModalSuppression.initForm(animalID);
    },
  },
  components: {
    FormAnimal,
    ModalSuppression,
  },
};
</script>

<style scoped></style>
