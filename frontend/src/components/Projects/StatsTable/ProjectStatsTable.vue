<script setup>
import ProjectRow from "./ProjectRow.vue"

const props = defineProps({
  projects:Array,
});

</script>

<template>
  <div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="min-w-full text-sm">

      <thead class="bg-gray-50 text-gray-600">
        <tr>
          <th class="px-6 py-3 text-left">Project</th>
          <th class="px-6 py-3 text-left">Hours</th>
          <th class="px-6 py-3 text-right">Earnings</th>
        </tr>
      </thead>

      <tbody class="divide-y">
        <ProjectRow
          v-for="p in projects"
          :key="p.id"
          :project="p"
        />
      </tbody>

    </table>
  </div>
</template>