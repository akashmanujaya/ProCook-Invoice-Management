<template>
  <div class="mb-4 flex gap-4" style="width: 75%;">
    <input type="text" placeholder="Customer Name" v-model="filters.customerName" class="form-field">
    <VueDatePicker v-model="filters.dateRange" range class="form-field" :enable-time-picker="false" placeholder="Invoice Date"></VueDatePicker>
    <select v-model="filters.paidStatus" class="form-field">
      <option value="">All</option>
      <option value="1">Paid</option>
      <option value="0">Pending</option>
    </select>
    <button @click="applyFilters" class="btn-apply">
      Apply Filters
    </button>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'

const emit = defineEmits(['update-filters']);

const filters = ref({
  customerName: '',
  dateRange: {
    0: null,
    1: null
  },
  paidStatus: '',
});

function applyFilters() {
  const filterPayload = {
    customerName: filters.value.customerName,
    startDate: filters.value.dateRange && filters.value.dateRange[0] ? filters.value.dateRange[0].toISOString().split('T')[0] : null ,
    endDate: filters.value.dateRange && filters.value.dateRange[1] ? filters.value.dateRange[1].toISOString().split('T')[0] : new Date().toISOString().split('T')[0],
    paidStatus: filters.value.paidStatus
  };
  emit('update-filters', filterPayload);
}
</script>

<style scoped>
.form-field {
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  border: 1px solid #ccc;
  border-radius: 0.375rem;
  /* padding: 0.5rem 0.75rem; */
  height: 2.5rem;
  width: 100%;
  position: relative; /* Ensure it doesn't overlap with other elements */
  z-index: 1; /* Set a higher z-index to ensure visibility */
}

.date-range-picker {
  width: 100%;
  position: relative; /* Ensure it doesn't overlap with other elements */
  z-index: 2; /* Set a higher z-index to ensure visibility */
}

.vue-datepicker-ui {
  width: 100%;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
  border: 1px solid #ccc;
  border-radius: 0.375rem;
  padding: 0.5rem 0.75rem;
  height: 2.5rem;
}

.btn-apply {
  width: 50%;
  background-color: #4299e1; /* Tailwind blue-500 */
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 0.375rem;
  cursor: pointer;
  transition: background-color 0.3s;
}

.btn-apply:hover {
  background-color: #3182ce; /* Tailwind blue-600 */
}
</style>

