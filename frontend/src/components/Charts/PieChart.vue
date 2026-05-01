<script setup>
import { ref, onMounted } from "vue";
import Chart from "chart.js/auto";
import api from "../../api";

const chartRef = ref(null);

onMounted(async () => {
  const res = await api.get("/stats/projects");

  const labels = res.data.map(i => i.project);
  const data = res.data.map(i => i.earnings);

  new Chart(chartRef.value, {
    type: "pie",
    data: {
      labels,
      datasets: [
        {
          data,
          backgroundColor: [
            "#3b82f6",
            "#22c55e",
            "#f59e0b",
            "#ef4444",
          ],
        },
      ],
    },
  });
});
</script>

<template>
  <div class="bg-white p-4 rounded-xl shadow">
    <h2 class="font-bold mb-2">Project Breakdown</h2>
    <canvas ref="chartRef"></canvas>
  </div>
</template>