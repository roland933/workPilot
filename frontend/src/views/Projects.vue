<script setup>
import { ref, onMounted,watch } from "vue";
import api from "../api";
import ProjectCreateModal from "../components/Projects/ProjectCreateModal.vue";
import ProjectEditModal from "../components/Projects/ProjectEditModal.vue";
import {setError} from "../stores/error.js"
import { activeTimer, loadActiveTimer } from "../stores/timer";


const projects = ref([]);

const showModal = ref(false);
const showEditModal = ref(false);

const elapsed = ref(0);

const editProject = ref(null);
const loadingBtn = ref(false);
const disabledGenerateInvoiceButton = ref(false);

let interval = null;

const formatTime = (sec) => {
  const h = Math.floor(sec / 3600);
  const m = Math.floor((sec % 3600) / 60);
  const s = sec % 60;

  return `${h}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
};

const startLiveTimer = () => {
  if (!activeTimer.value || !activeTimer.value.start_time) return;

  const start = new Date(activeTimer.value.start_time);

   if (isNaN(start)) return;

  interval = setInterval(() => {
    const now = new Date();
    elapsed.value = Math.floor((now - start) / 1000);
  }, 1000);
};

const stopLiveTimer = () => {
  if (interval) {
    clearInterval(interval);
    interval = null;
  }
  elapsed.value = 0;
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
  loadingBtn.value = true;
  try {
    const res = await api.post("/time/start", {
      project_id: projectId,
      description: "working",
    });

    activeTimer.value = res.data; 

  } catch {
    setError("Nem sikerült elindítani a timert");
  } finally {
    loadingBtn.value = false
  }

  await load();
};

const stop = async (projectId) => {
   loadingBtn.value = true;
  try {
    await api.post("/time/stop",{
       project_id: projectId,
    });
    activeTimer.value = null; 
    stopLiveTimer()
  } catch(e) {
    setError("Nem sikerült a timert leállítani");
  }finally{
     loadingBtn.value = false
  }

  await load();
};

const handleEditProject = (p) => {
    showEditModal.value = true;
    editProject.value = p;
}

const downloadInvoice = async () => {
  disabledGenerateInvoiceButton.value = true;
  try {
  const res = await api.get("/invoice", {
    responseType: "blob"
  });

  const url = window.URL.createObjectURL(new Blob([res.data]));
  const link = document.createElement("a");
  link.href = url;
  link.setAttribute("download", "invoice.pdf");
  document.body.appendChild(link);
  link.click();
  } catch(err) {
    setError(err.response?.data?.message || 'Nem sikerült a számla generálás!')
  } finally {
    disabledGenerateInvoiceButton.value = false;
  }

}

watch(activeTimer, (newVal) => {
  stopLiveTimer();

  if (newVal && newVal.start_time) {
    startLiveTimer();
  }
});

onMounted(async() => {
  await load();
  await loadActiveTimer();


  if (activeTimer.value) {
    startLiveTimer(); 
  }
});


</script>

<template>

       <Teleport to="body">
          <ProjectCreateModal  v-model="showModal" @load="load" />
          <ProjectEditModal  v-model="showEditModal" @load="load" :item="editProject"/>
    </Teleport>

  <div class="space-y-6">
       <div v-if="activeTimer && activeTimer.id"
            class="bg-yellow-50 border border-yellow-200 p-4 rounded-2xl mb-6">

            <p class="text-sm text-yellow-700">Active Timer</p>
            <h3 class="font-bold">{{ activeTimer?.project?.name }}</h3>

            <p class="text-xl font-mono mt-1">
              {{ formatTime(elapsed) }}
            </p>
        </div>

    <div class="actions flex gap-4">
        <div>
                <button
              @click="showModal = true"
              class="bg-green-500 text-white px-4 py-2 rounded shadow-md hover:bg-green-400"
               >
               + New Project
              </button>
        </div>

        <div>
                <button
                :disabled="disabledGenerateInvoiceButton"
                @click="downloadInvoice"
                class="bg-white text-gray px-4 py-2 rounded shadow-md hover:bg-gray-300"
                  >
                Generate invoice
                </button>


        </div>


    </div>    
 
    <h1 class="text-2xl font-bold">Projects</h1>


    

   


    <div v-if="projects.length === 0" class="text-gray-500">
      No projects yet. Create your first one
    </div>    

    <div class="bg-white rounded-xl shadow p-4" v-else>

      <div v-for="p in projects" :key="p.id"
           class="flex justify-between items-center py-3 border-b">

        <div>
          <p class="font-medium">{{ p.name }}</p>
          <p class="text-sm text-gray-500">
            ${{ p.hourly_rate }}/hour
          </p>
        </div>

        <div class="space-x-2">
          <button 
          :disabled="activeTimer && activeTimer?.id"
          @click="start(p.id)" class="bg-green-500 text-white px-2 py-1 rounded disabled:bg-green-300">
            Start
          </button>

          <button 
          :disabled="!activeTimer"
          @click="stop(p.id)" class="bg-red-500 text-white px-2 py-1 rounded disabled:bg-red-400">
            Stop
          </button>

          <button
          :disabled="p.id == activeTimer?.project?.id"
          @click="handleEditProject(p)" 
          class="bg-yellow-500 text-white px-2 py-1 rounded">
            Edit
          </button>

          <button
           :disabled="activeTimer && activeTimer?.id"
            @click="deleteProject(p.id)"
            class="bg-red-500 text-white px-2 py-1 rounded disabled:bg-red-400"
          >
            Delete
          </button>
        </div>

      </div>

    </div>

  </div>
</template>