<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import api from "../api";

const email = ref("");
const password = ref("");
const router = useRouter();

const login = async () => {
  const res = await api.post("/login", {
    email: email.value,
    password: password.value,
  });

  localStorage.setItem("token", res.data.token);
  router.push("/dashboard");
};
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-xl shadow-md w-80">
      <h2 class="text-xl font-bold mb-4">Login</h2>

      <input
        v-model="email"
        class="w-full mb-3 p-2 border rounded"
        placeholder="Email"
      />

      <input
        v-model="password"
        type="password"
        class="w-full mb-4 p-2 border rounded"
        placeholder="Password"
      />

      <button
        @click="login"
        class="w-full bg-blue-500 text-white p-2 rounded hover:bg-blue-600"
      >
        Login
      </button>
    </div>
  </div>
</template>