<script setup>
import { ref, onMounted } from "vue";
import api from "../api";

const stats = ref([]);
const projects = ref([]);

const activeTimer = ref(null);

const loadActive = async () => {
  const res = await api.get("/time/active");
  
  activeTimer.value = res.data && res.data.id ? res.data : null;
  
};

const loadData = async () => {
  const statsRes = await api.get("/stats");
  stats.value = statsRes.data;

  const projRes = await api.get("/projects");
  projects.value = projRes.data;
};

const start = async (projectId) => {
  await api.post("/time/start", {
    project_id: projectId,
    description: "working",
  });
  await loadData();
  await loadActive();
};

const stop = async () => {
  await api.post("/time/stop");
  
  await loadData();
  await loadActive();
};

onMounted(loadData);
</script>

<template>
  <div class="p-6 bg-gray-100 min-h-screen">

    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    <!-- STATS -->
    <div class="mb-8">
      <h2 class="text-xl font-semibold mb-2">Stats</h2>

      <div v-for="item in stats" :key="item.project"
           class="bg-white p-4 mb-2 rounded shadow">
        <h3 class="font-bold">{{ item.project }}</h3>
        <p>Hours: {{ item.hours }}</p>
        <p class="text-green-600">Earnings: ${{ item.earnings }}</p>
      </div>
    </div>

    <!-- PROJECTS -->
    <div>
      <h2 class="text-xl font-semibold mb-2">Projects</h2>

      <div v-if="activeTimer" class="bg-yellow-100 p-4 mb-4 rounded">
  ⏱️ Running: {{ activeTimer.project.name }}
</div>


      <div v-for="p in projects" :key="p.id"
           class="bg-white p-4 mb-2 rounded shadow flex justify-between items-center">

        <span>{{ p.name }}</span>

        <div>
          <button
            class="bg-green-500 text-white px-3 py-1 rounded mr-2"
            :disabled="activeTimer !== null"
            @click="start(p.id)"
          >
            Start
          </button>
         

          <button
          v-if="activeTimer"
            class="bg-red-500 text-white px-3 py-1 rounded"
            @click="stop"
          >
            Stop
          </button>
        </div>

      </div>
    </div>

  </div>
</template>