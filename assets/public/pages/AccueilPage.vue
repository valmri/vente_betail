<template>
  <div class="public-page">
    <h1 class="text-3xl font-bold underline">{{ title }}</h1>
    <p>{{ description }}</p>
    <button v-on:click="test">Test</button>
  </div>
</template>

<script>
export default {
  data() {
    return {
      title: "Titre",
      description: "Desc",
    };
  },
  methods: {
    test: function () {
      console.log("Hey");
    },
  },
};
</script>

<style scoped>
</style>
