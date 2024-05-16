<script setup>
import { ref } from 'vue';
import { VBtn } from 'vuetify/components';

const isModalOpen = ref(false);
const invoice = ref({});

function openModal() {
  isModalOpen.value = true;
}

function closeModal() {
  isModalOpen.value = false;
}

function setInvoice(data) {
  invoice.value = data;
}

// Use defineExpose to expose the methods
defineExpose({
  openModal,
  closeModal,
  setInvoice
});
</script>

<template>
  <div v-if="isModalOpen" class="modal-backdrop" @click.self="closeModal">
    <div class="modal">
      <h3 class="font-semibold text-lg mb-10">Invoice Details</h3>
      <div class="text-left p-4">
        <p><strong>Invoice Number:</strong> {{ invoice.invoice_number }}</p>
        <p><strong>Customer Name:</strong> {{ invoice.first_name }} {{ invoice.last_name }}</p>
        <p><strong>Invoice Date:</strong> {{ invoice.invoice_date }}</p>
        <p><strong>Payment Term:</strong> {{ invoice.payment_term }} Days</p>
        <p><strong>Due Date:</strong> {{ invoice.due_date }}</p>
        <p><strong>Total Amount:</strong> ${{ invoice.total_amount }}</p>
        <p><strong>Description:</strong> {{ invoice.description }}</p>
        <p><strong>Status:</strong> {{ invoice.status }}</p>
      </div>
      <v-btn color="red darken-1" text @click="closeModal">Close</v-btn>
    </div>
  </div>
</template>

<style scoped>
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1040;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0,0,0,0.5);
  display: flex;
  align-items: center;
  justify-content: center;
}
.modal {
  background: white;
  padding: 20px;
  border-radius: 5px;
  width: 40%;
  box-shadow: 0 5px 15px rgba(0,0,0,0.5);
}
</style>
