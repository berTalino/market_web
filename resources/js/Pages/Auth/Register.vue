<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />

        <div class="space-y-6">
            <div class="space-y-4">
                <span class="badge-pill">Create account</span>
                <div class="space-y-2">
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-950">
                        Start your merchant workspace
                    </h1>
                    <p class="text-sm leading-7 text-slate-600 sm:text-base">
                        Set up your account to manage products, orders and customer flows inside the refreshed Platio-inspired marketplace.
                    </p>
                </div>
            </div>

            <form class="space-y-5" @submit.prevent="submit">
                <div>
                    <label for="name" class="mb-2 block text-sm font-semibold text-slate-700">
                        Full name
                    </label>
                    <div class="field-shell">
                        <svg class="h-5 w-5 shrink-0 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path d="M10 10a4 4 0 100-8 4 4 0 000 8Z" />
                            <path fill-rule="evenodd" d="M2 15.5A5.5 5.5 0 017.5 10h5A5.5 5.5 0 0118 15.5a.5.5 0 01-.5.5h-15a.5.5 0 01-.5-.5Z" clip-rule="evenodd" />
                        </svg>
                        <TextInput
                            id="name"
                            v-model="form.name"
                            type="text"
                            class="block w-full"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Alex Merchant"
                        />
                    </div>
                    <InputError class="mt-2 text-sm" :message="form.errors.name" />
                </div>

                <div>
                    <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                        Work email
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
                            autocomplete="username"
                            placeholder="founder@brand.com"
                        />
                    </div>
                    <InputError class="mt-2 text-sm" :message="form.errors.email" />
                </div>

                <div>
                    <div class="mb-2 flex items-center justify-between gap-2">
                        <label for="password" class="block text-sm font-semibold text-slate-700">
                            Password
                        </label>
                        <span class="text-xs font-medium text-slate-400">Use 8+ characters</span>
                    </div>
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
                            autocomplete="new-password"
                            placeholder="Create a strong password"
                        />
                    </div>
                    <InputError class="mt-2 text-sm" :message="form.errors.password" />
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">
                        Confirm password
                    </label>
                    <div class="field-shell">
                        <svg class="h-5 w-5 shrink-0 text-slate-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-7.25 7.25a1 1 0 01-1.414 0L4.293 10.207a1 1 0 011.414-1.414l3.043 3.043 6.543-6.543a1 1 0 011.414 0Z" clip-rule="evenodd" />
                        </svg>
                        <TextInput
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            type="password"
                            class="block w-full"
                            required
                            autocomplete="new-password"
                            placeholder="Repeat your password"
                        />
                    </div>
                    <InputError class="mt-2 text-sm" :message="form.errors.password_confirmation" />
                </div>

                <button
                    type="submit"
                    class="btn-primary w-full justify-center py-3.5 text-sm"
                    :class="{ 'opacity-60': form.processing }"
                    :disabled="form.processing"
                >
                    Create account
                </button>
            </form>

            <div class="rounded-brand-md bg-slate-50 px-4 py-4 text-sm text-slate-600">
                Already registered?
                <Link :href="route('login')" class="font-semibold text-brand-700 hover:text-brand-800">
                    Sign in instead
                </Link>
                and continue where you left off.
            </div>
        </div>
    </GuestLayout>
</template>
