<template>
  <div
    v-if="showModal"
    class="!absolute top-0 left-0 right-0 bottom-0 bg-[#000000a1] flex flex-col"
  >
    <div
      class="container mx-auto py-5 px-10 my-6 flex flex-col justify-center bg-white rounded-lg lg:w-1/2 gap-2"
    >
      <div
        class="flex justify-between border-solid border-b-2 border-b-gray-100 pb-4 mb-2 items-end"
      >
        <h2 class="font-bold text-2xl leading-6">
          Confirmation de suppression
        </h2>
        <button
          type="button"
          @click="toggleModal"
          class="bg-red-500 text-white border-red-300 border-2 rounded-md hover:bg-red-600 hover:border-red-500 hover:transition-colors px-3 py-1"
        >
          x
        </button>
      </div>
      <p>Voulez-vous vraiment supprimer l'animal n°{{ animalID }} ?</p>
      <div class="mt-4 flex justify-center gap-3">
        <button
          type="button"
          @click="toggleModal"
          class="bg-gray-500 text-white py-2 px-5 my-1 border-gray-300 border-2 rounded-md hover:bg-gray-600 hover:border-gray-500 hover:transition-colors w-full"
        >
          Non
        </button>
        <button
          type="button"
          @click="deleteAnimal"
          class="bg-blue-500 text-white py-2 px-5 my-1 border-blue-300 border-2 rounded-md hover:bg-blue-600 hover:border-blue-500 hover:transition-colors w-full"
        >
          Oui
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      showModal: false,
      animalID: null,
    };
  },
  methods: {
    initForm(animalID) {
      this.animalID = animalID;
      this.toggleModal();
    },
    toggleModal() {
      this.showModal = !this.showModal;
    },
    async deleteAnimal() {
      await fetch("/api/animal/delete", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ id: this.animalID }),
      });
      this.$emit("animalDeleted", this.animalID);
      this.toggleModal();
    },
  },
};
</script>

<style scoped></style>
