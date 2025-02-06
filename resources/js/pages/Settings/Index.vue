<script setup>
import { computed, ref, watch } from 'vue';
import { router, useForm, usePage } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import Separator from '@/Components/ui/separator/Separator.vue';
import { Select, SelectContent, SelectGroup, SelectItem, SelectLabel, SelectTrigger, SelectValue } from '@/Components/ui/select';
import { NumberField, NumberFieldContent, NumberFieldDecrement, NumberFieldIncrement, NumberFieldInput } from '@/Components/ui/number-field';
import { Tabs, TabsList, TabsTrigger, TabsContent } from '@/Components/ui/tabs';
import { useColorMode, watchDeep } from '@vueuse/core';
import Textarea from '@/Components/ui/textarea/Textarea.vue';
import { ReloadIcon } from '@radix-icons/vue';
import { useToast } from '@/Components/ui/toast';
import { Trash2, X } from 'lucide-vue-next';

const props = defineProps({ company: Object, coa: Array });
const page = usePage();
const { system, store } = useColorMode();
const { toast } = useToast();
const fileError = ref('');
const onProses = ref(false);
const previewImage = ref(null);
const allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml', 'image/bmp']; // Semua tipe gambar yang didukung

const formCompany = useForm({
    name: props.company.name || '',
    slogan: props.company.slogan || '',
    logo: props.company.logo || null,
    address: props.company.address || '',
    phone: props.company.phone || '',
    email: props.company.email || '',
    website: props.company.website || '',
});

const formPreferences = useForm({
    currency: props.company.currency || 'IDR',
    decimal: props.company.decimal || 2,
    language: props.company.language || 'id',
    timezone: props.company.timezone || 'Asia/Jakarta',
});

const formSecurity = useForm({
    current_password: '',
    new_password: '',
    new_password_confirmation: '',
});

const formAccount = useForm({
    retained_earning_id: props.company.retained_earning_id || '',
});

const getLogoImage = () => (previewImage.value == null ? (formCompany.logo ? `/storage/${formCompany.logo}` : '') : previewImage.value);

const submitCompany = () => {
    const formData = new FormData();

    // Append form values to FormData
    formData.append('_method', 'put');
    formData.append('name', formCompany.name || '');
    formData.append('slogan', formCompany.slogan || '');
    formData.append('address', formCompany.address || '');
    formData.append('phone', formCompany.phone || '');
    formData.append('email', formCompany.email || '');
    formData.append('website', formCompany.website || '');

    if (previewImage.value) {
        formData.append('logo', formCompany.logo);
    }
    // form.post(route('members.store'), {
    router.post(`/settings/company/${props.company.id}`, formData, {
        _token: page.props.csrf_token,
        preserveState: true,
        replace: true,
        async: true,
        forceFormData: true,
        onFinish: () => {
            onProses.value = false;
        },
        onSuccess: () => {
            toast({
                title: 'Success',
                description: `Data berhasil di update`,
            });
        },
        onProgress: () => {
            onProses.value = true;
        },
        onError: (errors) => {
            toast({
                title: 'Error',
                description: errors,
                variant: 'destructive',
            });
        },
    });
};

const submitPreferences = () => {
    formPreferences.put(route('settings.updatePreferences', props.company.id), {
        preserveState: true,
        replace: true,
        async: true,
        onSuccess: () => {
            toast({
                title: 'Success',
                description: `Data berhasil di update`,
            });
        },
        onError: (errors) => {
            toast({
                title: 'Error',
                description: errors,
                variant: 'destructive',
            });
        },
    });
};

const updateAccount = () => {
    formAccount.put(route('settings.updateAccount', props.company.id), {
        preserveState: true,
        replace: true,
        async: true,
        onSuccess: () => {
            toast({
                title: 'Success',
                description: `Data berhasil di update`,
            });
        },
        onError: (errors) => {
            toast({
                title: 'Error',
                description: errors,
                variant: 'destructive',
            });
        },
    });
};

const updatePassword = () => {
    formSecurity.put(route('user.updatePassword'), {
        preserveScroll: true,
        replace: true,
        async: true,
        onSuccess: () => {
            toast({
                title: 'Success',
                description: `Password berhasil diperbaharui`,
            });
            formSecurity.reset();
        },
        onError: (errors) => {
            toast({
                title: 'Error',
                description: errors,
                variant: 'destructive',
            });
        },
    });
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: formPreferences.currency,
        minimumFractionDigits: formPreferences.decimal,
    }).format(value);
};

