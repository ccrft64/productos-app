<template>
    <v-dialog v-model="dialog" max-width="600px">
        <v-card>
            <v-card-title>
                <span class="text-h5">{{ modalTitle }}</span>
            </v-card-title>
            <v-card-text>
                <v-container>
                    <v-form @submit.prevent="submitForm">
                        <v-text-field
                            v-model="form.name"
                            :label="nameLabel"
                            :error-messages="form.errors.name"
                            required
                        ></v-text-field>

                        <template v-if="recordType === 'product'">
                            <v-textarea
                                v-model="form.description"
                                label="Descripción"
                                :error-messages="form.errors.description"
                                rows="2"
                            ></v-textarea>

                            <v-text-field
                                v-model="priceInput"
                                label="Valor (Precio)*"
                                type="text"
                                :error-messages="form.errors.price"
                                required
                                prefix="$"
                            ></v-text-field>

                            <v-select
                                v-model="form.category_ids"
                                :items="categories"
                                item-title="name"
                                item-value="id"
                                label="Categorías*"
                                multiple
                                chips
                                :error-messages="form.errors.category_ids"
                                required
                            ></v-select>

                            <v-menu
                                v-model="showDatePicker"
                                :close-on-content-click="false"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"
                            >
                                <template v-slot:activator="{ props: menuProps }">
                                    <v-text-field
                                        v-if="isFoodProduct"
                                        v-model="form.expiration_date"
                                        label="Fecha de Vencimiento*"
                                        prepend-inner-icon="mdi-calendar"
                                        readonly
                                        v-bind="menuProps"
                                        :error-messages="form.errors.expiration_date"
                                        required
                                    ></v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="selectedExpirationDate"
                                    :min="minExpirationDate"
                                    @update:model-value="updateExpirationDate"
                                    color="primary"
                                    scrollable
                                ></v-date-picker>
                            </v-menu>
                        </template>

                        <small>* indica campo requerido</small>
                    </v-form>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue-darken-1" variant="text" @click="closeModal">
                    Cancelar
                </v-btn>
                <v-btn
                    color="blue-darken-1"
                    variant="text"
                    @click="submitForm"
                    :loading="form.processing"
                    :disabled="form.processing || formDisabledByPermissions"
                >
                    {{ submitButtonText }}
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { format, parseISO } from 'date-fns';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    recordType: {
        type: String,
        required: true,
        validator: (value) => ['product', 'category'].includes(value),
    },
    record: {
        type: Object,
        default: null,
    },
    categories: {
        type: Array,
        default: () => [],
    },
    foodCategoryId: {
        type: Number,
        required: false,
        default: null,
    },
    can: {
        type: Object,
        required: true,
        default: () => ({
            createProduct: false, editProduct: false, deleteProduct: false,
            createCategory: false, editCategory: false, deleteCategory: false,
        }),
    },
});

const emit = defineEmits(['close', 'submitted']);

const dialog = ref(props.show);
const showDatePicker = ref(false);

const editingRecord = ref(null);

const form = useForm({
    id: null,
    name: '',
    description: null,
    price: null,
    category_ids: [],
    expiration_date: null,
});

const selectedExpirationDate = ref(null);

const nameLabel = computed(() => {
    return props.recordType === 'product' ? 'Nombre del Producto*' : 'Nombre de la Categoría*';
});

const modalTitle = computed(() => {
    const action = editingRecord.value ? 'Editar' : 'Agregar Nuevo';
    const type = props.recordType === 'product' ? 'Producto' : 'Categoría';
    return `${action} ${type}`;
});

const submitButtonText = computed(() => {
    return editingRecord.value ? 'Actualizar' : 'Guardar';
});

const isFoodProduct = computed(() => {
    return props.recordType === 'product' && form.category_ids && props.foodCategoryId && form.category_ids.includes(props.foodCategoryId);
});

const minExpirationDate = computed(() => {
    return format(new Date(), 'yyyy-MM-dd');
});

