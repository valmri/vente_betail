<template>
  <header class="bg-gray-700">
    <div class="container mx-auto px-5 py-6 flex justify-between items-center">
      <h1 class="block text-white text-xl font-bold">Vente de betail</h1>
      <a
        href="tel:0641999706"
        class="bg-blue-500 text-white px-5 py-2 border-blue-300 border-2 rounded-md hover:bg-blue-600 hover:border-blue-500 hover:transition-colors float-end"
        >06 41 99 97 06</a
      >
    </div>
  </header>
  <AnimalTable ref="AnimalTable"></AnimalTable>
</template>

<script>
import AnimalTable from "../../shared/components/tables/AnimalTable.vue";

export default {
  components: {
    AnimalTable
  }
};
</script>

<style scoped></style>
