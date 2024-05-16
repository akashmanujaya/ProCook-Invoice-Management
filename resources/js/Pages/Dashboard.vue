<script setup>
import { onMounted } from 'vue';
import { useStore } from 'vuex';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import InvoiceCreator from '@/Components/InvoiceCreator.vue';
import InvoiceFilters from '@/Components/InvoiceFilters.vue';
import InvoiceTable from '@/Components/InvoiceTable.vue';

const store = useStore();

onMounted(() => {
  store.dispatch('fetchInvoices');
});
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <InvoiceCreator @invoice-created="invoice => store.dispatch('createInvoice', invoice)" />
                    <InvoiceFilters />
                    <InvoiceTable :invoices="store.getters.allInvoices" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
