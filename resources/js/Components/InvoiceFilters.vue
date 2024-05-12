<template>
    <div class="mb-4 flex gap-4">
      <input type="text" placeholder="Customer Name" v-model="filters.customerName" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
      <input type="date" v-model="filters.startDate" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
      <input type="date" v-model="filters.endDate" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
      <select v-model="filters.paidStatus" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700">
        <option value="">All</option>
        <option value="paid">Paid</option>
        <option value="unpaid">Unpaid</option>
      </select>
      <button @click="applyFilters" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Apply Filters
      </button>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const filters = ref({
    customerName: '',
    startDate: '',
    endDate: '',
    paidStatus: '',
  });
  
  function applyFilters() {
    console.log('Applying filters:', filters.value);
    // Here you would typically emit an event or call an API to fetch filtered data
  }
  </script>
  