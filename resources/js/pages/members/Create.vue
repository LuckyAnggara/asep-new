<template>
    <Head title="Members" />
    <AuthenticatedLayout>
        <div class="flex items-center">
            <h1 class="text-lg font-semibold md:text-2xl">Tambah Member</h1>
        </div>

        <div
            class="flex flex-col items-center space-y-4 rounded-lg border border-dashed p-6 shadow-sm lg:flex-row lg:space-x-6"
        >
            <form class="w-full space-y-4 lg:w-1/3">
                <FormField
                    v-slot="{ componentField }"
                    name="name"
                    :validate-on-blur="!isFieldDirty"
                >
                    <FormItem v-auto-animate>
                        <FormLabel>Nama Lengkap</FormLabel>
                        <FormControl>
                            <Input
                                type="text"
                                placeholder="Isi dengan nama lengkap"
                                v-bind="componentField"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField
                    v-slot="{ componentField }"
                    name="gender"
                    :validate-on-blur="!isFieldDirty"
                >
                    <FormItem>
                        <FormLabel>Jenis Kelamin</FormLabel>
                        <Select v-bind="componentField">
                            <FormControl>
                                <SelectTrigger>
                                    <SelectValue
                                        placeholder="Pilih jenis kelamin"
                                    />
                                </SelectTrigger>
                            </FormControl>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem value="male">
                                        Laki - Laki
                                    </SelectItem>
                                    <SelectItem value="female">
                                        Perempuan
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField
                    v-slot="{ componentField }"
                    name="address"
                    :validate-on-blur="!isFieldDirty"
                >
                    <FormItem>
                        <FormLabel>Alamat</FormLabel>
                        <FormControl>
                            <Textarea
                                class="resize-none"
                                placeholder="Isi dengan alamat lengkap"
                                v-bind="componentField"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField name="date_of_birth">
                    <FormItem class="flex flex-col">
                        <FormLabel>Tanggal Lahir</FormLabel>
                        <Popover>
                            <PopoverTrigger as-child>
                                <FormControl>
                                    <Button
                                        variant="outline"
                                        :class="
                                            cn(
                                                'ps-3 text-start font-normal',
                                                !dobValue &&
                                                    'text-muted-foreground',
                                            )
                                        "
                                    >
                                        <span>{{
                                            dobValue
                                                ? df.format(toDate(dobValue))
                                                : 'Isi dengan tanggal lahir'
                                        }}</span>
                                        <CalendarIcon
                                            class="ms-auto h-4 w-4 opacity-50"
                                        />
                                    </Button>
                                    <input hidden />
                                </FormControl>
                            </PopoverTrigger>
                            <PopoverContent class="w-auto p-0">
                                <Calendar
                                    v-model:placeholder="dobPlaceholder"
                                    v-model="dobValue"
                                    calendar-label="Date of birth"
                                    initial-focus
                                    :min-value="new CalendarDate(1900, 1, 1)"
                                    :max-value="today(getLocalTimeZone())"
                                    @update:model-value="
                                        (v) => {
                                            if (v) {
                                                setFieldValue(
                                                    'date_of_birth',
                                                    v.toString(),
                                                );
                                            } else {
                                                setFieldValue(
                                                    'date_of_birth',
                                                    undefined,
                                                );
                                            }
                                        }
                                    "
                                />
                            </PopoverContent>
                        </Popover>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <Separator />
                <FormField
                    v-slot="{ componentField }"
                    name="phone"
                    :validate-on-blur="!isFieldDirty"
                >
                    <FormItem>
                        <FormLabel>Nomor Telepon</FormLabel>
                        <FormControl>
                            <Input
                                type="text"
                                placeholder="Isi dengan nomor telepon yang dapat di hubungi"
                                v-bind="componentField"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField
                    v-slot="{ componentField }"
                    name="email"
                    :validate-on-blur="!isFieldDirty"
                >
                    <FormItem>
                        <FormLabel>Email</FormLabel>
                        <FormControl>
                            <Input
                                type="email"
                                placeholder="Isi dengan Email"
                                v-bind="componentField"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <Separator />
                <FormField
                    v-slot="{ componentField }"
                    name="job_title"
                    :validate-on-blur="!isFieldDirty"
                >
                    <FormItem>
                        <FormLabel>Nama Jabatan</FormLabel>
                        <FormControl>
                            <Input
                                type="text"
                                placeholder="Isi dengan nama jabatan"
                                v-bind="componentField"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField
                    v-slot="{ componentField }"
                    name="department"
                    :validate-on-blur="!isFieldDirty"
                >
                    <FormItem>
                        <FormLabel>Departemen</FormLabel>
                        <FormControl>
                            <Input
                                type="text"
                                placeholder="Isi dengan nama departemen"
                                v-bind="componentField"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <Separator />
                <FormField name="join_date">
                    <FormItem class="flex flex-col">
                        <FormLabel>Tanggal Bergabung Member</FormLabel>
                        <Popover>
                            <PopoverTrigger as-child>
                                <FormControl>
                                    <Button
                                        variant="outline"
                                        :class="
                                            cn(
                                                'ps-3 text-start font-normal',
                                                !jdPlaceholder &&
                                                    'text-muted-foreground',
                                            )
                                        "
                                    >
                                        <span>{{
                                            jdPlaceholder
                                                ? df.format(
                                                      toDate(jdPlaceholder),
                                                  )
                                                : 'Isi dengan tanggal lahir'
                                        }}</span>
                                        <CalendarIcon
                                            class="ms-auto h-4 w-4 opacity-50"
                                        />
                                    </Button>
                                    <input hidden />
                                </FormControl>
                            </PopoverTrigger>
                            <PopoverContent class="w-auto p-0">
                                <Calendar
                                    v-model:placeholder="jdPlaceholder"
                                    v-model="jdValue"
                                    calendar-label="Date of birth"
                                    initial-focus
                                    :min-value="new CalendarDate(1900, 1, 1)"
                                    :max-value="today(getLocalTimeZone())"
                                    @update:model-value="
                                        (v) => {
                                            if (v) {
                                                setFieldValue(
                                                    'join_date',
                                                    v.toString(),
                                                );
                                            } else {
                                                setFieldValue(
                                                    'join_date',
                                                    undefined,
                                                );
                                            }
                                        }
                                    "
                                />
                            </PopoverContent>
                        </Popover>
                        <FormMessage />
                    </FormItem>
                </FormField>
            </form>
            <Separator class="hidden lg:block" orientation="vertical" />
            <form class="w-full space-y-4 place-self-start lg:w-1/3">
                <img
                    :src="previewImage ?? defaultAvatar"
                    alt="Image"
                    class="object-cover dark:brightness-[0.7]"
                />
                <FormField name="photo">
                    <FormItem>
                        <FormLabel :class="fileError ? 'text-destructive' : ''"
                            >Photo</FormLabel
                        >
                        <FormControl>
                            <Input
                                type="file"
                                id="file"
                                name="file"
                                @change="handleFileUpload"
                            />
                        </FormControl>
                        <p
                            v-if="fileError"
                            class="text-[0.8rem] font-medium text-destructive"
                        >
                            Maksimal size 2MB
                        </p>
                    </FormItem>
                </FormField>
            </form>
        </div>
        <Separator />
        <div class="block">
            <Button
                type="button"
                @click="openAlertDialog = true"
                :disabled="onProses"
            >
                <span v-if="onProses" class="flex">
                    <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                    Please wait
                </span>

                <span v-else>Submit</span>
            </Button>
        </div>
        <AlertDialog v-model:open="openAlertDialog">
            <AlertDialogContent>
                <AlertDialogHeader>
                    <AlertDialogTitle>Confirmation</AlertDialogTitle>
                    <AlertDialogDescription>
                        Data Anda akan dikirim. Periksa kembali apakah semuanya
                        sudah sesuai. Lanjutkan?
                    </AlertDialogDescription>
                </AlertDialogHeader>
                <AlertDialogFooter>
                    <AlertDialogCancel>Cancel</AlertDialogCancel>
                    <Button @click="onSubmit" :disabled="onProses">
                        <span v-if="onProses" class="flex">
                            <ReloadIcon class="mr-2 h-4 w-4 animate-spin" />
                            Please wait
                        </span>

                        <span v-else>Proses</span>
                    </Button>
                </AlertDialogFooter>
            </AlertDialogContent>
        </AlertDialog>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import { vAutoAnimate } from '@formkit/auto-animate/vue';
