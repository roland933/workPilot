<script setup>
import { ref, onMounted } from "vue";
import api from "../api";

const stats = ref([]);

onMounted(async () => {
  const res = await api.get("/stats");
  stats.value = res.data;
});
</script>

<template>
  <div class="min-h-screen bg-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div
        v-for="item in stats"
        :key="item.project"
        class="bg-white p-4 rounded-xl shadow"
      >
        <h2 class="font-semibold text-lg">{{ item.project }}</h2>
        <p class="text-gray-600">Hours: {{ item.hours }}</p>
        <p class="text-green-600 font-bold">$ {{ item.earnings }}</p>
      </div>
    </div>
  </div>
</template>