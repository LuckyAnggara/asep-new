<script setup>
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Select, SelectItem } from '@/Components/ui/select';

// Props
const props = defineProps({
    categories: Array,
});

// Form setup
const form = useForm({
    sku: '',
    name: '',
    barcode: '',
    category_id: '',
    image: null,
    cogs: '',
    price: '',
    min_stock: '',
    weight: '',
    description: '',
    variations: [],
});

// Handle file upload
const handleFileUpload = (event) => {
    form.image = event.target.files[0];
};

// 🛠 Manage Variations
const variationName = ref('');
const variationOptions = ref('');

// ➕ Add New Variation
const addVariation = () => {
    if (variationName.value && variationOptions.value) {
        form.variations.push({
            name: variationName.value,
            options: variationOptions.value.split(',').map((opt) => opt.trim()), // Convert to array
        });
        variationName.value = '';
        variationOptions.value = '';
    }
};

// ❌ Remove Variation
const removeVariation = (index) => {
    form.variations.splice(index, 1);
};

// Submit Form
const submit = () => {
    form.post(route('items.store'));
};
</script>

<template>
    <div class="mx-auto max-w-3xl rounded-lg bg-white p-6 shadow-lg">
        <h1 class="mb-4 text-2xl font-bold">➕ Add New Item</h1>

        <form @submit.prevent="submit" class="space-y-4">
            <div>
                <Label>SKU</Label>
                <Input v-model="form.sku" type="text" required />
            </div>
            <div>
                <Label>Name</Label>
                <Input v-model="form.name" type="text" required />
            </div>
            <div>
                <Label>Barcode</Label>
                <Input v-model="form.barcode" type="text" />
            </div>
            <div>
                <Label>Category</Label>
                <Select v-model="form.category_id">
                    <SelectItem v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </SelectItem>
                </Select>
            </div>
            <div>
                <Label>Image</Label>
                <input type="file" @change="handleFileUpload" class="w-full border p-2" />
            </div>
            <div>
                <Label>COGS</Label>
                <Input v-model="form.cogs" type="number" required />
            </div>
            <div>
                <Label>Price</Label>
                <Input v-model="form.price" type="number" required />
            </div>
            <div>
                <Label>Minimum Stock</Label>
                <Input v-model="form.min_stock" type="number" required />
            </div>
            <div>
                <Label>Weight (kg)</Label>
                <Input v-model="form.weight" type="number" />
            </div>
            <div>
                <Label>Description</Label>
                <Input v-model="form.description" type="text" />
            </div>

            <!-- 🛠 Variations Section -->
            <div>
                <h2 class="text-lg font-semibold">Variations</h2>
                <div class="flex gap-2">
                    <Input v-model="variationName" placeholder="e.g. Size, Color" />
                    <Input v-model="variationOptions" placeholder="Options (comma-separated)" />
                    <Button @click="addVariation" class="bg-green-500 text-white">➕ Add</Button>
                </div>

                <!-- List Variations -->
                <div v-for="(variation, index) in form.variations" :key="index" class="mt-2 rounded bg-gray-100 p-2">
                    <strong>{{ variation.name }}</strong
                    >: {{ variation.options.join(', ') }}
                    <Button size="sm" @click="removeVariation(index)" class="ml-2 bg-red-500 text-white">❌</Button>
                </div>
            </div>

            <Button type="submit" class="bg-blue-500 text-white">Save Item</Button>
        </form>
    </div>
</template>
