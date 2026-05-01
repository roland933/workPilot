import { createRouter, createWebHistory } from "vue-router";
import AppLayout from "./layouts/AppLayout.vue";
import Login from "./views/Login.vue";
import Dashboard from "./views/Dashboard.vue";
import Projects from "./views/Projects.vue";

const routes = [
  {
    path: "/",
    component: Login,
  },
  {
    path: "/",
    component: AppLayout,
    children: [
      { path: "dashboard", component: Dashboard },
      { path: "projects", component: Projects },
    ],
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});


router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("token");

  if (!token && to.path !== "/") {
    return next("/");
  }

  if (token && to.path === "/") {
    return next("/dashboard");
  }

  next();
});

export default router;