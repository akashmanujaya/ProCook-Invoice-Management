<template>
    <div
      class="relative flex flex-col min-w-0 break-words w-full mb-6 shadow-lg rounded"
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
                {{ invoice.number }}
              </td>
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                {{ invoice.customerName }}
              </td>
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                ${{ invoice.totalAmount }}
              </td>
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                {{ invoice.dueDate }}
              </td>
              <td class="border-t-0 px-6 align-middle border-l-0 border-r-0 text-xs whitespace-nowrap p-4">
                <i :class="{'text-orange-500': invoice.status === 'Unpaid', 'text-emerald-500': invoice.status === 'Paid', 'text-red-500': invoice.status === 'Overdue'}" class="fas fa-circle mr-2"></i>
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
        <ul class="inline-flex -space-x-px">
            <li v-for="page in pageRange" :key="page" @click="goToPage(page)" :class="{'bg-blue-500 text-black': page === currentPage, 'bg-white text-blue-500 hover:bg-blue-500 hover:text-white': page !== currentPage}" class="py-2 px-3 leading-tight text-blue-500 bg-white border border-gray-300 cursor-pointer">
            {{ page }}
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
  },
  data() {
    return {
      invoices: [
        { id: 1, number: 'INV001', customerName: 'John Doe', totalAmount: 1200, dueDate: '2021-09-01', status: 'Paid' },
          { id: 2, number: 'INV002', customerName: 'Jane Doe', totalAmount: 350, dueDate: '2021-09-05', status: 'Unpaid' },
      ],
      currentPage: 1,
      pageSize: 10,
    };
  },
  computed: {
    totalPages() {
      return Math.ceil(this.invoices.length / this.pageSize);
    },
    paginatedInvoices() {
      const start = (this.currentPage - 1) * this.pageSize;
      const end = start + this.pageSize;
      return this.invoices.slice(start, end);
    },
    pageRange() {
      const pages = [];
      for (let i = 1; i <= this.totalPages; i++) {
        pages.push(i);
      }
      return pages;
    },
  },
  methods: {
    goToPage(page) {
      this.currentPage = page;
    },
  },
};
</script>

<style scoped>
.active {
  color: white;
  background-color: #007bff;
}
</style>
  