<template>
    <div class="p-6 bg-gray-900 min-h-screen">
      <!-- Heading Section -->
      <h1 class="text-3xl font-bold text-center text-white mb-6">
        Aneka Resep Nusantara
      </h1>
  
      <!-- Filter Section -->
      <div class="flex justify-center space-x-4 mb-6">
        <FilterDropdown />
      </div>
  
      <!-- Recipe List Section -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <RecipeCard v-for="recipe in recipes" :key="recipe.id" :recipe="recipe" />
      </div>
  
      <!-- Pagination Section -->
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
          ]"
        >
          {{ page }}
        </button>
      </div>
  
      <!-- Add Recipe Button -->
      <div class="flex justify-center mt-6">
        <button
          @click="showForm = true"
          class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition duration-200"
        >
          + Resep
        </button>
      </div>
  
      <!-- Modal Form to Add Recipe -->
      <RecipeForm v-if="showForm" @close="handleCloseForm" />
    </div>
  </template>
  
  <script setup>
  import { ref, computed, onMounted } from 'vue'
  import RecipeCard from '../components/RecipeCard.vue'
  import FilterDropdown from '../components/FilterDropdown.vue'
  import RecipeForm from '../components/RecipeForm.vue'
  import { useRecipeStore } from '../store/recipes'
  
  const showForm = ref(false)
  const store = useRecipeStore()
  
  // ✅ Gunakan resep yang sudah difilter dan dipaginasi
  const recipes = computed(() => store.paginatedRecipes)
  const totalPages = computed(() => store.totalPages)
  const currentPage = computed(() => store.currentPage)
  
  const setPage = (page) => {
    store.setPage(page)
  }
  
  // ✅ Fetch data resep saat halaman dibuka
  onMounted(() => {
    store.fetchRecipes()
  })
  
  // ✅ Tutup form dan reload data setelah tambah resep
  const handleCloseForm = async () => {
    showForm.value = false
    await store.fetchRecipes()
  }
  </script>
  
  <style scoped>
  body {
    background-color: #1f2937;
    color: #e5e7eb;
  }
  </style>
  