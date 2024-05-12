<template>
    <div>
      <table class="min-w-full leading-normal">
        <thead>
          <tr>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Invoice Number
            </th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Customer Name
            </th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Invoice Date
            </th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Due Date
            </th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Total Amount
            </th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Status
            </th>
            <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
              Actions
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="invoice in invoices" :key="invoice.id">
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <div class="flex items-center">
                <div class="ml-3">
                  <p class="text-gray-900 whitespace-no-wrap">
                    {{ invoice.number }}
                  </p>
                </div>
              </div>
            </td>
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <p class="text-gray-900 whitespace-no-wrap">{{ invoice.customerName }}</p>
            </td>
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <p class="text-gray-900 whitespace-no-wrap">
                {{ invoice.invoiceDate }}
              </p>
            </td>
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <p class="text-gray-900 whitespace-no-wrap">{{ invoice.dueDate }}</p>
            </td>
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <p class="text-gray-900 whitespace-no-wrap">
                {{ invoice.totalAmount }}
              </p>
            </td>
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
              <span
                :class="{'bg-green-200 text-green-900': invoice.paidStatus === 'paid', 'bg-red-200 text-red-900': invoice.paidStatus === 'unpaid'}"
                class="relative inline-block px-3 py-1 font-semibold leading-tight">
                <span aria-hidden
                      class="absolute inset-0 opacity-50 rounded-full"></span>
                <span class="relative">{{ invoice.paidStatus }}</span>
              </span>
            </td>
            <!-- Existing <td> element for actions -->
            <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
            <div class="relative inline-block text-left dropdown">
                <button @click="toggleDropdown(invoice.id)" class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium leading-5 text-gray-700 transition duration-150 ease-in-out bg-white border border-gray-300 rounded-md hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue active:bg-gray-50 active:text-gray-800" aria-haspopup="true" aria-expanded="true">
                <span>Actions</span>
                <svg class="w-5 h-5 ml-2 -mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M6 10a2 2 0 110-4 2 2 0 010 4zm8 0a2 2 0 110-4 2 2 0 010 4zM10 14a2 2 0 110-4 2 2 0 010 4z" />
                </svg>
                </button>
                <div v-show="openedDropdown === invoice.id" class="dropdown-menu transition-opacity opacity-100 scale-100 absolute mt-1 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" :class="{'origin-top-right': isUp, 'origin-bottom-right': !isUp}" style="display: none;">
                <div class="py-1">
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">View</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mark as Paid/Unpaid</a>
                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Delete</a>
                </div>
                </div>
            </div>
            </td>

          </tr>
        </tbody>
      </table>
      <div v-if="invoices.length === 0" class="text-center py-5">
        No records found.
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const invoices = ref([
    { id: 1, number: 'INV-001', customerName: 'John Doe', invoiceDate: '2021-01-01', dueDate: '2021-01-31', totalAmount: 200, paidStatus: 'unpaid' },
    { id: 2, number: 'INV-002', customerName: 'Jane Smith', invoiceDate: '2021-02-01', dueDate: '2021-02-28', totalAmount: 300, paidStatus: 'paid' },
  ]);
  const openedDropdown = ref(null);
  const isUp = ref(false);
  
  const toggleDropdown = (invoiceId) => {
    if (openedDropdown.value === invoiceId) {
      openedDropdown.value = null;
    } else {
      openedDropdown.value = invoiceId;
      const dropdownElement = document.querySelector(`#dropdown-menu-${invoiceId}`);
      const rect = dropdownElement.getBoundingClientRect();
      isUp.value = (window.innerHeight - rect.bottom) < 200;
    }
  };
  </script>
  
  
  <style scoped>

  .dropdown-menu {
    transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out, visibility 0.3s ease-in-out;
    z-index: 50; /* Make sure it's above other table elements */
    }

  .dropdown:hover .dropdown-menu {
    opacity: 1;
    visibility: visible;
    transform: translate(0);
  }
  </style>
  