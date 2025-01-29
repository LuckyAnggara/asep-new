<script setup>
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { ReloadIcon } from '@radix-icons/vue';
import { useToast } from '@/components/ui/toast/use-toast';
import { ToastAction } from '@/components/ui/toast';
import GuestLayout from '@/layouts/GuestLayout.vue';
import { h } from 'vue';
import { useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const { toast } = useToast();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onSuccess: () => {
            toast({
                title: 'Success!',
                description: 'You have been logged in.',
            });
        },
        onFinish: () => form.reset('password'),
        onError: (errors) =>
            toast({
                title: 'Uh oh! Something went wrong.',
                description: errors,
                variant: 'destructive',
            }),
    });
};
</script>

<template>
    <GuestLayout>
        <div
            class="w-full lg:grid lg:min-h-[600px] lg:grid-cols-2 xl:min-h-[800px]"
        >
            <div class="flex items-center justify-center py-12">
                <div class="mx-auto grid w-[350px] gap-6">
                    <div class="grid gap-2 text-center">
                        <h1 class="text-3xl font-bold">Login</h1>
                        <p class="text-balance text-muted-foreground">
                            Enter your email below to login to your account
                        </p>
                    </div>

                    <form @submit.prevent="submit">
                        <div class="grid gap-4">
                            <div class="grid gap-2">
                                <Label for="email">Email</Label>
                                <Input
                                    id="email"
                                    type="email"
                                    class="mt-1 block w-full"
                                    v-model="form.email"
                                    required
                                    autofocus
                                    autocomplete="username"
                                />
                                <div v-if="form.errors.email">
                                    {{ form.errors.email }}
                                </div>
                            </div>
                            <div class="grid gap-2">
                                <div class="flex items-center">
                                    <Label for="password">Password</Label>
                                    <a
                                        v-if="canResetPassword"
                                        :href="route('password.request')"
                                        class="ml-auto inline-block text-sm underline"
                                    >
                                        Forgot your password?
                                    </a>
                                </div>
                                <Input
                                    id="password"
                                    type="password"
                                    class="mt-1 block w-full"
                                    v-model="form.password"
                                    required
                                    autocomplete="current-password"
                                />
                            </div>
                            <Button
                                type="submit"
                                class="w-full"
                                :disabled="form.processing"
                            >
                                <span v-if="form.processing" class="flex">
                                    <ReloadIcon
                                        class="mr-2 h-4 w-4 animate-spin"
                                    />
                                    Please wait
                                </span>

                                <span v-else>Login</span>
                            </Button>
                        </div>
                        <div class="mt-4 text-center text-sm">
                            Don't have an account?
                            <a href="#" class="underline"> Sign up </a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="hidden bg-muted lg:block">
                <img
                    src="https://img.freepik.com/free-vector/login-concept-illustration_114360-739.jpg?t=st=1737732583~exp=1737736183~hmac=1326d8daa361f08fca331f7dfe00a3214f8174795cd8d5c5b1a845f3ba817c97&w=826"
                    alt="Image"
                    width="1920"
                    height="1080"
                    class="h-full w-full object-cover dark:brightness-[0.2] dark:grayscale"
                />
            </div>
        </div>
    </GuestLayout>
</template>
