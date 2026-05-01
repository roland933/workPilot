<script setup>
import { ref, onMounted } from "vue";
import Chart from "chart.js/auto";
import api from "../../api";

const chartRef = ref(null);

onMounted(async () => {
  const res = await api.get("/stats/earnings");

  const labels = res.data.map(i => i.date);
  const data = res.data.map(i => i.earnings);

  new Chart(chartRef.value, {
    type: "line",
    data: {
      labels,
      datasets: [
        {
          label: "Earnings",
          data,
          borderColor: "#22c55e",
          backgroundColor: "rgba(34,197,94,0.2)",
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
    <h2 class="font-bold mb-2">Earnings</h2>
    <canvas ref="chartRef"></canvas>
  </div>
</template>