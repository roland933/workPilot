<script setup>
import { ref, onMounted } from "vue";
import api from "../api";
import ProjectCreateModal from "../components/Projects/ProjectCreateModal.vue";
import ProjectEditModal from "../components/Projects/ProjectEditModal.vue";
import {setError} from "../stores/error.js"

const projects = ref([]);

const activeTimer = ref(null);

const showModal = ref(false);
const showEditModal = ref(false);

const elapsed = ref(0);

const editProject = ref(null)

let interval = null;

const formatTime = (sec) => {
  const h = Math.floor(sec / 3600);
  const m = Math.floor((sec % 3600) / 60);
  const s = sec % 60;

  return `${h}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
};

const startLiveTimer = () => {
  if (!activeTimer.value) return;

  const start = new Date(activeTimer.value.start_time);

  interval = setInterval(() => {
    const now = new Date();
    elapsed.value = Math.floor((now - start) / 1000);
  }, 1000);
};

const stopLiveTimer = () => {
  clearInterval(interval);
  elapsed.value = 0;
};

const loadActive = async () => {
  try {
  const res = await api.get("/time/active");
 

  activeTimer.value = res.data && res.data.id ? res.data : null;
  stopLiveTimer();
  console.log(activeTimer.value)

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

const handleEditProject = (p) => {
    showEditModal.value = true;
    editProject.value = p;
}




onMounted(load);


</script>

<template>

       <Teleport to="body">
          <ProjectCreateModal  v-model="showModal" @load="load" />
          <ProjectEditModal  v-model="showEditModal" @load="load" :item="editProject"/>
    </Teleport>

  <div class="space-y-6">
       <div v-if="activeTimer"
            class="bg-yellow-50 border border-yellow-200 p-4 rounded-2xl mb-6">

            <p class="text-sm text-yellow-700">Active Timer</p>
            <h3 class="font-bold">{{ activeTimer.project.name }}</h3>

            <p class="text-xl font-mono mt-1">
              {{ formatTime(elapsed) }}
            </p>
        </div>
 
    <h1 class="text-2xl font-bold">Projects</h1>


        <button
      @click="showModal = true"
      class="bg-blue-500 text-white px-4 py-2 rounded"
    >
      + New Project
    </button>

      

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
          <button @click="start(p.id)" class="bg-green-500 text-white px-2 py-1 rounded">
            Start
          </button>

          <button @click="stop" class="bg-red-500 text-white px-2 py-1 rounded">
            Stop
          </button>

          <button
          :disabled="p.id == activeTimer?.project?.id"
          @click="handleEditProject(p)" 
          class="bg-yellow-500 text-white px-2 py-1 rounded">
            Edit
          </button>

          <button
            :disabled="p.id == activeTimer?.project?.id"
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