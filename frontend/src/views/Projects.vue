<script setup>
import { ref, onMounted } from "vue";
import api from "../api";

const projects = ref([]);

onMounted(async () => {
  const res = await api.get("/projects");
  projects.value = res.data;
});
</script>

<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Projects</h1>

    <div v-for="p in projects" :key="p.id"
         class="bg-white p-4 mb-2 rounded shadow">
      {{ p.name }}
    </div>
  </div>
</template>