<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    email: {
        type: String,
        required: true,
    },
    token: {
        type: String,
        required: true,
    },
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Reset Password" />

        <div class="space-y-6">
            <div class="space-y-4">
                <span class="badge-pill">New credentials</span>
                <div class="space-y-2">
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-950">
                        Choose a new password
                    </h1>
                    <p class="text-sm leading-7 text-slate-600 sm:text-base">
                        Update your credentials below to restore account access and get back to managing your catalog and orders.
                    </p>
                </div>
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
                        New password
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
                            autocomplete="new-password"
                            placeholder="Create a strong password"
                        />
                    </div>
                    <InputError class="mt-2 text-sm" :message="form.errors.password" />
                </div>

                <div>
                    <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">
                        Confirm new password
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
                            placeholder="Repeat your new password"
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
                    Reset password
                </button>
            </form>

            <div class="rounded-brand-md bg-slate-50 px-4 py-4 text-sm text-slate-600">
                Need a different route?
                <Link :href="route('login')" class="font-semibold text-brand-700 hover:text-brand-800">
                    Return to sign in
                </Link>
                after updating your credentials.
            </div>
        </div>
    </GuestLayout>
</template>