const handleFileUpload = (event) => {
    const selectedFile = event.target.files[0];

    if (!selectedFile) return;

    // Validasi ukuran file (maks 4MB)
    if (selectedFile.size > 4096000) {
        fileError.value = true;
        toast({
            title: 'Error',
            description: 'File size exceeds 4MB',
            variant: 'destructive',
        });
        event.target.value = null; // Reset input
        return;
    }

    // Validasi tipe file
    if (!allowedTypes.includes(selectedFile.type)) {
        fileError.value = true;
        toast({
            title: 'Error',
            description: 'Unsupported file type. Allowed types: JPEG, PNG, GIF, WEBP, SVG, BMP.',
            variant: 'destructive',
        });
        event.target.value = null; // Reset input
        return;
    }

    // Jika semua validasi lolos, tampilkan preview gambar
    fileError.value = false;
    const reader = new FileReader();
    reader.onload = (e) => {
        previewImage.value = e.target.result;
    };
    reader.readAsDataURL(selectedFile);

    // Simpan file ke form
    formCompany.logo = selectedFile;
};

function removeLogo() {
    formCompany.logo = props.company.logo;
    previewImage.value = null;
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">Setting Apps</h1>
        </div>
        <div class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm">
            <Tabs default-value="company" class="mb-5">
                <TabsList>
                    <TabsTrigger value="company">Company</TabsTrigger>
                    <TabsTrigger value="preferences">Preferences</TabsTrigger>
                    <TabsTrigger value="security">Security</TabsTrigger>
                    <TabsTrigger value="account">Account</TabsTrigger>
                </TabsList>

                <!-- Company Settings -->
                <TabsContent value="company" class="my-6">
                    <h3 class="mb-2 text-lg font-semibold">Company Settings</h3>
                    <form @submit.prevent="submitCompany" class="">
                        <div class="mb-6 flex flex-row space-x-4">
                            <div class="w-full space-y-4 lg:w-1/3">
                                <div class="grid gap-4">
                                    <div>
                                        <Label for="name">Company Name</Label>
                                        <Input id="name" v-model="formCompany.name" required />
                                    </div>

                                    <div>
                                        <Label for="slogan">Slogan</Label>
                                        <Input id="slogan" v-model="formCompany.slogan" />
                                    </div>

                                    <div>
                                        <Label for="address">Address</Label>
                                        <Textarea class="resize-none" id="address" v-model="formCompany.address" />
                                    </div>

                                    <div>
                                        <Label for="phone">Phone</Label>
                                        <Input id="phone" v-model="formCompany.phone" />
                                    </div>

                                    <div>
                                        <Label for="email">Email</Label>
                                        <Input id="email" v-model="formCompany.email" />
                                    </div>

                                    <div>
                                        <Label for="website">Website</Label>
                                        <Input id="website" v-model="formCompany.website" />
                                    </div>
                                </div>
                            </div>
                            <Separator class="hidden lg:block" orientation="vertical" />
                            <div class="w-full space-y-4 place-self-start lg:w-1/3">
                                <div class="flex flex-row space-x-4">
                                    <img :src="getLogoImage()" alt="Image" class="h-60 w-60 object-cover dark:brightness-[0.7]" />
                                    <Trash2 v-if="previewImage" class="cursor-pointer" @click="removeLogo" />
                                </div>

                                <div>
                                    <Label for="logo">Logo</Label>
                                    <Input id="logo" type="file" @change="handleFileUpload" />
                                    <p v-if="fileError" class="text-[0.8rem] font-medium text-destructive">Maksimal size 4MB</p>
                                </div>
                            </div>
                        </div>
                        <Separator />
                        <div class="mt-6">
                            <Button type="submit" :disabled="onProses">
                                <span v-if="onProses" class="flex">
                                    <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                                    Please wait
                                </span>

                                <span v-else>Update</span>
                            </Button>
                        </div>
                    </form>
                </TabsContent>

                <!-- Preferences -->
                <TabsContent value="preferences" class="my-6">
                    <h3 class="mb-2 text-lg font-semibold">App Preferences</h3>
                    <form @submit.prevent="submitPreferences">
                        <div class="flex flex-row space-x-6">
                            <div class="mb-6 grid w-full gap-4 lg:w-1/3">
                                <div>
                                    <Label for="language">Bahasa</Label>
                                    <Select id="language" v-model="formPreferences.language">
                                        <SelectTrigger>
                                            <SelectValue placeholder="Pilih Bahasa" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="en"> English </SelectItem>
                                            <SelectItem value="id"> Indonesia </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                                <div>
                                    <Label for="currency">Default Currency</Label>
                                    <Select id="currency" v-model="formPreferences.currency">
                                        <SelectTrigger>
                                            <SelectValue placeholder="Pilih Currency" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="IDR"> IDR (Indonesian Rupiah) </SelectItem>
                                            <SelectItem value="USD"> USD (US Dollar) </SelectItem>
                                            <SelectItem value="EUR"> EUR (Euro) </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                                <div>
                                    <Label for="currency">Decimal</Label>
                                    <NumberField v-model="formPreferences.decimal">
                                        <NumberFieldContent>
                                            <NumberFieldDecrement />
                                            <NumberFieldInput />
                                            <NumberFieldIncrement />
                                        </NumberFieldContent>
                                    </NumberField>
                                </div>
                                <div>
                                    <Label for="timezone">Timezone</Label>
                                    <Select id="timezone" v-model="formPreferences.timezone">
                                        <SelectTrigger>
                                            <SelectValue placeholder="Pilih Timezone" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="Asia/Jakarta"> Asia/Jakarta </SelectItem>
                                            <SelectItem value="Asia/Makassar"> Asia/Makassar </SelectItem>
                                            <SelectItem value="Asia/Jayapura"> Asia/Jayapura </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                            </div>
                            <div class="items-center justify-center rounded-lg border border-dashed p-6 shadow-sm lg:w-1/3">
                                <div>
                                    <Label for="language">Preview Currency</Label>
                                    <p>{{ formatCurrency(10000) }}</p>
                                </div>
                            </div>
                        </div>
                        <Separator />
                        <div class="mt-6">
                            <Button type="submit" :disabled="formPreferences.processing">
                                <span v-if="formPreferences.processing" class="flex">
                                    <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                                    Please wait
                                </span>

                                <span v-else>Update</span>
                            </Button>
                        </div>
                    </form>
                </TabsContent>

                <!-- Security -->
                <TabsContent value="security" class="my-6">
                    <h3 class="mb-2 text-lg font-semibold">Security</h3>
                    <form @submit.prevent="updatePassword">
                        <div class="mb-6 grid w-full gap-4 lg:w-1/3">
                            <div>
                                <Label for="current_password">Current Password</Label>
                                <Input type="password" id="current_password" v-model="formSecurity.current_password" required />
                            </div>

                            <div>
                                <Label for="new_password">New Password</Label>
                                <Input type="password" id="new_password" v-model="formSecurity.new_password" required />
                            </div>

                            <div>
                                <Label for="confirm_password">Confirm Password</Label>
                                <Input type="password" id="confirm_password" v-model="formSecurity.new_password_confirmation" required />
                            </div>
                        </div>

                        <Separator />
                        <div class="mt-6">
                            <Button type="submit" :disabled="formSecurity.processing">
                                <span v-if="formSecurity.processing" class="flex">
                                    <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                                    Please wait
                                </span>

                                <span v-else>Update</span>
                            </Button>
                        </div>
                    </form>
                </TabsContent>

                <!-- Account -->
                <TabsContent value="account" class="my-6">
                    <h3 class="mb-2 text-lg font-semibold">Account</h3>
                    <form @submit.prevent="updateAccount">
                        <div class="mb-6 grid w-full gap-4 lg:w-1/3">
                            <div>
                                <Label for="re">Retained Earning Account</Label>
                                <Select id="re" v-model="formAccount.retained_earning_id" clearable>
                                    <SelectTrigger>
                                        <SelectValue placeholder="Pilih Account Retained Earning" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem :value="item.id" v-for="item in coa" :key="item.id"> {{ item.code }} - {{ item.name }} </SelectItem>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>

                        <Separator />
                        <div class="mt-6">
                            <Button type="submit" :disabled="formAccount.processing">
                                <span v-if="formAccount.processing" class="flex">
                                    <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                                    Please wait
                                </span>

                                <span v-else>Update</span>
                            </Button>
                        </div>
                    </form>
                </TabsContent>
            </Tabs>
        </div>
    </AuthenticatedLayout>
</template>
