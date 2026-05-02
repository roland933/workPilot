<script setup>
import { ref, onMounted } from "vue";
import api from "../api";
import EarningsChart from "../components/Charts/ EarningsChart.vue";
import HoursChart from "../components/Charts/HoursChart.vue";
import ProjectList from "../components/Projects/ProjectList.vue";
import PieChart from "../components/Charts/PieChart.vue";
import { setError } from "../stores/error";
import { formatMoney } from "../js/utils/utils";

const summary = ref({
  hours: 0,
  earnings: 0,
  projects: 0
});

const stats = ref();
const loading = ref(true);
const loadStatsData = async () => {
  const statsRes = await api.get("/stats");
  stats.value = statsRes.data;
};

const loadSummary = async () => {
 loading.value=true
  try {
  const res = await api.get("/stats/summary");
  summary.value = res.data;
  }catch(e){
    setError('Nem sikerült a statisztika lekérése!')
  }finally{
    loading.value=false
  }
};


onMounted(async () => {

  await loadStatsData();
  await loadSummary();

});


</script>

<template>
  <div class="p-6 min-h-screen">

    <div class="flex justify-between mb-6">
         <h1 class="text-2xl font-bold">Dashboard</h1>
    </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">

      <div class="bg-white p-4 rounded-md shadow">
          <div v-if="loading">Loading...</div>
          <div v-else>
            <p class="text-gray-500">Total Hours</p>
            <h2 class="text-2xl font-bold">{{ summary.hours }}</h2>
        </div>
      </div>

      <div class="bg-white p-4 rounded-md shadow">
      <div v-if="loading">Loading...</div>
        <div v-else>
        <p class="text-gray-500">Earnings</p>
        <h2 class="text-2xl font-bold text-green-600">{{ formatMoney(summary.earnings) }}</h2>
        </div>
      </div>

      <div class="bg-white p-4 rounded-md shadow">
        <div v-if="loading">Loading...</div>
         <div v-else>
          <p class="text-gray-500">Total project</p>
          <h2 class="text-2xl font-bold">  {{ summary.projects }}</h2>
        </div>
      </div>

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
         <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
          <EarningsChart />
          <HoursChart />
          <PieChart />
          </div>
      </div>

  
  </div>
</template>