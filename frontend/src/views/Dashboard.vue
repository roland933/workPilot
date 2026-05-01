<script setup>
import { ref, onMounted } from "vue";
import api from "../api";
import EarningsChart from "../components/Charts/ EarningsChart.vue";
import HoursChart from "../components/Charts/HoursChart.vue";
import ProjectList from "../components/Projects/ProjectList.vue";
import PieChart from "../components/Charts/PieChart.vue";
import { setError } from "../stores/error";

const stats = ref([]);
const projects = ref([]);
const elapsed = ref(0);
let interval = null;

const activeTimer = ref(null);

const loadActive = async () => {
  try {
  const res = await api.get("/time/active");
  
  activeTimer.value = res.data && res.data.id ? res.data : null;
   stopLiveTimer();

  if (activeTimer.value) {
    startLiveTimer();
  }
  }catch(err) {
    console.log(err);
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



const startLiveTimer = () => {
  if (!activeTimer.value) return;

  const start = new Date(activeTimer.value.start_time);

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

  await loadData();

});


</script>

<template>
  <div class="p-6 min-h-screen">

    <div class="flex justify-between items-center mb-6">
         <h1 class="text-2xl font-bold">Dashboard</h1>
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

 <div v-if="activeTimer"
     class="bg-yellow-50 border border-yellow-200 p-4 rounded-2xl mb-6">

  <p class="text-sm text-yellow-700">Active Timer</p>
  <h3 class="font-bold">{{ activeTimer.project.name }}</h3>

  <p class="text-xl font-mono mt-1">
    {{ formatTime(elapsed) }}
  </p>
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
      

      <div class="space-y-6">
          <EarningsChart />
          <HoursChart />
          <PieChart />
      </div>

  
  </div>
</template>