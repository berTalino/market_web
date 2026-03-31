<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Forgot Password" />

        <div class="space-y-6">
            <div class="space-y-4">
                <span class="badge-pill">Password recovery</span>
                <div class="space-y-2">
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-950">
                        Reset your password
                    </h1>
                    <p class="text-sm leading-7 text-slate-600 sm:text-base">
                        Enter the email linked to your account and we’ll send a secure reset link so you can restore access quickly.
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
                        Account email
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

                <button
                    type="submit"
                    class="btn-primary w-full justify-center py-3.5 text-sm"
                    :class="{ 'opacity-60': form.processing }"
                    :disabled="form.processing"
                >
                    Email password reset link
                </button>
            </form>

            <div class="rounded-brand-md bg-slate-50 px-4 py-4 text-sm text-slate-600">
                Remembered your credentials?
                <Link :href="route('login')" class="font-semibold text-brand-700 hover:text-brand-800">
                    Back to sign in
                </Link>
                and continue securely.
            </div>
        </div>
    </GuestLayout>
</template>
