<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";
import api from "../api";

const email = ref("");
const password = ref("");
const router = useRouter();
const error = ref("");

const login = async () => {
  try {
    const res = await api.post("/login", {
      email: email.value,
      password: password.value,
    });

    localStorage.setItem("token", res.data.token);
    router.push("/dashboard");

  } catch (err) {
    error.value = err.response?.data?.message || "Login failed";
  }
};
setTimeout(() => error.value = "", 3000);
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white p-8 rounded-xl shadow-md w-80">
      <div v-if="error" class="bg-red-100 text-red-700 p-2 mb-3 rounded">
        {{ error }}
      </div>
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