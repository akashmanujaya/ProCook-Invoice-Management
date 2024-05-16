<template>
    <div
      class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded p-10"
      :class="[color === 'light' ? 'bg-white' : 'bg-emerald-900 text-white']"
    >
      <div class="rounded-t mb-0 px-4 py-3 border-0">
        <div class="flex flex-wrap items-center">
          <div class="relative w-full px-4 max-w-full flex-grow flex-1">
            <h3 class="font-semibold text-lg" :class="[color === 'light' ? 'text-blueGray-700' : 'text-white']">
              Invoice List
            </h3>
          </div>
        </div>
      </div>
      <div v-if="invoices.length > 0" class="block w-full overflow-x-auto">
        <!-- Invoices table -->
        <table class="items-center w-full bg-transparent border-collapse">
          <thead>
            <tr>
              <th class="px-6 py-3 border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                :class="[color === 'light' ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100' : 'bg-emerald-800 text-emerald-300 border-emerald-700']">
                Invoice Number
              </th>
              <th class="px-6 py-3 border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                :class="[color === 'light' ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100' : 'bg-emerald-800 text-emerald-300 border-emerald-700']">
                Customer Name
              </th>
              <th class="px-6 py-3 border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                :class="[color === 'light' ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100' : 'bg-emerald-800 text-emerald-300 border-emerald-700']">
                Total Amount
              </th>
              <th class="px-6 py-3 border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                :class="[color === 'light' ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100' : 'bg-emerald-800 text-emerald-300 border-emerald-700']">
                Invoice Date
              </th>
              <th class="px-6 py-3 border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                :class="[color === 'light' ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100' : 'bg-emerald-800 text-emerald-300 border-emerald-700']">
                Due Date
              </th>
              <th class="px-6 py-3 border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-left"
                :class="[color === 'light' ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100' : 'bg-emerald-800 text-emerald-300 border-emerald-700']">
                Status
              </th>
              <th class="px-6 py-3 border border-solid py-3 text-xs uppercase border-l-0 border-r-0 whitespace-nowrap font-semibold text-right"
                :class="[color === 'light' ? 'bg-blueGray-50 text-blueGray-500 border-blueGray-100' : 'bg-emerald-800 text-emerald-300 border-emerald-700']">
                Actions
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="invoice in invoices" :key="invoice.id">
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-left">
                {{ invoice.invoice_number }}
              </td>
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                {{ invoice.customer_name }}
              </td>
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                ${{ invoice.total_amount }}
              </td>
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                {{ invoice.invoice_date }}
              </td>
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                {{ invoice.due_date }}
              </td>
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                <i :class="{'text-orange-500': invoice.status === 'Pending', 'text-emerald-500': invoice.status === 'Paid', 'text-red-500': invoice.status === 'Overdue'}" class="fas fa-circle mr-2"></i>
                {{ invoice.status }}
              </td>
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4 text-right">
                <table-dropdown :invoice-number="invoice.invoice_number" @view-invoice="handleViewInvoice"/>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div v-else class="text-center py-10">
        <span class="text-gray-500">No records found.</span>
      </div>
      <div v-if="invoices.length > 0" class="px-5 py-3 flex justify-center">
        <ul class="inline-flex items-center space-x-2">
          <li v-for="page in visiblePages()" :key="page" class="pagination-button cursor-pointer" :class="{ 'active': page === currentPage }" @click="page !== '...' && goToPage(page)">
            <span v-if="page !== '...'">{{ page }}</span>
            <span v-else>...</span>
          </li>
        </ul>
      </div>
    </div>

    <invoice-viewer ref="invoiceViewer" />
  </template>
  
  <script>
  import TableDropdown from "@/Components/TableDropdown.vue";
  import InvoiceViewer from '@/Components/InvoiceViewer.vue';
  
  export default {
    components: {
      TableDropdown,
      InvoiceViewer,
    },
    props: {
      color: {
        type: String,
        default: "light",
        validator: function (value) {
          return ["light", "dark"].indexOf(value) !== -1;
        },
      },
      filters: Object,
    },
    data() {
      return {
        invoices: [],
        currentPage: 1,
        pageSize: 8,
        totalInvoices: 0,
        currentFilters: {}, // Store current filters to be used for fetching invoices
        noRecordsFound: false, // Indicates no records found
      };
    },
    watch: {
      filters: {
        handler(newFilters) {
          this.currentFilters = newFilters; // Update currentFilters with the new ones
          this.fetchFilteredInvoices(); // Fetch with updated filters
        },
        deep: true,
        immediate: true
      }
    },
    methods: {
      fetchFilteredInvoices(filters) {
        const params = {
          page: this.currentPage,
          perPage: this.pageSize,
          ...this.currentFilters
        };
        axios.get(`/invoices`, { params })
          .then(response => {
            if (response.data.status === 'Not Found') {
            this.invoices = [];
            this.noRecordsFound = true;
            this.totalInvoices = 0; // Reset total invoices to hide pagination
          } else {
            response =  response.data;
            this.invoices = response.data.data;
            this.totalInvoices = response.data.pagination.total;
            this.noRecordsFound = false;
            this.pageSize = response.data.pagination.perPage;
          }
          
            
          })
          .catch(error => {
            console.error('Error fetching invoices:', error);
            this.invoices = [];
            this.noRecordsFound = true;
          });
      },
      goToPage(page) {
        this.currentPage = page;
        this.fetchFilteredInvoices();
      },
      visiblePages() {
        let range = [];
        const total = this.totalPages;
        const current = this.currentPage;
        const delta = 2; // pages before and after current

        for (let i = Math.max(2, current - delta); i <= Math.min(total - 1, current + delta); i++) {
          range.push(i);
        }

        if (current - delta > 2) {
          range.unshift("...");
        }

        if (current + delta < total - 1) {
          range.push("...");
        }

        range.unshift(1);
        if (total > 1) range.push(total);

        return range;
      },
      handleViewInvoice(invoiceNumber) {
        axios.get(`/invoices/${invoiceNumber}`)
          .then(response => {
            const invoiceDetails = response.data.data;
            // Now do something with invoiceDetails, like showing it in a modal or a dedicated section
            this.$refs.invoiceViewer.setInvoice(invoiceDetails);
            this.$refs.invoiceViewer.openModal();
          })
          .catch(error => {
            console.error('Error fetching invoice details:', error);
          });
      }
    },
    computed: {
      totalPages() {
        return Math.ceil(this.totalInvoices / this.pageSize);
      },
      pageRange() {
        const pages = [];
        for (let i = 1; i <= this.totalPages; i++) {
          pages.push(i);
        }
        return pages;
      },
    },
  };
  </script>
  

  <style scoped>
  .pagination-button {
    transition: background-color .3s, color .3s;
    border-radius: 50%; /* Circle shape */
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  
  .pagination-button:hover {
    background-color: #4299e1; /* Tailwind blue-500 */
    color: white;
  }
  
  .active {
    background-color: #4299e1; /* Tailwind blue-500 */
    color: white;
  }
  </style>
  
  