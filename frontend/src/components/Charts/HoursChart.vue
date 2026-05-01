<script setup>
import { ref, onMounted } from "vue";
import Chart from "chart.js/auto";
import api from "../../api";

const chartRef = ref(null);

onMounted(async () => {
  const res = await api.get("/stats/daily");

  const labels = res.data.map(i => i.date);
  const data = res.data.map(i => i.hours);

  new Chart(chartRef.value, {
    type: "line",
    data: {
      labels,
      datasets: [
        {
          label: "Earnings",
          data,
          borderColor: "#3b82f6",
          backgroundColor: "rgba(59,130,246,0.2)",
          fill: true,
          tension: 0.4,
        },
      ],
    },
  });
});
</script>

<template>
  <div class="bg-white p-4 rounded-xl shadow">
    <h2 class="font-bold mb-2">Daily Hours</h2>
    <canvas ref="chartRef"></canvas>
  </div>
</template>