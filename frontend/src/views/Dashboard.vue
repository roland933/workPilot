<script setup>
import { ref, onMounted } from "vue";
import api from "../api";
import Chart from "chart.js/auto";
const stats = ref([]);
const projects = ref([]);
const elapsed = ref(0);
const chartRef = ref(null);
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


onMounted(async () => {

  loadData()
  const res = await api.get("/stats/daily");

  const labels = res.data.map(i => i.date);
  const data = res.data.map(i => i.hours);

  new Chart(chartRef.value, {
    type: "line",
    data: {
      labels,
      datasets: [
        {
          label: "Hours",
          data,
          borderWidth: 2,
          tension: 0.3,
        },
      ],
    },
  });
});





</script>

<template>
  <div class="p-6 bg-gray-100 min-h-screen">


    <div class="flex justify-between items-center mb-6">
  <h1 class="text-2xl font-bold">Dashboard</h1>

  <span class="text-gray-500">
    Welcome back 👋
  </span>
</div>

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
           class=" hover:shadow-md transition cursor-pointer bg-white p-4 mb-2 rounded shadow flex justify-between items-center">

        <span>{{ p.name }}</span>

        <div>
          <button
            class="hover:bg-green-400 disabled:cursor-not-allowed  disabled:bg-green-200 bg-green-500 text-white px-3 py-1 rounded mr-2"
            :disabled="activeTimer !== null"
            @click="start(p.id)"
          >
            Start
          </button>
         

          <button
            :disabled="!activeTimer"
            class="hover:bg-red-400 disabled:cursor-not-allowed disabled:bg-red-200 bg-red-500 text-white px-3 py-1 rounded"
            @click="stop"
          >
            Stop
          </button>
        </div>

      </div>
    </div>

      <div class="bg-white p-4 rounded-xl shadow mb-6">
      <h2 class="font-bold mb-2">Daily Hours</h2>
      <canvas ref="chartRef"></canvas>
      </div>

  </div>
</template>