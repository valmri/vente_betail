<template>
  <div class="container mx-auto px-5 my-6 flex flex-col items-center">
    <form
      @submit.prevent="submitForm"
      method="post"
      class="border-2 border-solid border-black p-5 rounded-lg lg:w-1/4"
    >
      <div v-if="errorMessage" class="alert alert-danger">
        {{ errorMessage }}
      </div>
      <div v-if="successMessage" class="alert alert-success">
        {{ successMessage }}
      </div>

      <h1 class="mb-5 text-center font-bold text-2xl">Connexion</h1>
      <div class="mb-4">
        <label for="username">Identifiant</label><br />
        <input
          type="text"
          id="username"
          class="border-2 border-solid border-black rounded-sm w-full p-1"
          autocomplete="username"
          aria-label="Username"
          required
          autofocus
          v-model="form.username"
        />
      </div>
      <div class="mb-4">
        <label for="password">Mot de passe</label><br />
        <input
          type="password"
          id="password"
          class="border-2 border-solid border-black rounded-sm w-full p-1"
          autocomplete="current-password"
          aria-label="Password"
          required
          v-model="form.password"
        />
      </div>

      <button
        class="bg-blue-500 text-white px-5 py-2 border-blue-300 border-2 rounded-md hover:bg-blue-600 hover:border-blue-500 hover:transition-colors block mx-auto"
        type="submit"
      >
        Se connecter
      </button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      title: "Login",
      errorMessage: "",
      successMessage: "",
      form: {
        username: "",
        password: "",
      },
    };
  },
  async mounted() {
    const response = await fetch("/api/login", {
      method: "POST",
    });

    if (response.ok) {
      window.location.href = "/admin/dashboard";
    }
  },
  methods: {
    async submitForm() {
      const response = await fetch("/api/login", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          username: this.form.username,
          password: this.form.password,
        }),
      });

      if (!response.ok) {
        const errorData = await response.json();
        this.errorMessage = errorData.message || "Login failed";
      } else {
        window.location.href = "/admin/dashboard";
      }
    },
  },
};
</script>

<style scoped></style>
