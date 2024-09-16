<template>
  <div>
    <form @submit.prevent="submitForm()" v-if="showForm">
      <label for="type">Type</label><br />
      <input
        type="text"
        name="type"
        id="type"
        v-model="animal.type.nom"
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
      newType: null,
      animal: {
        type: {
          nom: "",
        },
      },
    };
  },
  methods: {
    async initForm() {
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
      const response = await fetch("/api/type/create", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          type: {
            nom: this.animal.type.nom,
          },
        }),
      });
      const datas = await response.json();
      if (datas.type && datas.type.id) {
        this.$emit("animalTypeAdded", datas.type);
      }
      this.resetForm();
    },
    resetForm() {
      this.toggleForm();
      this.animal.type.nom = "";
    },
  },
};
</script>

<style scoped></style>
