<script setup>
import { ref,onMounted } from "vue";
import { useRouter } from "vue-router";
import {setError} from "../stores/error.js"
const router = useRouter();
const showMenu = ref(false);

import api from "../api";

const user = ref(null);

const logout = () => {
  localStorage.removeItem("token");
  router.push("/");
};


onMounted(async () => {
  try {
    const res = await api.get("/user");
    user.value = res.data;
  } catch (e) {
     setError("Hiba történt a felhasználó lekérése közben!")
  }
});

</script>

<template>
  <div class="flex h-screen bg-gray-100">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white text-gray flex flex-col shadow-md">

      <div class="p-6 text-lg font-bold border-b border-gray-200 bg-gray-100">
        WorkPilot
      </div>

      <nav class="flex-1 p-4 space-y-2">
        <router-link 
          to="/dashboard"
          :class="$route.path === '/dashboard'  ? 'bg-gray-300' : 'hover:bg-gray-200' "
          class="block px-3 py-2 rounded">
          Dashboard
        </router-link>

        <router-link 
          to="/projects"
           :class="$route.path === '/projects'  ? 'bg-gray-300' : 'hover:bg-gray-200' "
          class="block px-3 py-2 rounded ">
          Projects
        </router-link>
      </nav>

    </aside>

    <!-- MAIN -->
    <div class="flex-1 flex flex-col">

      <!-- HEADER -->
      <header class="bg-white  h-16 shadow-sm !border-l !border-gray-200">

        <div class="max-w-6xl mx-auto flex items-center justify-between h-full px-6">

          <div >
           
          </div>

          <!-- USER -->
          <div class="relative">

            <div
              class="flex items-center gap-3 cursor-pointer"
              @click="showMenu = !showMenu"
            >
              <div class="w-8 h-8 rounded-full bg-gray-300 flex items-center justify-center">
                {{ user?.email.charAt(0).toUpperCase() }}
              </div>

              <span class="text-sm text-gray-600">
                {{  user?.email }}
              </span>
            </div>

            <!-- DROPDOWN -->
            <div
              v-if="showMenu"
              class="absolute right-0 mt-2 w-40 bg-white rounded-xl shadow-lg p-2"
            >
              <button
                @click="logout"
                class="w-full text-left px-3 py-2 hover:bg-gray-100 rounded"
              >
                Logout
              </button>
            </div>

          </div>

        </div>

      </header>

      <!-- CONTENT -->
      <main class="flex-1 overflow-y-auto">
        <div class="max-w-6xl mx-auto p-6">
          <router-view />
        </div>
      </main>

    </div>

  </div>
</template>