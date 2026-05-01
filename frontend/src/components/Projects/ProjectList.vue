<script setup >
    defineProps({
    items:Array,
    })

</script>

<template>

    
    <div v-for="p in items" :key="p.id"
           class="hover:shadow-md transition cursor-pointer bg-white p-4 mb-2 rounded shadow flex justify-between items-center">

        <span>{{ p.name }}</span>

        <div>
           <slot name="actions" :id="p.id"> </slot> 
          
        </div>

      </div>

</template>
