<template>
 <tr :class="[
  'transition',
  isActive ? 'bg-yellow-50' : '',
  isDisabled ? 'opacity-50 pointer-events-none' : 'hover:bg-gray-50'
  ]">

    <td class="px-6 py-4 text-left">
      <p class="font-medium">{{ project.name }}</p>
    </td>

    <td class="px-6 py-4 text-gray-500 text-left">
      ${{ project.hourly_rate }}/hour
    </td>

    <td class="px-6 py-4 text-right">
      <ProjectActions
        :project="project"
        :activeTimer="activeTimer"
        @start="$emit('start', project.id)"
        @stop="$emit('stop',project.id)"
        @edit="$emit('edit', project)"
        @delete="$emit('delete', project.id)"
      />
    </td>

  </tr>
</template>

<script setup>
import {computed} from "vue";

import ProjectActions from "./ProjectActions.vue"

const props = defineProps({
  project: Object,
  activeTimer: Object
});

const isActive = computed(() =>
  props.project.id === props.activeTimer?.project_id
);

const isDisabled = computed(() =>
  props.activeTimer?.project_id && !isActive.value
);

</script>