<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <Home />
    <button @click="scrollToTop" class="fixed bottom-4 right-4 bg-blue-600 text-white p-3 rounded-full shadow-lg hover:bg-blue-700 transition">
      ↑
    </button>
  </div>
</template>

<script setup>
import Home from './views/Home.vue'

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}
</script>

<style>
/* Global styles can be added here */
</style>
