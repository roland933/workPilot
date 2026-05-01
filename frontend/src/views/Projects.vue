<script setup>
import { ref, onMounted } from "vue";
import api from "../api";

const projects = ref([]);

const activeTimer = ref(null);

const showModal = ref(false);

const loadActive = async () => {
  try {
  const res = await api.get("/time/active");
  
  activeTimer.value = res.data && res.data.id ? res.data : null;
   stopLiveTimer();

  if (activeTimer.value) {
    startLiveTimer();
  }
  }catch(err) {
    console.log(err);
  }
  
};

const load = async () => {
  const res = await api.get("/projects");
  projects.value = res.data;
};

const deleteProject = async (id) => {
  if (!confirm("Delete project?")) return;

  await api.delete(`/projects/${id}`);
  load();
};


const start = async (projectId) => {
 
  await api.post("/time/start", {
    project_id: projectId,
    description: "working",
  }).catch(() => {
       setError("Nem sikerült elindítani a timert");
  });
  await load();
  await loadActive();

  
};

const stop = async () => {
 
  await api.post("/time/stop").catch((e) =>{
       setError("Nem sikerült a timert leállítani");
  });
  
  await load();
  await loadActive();

 
};

const form = ref({
  name: "",
  hourly_rate: ""
});

const createProject = async () => {
  await api.post("/projects", form.value);

  showModal.value = false;
  form.value = { name: "", hourly_rate: "" };

  load();
};

onMounted(load);
</script>

<template>
  <div class="space-y-6">

    <h1 class="text-2xl font-bold">Projects</h1>


        <button
      @click="showModal = true"
      class="bg-blue-500 text-white px-4 py-2 rounded"
    >
      + New Project
    </button>

      <div v-if="showModal" class="fixed inset-0 bg-black/30 flex items-center justify-center">

  <div class="bg-white p-6 rounded-xl w-80">

    <h2 class="font-bold mb-4">New Project</h2>

    <input v-model="form.name" placeholder="Name"
           class="w-full mb-2 p-2 border rounded" />

    <input v-model="form.hourly_rate" placeholder="Rate"
           class="w-full mb-4 p-2 border rounded" />

    <div class="flex justify-end gap-2">
      <button @click="showModal = false">Cancel</button>
      <button @click="createProject"
              class="bg-blue-500 text-white px-3 py-1 rounded">
        Save
      </button>
    </div>

  </div>

</div>

    <div class="bg-white rounded-xl shadow p-4">

      <div v-for="p in projects" :key="p.id"
           class="flex justify-between items-center py-3 border-b">

        <div>
          <p class="font-medium">{{ p.name }}</p>
          <p class="text-sm text-gray-500">
            ${{ p.hourly_rate }}/hour
          </p>
        </div>

        <div class="space-x-2">
          <button class="bg-green-500 text-white px-2 py-1 rounded">
            Start
          </button>

          <button class="bg-yellow-500 text-white px-2 py-1 rounded">
            Edit
          </button>

          <button
            @click="deleteProject(p.id)"
            class="bg-red-500 text-white px-2 py-1 rounded"
          >
            Delete
          </button>
        </div>

      </div>

    </div>

  </div>
</template>