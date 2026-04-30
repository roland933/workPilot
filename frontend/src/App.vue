<script setup>
</script>


<template>
  <div class="flex min-h-screen bg-gray-100">
    
    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-md p-4">
      <h1 class="text-xl font-bold mb-6">WorkPilot</h1>

      <nav class="space-y-2">
        <a href="/dashboard" class="block p-2 rounded hover:bg-gray-100">Dashboard</a>
        <a href="/projects" class="block p-2 rounded hover:bg-gray-100">Projects</a>
      </nav>
    </aside>

    <!-- MAIN -->
    <div class="flex-1 p-6">
      <router-view />
    </div>

  </div>
</template>


