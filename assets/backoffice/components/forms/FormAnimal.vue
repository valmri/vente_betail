<template>
  <div
    v-if="showForm"
    class="!absolute top-0 left-0 right-0 bottom-0 bg-[#000000a1] flex flex-col"
  >
    <div
      class="container mx-auto py-5 px-10 my-6 flex flex-col justify-center bg-white rounded-lg lg:w-1/2 gap-2"
    >
      <div
        class="flex justify-between border-solid border-b-2 border-b-gray-100 pb-4 mb-2 items-end"
      >
        <h2 class="font-bold text-2xl leading-6">Animal</h2>
        <button
          type="button"
          @click="resetForm"
          class="bg-red-500 text-white border-red-300 border-2 rounded-md hover:bg-red-600 hover:border-red-500 hover:transition-colors px-3 py-1"
        >
          x
        </button>
      </div>
      <form @submit.prevent="submitForm" method="post">
        <AlertDanger :show="errorMessage" :text="errorMessage"></AlertDanger>

        <div class="flex gap-2 mt-4">
          <div class="w-full">
            <label for="nom">Nom</label><br />
            <input
              type="text"
              name="nom"
              id="nom"
              v-model="animal.nom"
              class="border-2 border-black w-full p-1"
            />
          </div>

          <div class="w-full">
            <label for="age">Age</label><br />
            <input
              type="number"
              name="age"
              id="age"
              v-model="animal.age"
              class="border-2 border-black w-full p-1"
            />
          </div>
        </div>

        <div class="mt-4">
          <label for="description">Description</label>
          <textarea
            name="description"
            id="description"
            v-model="animal.description"
            class="border-2 border-black w-full p-1"
          >
          </textarea>
        </div>

        <div class="flex gap-2 mt-4">
          <div class="w-full">
            <div v-if="showSelectAnimalType">
              <label for="type">Type</label>
              <select
                @change="getListeRaces()"
                name="type"
                id="type"
                v-model="animal.type.id"
                class="border-2 border-black w-full p-1"
              >
                <option v-for="type of types" :value="type.id">
                  {{ type.nom }}
                </option>
              </select>
            </div>
            <FormAnimalType
              ref="FormAnimalType"
              @animalTypeAdded="addAnimalType"
              @formClosed="toggleInputAnimalType"
            ></FormAnimalType>
            <button
              type="button"
              @click="callFormAnimalType()"
              v-if="showSelectAnimalType"
              class="bg-blue-500 text-white px-2 py-1 my-1 border-blue-300 border-2 rounded-md hover:bg-blue-600 hover:border-blue-500 hover:transition-colors w-full"
            >
              Ajouter un type
            </button>
          </div>

          <div class="w-full">
            <div v-if="showSelectAnimalRace">
              <label for="race">Race</label>
              <select
                name="race"
                id="race"
                v-model="animal.race.id"
                class="border-2 border-black w-full p-1"
              >
                <option v-for="race of races" :value="race.id">
                  {{ race.nom }}
                </option>
              </select>
            </div>
            <FormAnimalRace
              ref="FormAnimalRace"
              @animalRaceAdded="addAnimalRace"
              @formClosed="toggleInputAnimalRace"
            ></FormAnimalRace>
            <button
              type="button"
              @click="callFormAnimalRace()"
              v-if="showSelectAnimalRace"
              class="bg-blue-500 text-white px-2 py-1 my-1 border-blue-300 border-2 rounded-md hover:bg-blue-600 hover:border-blue-500 hover:transition-colors w-full"
            >
              Ajouter une race
            </button>
          </div>
        </div>

        <div class="mt-4">
          <label for="prixTTC">Prix TTC</label><br />
          <input
            type="text"
            name="prixTTC"
            id="prixTTC"
            v-model="animal.prixTTC"
            class="border-2 border-black w-full p-1"
          />
        </div>

        <div class="mt-4">
          <label for="statutventre">Statut de vente</label>
          <select
            name="setatutvente"
            id="statutvente"
            v-model="animal.statut.id"
            class="border-2 border-black w-full p-1"
          >
            <option v-for="statut of statuts" :value="statut.id">
              {{ statut.nom }}
            </option>
          </select>
        </div>

        <div class="mt-4 flex justify-end gap-3">
          <button
            type="button"
            @click="resetForm"
            class="bg-gray-500 text-white p-2 my-1 border-gray-300 border-2 rounded-md hover:bg-gray-600 hover:border-gray-500 hover:transition-colors"
          >
            Annuler
          </button>
          <button
            type="submit"
            class="bg-blue-500 text-white p-2 my-1 border-blue-300 border-2 rounded-md hover:bg-blue-600 hover:border-blue-500 hover:transition-colors"
          >
            Sauvegarder
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script>
import FormAnimalRace from "./FormAnimalRace.vue";
import FormAnimalType from "./FormAnimalType.vue";
import AlertDanger from "../alerts/AlertDanger.vue";

