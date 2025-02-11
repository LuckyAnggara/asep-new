<script setup>
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Textarea } from '@/Components/ui/textarea';
import { Select, SelectItem } from '@/Components/ui/select';

const props = defineProps({
    item: Object,
    categories: Array,
});

// Form setup with Inertia.js
const form = useForm({
    sku: props.item.sku,
    name: props.item.name,
    barcode: props.item.barcode,
    category_id: props.item.category_id,
    image: null,
    cogs: props.item.cogs,
    price: props.item.price,
    min_stock: props.item.min_stock,
    weight: props.item.weight,
    description: props.item.description,
});

// Handle file upload
const handleFileUpload = (event) => {
    form.image = event.target.files[0];
};

// Submit update form
const submit = () => {
    form.post(route('items.update', props.item.id), { _method: 'put' });
};
</script>

<template>
    <div class="mx-auto max-w-3xl rounded-lg bg-white p-6 shadow-lg">
        <h1 class="mb-4 text-2xl font-bold">📝 Edit Item</h1>

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
                <Label>Current Image</Label>
                <img v-if="item.image" :src="`/storage/${item.image}`" class="h-20 w-20 rounded-md border object-cover" />
            </div>
            <div>
                <Label>Upload New Image</Label>
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
                <Textarea v-model="form.description" rows="3" />
            </div>

            <Button type="submit" class="bg-green-500 text-white">Save Changes</Button>
        </form>
    </div>
</template>
