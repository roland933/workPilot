<script setup>
import { ref, onMounted } from "vue";
import api from "../api";

const stats = ref([]);
const projects = ref([]);
const elapsed = ref(0);
let interval = null;

const activeTimer = ref(null);

const loadActive = async () => {
  const res = await api.get("/time/active");
  
  activeTimer.value = res.data && res.data.id ? res.data : null;
   stopLiveTimer();

  if (activeTimer.value) {
    startLiveTimer();
  }
  
};

const loadData = async () => {
  const statsRes = await api.get("/stats");
  stats.value = statsRes.data;

  const projRes = await api.get("/projects");
  projects.value = projRes.data;
};

const formatTime = (sec) => {
  const h = Math.floor(sec / 3600);
  const m = Math.floor((sec % 3600) / 60);
  const s = sec % 60;

  return `${h}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
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


const startLiveTimer = () => {
  if (!activeTimer.value) return;

 const start = new Date(activeTimer.value.start_time + "Z");

  interval = setInterval(() => {
    const now = new Date();
    elapsed.value = Math.floor((now - start) / 1000);

  
  }, 1000);
};

const stopLiveTimer = () => {
  clearInterval(interval);
  elapsed.value = 0;
};

onMounted(loadData);





</script>

<template>
  <div class="p-6 bg-gray-100 min-h-screen">

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

 

</div>

    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

  <div class="bg-white p-4 rounded-xl shadow">
    <p class="text-gray-500">Total Hours</p>
    <h2 class="text-2xl font-bold">12.5</h2>
  </div>

  <div class="bg-white p-4 rounded-xl shadow">
    <p class="text-gray-500">Earnings</p>
    <h2 class="text-2xl font-bold text-green-600">$250</h2>
  </div>

  <div class="bg-white p-4 rounded-xl shadow">
    <p class="text-gray-500">Active Timer</p>
    <h2 class="text-xl font-bold text-yellow-500">
      {{ activeTimer ? formatTime(elapsed) : "No active timer" }}
    </h2>
  </div>

</div>

<div v-if="activeTimer" class="bg-yellow-100 p-4 rounded-xl mb-6 shadow">
  <h3 class="font-semibold">
    ⏱️ {{ activeTimer.project.name }}
  </h3>
  <p class="text-xl font-bold">{{ formatTime(elapsed) }}</p>
</div>

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
            :disabled="!activeTimer"
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