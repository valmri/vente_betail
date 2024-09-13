import { createApp } from "vue";
import AccueilPage from "./pages/AccueilPage.vue";

function mountVueComponent() {
  const accueilElement = document.getElementById("accueil-page");

  if (accueilElement) {
    createApp(AccueilPage).mount(accueilElement);
  }
}

mountVueComponent();