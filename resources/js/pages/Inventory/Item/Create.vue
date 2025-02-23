<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { ref, reactive, watch, computed } from 'vue';
import { useForm, router, usePage } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { NumberField, NumberFieldContent, NumberFieldDecrement, NumberFieldIncrement, NumberFieldInput } from '@/components/ui/number-field';
import { Textarea } from '@/components/ui/textarea';
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/components/ui/select';
import { useToast } from '@/components/ui/toast';
import { useDebounceFn } from '@vueuse/core';
import { toast } from 'vue-sonner';
import { ReloadIcon } from '@radix-icons/vue';

const page = usePage();
const company = computed(() => page.props.auth.company);
const props = defineProps({
    categories: Array,
    units: Array,
});

// const { toast } = useToast();
const previewImage = ref(null);
const autoSku = ref(false);
const skuExists = ref(false);
const loadingSkuCheck = ref(false);

const formatOptions = {
    style: 'currency',
    currency: company.value.currency,
    minimumFractionDigits: company.value.decimal,
    currencyDisplay: 'code',
    currencySign: 'accounting',
};

const form = useForm({
    name: '',
    sku: '',
    image: null,
    minimum_stock: 1,
    cost_price: 0,
    selling_price: 0,
    category_id: null,
    unit_id: null,
    description: null,
});

// Handle File Upload
const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.image = file;
        previewImage.value = URL.createObjectURL(file);
    }
};

const generateSku = () => {
    if (autoSku.value) {
        const categoryCode = form.category_id ? String(form.category_id).padStart(3, '0') : '000';
        const timestamp = Date.now().toString().slice(-4); // 4-digit timestamp
        form.sku = `SKU-${categoryCode}-${timestamp}`;
    } else {
        // form.sku == '' ? (form.sku = '') : form.sku; // Allow manual input
    }
};

watch(() => form.category_id, generateSku);

// Handle Submit
const submitForm = () => {
    form.post(route('item.store'), {
        onSuccess: () => {
            toast.success('Data berhasil ditambahkan!');
        },
        onError: (errors) => {
            toast.error(errors.name?.[0] || 'Ada permasalahan saat menambah data');
        },
    });
};

const checkSku = useDebounceFn(async () => {
    if (!form.sku) return;
    loadingSkuCheck.value = true;

    try {
        const response = await axios.get(route('item.checkSku'), { params: { sku: form.sku } });
        skuExists.value = response.data.exists;
    } finally {
        loadingSkuCheck.value = false;
    }
}, 1000);
</script>

<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">Tambah Item Baru</h1>
        </div>
        <div class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm">
            <form @submit.prevent="submitForm" class="flex flex-col space-y-4">
                <div class="flex w-full flex-row space-x-4">
                    <div class="flex w-1/2 flex-col space-y-4">
                        <!-- SKU / Kode Barang -->
                        <div class="flex items-center space-x-2">
                            <input type="checkbox" v-model="autoSku" @change="generateSku" />
                            <label for="terms" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                Generate SKU Automatically
                            </label>
                        </div>

                        <div>
                            <Label for="sku">SKU / Kode </Label>
                            <Input required id="sku" v-model="form.sku" :disabled="autoSku" @input="checkSku" :class="{ 'border-red-500': skuExists }" />
                            <span v-if="loadingSkuCheck" class="text-gray-500">Checking...</span>
                            <span v-else-if="skuExists" class="text-red-500">SKU already exists!</span>
                            <span v-else-if="form.sku !== ''" class="text-green-500">SKU available</span>
                        </div>
                        <!-- Nama Item -->
                        <div>
                            <Label for="name">Nama Item</Label>
                            <Input id="name" v-model="form.name" required />
                        </div>

                        <!-- Minimum Stock -->

                        <NumberField id="minimum_stock" v-model="form.minimum_stock" :min="1" required>
                            <Label for="minimum_stock">Minimum Stock</Label>
                            <NumberFieldContent>
                                <NumberFieldDecrement />
                                <NumberFieldInput />
                                <NumberFieldIncrement />
                            </NumberFieldContent>
                        </NumberField>

                        <!-- Harga Pokok -->

                        <NumberField id="cost_price" v-model="form.cost_price" :format-options="formatOptions">
                            <Label for="cost_price">Harga Pokok</Label>
                            <NumberFieldContent>
                                <NumberFieldDecrement />
                                <NumberFieldInput />
                                <NumberFieldIncrement />
                            </NumberFieldContent>
                        </NumberField>

                        <!-- Harga Jual -->

                        <NumberField id="balance" v-model="form.selling_price" :format-options="formatOptions">
                            <Label for="balance">Harga Jual</Label>
                            <NumberFieldContent>
                                <NumberFieldDecrement />
                                <NumberFieldInput />
                                <NumberFieldIncrement />
                            </NumberFieldContent>
                        </NumberField>

                        <!-- Kategori -->
                        <div>
                            <Label for="category_id">Kategori</Label>
                            <Select v-model="form.category_id">
                                <SelectTrigger>
                                    <SelectValue placeholder="Pilih Kategori" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="category in categories" :key="category.id" :value="category.id.toString()">
                                        {{ category.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- Satuan -->
                        <div>
                            <Label for="unit_id">Satuan</Label>
                            <Select v-model="form.unit_id">
                                <SelectTrigger>
                                    <SelectValue placeholder="Pilih Satuan" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="unit in units" :key="unit.id" :value="unit.id.toString()">
                                        {{ unit.name }}
                                    </SelectItem>
                                </SelectContent>
                            </Select>
                        </div>

                        <!-- Description -->
                        <div>
                            <Label for="name">Deskripsi</Label>
                            <Textarea id="name" v-model="form.description" class="resize-none" />
                        </div>
                    </div>
                    <div class="w-1/2">
                        <!-- Upload Gambar -->
                        <div>
                            <img v-if="previewImage" :src="previewImage" alt="Preview" class="mt-2 h-32 w-32 rounded-lg shadow-md" />
                            <Label for="image">Upload Gambar</Label>
                            <Input type="file" id="image" @change="handleFileChange" accept="image/*" />
                        </div>
                    </div>
                    <!-- Tombol Aksi -->
                </div>
                <div class="flex justify-end space-x-2">
                    <Button type="button" :disabled="form.processing" variant="outline" @click="router.visit(route('item.index'))"> Batal </Button>
                    <Button type="submit" :disabled="skuExists || form.processing">
                        <span v-if="form.processing" class="flex">
                            <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                            Please wait
                        </span>

                        <span v-else>Submit</span>
                    </Button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
