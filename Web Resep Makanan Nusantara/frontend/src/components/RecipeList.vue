<template>
    <div>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <RecipeCard v-for="recipe in recipes" :key="recipe.id" :recipe="recipe" />
      </div>
  
      <div class="flex justify-center mt-6 gap-2">
        <button
          v-for="page in totalPages"
          :key="page"
          @click="setPage(page)"
          :class="[
            'px-4 py-2 rounded-lg',
            page === currentPage
              ? 'bg-blue-600 text-white'
              : 'bg-gray-800 text-white hover:bg-blue-700'
          ]">
          {{ page }}
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  import { useRecipeStore } from '../store/recipes'
  import { onMounted, computed } from 'vue'
  import RecipeCard from './RecipeCard.vue'
  
  const store = useRecipeStore()
  onMounted(() => store.fetchRecipes())
  
  const recipes = computed(() => store.recipes)
  const totalPages = computed(() => store.totalPages)
  const currentPage = computed(() => store.currentPage)
  const setPage = (page) => store.setPage(page)
  </script>
  