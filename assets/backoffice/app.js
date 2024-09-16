import { createApp } from "vue";
import LoginPage from "./pages/LoginPage.vue";
import DashboardPage from "./pages/DashboardPage.vue";

function mountVueComponent() {
  const loginElement = document.getElementById("login-page");
  const dashboardElement = document.getElementById("dashboard-page");

  if (loginElement) {
    createApp(LoginPage).mount(loginElement);
  }

  if (dashboardElement) {
    createApp(DashboardPage).mount(dashboardElement);
  }
}

mountVueComponent();