defineProps({
    members: Object,
});
import { Button } from '@/components/ui/button';
import { Separator } from '@/components/ui/separator';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectLabel,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Input } from '@/components/ui/input';
import {
    FormControl,
    FormDescription,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from '@/components/ui/form';
import { Textarea } from '@/components/ui/textarea';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';

import { Calendar } from '@/components/ui/calendar';
import {
    CalendarDate,
    DateFormatter,
    getLocalTimeZone,
    parseDate,
    today,
} from '@internationalized/date';
import { CalendarIcon, ReloadIcon } from '@radix-icons/vue';
import { toDate } from 'radix-vue/date';
import { cn } from '@/lib/utils';
import { useToast } from '@/components/ui/toast/use-toast';

import { toTypedSchema } from '@vee-validate/zod';
import { useForm as formValidate, useField, defineRule } from 'vee-validate';
import { required, size } from '@vee-validate/rules';

import { Head, router, useForm, usePage } from '@inertiajs/vue3';

import { computed, h, ref } from 'vue';
import * as z from 'zod';

defineRule('required', required);
defineRule('size', size);

const { toast } = useToast();
const fileError = ref(false);
const openAlertDialog = ref(false);
const defaultAvatar =
    'https://s3.amazonaws.com/37assets/svn/765-default-avatar.png';
const previewImage = ref(null);
const page = usePage();

const formSchema = toTypedSchema(
    z.object({
        name: z.string().min(2).max(50),
        address: z.string().min(5),
        gender: z.string('Required'),
        email: z.string().email().min(1, 'Required'),
        phone: z.string().min(1, 'Required'),
        job_title: z.string('Required'),
        date_of_birth: z
            .string()
            .refine((v) => v, { message: 'Tanggal lahir di butuhkan.' }),
        join_date: z.string(),
    }),
);

const { isFieldDirty, handleSubmit, setFieldValue, values } = formValidate({
    validationSchema: formSchema,
    initialValues: {},
});

const df = new DateFormatter('en-US', {
    dateStyle: 'long',
});
const dobPlaceholder = ref();
const jdPlaceholder = ref();
const dobValue = computed({
    get: () =>
        values.date_of_birth ? parseDate(values.date_of_birth) : undefined,
    set: (val) => val,
});
const jdValue = computed({
    get: () => (values.join_date ? parseDate(values.join_date) : undefined),
    set: (val) => val,
});

const onProses = ref(false);

const onSubmit = handleSubmit((a) => {
    const formData = new FormData();

    // Append form values to FormData
    formData.append('name', values.name || '');
    formData.append('address', values.address || '');
    formData.append('gender', values.gender || '');
    formData.append('email', values.email || '');
    formData.append('phone', values.phone || '');
    formData.append('job_title', values.job_title || '');
    formData.append('department', values.job_title || '');
    formData.append('date_of_birth', values.date_of_birth || '');
    formData.append('join_date', values.join_date || '');

    // If additional fields like 'profile_pic' exist, you can append them
    if (values.photo) {
        formData.append('photo', values.photo);
    }
    // form.post(route('members.store'), {
    router.visit('/members', {
        _token: page.props.csrf_token,
        method: 'post',
        preserveState: true,
        data: formData,
        replace: true,
        forceFormData: true,
        onFinish: () => {
            openAlertDialog.value = false;
            onProses.value = false;
        },
        onSuccess: () => {
            toast({
                title: 'Success',
                description: `Data berhasil di simpan`,
            });
        },
        onProgress: () => {
            onProses.value = true;
        },
        onError: (errors) => {
            console.info(errors);
            toast({
                title: 'Error',
                description: errors,
                variant: 'destructive',
            });
        },
    });
});

const handleFileUpload = (event) => {
    const selectedFile = event.target.files[0];
    if (selectedFile.size > 2048000) {
        fileError.value = true;
        toast({
            title: 'Error',
            description: 'File size is too large',
            variant: 'destructive',
        });
        if (!event.target.files[0]) {
            event.target.value = null;
        }
    } else if (selectedFile.type !== 'image/jpeg') {
        fileError.value = true;
        toast({
            title: 'Error',
            description: 'File type is not supported',
            variant: 'destructive',
        });
        if (!event.target.files[0]) {
            event.target.value = null;
        }
    } else {
        fileError.value = false;
        if (selectedFile) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImage.value = e.target.result;
            };
            reader.readAsDataURL(selectedFile);
        }
        setFieldValue('photo', selectedFile);
    }
};
</script>
