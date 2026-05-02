<script setup>
import { errorMessage } from './stores/error';
</script>

<template>
<div v-if="errorMessage"
     class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded">
  {{ errorMessage }}
</div>
    <router-view />

</template>