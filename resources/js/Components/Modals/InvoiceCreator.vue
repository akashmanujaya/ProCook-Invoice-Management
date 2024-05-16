<template>
  <div class="flex justify-end mb-4">
    <button @click="openModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
      Create Invoice
    </button>

    <!-- Modal Starts Here -->
    <div v-if="isModalOpen" class="modal-backdrop">
      <div class="modal">
        <h3 class="font-semibold text-lg mb-10">Create New Invoice</h3>
        <!-- Form for creating a new invoice -->
        <v-form>
          <v-text-field required v-model="invoice.firstName" label="First Name" outlined :error-messages="errors.first_name"></v-text-field>
          <v-text-field v-model="invoice.lastName" label="Last Name" outlined :error-messages="errors.last_name"></v-text-field>

          <!-- Vue 3 Datepicker for Invoice Date -->
          <VueDatePicker v-model="invoice.invoiceDate" :config="{ type: 'datetime' }" class="mb-2" :error-messages="errors.invoice_date"></VueDatePicker>

          <v-text-field v-model="invoice.paymentTerm" label="Payment Term" type="number" :rules="paymentTermRules" outlined :error-messages="errors.payment_term"></v-text-field>

          <!-- Vue 3 Datepicker for Due Date -->
          <VueDatePicker v-model="invoice.dueDate" :config="{ type: 'datetime' }"  class="mb-2" :error-messages="errors.due_date"></VueDatePicker>

          <v-textarea v-model="invoice.description" label="Description" :counter="3000" outlined :error-messages="errors.description"></v-textarea>
          <v-text-field v-model="invoice.totalAmount" label="Total Amount" prefix="$" type="number" step="0.01" outlined :error-messages="errors.total_amount"></v-text-field>
          <div class="flex items-center justify-between">
            <v-btn
              :disabled="loading"
              :loading="loading"
              color="blue darken-1"
              text
              @click="createInvoice"
            >
              Save Invoice
            </v-btn>
            <v-btn color="red darken-1" text @click="closeModal">Cancel</v-btn>
          </div>
        </v-form>
      </div>
    </div>
    <!-- Modal Ends Here -->
  </div>
</template>

<script setup>
import { ref, watch, onUnmounted } from 'vue';
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css'
import { VForm, VTextField, VTextarea, VBtn } from 'vuetify/components';
import 'vuetify/styles' 
import moment from 'moment';
import Swal from 'sweetalert2';

const isModalOpen = ref(false);
const invoice = ref({
  firstName: '',
  lastName: '',
  invoiceDate: new Date(),
  dueDate: new Date(),
  paymentTerm: 30,
  description: '',
  totalAmount: '',
});
const errors = ref({});
const loading = ref(false);


let stopWatch; // Variable to hold the stop function for the watcher

const paymentTermRules = [value => (value >= 1 && value <= 100) || 'Payment term must be between 1 and 100'];

function openModal() {
  isModalOpen.value = true;
  // Start watching when the modal is opened
  if (invoice.value.invoiceDate && invoice.value.paymentTerm) {
    invoice.value.dueDate.setDate(invoice.value.invoiceDate.getDate() + 30);
  }
  
  stopWatch = watch([() => invoice.value.invoiceDate, () => invoice.value.paymentTerm], () => {
    if (!invoice.value.invoiceDate || !invoice.value.paymentTerm) {
      return;
    }
    const termDays = Number(invoice.value.paymentTerm);
    const newDueDate = new Date(invoice.value.invoiceDate);
    newDueDate.setDate(newDueDate.getDate() + termDays);
    invoice.value.dueDate = newDueDate;
  });
}

function closeModal() {
  isModalOpen.value = false;
}

function createInvoice() {
  loading.value = true;

  const formattedInvoiceDate = moment(invoice.value.invoiceDate).format("YYYY-MM-DDTHH:mm:ss");
  const formattedDueDate = moment(invoice.value.dueDate).format("YYYY-MM-DDTHH:mm:ss");

    axios.post('/invoices', {
      first_name: invoice.value.firstName,
      last_name: invoice.value.lastName,
      invoice_date: formattedInvoiceDate,
      due_date: formattedDueDate,
      payment_term: invoice.value.paymentTerm,
      description: invoice.value.description,
      total_amount: invoice.value.totalAmount,
    })
    .then(response => {
        if (response.data.status === 'success') {
          Swal.fire({
            title: 'Success!',
            text: 'Invoice created successfully.',
            icon: 'success',
            confirmButtonText: 'OK'
          }).then(() => {
            loading.value = false;
            setDefaultvalues();
            closeModal();
            errors.value = {};
            window.location.reload(); // Refresh the page
          });
          
        }
    })
    .catch(error => {
        if (error.response && error.response.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error('Error creating invoice:', error);
            alert('Error creating invoice: ' + (error.response.data.message || 'Unknown error'));
        }

        loading.value = false; // Stop loading
    });
}

onUnmounted(() => {
  // Stop watching when the component is unmounted
  stopWatch();
});

function setDefaultvalues() {
  invoice.value = {
    firstName: '',
    lastName: '',
    invoiceDate: new Date(),
    dueDate: new Date(),
    paymentTerm: 30,
    description: '',
    totalAmount: '',
  };
}
</script>

<style scoped>
.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 1040;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0,0,0,0.5);
}
.modal {
  position: fixed;
  top: 50%;
  left: 50%;
  z-index: 1050;
  transform: translate(-50%, -50%);
  box-shadow: 0 5px 15px rgba(0,0,0,0.5);
  background: white;
  padding: 20px;
  border-radius: 5px;
  width: 40%; /* Adjusted for better form presentation */
}

/* Direct targeting input fields inside Vuetify components */
.v-text-field input, .v-textarea textarea {
    background-color: white !important;
    border-radius: 8px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.1);
    border: none !important;
}

.v-text-field--outlined.v-input--is-focused .v-input__control .v-input__slot,
.v-textarea--outlined.v-input--is-focused .v-input__control .v-input__slot {
    border: 2px solid rgb(213, 232, 14) !important; /* Ensuring the border color changes on focus */
}

.v-input__slot::before,
.v-input__slot::after {
    display: none !important;
}
</style>
