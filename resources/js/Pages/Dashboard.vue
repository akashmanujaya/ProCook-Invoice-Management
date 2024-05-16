<script setup>
import { onMounted, ref } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import InvoiceCreator from '@/Components/Modals/InvoiceCreator.vue';
import InvoiceFilters from '@/Components/Filters/InvoiceFilters.vue';
import InvoiceTable from '@/Components/Tables/InvoiceTable.vue';


const filters = ref({});

function updateFilters(newFilters) {
    filters.value = newFilters;
}
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
                    <InvoiceCreator/>
                    <InvoiceFilters @update-filters="updateFilters" />
                    <InvoiceTable :filters="filters" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
