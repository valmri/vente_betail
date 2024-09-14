import { createApp } from "vue";
import LoginPage from "./pages/LoginPage.vue";

function mountVueComponent() {
  const loginElement = document.getElementById("login-page");

  if (loginElement) {
    createApp(LoginPage).mount(loginElement);
  }
}

mountVueComponent();
