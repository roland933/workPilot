<script setup>

import { ref, onMounted } from "vue";
import api from "../../api";


const model = defineModel()
const emit = defineEmits(['load'])
  
const form = ref({
  name: "",
  hourly_rate: ""
});

const createProject = async () => {
    try {
        await api.post("/projects", form.value);

        model.value = false;
        form.value = { name: "", hourly_rate: "" };

        emit('load');

    }catch(e) {
        
    }
};

</script>

<template>

      <div v-if="model" class="fixed inset-0 bg-black/30 flex items-center justify-center">
           <div class="bg-white p-6 rounded-xl w-80 shadow-md">
                        <h2 class="font-bold mb-4">New Project</h2>
                        <input v-model="form.name" placeholder="Name"
                            class="w-full mb-2 p-2 border rounded" />
                        <input v-model="form.hourly_rate" placeholder="Rate"
                            class="w-full mb-4 p-2 border rounded" />
                        <div class="flex justify-end gap-2">

                                <button @click="model = false">Cancel</button>

                                <button @click="createProject"
                                        class="bg-blue-500 text-white px-3 py-1 rounded">
                                    Save
                                </button>
                        </div>

                         </div>

             </div>

</template>