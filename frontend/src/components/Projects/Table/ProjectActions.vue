<template>
  <div class="flex justify-end gap-2">

    <button
      :disabled="activeTimer && activeTimer.id"
      @click="$emit('start')"
      class="bg-green-500 text-white px-3 py-1 rounded text-xs"
    >
      Start
    </button>

    <button
      :disabled="!activeTimer"
      @click="$emit('stop')"
      class="bg-red-500 text-white px-3 py-1 rounded text-xs"
    >
      Stop
    </button>

    <button
      :disabled="project.id === activeTimer?.project?.id"
      @click="$emit('edit')"
      class="bg-yellow-500 text-white px-3 py-1 rounded text-xs"
    >
      Edit
    </button>

    <button
      :disabled="activeTimer && activeTimer.id"
      @click="$emit('delete')"
      class="bg-red-500 text-white px-3 py-1 rounded text-xs"
    >
      Delete
    </button>

  </div>
</template>

<script setup>
defineProps({
  project: Object,
  activeTimer: Object
});
</script>