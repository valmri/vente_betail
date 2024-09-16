<template>
  <header class="bg-gray-700">
    <div class="container mx-auto px-5 py-6 flex justify-between items-center">
      <h1 class="block text-white text-xl font-bold">{{ title }}</h1>
      <a
        href="/logout"
        class="block text-white px-5 py-2 bg-red-500 border-red-300 border-2 rounded-md hover:bg-red-600 hover:border-red-500 hover:transition-colors"
        >Déconnexion</a
      >
    </div>
  </header>
  <AnimalTable ref="AnimalTable" hasAction="true"></AnimalTable>
</template>

<script>
import AnimalTable from "../../shared/components/tables/AnimalTable.vue";
export default {
  data() {
    return {
      title: "Dashboard",
    };
  },
  components: {
    AnimalTable,
  },
};
</script>

<style scoped></style>
