<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    leads: Array
})
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg dark:bg-gray-800">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="mb-4 text-lg font-semibold">Leads registrados</h3>
                        <button
                            @click="() => router.visit(route('leads.create'))"
                            class="inline-block rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-blue-500 dark:hover:bg-blue-600 cursor-pointer"
                        >
                            Registrar Lead
                        </button>
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left">Nombre</th>
                                    <th class="px-4 py-2 text-left">Correo</th>
                                    <th class="px-4 py-2 text-left">Teléfono</th>
                                    <th class="px-4 py-2 text-left">Empresa</th>
                                    <th class="px-4 py-2 text-left">Estatus</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="lead in leads" :key="lead.id" class="border-t dark:border-gray-700">
                                    <td class="px-4 py-2">{{ lead.nombre }}</td>
                                    <td class="px-4 py-2">{{ lead.correo }}</td>
                                    <td class="px-4 py-2">{{ lead.telefono }}</td>
                                    <td class="px-4 py-2">{{ lead.empresa }}</td>
                                    <td class="px-4 py-2">{{ lead.estatus }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
