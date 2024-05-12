<template>
    <div class="flex justify-end mb-4">
      <button @click="openModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Create Invoice
      </button>
  
      <!-- Modal Starts Here -->
      <div v-if="isModalOpen" class="fixed inset-0 bg-black bg-opacity-30 backdrop-filter backdrop-blur-sm flex justify-center items-center">
        <div class="bg-white p-4 w-1/2 rounded shadow-lg">
          <h3 class="font-semibold text-lg">Create New Invoice</h3>
          <!-- Form for creating a new invoice -->
          <form @submit.prevent="createInvoice">
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">Customer Name</label>
              <input type="text" v-model="invoice.customerName" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">Invoice Date</label>
              <input type="date" v-model="invoice.invoiceDate" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>
            <div class="mb-4">
              <label class="block text-gray-700 text-sm font-bold mb-2">Total Amount</label>
              <input type="number" v-model="invoice.totalAmount" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700" required>
            </div>
            <div class="flex items-center justify-between">
              <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded" type="submit">
                Save Invoice
              </button>
              <button @click="closeModal" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
      <!-- Modal Ends Here -->
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const isModalOpen = ref(false);
  const invoice = ref({
    customerName: '',
    invoiceDate: '',
    totalAmount: 0,
  });
  
  function openModal() {
    isModalOpen.value = true;
  }
  
  function closeModal() {
    isModalOpen.value = false;
  }
  
  function createInvoice() {
    console.log('Invoice Data:', invoice.value);
    closeModal();
    // Here you would typically emit an event or call an API to save the invoice data
  }
  </script>
  
  <style scoped>
  /* Add any additional CSS for your modal here */
  </style>
  