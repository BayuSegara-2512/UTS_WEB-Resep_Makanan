<template>
    <div class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50">
      <div class="bg-gray-900 w-full max-w-2xl p-6 rounded-xl shadow-xl relative text-gray-100">
        <h2 class="text-2xl font-bold mb-4">Tambah Resep</h2>
  
        <form @submit.prevent="submitRecipe" class="space-y-4">
          <div>
            <label class="block mb-1 font-semibold">Judul</label>
            <input v-model="form.title" type="text" class="w-full bg-gray-800 border border-gray-600 p-3 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" required />
          </div>
  
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block mb-1 font-semibold">Kategori</label>
              <select v-model="form.category" class="w-full bg-gray-800 border border-gray-600 p-3 rounded-md">
                <option value="">-- Pilih --</option>
                <option>Sayuran</option>
                <option>Daging</option>
                <option>Seafood</option>
                <option>Jajanan</option>
              </select>
            </div>
            <div>
              <label class="block mb-1 font-semibold">Tingkat Kesulitan</label>
              <select v-model="form.difficulty" class="w-full bg-gray-800 border border-gray-600 p-3 rounded-md">
                <option value="">-- Pilih --</option>
                <option>Mudah</option>
                <option>Sedang</option>
                <option>Sulit</option>
              </select>
            </div>
          </div>
  
          <div>
            <label class="block mb-2 font-semibold">Bahan</label>
            <div v-for="(item, index) in form.ingredients" :key="index" class="flex gap-2 mb-2">
              <input v-model="form.ingredients[index]" type="text" class="flex-1 bg-gray-800 border border-gray-600 p-2 rounded-md" required />
              <button type="button" @click="removeIngredient(index)" class="text-red-400 hover:text-red-600">✖</button>
            </div>
            <button type="button" @click="addIngredient" class="text-blue-400 hover:text-blue-600 text-sm mt-1">+ Tambah Bahan</button>
          </div>
  
          <div>
            <label class="block mb-2 font-semibold">Langkah</label>
            <div v-for="(step, index) in form.steps" :key="index" class="flex gap-2 mb-2">
              <input v-model="form.steps[index]" type="text" class="flex-1 bg-gray-800 border border-gray-600 p-2 rounded-md" required />
              <button type="button" @click="removeStep(index)" class="text-red-400 hover:text-red-600">✖</button>
            </div>
            <button type="button" @click="addStep" class="text-blue-400 hover:text-blue-600 text-sm mt-1">+ Tambah Langkah</button>
          </div>
  
          <div class="flex justify-end gap-3 pt-4">
            <button type="button" @click="$emit('close')" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 rounded-md">Batal</button>
            <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-md text-white">Simpan</button>
          </div>
        </form>
  
        <p v-if="errorMessage" class="text-red-500 mt-4">{{ errorMessage }}</p>
        <p v-if="successMessage" class="text-green-500 mt-4">{{ successMessage }}</p>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, reactive } from 'vue'
  import { useRecipeStore } from '../store/recipes'
  
  const emit = defineEmits(['close'])
  const store = useRecipeStore()
  
  const form = reactive({
    title: '',
    category: '',
    difficulty: '',
    ingredients: [''],
    steps: ['']
  })
  
  const errorMessage = ref('')
  const successMessage = ref('')
  
  const addIngredient = () => form.ingredients.push('')
  const removeIngredient = (index) => form.ingredients.splice(index, 1)
  
  const addStep = () => form.steps.push('')
  const removeStep = (index) => form.steps.splice(index, 1)
  
  const submitRecipe = async () => {
    errorMessage.value = ''
    successMessage.value = ''
  
    if (form.ingredients.length < 1 || form.steps.length < 1) {
      errorMessage.value = 'Minimal 1 bahan dan 1 langkah.'
      return
    }
  
    const res = await store.addRecipe(form)
  
    if (res.data.success) {
      successMessage.value = res.data.message
      await store.fetchRecipes()
      setTimeout(() => emit('close'), 1000)
    } else {
      errorMessage.value = res.data.errors?.join(', ') || res.data.message
    }
  }
  </script>
  