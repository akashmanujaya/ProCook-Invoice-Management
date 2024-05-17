<template>
    <div class="flex justify-end mb-4">
      <button @click="openModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded shadow">
        Edit Invoice
      </button>
  
      <!-- Modal Starts Here -->
      <div v-if="isModalOpen" class="modal-backdrop">
        <div class="modal">
          <h3 class="font-semibold text-lg mb-10">Edit Invoice</h3>
          <!-- Form for editing an existing invoice -->
          <v-form>
            <v-text-field required v-model="invoice.first_name" label="First Name" outlined :error-messages="errors.first_name"></v-text-field>

            <v-text-field required v-model="invoice.last_name" label="Last Name" outlined :error-messages="errors.last_name"></v-text-field>

            <label for="invoiceDate" class="form-label">Invoice Date</label>
            <VueDatePicker
                v-model="invoice.invoice_date"
                :config="{ type: 'datetime', useUtc: false }"
                class="mb-2"
                :error-messages="errors.invoice_date">
            </VueDatePicker>

            <v-text-field v-model="invoice.payment_term" label="Payment Term" type="number" :rules="paymentTermRules" outlined :error-messages="errors.payment_term"></v-text-field>

            <label for="DueDate" class="form-label">Due Date</label>
            <VueDatePicker v-model="invoice.due_date" :config="{ type: 'datetime' }" class="mb-2" :error-messages="errors.due_date"></VueDatePicker>

            <v-textarea v-model="invoice.description" label="Description" :counter="3000" outlined :error-messages="errors.description"></v-textarea>

            <v-text-field v-model="invoice.total_amount" label="Total Amount" prefix="$" type="number" step="0.01" outlined :error-messages="errors.total_amount"></v-text-field>
            
            <div class="flex items-center justify-between">
              <v-btn :disabled="loading" :loading="loading" color="blue darken-1" text @click="updateInvoice">
                Save Changes
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
    first_name: '',
    last_name: '',
    invoice_date: new Date(),
    due_date: new Date(),
    payment_term: 30,
    description: '',
    total_amount: '',
  });
  const errors = ref({});
  const loading = ref(false);
  let stopWatch; 
  const paymentTermRules = [value => (value >= 1 && value <= 100) || 'Payment term must be between 1 and 100'];
  
  function openModal(invoiceDetails) {
    isModalOpen.value = true;
    const convereted_invoice_date = convertToISOString(invoiceDetails.invoice_date);
    const convereted_due_date = convertToISOString(invoiceDetails.due_date);
    invoice.value = {
      ...invoiceDetails,
      invoice_date: convereted_invoice_date,
      due_date: convereted_due_date, 
      total_amount: convertFormattedNumber(invoiceDetails.total_amount)
    };

    stopWatch = watch([() => invoice.value.invoice_date, () => invoice.value.payment_term], () => {
        if (!invoice.value.invoice_date || !invoice.value.payment_term) {
        return;
        }
        const termDays = Number(invoice.value.payment_term);
        const newDueDate = new Date(invoice.value.invoice_date);
        newDueDate.setDate(newDueDate.getDate() + termDays);
        invoice.value.due_date = newDueDate;
    });
  }

    function convertToISOString(dateString) {
        // Parse the input date string using the correct format
        const date = moment(dateString, 'DD-MM-YYYY hh:mm A');
        // Format the date to ISO string
        const isoString = date.format('YYYY-MM-DDTHH:mm:ss');
        return isoString;
    }
  
  function closeModal() {
    isModalOpen.value = false;
  }

  function convertFormattedNumber(formattedNumber) {
    // Remove commas from the string
    const plainNumberString = formattedNumber.replace(/,/g, '');
    // Convert the string to a number
    const plainNumber = parseFloat(plainNumberString);
    return plainNumber;
  }

  
  function updateInvoice() {
    loading.value = true;
    const formattedInvoiceDate = moment(invoice.value.invoice_date).format("YYYY-MM-DDTHH:mm:ss");
    const formattedDueDate = moment(invoice.value.due_date).format("YYYY-MM-DDTHH:mm:ss");
  
    axios.put(`/invoices/${invoice.value.invoice_number}`, {
      first_name: invoice.value.first_name,
      last_name: invoice.value.last_name,
      invoice_date: formattedInvoiceDate,
      due_date: formattedDueDate,
      payment_term: invoice.value.payment_term,
      description: invoice.value.description,
      total_amount: invoice.value.total_amount,
    })
    .then(response => {
        if (response.data.status === 'success') {
            Swal.fire({
            title: 'Success!',
            text: 'Invoice updated successfully.',
            icon: 'success',
            confirmButtonText: 'OK'
          }).then(() => {
            loading.value = false;
            closeModal();
            errors.value = {};
            window.location.reload();
          });
        }
    })
    .catch(error => {
        errors.value = error.response.data.errors || {};
        console.error('Error updating invoice:', error);
        loading.value = false;
    });
  }

  defineExpose({
    openModal,
    closeModal,
    updateInvoice
  });
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
  </style>
  