export default {
  data() {
    return {
      types: [],
      races: [],
      statuts: [],
      showForm: false,
      showSelectAnimalRace: true,
      showSelectAnimalType: true,
      errorMessage: null,
      animal: {
        id: null,
        nom: null,
        age: 0,
        description: null,
        prixTTC: 0,
        statut: {
          id: 0,
          nom: null,
        },
        type: {
          id: 0,
          nom: null,
        },
        race: {
          id: 0,
          nom: null,
        },
      },
    };
  },
  methods: {
    async initForm(animalID) {
      this.toggleForm();

      if (animalID) {
        this.getAnimal(animalID);
      }

      if (this.showForm) {
        this.getListeTypes();
        this.getStatuts();
      }
    },
    toggleForm() {
      this.showForm = !this.showForm;
    },
    toggleInputAnimalRace() {
      this.showSelectAnimalRace = !this.showSelectAnimalRace;
    },
    toggleInputAnimalType() {
      this.showSelectAnimalType = !this.showSelectAnimalType;
    },
    async submitForm() {
      let response;
      if (this.animal.id) {
        response = await this.editAnimal();
        this.$emit("animalUpdated", response.animal, true);
        this.resetForm();
      } else {
        response = await this.createAnimal();
        this.$emit("animalUpdated", response.animal, false);
        this.resetForm();
      }
    },
    async createAnimal() {
      const response = await fetch("/api/animal/create", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          nom: this.animal.nom,
          age: this.animal.age,
          description: this.animal.description,
          prixTTC: this.animal.prixTTC,
          type: this.animal.type.id,
          race: this.animal.race.id,
          statut: this.animal.statut.id,
        }),
      });

      if (!response.ok) {
        const datas = await response.json();
        this.errorMessage = datas.message;
      } else {
        return await response.json();
      }
    },
    async editAnimal() {
      const response = await fetch("/api/animal/edit", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          id: this.animal.id,
          nom: this.animal.nom,
          age: this.animal.age,
          description: this.animal.description,
          prixTTC: this.animal.prixTTC,
          type: this.animal.type.id,
          race: this.animal.race.id,
          statut: this.animal.statut.id,
        }),
      });

      if (!response.ok) {
        const datas = await response.json();
        this.errorMessage = datas.message;
      } else {
        return await response.json();
      }
    },
    async getAnimal(animalID) {
      this.animal.id = animalID;

      const response = await fetch("/api/animal", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id: animalID }),
      });

      const datas = await response.json();

      if (!response.ok) {
        this.errorMessage = datas.message;
      } else {
        this.animal.nom = datas.nom;
        this.animal.age = datas.age;
        this.animal.description = datas.description;
        this.animal.prixTTC = datas.prixTTC;
        this.animal.statut.id = datas.statut.id;
        this.animal.statut.nom = datas.statut.nom;
        this.animal.type.id = datas.type.id;
        this.animal.race.id = datas.race.id;
        this.animal.type.nom = datas.type.nom;
        this.animal.race.nom = datas.race.nom;
      }
    },
    async getListeTypes() {
      const response = await fetch("/api/types", {
        method: "POST",
      });

      if (!response.ok) {
        throw new Error(`Erreur HTTP : ${response.status}`);
      }

      this.types = await response.json();
      this.animal.type.id = this.types[0].id;
      this.getListeRaces();
    },
    async getListeRaces() {
      const response = await fetch("/api/race/all", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          id: this.animal.type.id,
        }),
      });

      if (!response.ok) {
        throw new Error(`Erreur HTTP : ${response.status}`);
      }

      const datas = await response.json();
      this.races = datas;
      if (datas.length > 0) {
        this.animal.race.id = this.races[0].id;
      } else {
        this.callFormAnimalRace();
      }
    },
    async getStatuts() {
      const response = await fetch("/api/statuts", {
        method: "POST",
      });

      if (!response.ok) {
        throw new Error(`Erreur HTTP : ${response.status}`);
      }

      this.statuts = await response.json();
      this.animal.statut.id = this.statuts[0].id;
    },
    callFormAnimalRace() {
      this.$refs.FormAnimalRace.initForm(this.animal.type.id);
      this.toggleInputAnimalRace();
    },
    callFormAnimalType() {
      this.$refs.FormAnimalType.initForm();
      this.toggleInputAnimalType();
    },
    addAnimalRace(newRace) {
      this.races.push(newRace);
      this.animal.race.id = newRace.id;
      this.toggleInputAnimalRace();
    },
    addAnimalType(newType) {
      this.types.push(newType);
      this.animal.type.id = newType.id;
      this.toggleInputAnimalType();
      this.getListeRaces();
    },
    resetForm() {
      this.animal.nom = null;
      this.animal.age = 0;
      this.animal.description = null;
      this.animal.prixTTC = 0;
      this.animal.type.id = 0;
      this.animal.race.id = 0;
      this.animal.statut.id = 0;

      this.errorMessage = null;
      this.toggleForm();
    },
  },
  components: {
    FormAnimalRace,
    FormAnimalType,
    AlertDanger,
  },
};
</script>

<style scoped></style>
