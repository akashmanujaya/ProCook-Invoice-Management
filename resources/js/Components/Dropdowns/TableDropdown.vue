<template>
  <div>
    <div
      class="text-blueGray-500 py-1 px-3"
      href="#pablo"
      ref="btnDropdownRef"
      @mouseenter="toggleDropdown"  
      @mouseleave="closeDropdown"   
    >
      <i class="fa-solid fa-list-ul"></i>
  </div>
    <div
      ref="popoverDropdownRef"
      class="bg-white text-base z-50 float-left py-2 list-none text-left rounded shadow-lg min-w-48"
      :class="{
        hidden: !dropdownPopoverShow,
        block: dropdownPopoverShow,
      }"
      @mouseenter="keepDropdownOpen"
      @mouseleave="closeDropdown"    
    >
      <a
        href="javascript:void(0);"
        class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700 hover:bg-cyan-50"
        @click="emitViewInvoice"
      >
        View Invoice
      </a>
      <a
        href="javascript:void(0);"
        class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700 hover:bg-cyan-50"
        @click="emitEditInvoice"
      >
        Edit Invoice
      </a>
      <a
        href="javascript:void(0);"
        class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700 hover:bg-cyan-50"
        @click="confirmStatusChange()"
      >
      {{ invoiceStatus === 'Paid' ? 'Mark as Pending' : 'Mark as Paid' }}
      </a>
      <a
        href="javascript:void(0);"
        class="text-sm py-2 px-4 font-normal block w-full whitespace-nowrap bg-transparent text-blueGray-700 hover:bg-cyan-50"
        @click="confirmDelete()"
      >
        Delete Invoice
      </a>
    </div>
  </div>
</template>

<script>
import { createPopper } from "@popperjs/core";
import Swal from 'sweetalert2';


export default {
  data() {
    return {
      dropdownPopoverShow: false,
    };
  },
  methods: {
    toggleDropdown() {
      this.dropdownPopoverShow = true;
      createPopper(this.$refs.btnDropdownRef, this.$refs.popoverDropdownRef, {
        placement: "bottom-start",
      });
    },
    emitViewInvoice() {
      this.$emit('view-invoice', this.invoiceNumber);
      this.dropdownPopoverShow = false;
    },
    emitEditInvoice() {
        this.$emit('edit-invoice', this.invoiceNumber);
        this.dropdownPopoverShow = false;
    },
    confirmDelete() {
      Swal.fire({
        title: `Are you sure you want to delete Invoice ${this.invoiceNumber}?`,
        text: `This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          this.deleteInvoice(this.invoiceNumber);
        }
      });
    },
    deleteInvoice(invoiceId) {
      axios.delete(`invoices/${invoiceId}`)
        .then(response => {
          Swal.fire({
            title: 'Deleted!',
            text: 'Invoice deleted successfully.',
            icon: 'success',
            confirmButtonText: 'OK'
          }).then(() => {
            window.location.reload();
          });
        })
        .catch(error => {
          console.error('Error deleting the invoice:', error);
          Swal.fire(
            'Failed!',
            'Failed to delete the invoice.',
            'error'
          );
        });
    },
    confirmStatusChange() {
      Swal.fire({
        title: `Are you sure?`,
        text: `Do you want to change the status of Invoice ${this.invoiceNumber} to ${ this.invoiceStatus === 'Paid' ? 'Pending' : 'Paid'}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, change it!'
      }).then((result) => {
        if (result.isConfirmed) {
          this.toggleInvoiceStatus(this.invoiceNumber);
        }
      });
    },
    toggleInvoiceStatus(invoiceID) {
      axios.post(`/invoices/toggle-status/${invoiceID}`)
        .then(response => {
          Swal.fire({
            title: 'Status Changed!',
            text: 'Invoice status changed successfully.',
            icon: 'success',
            confirmButtonText: 'OK'
          }).then(() => {
            window.location.reload();
          });
        })
        .catch(error => {
          console.error('Error changing the status of the invoice:', error);
          Swal.fire(
            'Failed!',
            'Failed to chage the status of the invoice.',
            'error'
          );
        });
    },
    closeDropdown() {
      this.dropdownPopoverShow = false;
    },
    keepDropdownOpen() {
      this.dropdownPopoverShow = true;
    },
    
  },
  props: {
    invoiceNumber: String,
    invoiceStatus: String,
  },
};
</script>
