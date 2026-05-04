<template>
 <tr :class="[
  'transition',
  isActive ? 'bg-yellow-50' : '',
  isDisabled ? 'opacity-50 pointer-events-none' : 'hover:bg-gray-50'
]">

    <td class="px-6 py-4 text-left">
      <p class="font-medium">{{ project.project }}</p>
    </td>

    <td class="px-6 py-4 text-gray-500 text-left">
      ${{ project.hours }}/hour
    </td>

    <td class="px-6 py-4 text-gray-500 text-left">
      ${{ project.earnings }}
    </td>

   

  </tr>
</template>

<script setup>
import {computed} from "vue";

const props = defineProps({
  project: Object,
});

</script>