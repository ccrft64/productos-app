<template>
    <AppLayout title="Categorías">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Categorías
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <v-container>
                        <div class="d-flex justify-end mb-4">
                            <v-btn
                                color="primary"
                                @click="openAddCategoryModal"
                                v-if="can.createCategory"
                            >
                                Agregar Categoría
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
                                :items="categories.data"
                                :items-length="categories.total"
                                :loading="loading"
                                :headers="visibleHeaders"
                                item-value="id"
                                class="elevation-1"
                                @update:options="loadItems"
                                must-sort="true"
                            >
                                <template v-slot:item.actions="{ item }">
                                    <v-icon
                                        v-if="can.editCategory"
                                        small
                                        class="mr-2"
                                        @click="openEditCategoryModal(item)"
                                    >
                                        mdi-pencil
                                    </v-icon>
                                    <v-icon
                                        v-if="can.deleteCategory"
                                        small
                                        @click="confirmDeleteCategory(item)"
                                    >
                                        mdi-delete
                                    </v-icon>
                                </template>
                                <template v-slot:no-data>
                                    <v-alert :value="true" color="warning" icon="mdi-alert">
                                        No se encontraron categorías.
                                    </v-alert>
                                </template>
                            </v-data-table-server>
                        </v-card>
                    </v-container>
                </div>
            </div>
        </div>

        <FormModal
            :show="showCategoryModal"
            recordType="category"
            :record="selectedCategory"
            :can="can"
            @close="closeCategoryModal"
            @submitted="handleCategorySubmitted"
        />

        <v-dialog v-model="showDeleteDialog" max-width="500px">
            <v-card>
                <v-card-title class="text-h5">Confirm Delete</v-card-title>
                <v-card-text>Are you sure you want to delete this category?</v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue-darken-1" variant="text" @click="cancelDelete">Cancel</v-btn>
                    <v-btn color="red-darken-1" variant="text" @click="deleteCategory">Delete</v-btn>
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
    categories: Object,
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
    { title: 'Acciones', align: 'center', key: 'actions', sortable: false },
];

const visibleHeaders = computed(() => {
    if (props.can.editCategory || props.can.deleteCategory) {
        return allHeaders;
    } else {
        return allHeaders.filter(header => header.key !== 'actions');
    }
});

const showCategoryModal = ref(false);
const selectedCategory = ref(null);

const showDeleteDialog = ref(false);
const categoryToDelete = ref(null);

const loadItems = ({ page, itemsPerPage: newItemsPerPage, sortBy, search: currentSearch }) => {
    loading.value = true;

    currentSortBy.value = sortBy;

    const sortByKey = sortBy.length ? sortBy[0].key : null;
    const sortDesc = sortBy.length ? sortBy[0].order === 'desc' : null;

    router.get(route('categories.index'), {
        page: page,
        per_page: newItemsPerPage,
        sort_by: sortByKey,
        sort_desc: sortDesc,
        search: currentSearch || search.value,
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

const openAddCategoryModal = () => {
    selectedCategory.value = null;
    showCategoryModal.value = true;
};

const openEditCategoryModal = (category) => {
    selectedCategory.value = category;
    showCategoryModal.value = true;
};

const closeCategoryModal = () => {
    showCategoryModal.value = false;
    selectedCategory.value = null;
};

const handleCategorySubmitted = () => {
    router.reload({ only: ['categories'] });
};

const confirmDeleteCategory = (category) => {
    categoryToDelete.value = category;
    showDeleteDialog.value = true;
};

const cancelDelete = () => {
    showDeleteDialog.value = false;
    categoryToDelete.value = null;
};

const deleteCategory = () => {
    if (categoryToDelete.value) {
        router.delete(route('categories.destroy', categoryToDelete.value.id), {
            onSuccess: () => {
                showDeleteDialog.value = false;
                categoryToDelete.value = null;
                router.reload({ only: ['categories'] });
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