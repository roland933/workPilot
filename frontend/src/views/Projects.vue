<script setup>
import { ref, onMounted,watch } from "vue";
import api from "../api";
import ProjectCreateModal from "../components/Projects/ProjectCreateModal.vue";
import ProjectEditModal from "../components/Projects/ProjectEditModal.vue";
import {setError} from "../stores/error.js"
import { activeTimer, loadActiveTimer } from "../stores/timer";
import ProjectTable from "../components/Projects/Table/ProjectTable.vue"
import {formatTime} from "../js/utils/utils.js";

const projects = ref([]);

const showModal = ref(false);
const showEditModal = ref(false);

const elapsed = ref(0);

const editProject = ref(null);
const loadingBtn = ref(false);
const disabledGenerateInvoiceButton = ref(false);

let interval = null;

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

const previewInvoice = async () => {
  const res = await api.get("/invoice/preview",{
    responseType: "blob"
  });

  const url = window.URL.createObjectURL(res.data);
  window.open(url);
};

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
       <div v-if="activeTimer && activeTimer?.project_id"
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
              class="bg-green-500 text-white px-4 py-2 rounded shadow-md hover:bg-green-400 w-[180px] transition"
               >
               + New Project
              </button>
        </div>

        <div>
                <button
                :disabled="disabledGenerateInvoiceButton || projects.length === 0"
                :title="projects.length === 0 ? 'Not projects yet': ''"
                @click="downloadInvoice"
                class="bg-white text-gray px-4 py-2 rounded shadow-md hover:bg-gray-300 w-[180px] transition disabled:cursor-not-allowed"
                  >
                  {{ disabledGenerateInvoiceButton ? "Generating..." : "Generate Invoice" }}
                </button>

        </div>

        <div>

                <button @click="previewInvoice"
                          class="bg-white text-gray px-4 py-2 rounded shadow-md hover:bg-gray-300 w-[180px] transition disabled:cursor-not-allowed">
                        Preview Invoice
                </button>
        
        </div>


    </div>    
 
    <h1 class="text-2xl font-bold">Projects</h1>

      <div class="bg-white rounded-xl shadow overflow-hidden">

                  <ProjectTable
                      :projects="projects"
                      :activeTimer="activeTimer"
                      @start="start"
                      @stop="stop"
                      @edit="handleEditProject"
                      @delete="deleteProject"
            />


</div>



  </div>
</template>