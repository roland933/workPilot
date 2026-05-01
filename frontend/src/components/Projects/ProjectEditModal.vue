<script setup>

import { ref, onMounted,watch } from "vue";
import api from "../../api";


const model = defineModel()
const emit = defineEmits(['load'])
const errors = ref({});


const props = defineProps({
  item: Object,
})

const form = ref();

const editProject = async (p) => {
    try {
         await api.put(`/projects/${form.value.id}`, form.value);
         model.value = false;

        emit('load');

    }catch(e) {
          errors.value = e.response?.data?.errors || {};
    }
};

watch(() => model.value, (value) => {
    if(value) {
         form.value = {...props.item}
    } else  {
        errors.value = {};
    }
 
})



</script>

<template>

      <div v-if="model" class="fixed inset-0 bg-black/30 flex items-center justify-center">
                    <div class="bg-white p-6 rounded-xl w-80">
                        <h2 class="font-bold mb-4">Edit Project</h2>

                        <input v-model="form.name" placeholder="Name"
                            class="w-full mb-2 p-2 border rounded" />

                            <p v-if="errors.name" class="text-red-500 text-xs mb-2">
                            {{ errors.name[0] }}
                            </p>

                        <input v-model="form.hourly_rate" placeholder="Rate"
                            class="w-full mb-4 p-2 border rounded" />

                              <p v-if="errors.hourly_rate" class="text-red-500 text-xs mb-5">
                                    {{ errors.hourly_rate[0] }}
                               </p>


                        <div class="flex justify-end gap-2">

                                <button @click="model = false">Cancel</button>

                                <button @click="editProject"
                                        class="bg-blue-500 text-white px-3 py-1 rounded">
                                    Save
                                </button>
                        </div>

                         </div>

                    </div>

</template>