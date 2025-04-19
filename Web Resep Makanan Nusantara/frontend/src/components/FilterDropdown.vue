<template>
    <div class="flex flex-wrap gap-6 justify-center">
      <div>
        <label class="block text-sm font-medium mb-1 text-gray-200">Kategori</label>
        <select v-model="selectedCategory" class="bg-gray-800 text-white border border-gray-600 p-2 rounded-md">
          <option value="">Semua</option>
          <option v-for="cat in categories" :key="cat" :value="cat">{{ cat }}</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium mb-1 text-gray-200">Kesulitan</label>
        <select v-model="selectedDifficulty" class="bg-gray-800 text-white border border-gray-600 p-2 rounded-md">
          <option value="">Semua</option>
          <option v-for="dif in difficulties" :key="dif" :value="dif">{{ dif }}</option>
        </select>
      </div>
    </div>
  </template>
  
  <script setup>
  import { computed } from 'vue'
  import { useRecipeStore } from '../store/recipes'
  
  const store = useRecipeStore()
  
  const categories = ['', 'Sayuran', 'Daging', 'Seafood', 'Jajanan']
  const difficulties = ['', 'Mudah', 'Sedang', 'Sulit']
  
  const selectedCategory = computed({
    get: () => store.filters.category,
    set: (val) => store.filters.category = val
  })
  
  const selectedDifficulty = computed({
    get: () => store.filters.difficulty,
    set: (val) => store.filters.difficulty = val
  })
  </script>
  