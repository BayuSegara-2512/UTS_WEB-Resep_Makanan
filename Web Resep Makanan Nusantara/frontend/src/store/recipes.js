import { defineStore } from 'pinia'
import axios from 'axios'

export const useRecipeStore = defineStore('recipeStore', {
  state: () => ({
    recipes: [],
    filters: {
      category: '',
      difficulty: ''
    },
    currentPage: 1,
    perPage: 6
  }),
  getters: {
    filteredRecipes(state) {
      return state.recipes.filter(r => {
        const cat = !state.filters.category || r.category === state.filters.category
        const dif = !state.filters.difficulty || r.difficulty === state.filters.difficulty
        return cat && dif
      })
    },
    paginatedRecipes(state) {
      const start = (state.currentPage - 1) * state.perPage
      return this.filteredRecipes.slice(start, start + state.perPage)
    },
    totalPages(state) {
      return Math.ceil(this.filteredRecipes.length / state.perPage)
    }
  },
  actions: {
    async fetchRecipes() {
      try {
        const res = await axios.get('http://localhost/Resep-Backend/backend/api/get_recipes.php')
        this.recipes = res.data
      } catch (error) {
        console.error('Gagal fetch resep:', error)
      }
    },
    async addRecipe(data) {
      try {
        return await axios.post('http://localhost/Resep-Backend/backend/api/add_recipe.php', data)
      } catch (error) {
        console.error('Gagal menambahkan resep:', error)
        return { data: { success: false, message: 'Gagal mengirim data ke server.' } }
      }
    },
    setPage(page) {
      this.currentPage = page
    }
  }
})
