<template>
  <div
    v-if="show"
    class="bg-red-500 text-white px-5 py-2 border-red-300 border-2 rounded-md w-full"
  >
    <span class="font-bold">{{ title }}</span> {{ text }}
  </div>
</template>

<script>
export default {
  data() {
    return {
      title: "Erreur :",
    };
  },
  props: {
    text: "",
    show: false,
  },
};
</script>

<style scoped></style>
©
