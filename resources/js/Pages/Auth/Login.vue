<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="space-y-6">
            <div class="space-y-4">
                <span class="badge-pill">Welcome back</span>
                <div class="space-y-2">
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-950">
                        Sign in to your account
                    </h1>
                    <p class="text-sm leading-7 text-slate-600 sm:text-base">
                        Access your storefront workspace, orders and merchant tools with the same polished experience as the new marketplace.
                    </p>
                </div>
            </div>

            <div
                v-if="status"
                class="rounded-brand-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700"
            >
                {{ status }}
            </div>

            <form class="space-y-5" @submit.prevent="submit">
                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                        Email address
                    </label>
                    <div class="field-shell">
                        <svg class="h-5 w-5 shrink-0 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M2.94 5.5A2 2 0 014.76 4.5h10.48a2 2 0 011.82 1L10 9.88 2.94 5.5Z" />
                            <path d="M2 7.12V13.5a2 2 0 002 2h12a2 2 0 002-2V7.12l-7.45 4.67a1 1 0 01-1.1 0L2 7.12Z" />
                        </svg>
                        <TextInput
                            id="email"
                            v-model="form.email"
                            type="email"
                            class="block w-full"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="you@company.com"
                        />
                    </div>
                    <InputError class="mt-2 text-sm" :message="form.errors.email" />
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">
                        Password
                    </label>
                    <div class="field-shell">
                        <svg class="h-5 w-5 shrink-0 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M10 2a4 4 0 00-4 4v2H5a2 2 0 00-2 2v5a2 2 0 002 2h10a2 2 0 002-2v-5a2 2 0 00-2-2h-1V6a4 4 0 00-4-4Zm2 6V6a2 2 0 10-4 0v2h4Z" clip-rule="evenodd" />
                        </svg>
                        <TextInput
                            id="password"
                            v-model="form.password"
                            type="password"
                            class="block w-full"
                            required
                            autocomplete="current-password"
                            placeholder="Enter your password"
                        />
                    </div>
                    <InputError class="mt-2 text-sm" :message="form.errors.password" />
                </div>

                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <label class="inline-flex items-center gap-3 text-sm text-slate-600">
                        <Checkbox name="remember" v-model:checked="form.remember" />
                        <span>Keep me signed in on this device</span>
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm font-medium text-brand-700 hover:text-brand-800"
                    >
                        Forgot your password?
                    </Link>
                </div>

                <button
                    type="submit"
                    class="btn-primary w-full justify-center py-3.5 text-sm"
                    :class="{ 'opacity-60': form.processing }"
                    :disabled="form.processing"
                >
                    Log in
                </button>
            </form>

            <div class="rounded-brand-md bg-slate-50 px-4 py-4 text-sm text-slate-600">
                New to the platform?
                <Link :href="route('register')" class="font-semibold text-brand-700 hover:text-brand-800">
                    Create an account
                </Link>
                to start selling with a conversion-focused storefront.
            </div>
        </div>
    </GuestLayout>
</template>
