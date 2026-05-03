<script setup>
import ProjectRow from "./ProjectRow.vue"

const props = defineProps({
  activeTimer: Object,
  projects:Array,
});

</script>

<template>
  <div class="bg-white rounded-xl shadow overflow-hidden">
    <table class="min-w-full text-sm">

      <thead class="bg-gray-50 text-gray-600">
        <tr>
          <th class="px-6 py-3 text-left">Project</th>
          <th class="px-6 py-3 text-left">Rate</th>
          <th class="px-6 py-3 text-right">Actions</th>
        </tr>
      </thead>

      <tbody class="divide-y">
        <ProjectRow
          v-for="p in projects"
          :key="p.id"
          :project="p"
          :activeTimer="activeTimer"
          @start="$emit('start', $event)"
          @stop="$emit('stop',$event)"
          @edit="$emit('edit', $event)"
          @delete="$emit('delete', $event)"
        />
      </tbody>

    </table>
  </div>
</template>