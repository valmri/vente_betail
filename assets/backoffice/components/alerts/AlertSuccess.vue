<template>
  <div v-if="show">
    <span class="font-bold">{{ title }}</span> {{ text }}
  </div>
</template>

<script>
export default {
  data() {
    return {
      title: "",
      text: "",
      show: false,
    };
  },
  methods: {
    initAlert(title, text) {
      this.show = !this.show ? true : false;
      this.title = title;
      this.text = text;
    },
  },
};
</script>

<style scoped></style>
