<template>
    <div>
      <button @click="show = !show" class="mt-2 text-blue-500 hover:underline">
        {{ show ? 'Sembunyikan Langkah' : 'Lihat Langkah' }}
      </button>
  
      <transition name="fade">
        <ul v-show="show" class="list-decimal mt-2 ml-5 space-y-2 text-gray-100 text-base leading-relaxed">
          <li v-for="(step, index) in steps" :key="index">{{ step }}</li>
        </ul>
      </transition>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue'
  
  const props = defineProps(['steps'])
  const show = ref(false)
  </script>
  
  <style scoped>
  .fade-enter-active, .fade-leave-active {
    transition: all 0.3s ease;
  }
  .fade-enter-from, .fade-leave-to {
    opacity: 0;
    transform: translateY(-10px);
  }
  </style>
  