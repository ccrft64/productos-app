<template>
    <AppLayout title="Productos">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Productos
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <v-container>
                        <div class="d-flex justify-end mb-4">
                            <v-btn
                                color="primary"
                                @click="openAddProductModal"
                                v-if="can.createProduct"
                            >
                                Agregar Producto
                            </v-btn>
                        </div>

                        <v-card flat>
                            <v-card-title class="d-flex align-center pe-2">
                                <v-text-field
                                    v-model="search"
                                    density="compact"
                                    label="Buscar"
                                    prepend-inner-icon="mdi-magnify"
                                    variant="solo-filled"
                                    flat
                                    hide-details
                                    single-line
                                ></v-text-field>
                            </v-card-title>
                            <v-divider></v-divider>
                            <v-data-table-server
                                v-model:items-per-page="itemsPerPage"
                                v-model:sort-by="currentSortBy" :search="search"
                                :items="products.data"
                                :items-length="products.total"
                                :loading="loading"
                                :headers="visibleHeaders"
                                item-value="id"
                                class="elevation-1"
                                @update:options="loadItems"
                                must-sort="true" >
                                <template v-slot:item.price="{ item }">
                                    ${{ new Intl.NumberFormat('es-CL', { maximumFractionDigits: 0 }).format(Number(item.price)) }}
                                </template>
                                <template v-slot:item.categories="{ item }">
                                    <v-chip-group>
                                        <v-chip v-for="category in item.categories" :key="category.id" small>
                                            {{ category.name }}
                                        </v-chip>
                                    </v-chip-group>
                                </template>
                                <template v-slot:item.expiration_date="{ item }">
                                    {{ item.expiration_date ? new Date(item.expiration_date).toLocaleDateString() : 'N/A' }}
                                </template>
                                <template v-slot:item.actions="{ item }">
                                    <v-icon
                                        v-if="can.editProduct"
                                        small
                                        class="mr-2"
                                        @click="openEditProductModal(item)"
                                    >
                                        mdi-pencil
                                    </v-icon>
                                    <v-icon
                                        v-if="can.deleteProduct"
                                        small
                                        @click="confirmDeleteProduct(item)"
                                    >
                                        mdi-delete
                                    </v-icon>
                                </template>
                                <template v-slot:no-data>
                                    <v-alert :value="true" color="warning" icon="mdi-alert">
                                        No se encontraron productos.
                                    </v-alert>
                                </template>
                            </v-data-table-server>
                        </v-card>
                    </v-container>
                </div>
            </div>
        </div>

        <FormModal
            :show="showProductModal"
            recordType="product" :record="selectedProduct" :categories="categories" :foodCategoryId="foodCategoryId" :can="can" @close="closeProductModal"
            @submitted="handleProductSubmitted"
        />

        <v-dialog v-model="showDeleteDialog" max-width="500px">
            <v-card>
                <v-card-title class="text-h5">Confirm Delete</v-card-title>
                <v-card-text>Are you sure you want to delete this product?</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue-darken-1" variant="text" @click="cancelDelete">Cancel</v-btn>
                    <v-btn color="red-darken-1" variant="text" @click="deleteProduct">Delete</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </AppLayout>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { router, usePage } from '@inertiajs/vue3';
import FormModal from '@/Components/FormModal.vue';

const props = defineProps({
    products: Object,
    categories: Array,
    foodCategoryId: Number,
    can: Object,
    initialSortBy: {
        type: Array,
        default: () => ([{ key: 'name', order: 'asc' }])
    }
});

const itemsPerPage = ref(10);
const search = ref('');
const loading = ref(false);

const currentSortBy = ref(props.initialSortBy);

const allHeaders = [
    { title: 'Nombre', align: 'start', key: 'name' },
    { title: 'Descripción', align: 'start', key: 'description' },
    { title: 'Valor', align: 'end', key: 'price' },
    { title: 'Categorías', align: 'start', key: 'categories', sortable: false },
    { title: 'Fecha de expiración', align: 'start', key: 'expiration_date' },
    { title: 'Acciones', align: 'center', key: 'actions', sortable: false },
];

const visibleHeaders = computed(() => {
    if (props.can.editProduct || props.can.deleteProduct) {
        return allHeaders;
    } else {
        return allHeaders.filter(header => header.key !== 'actions');
    }
});

const showProductModal = ref(false);
const selectedProduct = ref(null);

const showDeleteDialog = ref(false);
const productToDelete = ref(null);

const loadItems = ({ page, itemsPerPage: newItemsPerPage, sortBy, search: newSearch }) => {
    loading.value = true;

    currentSortBy.value = sortBy;

    const sortByKey = sortBy.length ? sortBy[0].key : null;
    const sortDesc = sortBy.length ? sortBy[0].order === 'desc' : null;

    router.get(route('products.index'), {
        page: page,
        per_page: newItemsPerPage,
        sort_by: sortByKey,
        sort_desc: sortDesc,
        search: newSearch || search.value,
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            loading.value = false;
        },
        onError: () => {
            loading.value = false;
        }
    });
};

const openAddProductModal = () => {
    selectedProduct.value = null;
    showProductModal.value = true;
};

const openEditProductModal = (product) => {
    selectedProduct.value = product;
    showProductModal.value = true;
};

const closeProductModal = () => {
    showProductModal.value = false;
    selectedProduct.value = null;
};

const handleProductSubmitted = () => {
    router.reload({ only: ['products'] });
};

const confirmDeleteProduct = (product) => {
    productToDelete.value = product;
    showDeleteDialog.value = true;
};

const cancelDelete = () => {
    showDeleteDialog.value = false;
    productToDelete.value = null;
};

const deleteProduct = () => {
    if (productToDelete.value) {
        router.delete(route('products.destroy', productToDelete.value.id), {
            onSuccess: () => {
                showDeleteDialog.value = false;
                productToDelete.value = null;
                router.reload({ only: ['products'] });
            },
            onError: (errors) => {
                console.error('Delete Error:', errors);
            },
            preserveScroll: true,
        });
    }
};

watch(search, (newSearch) => {
    loadItems({
        page: 1,
        itemsPerPage: itemsPerPage.value,
        sortBy: currentSortBy.value,
        search: newSearch
    });
});
</script>

<style scoped>
</style>