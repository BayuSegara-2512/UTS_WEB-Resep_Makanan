<template>
    <div class="bg-gray-800 hover:bg-gray-700 shadow-xl rounded-lg p-6 transition-all duration-300">
      <h2 class="text-2xl font-bold text-white mb-3">{{ recipe.title }}</h2>
  
      <div class="flex flex-wrap items-center gap-3 mb-4">
        <span class="bg-blue-600 text-white text-sm px-3 py-1 rounded-full">
          Kategori: {{ recipe.category }}
        </span>
        <span class="bg-green-600 text-white text-sm px-3 py-1 rounded-full">
          Kesulitan: {{ recipe.difficulty }}
        </span>
      </div>
  
      <!-- Daftar Bahan -->
      <div v-if="recipe.ingredients && recipe.ingredients.length" class="mb-4">
        <h3 class="text-lg font-semibold text-white mb-2">Bahan:</h3>
        <ul class="list-disc pl-6 text-base text-gray-200 space-y-1">
          <li v-for="(bahan, i) in recipe.ingredients" :key="i">{{ bahan }}</li>
        </ul>
      </div>
  
      <!-- Langkah-langkah -->
      <Accordion :steps="recipe.steps" />
  
      <!-- Tombol Hapus -->
      <button @click="deleteRecipe(recipe.id)" class="mt-6 w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-md transition-all">
        Hapus Resep
      </button>
    </div>
  </template>
  
  <script setup>
  import Accordion from './Accordion.vue'
  import axios from 'axios'
  import { useRecipeStore } from '../store/recipes'
  
  const props = defineProps(['recipe'])
  const store = useRecipeStore()
  
  const deleteRecipe = async (id) => {
    try {
      const res = await axios.delete('http://localhost/Resep-Backend/backend/api/delete_recipe.php', {
        data: { id }
      })
      if (res.data.success) {
        await store.fetchRecipes()
      } else {
        alert('Gagal menghapus resep.')
      }
    } catch (e) {
      console.error('Error delete:', e)
    }
  }
  </script>
  