const priceInput = computed({
    get() {
        if (form.price === null || form.price === undefined || isNaN(form.price)) {
            return '';
        }
        return form.price.toLocaleString('es-CL');
    },
    set(newValue) {
        let cleanedValue = String(newValue).replace(/\./g, '');
        cleanedValue = cleanedValue.replace(/,/g, '.');

        const parsedValue = parseFloat(cleanedValue);

        if (!isNaN(parsedValue)) {
            form.price = parsedValue;
        } else {
            form.price = null;
        }
    }
});

watch(() => props.show, (newValue) => {
    dialog.value = newValue;
    if (newValue) {
        initializeForm(props.record);
    }
});

watch(dialog, (newValue) => {
    if (!newValue) {
        emit('close');
    }
});

watch(isFoodProduct, (newValue) => {
    if (!newValue) {
        form.expiration_date = null;
        selectedExpirationDate.value = null;
    }
});

const initializeForm = (recordData) => {
    editingRecord.value = recordData;

    if (props.recordType === 'product') {
        form.reset({
            id: null,
            name: '',
            description: null,
            price: null,
            category_ids: [],
            expiration_date: null,
        });
    } else {
        form.reset({
            id: null,
            name: '',
        });
    }

    if (recordData) {
        form.id = recordData.id;
        form.name = recordData.name;

        if (props.recordType === 'product') {
            form.description = recordData.description;
            form.price = Number(recordData.price);
            form.category_ids = recordData.categories.map(category => category.id);

            if (recordData.expiration_date) {
                form.expiration_date = format(parseISO(recordData.expiration_date), 'yyyy-MM-dd');
                selectedExpirationDate.value = parseISO(recordData.expiration_date);
            } else {
                form.expiration_date = null;
                selectedExpirationDate.value = null;
            }
        }
    } else {
        if (props.recordType === 'product') {
            form.expiration_date = null;
            selectedExpirationDate.value = null;
        }
    }
    form.clearErrors();
};


const updateExpirationDate = (date) => {
    if (date) {
        form.expiration_date = format(date, 'yyyy-MM-dd');
    } else {
        form.expiration_date = null;
    }
    showDatePicker.value = false;
};

const formDisabledByPermissions = computed(() => {
    if (props.recordType === 'product') {
        return (!editingRecord.value && !props.can.createProduct) || (editingRecord.value && !props.can.editProduct);
    } else {
        return (!editingRecord.value && !props.can.createCategory) || (editingRecord.value && !props.can.editCategory);
    }
});

const submitForm = () => {
    let submitRoute;
    let requiredPermissionCreate, requiredPermissionEdit;

    if (props.recordType === 'product') {
        submitRoute = editingRecord.value ? route('products.update', editingRecord.value.id) : route('products.store');
        requiredPermissionCreate = props.can.createProduct;
        requiredPermissionEdit = props.can.editProduct;
    } else {
        submitRoute = editingRecord.value ? route('categories.update', editingRecord.value.id) : route('categories.store');
        requiredPermissionCreate = props.can.createCategory;
        requiredPermissionEdit = props.can.editCategory;
    }

    if ((!editingRecord.value && !requiredPermissionCreate) || (editingRecord.value && !requiredPermissionEdit)) {
        console.warn(`Attempted to submit ${props.recordType} form without necessary permissions.`);
        return;
    }

    const method = editingRecord.value ? 'put' : 'post';

    form[method](submitRoute, {
        onSuccess: () => {
            closeModal(true);
            emit('submitted');
        },
        onError: (errors) => {
            console.error(`${props.recordType} ${editingRecord.value ? 'Update' : 'Store'} Error:`, errors);
        },
        preserveScroll: true,
    });
};

const closeModal = (submitted = false) => {
    dialog.value = false;
    emit('close', submitted);
    if (!submitted) {
        form.reset();
        selectedExpirationDate.value = null;
        form.clearErrors();
    }
};

const openModal = (recordData) => {
    initializeForm(recordData);
    dialog.value = true;
};
defineExpose({ openModal });
</script>

<style scoped>
</style>