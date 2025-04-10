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
import { useI18n } from 'vue-i18n';

const { t, locale } = useI18n();
const props = defineProps({ company: Object, coa: Array });
const page = usePage();
const { system, store } = useColorMode();
import { toast } from 'vue-sonner';
// const { toast } = useToast();
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
    language: props.company.language || 'en',
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

        onProgress: () => {
            onProses.value = true;
        },
        onSuccess: () => {
            toast.success(t('settings.success_message'));
        },
        onError: (errors) => {
            toast.error(t('settings.error_message'));
        },
    });
};

const submitPreferences = () => {
    locale.value = formPreferences.language;
    formPreferences.put(route('settings.updatePreferences', props.company.id), {
        preserveState: true,
        replace: true,
        async: true,
        onSuccess: () => {
            toast.success(t('settings.success_message'));
        },
        onError: (errors) => {
            toast.error(t('settings.error_message'));
        },
    });
};

const updateAccount = () => {
    formAccount.put(route('settings.updateAccount', props.company.id), {
        preserveState: true,
        replace: true,
        async: true,
        onSuccess: () => {
            toast.success(t('settings.success_message'));
        },
        onError: (errors) => {
            toast.error(t('settings.error_message'));
        },
    });
};

const updatePassword = () => {
    formSecurity.put(route('user.updatePassword'), {
        preserveScroll: true,
        replace: true,
        async: true,
        onSuccess: () => {
            toast.success(t('settings.success_message'));
        },
        onError: (errors) => {
            toast.error(t('settings.error_message'));
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
        toast.error(t('settings.error_message_size'));
        event.target.value = null; // Reset input
        return;
    }

    // Validasi tipe file
    if (!allowedTypes.includes(selectedFile.type)) {
        fileError.value = true;
        toast.error(t('settings.error_message_type'));

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
            <h1 class="text-lg font-semibold md:text-2xl">{{ $t('settings.title') }}</h1>
        </div>
        <div class="flex-1 flex-col items-center justify-center rounded-lg border border-dashed p-6 shadow-sm">
            <Tabs default-value="company" class="mb-5">
                <TabsList>
                    <TabsTrigger value="company">{{ $t('settings.tabs.company') }}</TabsTrigger>
                    <TabsTrigger value="preferences">{{ $t('settings.tabs.preferences') }}</TabsTrigger>
                    <TabsTrigger value="security">{{ $t('settings.tabs.security') }}</TabsTrigger>
                    <TabsTrigger value="account">{{ $t('settings.tabs.account') }}</TabsTrigger>
                </TabsList>
                <!-- Company Settings -->
                <TabsContent value="company" class="my-6">
                    <h3 class="mb-2 text-lg font-semibold">{{ $t('settings.company.title') }}</h3>
                    <form @submit.prevent="submitCompany" class="">
                        <div class="mb-6 flex flex-row space-x-4">
                            <div class="w-full space-y-4 lg:w-1/3">
                                <div class="grid gap-4">
                                    <div>
                                        <Label for="name">{{ $t('settings.company.name') }}</Label>
                                        <Input id="name" v-model="formCompany.name" required />
                                    </div>

                                    <div>
                                        <Label for="slogan">{{ $t('settings.company.slogan') }}</Label>
                                        <Input id="slogan" v-model="formCompany.slogan" />
                                    </div>

                                    <div>
                                        <Label for="address">{{ $t('settings.company.address') }}</Label>
                                        <Textarea class="resize-none" id="address" v-model="formCompany.address" />
                                    </div>

                                    <div>
                                        <Label for="phone">{{ $t('settings.company.phone') }}</Label>
                                        <Input id="phone" v-model="formCompany.phone" />
                                    </div>

                                    <div>
                                        <Label for="email">{{ $t('settings.company.email') }}</Label>
                                        <Input id="email" v-model="formCompany.email" />
                                    </div>

                                    <div>
                                        <Label for="website">{{ $t('settings.company.website') }}</Label>
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
                                    <Label for="logo">{{ $t('settings.company.logo') }}</Label>
                                    <Input id="logo" type="file" @change="handleFileUpload" />
                                    <p v-if="fileError" class="text-[0.8rem] font-medium text-destructive">{{ $t('settings.error_message_size') }}</p>
                                </div>
                            </div>
                        </div>
                        <Separator />
                        <div class="mt-6">
                            <Button type="submit" :disabled="onProses">
                                <span v-if="onProses" class="flex">
                                    <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                                    {{ $t('settings.please_wait') }}
                                </span>

                                <span v-else>{{ $t('settings.update') }}</span>
                            </Button>
                        </div>
                    </form>
                </TabsContent>

                <!-- Preferences -->
                <TabsContent value="preferences" class="my-6">
                    <h3 class="mb-2 text-lg font-semibold">{{ $t('settings.preferences.title') }}</h3>
                    <form @submit.prevent="submitPreferences">
                        <div class="flex flex-row space-x-6">
                            <div class="mb-6 grid w-full gap-4 lg:w-1/3">
                                <div>
                                    <Label for="language">{{ $t('settings.preferences.language') }}</Label>
                                    <Select id="language" v-model="formPreferences.language">
                                        <SelectTrigger>
                                            <SelectValue :placeholder="$t('settings.preferences.select_language')" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="en"> English </SelectItem>
                                            <SelectItem value="id"> Indonesia </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                                <div>
                                    <Label for="currency">{{ $t('settings.preferences.currency') }}</Label>
                                    <Select id="currency" v-model="formPreferences.currency">
                                        <SelectTrigger>
                                            <SelectValue :placeholder="$t('settings.preferences.select_currency')" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="IDR"> IDR (Indonesian Rupiah) </SelectItem>
                                            <SelectItem value="USD"> USD (US Dollar) </SelectItem>
                                            <SelectItem value="EUR"> EUR (Euro) </SelectItem>
                                        </SelectContent>
                                    </Select>
                                </div>
                                <div>
                                    <Label for="currency">{{ $t('settings.preferences.decimal') }}</Label>
                                    <NumberField v-model="formPreferences.decimal">
                                        <NumberFieldContent>
                                            <NumberFieldDecrement />
                                            <NumberFieldInput />
                                            <NumberFieldIncrement />
                                        </NumberFieldContent>
                                    </NumberField>
                                </div>
                                <div>
                                    <Label for="timezone">{{ $t('settings.preferences.timezone') }}</Label>
                                    <Select id="timezone" v-model="formPreferences.timezone">
                                        <SelectTrigger>
                                            <SelectValue :placeholder="$t('settings.preferences.select_timezone')" />
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
                                    <Label for="language">{{ $t('settings.preferences.preview_currency') }}</Label>
                                    <p>{{ formatCurrency(10000) }}</p>
                                </div>
                            </div>
                        </div>
                        <Separator />
                        <div class="mt-6">
                            <Button type="submit" :disabled="formPreferences.processing">
                                <span v-if="formPreferences.processing" class="flex">
                                    <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                                    {{ $t('settings.please_wait') }}
                                </span>

                                <span v-else> {{ $t('settings.update') }}</span>
                            </Button>
                        </div>
                    </form>
                </TabsContent>

                <!-- Security -->
                <TabsContent value="security" class="my-6">
                    <h3 class="mb-2 text-lg font-semibold">{{ $t('settings.security.title') }}</h3>
                    <form @submit.prevent="updatePassword">
                        <div class="mb-6 grid w-full gap-4 lg:w-1/3">
                            <div>
                                <Label for="current_password">{{ $t('settings.security.current_password') }}</Label>
                                <Input type="password" id="current_password" v-model="formSecurity.current_password" required />
                            </div>

                            <div>
                                <Label for="new_password">{{ $t('settings.security.new_password') }}</Label>
                                <Input type="password" id="new_password" v-model="formSecurity.new_password" required />
                            </div>

                            <div>
                                <Label for="confirm_password">{{ $t('settings.security.confirm_password') }}</Label>
                                <Input type="password" id="confirm_password" v-model="formSecurity.new_password_confirmation" required />
                            </div>
                        </div>

                        <Separator />
                        <div class="mt-6">
                            <Button type="submit" :disabled="formSecurity.processing">
                                <span v-if="formSecurity.processing" class="flex">
                                    <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                                    {{ $t('settings.please_wait') }}
                                </span>

                                <span v-else> {{ $t('settings.update') }}</span>
                            </Button>
                        </div>
                    </form>
                </TabsContent>

                <!-- Account -->
                <TabsContent value="account" class="my-6">
                    <h3 class="mb-2 text-lg font-semibold">{{ $t('settings.account.title') }}</h3>
                    <form @submit.prevent="updateAccount">
                        <div class="mb-6 grid w-full gap-4 lg:w-1/3">
                            <div>
                                <Label for="re">{{ $t('settings.account.retained_earning_account') }}</Label>
                                <Select id="re" v-model="formAccount.retained_earning_id" clearable>
                                    <SelectTrigger>
                                        <SelectValue :placeholder="$t('settings.account.select_account')" />
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
                                    {{ $t('settings.please_wait') }}
                                </span>

                                <span v-else> {{ $t('settings.update') }}</span>
                            </Button>
                        </div>
                    </form>
                </TabsContent>
            </Tabs>
        </div>
    </AuthenticatedLayout>
</template>
