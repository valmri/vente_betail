<template>
  <div>
    <form @submit.prevent="submitForm()" v-if="showForm">
      <label for="race">Race</label><br />
      <input
        type="text"
        name="race"
        id="race"
        v-model="animal.race.nom"
        class="border-2 border-black w-full p-1"
      />
      <div class="flex gap-2">
        <button
          type="button"
          @click="closeForm"
          class="bg-red-500 text-white px-2 py-1 my-1 border-red-300 border-2 rounded-md hover:bg-red-600 hover:border-red-500 hover:transition-colors w-full"
        >
          Annuler
        </button>
        <button
          type="submit"
          class="bg-green-500 text-white px-2 py-1 my-1 border-green-300 border-2 rounded-md hover:bg-green-600 hover:border-green-500 hover:transition-colors w-full"
        >
          Sauvegarder
        </button>
      </div>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showForm: false,
      newRace: null,
      animal: {
        race: {
          id: 0,
          nom: "",
        },
        type: {
          id: 0,
        },
      },
    };
  },
  methods: {
    async initForm(animalTypeID) {
      this.animal.type.id = animalTypeID;
      this.toggleForm();
    },
    toggleForm() {
      this.showForm = !this.showForm;
    },
    closeForm() {
      this.toggleForm();
      this.$emit("formClosed");
    },
    async submitForm() {
      const response = await fetch("/api/race/create", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          type: {
            id: this.animal.type.id,
          },
          race: {
            nom: this.animal.race.nom,
          },
        }),
      });
      const datas = await response.json();
      if (datas.race && datas.race.id) {
        this.$emit("animalRaceAdded", datas.race);
      }
      this.resetForm();
    },
    resetForm() {
      this.toggleForm();
      this.animal.race.id = 0;
      this.animal.race.nom = "";
      this.animal.type.id = 0;
    },
  },
};
</script>

<style scoped></style>
