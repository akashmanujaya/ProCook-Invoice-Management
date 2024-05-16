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
      <div class="block w-full overflow-x-auto">
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
                <table-dropdown />
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="px-5 py-3 flex justify-center">
        <ul class="inline-flex items-center space-x-2">
          <li v-for="page in visiblePages()" :key="page" class="pagination-button cursor-pointer" :class="{ 'active': page === currentPage }" @click="page !== '...' && goToPage(page)">
            <span v-if="page !== '...'">{{ page }}</span>
            <span v-else>...</span>
          </li>
        </ul>
      </div>
    </div>
  </template>
  
  <script>
  import TableDropdown from "@/Components/TableDropdown.vue";
  
  export default {
    components: {
      TableDropdown,
    },
    props: {
      color: {
        type: String,
        default: "light",
        validator: function (value) {
          return ["light", "dark"].indexOf(value) !== -1;
        },
      },
      invoices: Array,
    },
    watch: {
      invoices(newValue, oldValue) {
        // Handle updates if necessary
      }
    },
    data() {
      return {
        invoices: [],
        currentPage: 1,
        pageSize: 3,
        totalInvoices: 0,
      };
    },
    mounted() {
      this.fetchInvoices();
    },
    methods: {
      fetchInvoices() {
        axios.get(`/invoices?page=${this.currentPage}&perPage=${this.pageSize}`)
          .then(response => {
            response =  response.data;
            this.invoices = response.data.data;
            this.totalInvoices = response.data.pagination.total;
            this.pageSize = response.data.pagination.perPage;
          })
          .catch(error => {
            console.error('Error fetching invoices:', error);
          });
      },
      goToPage(page) {
        this.currentPage = page;
        this.fetchInvoices();
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
  
  