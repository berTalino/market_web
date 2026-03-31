<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <div class="space-y-6">
            <div class="space-y-4">
                <span class="badge-pill">Secure checkpoint</span>
                <div class="space-y-2">
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-950">
                        Confirm your password
                    </h1>
                    <p class="text-sm leading-7 text-slate-600 sm:text-base">
                        This protected action requires one more confirmation before you can continue in your account.
                    </p>
                </div>
            </div>

            <div class="rounded-brand-md border border-brand-100 bg-brand-50/70 px-4 py-4 text-sm leading-7 text-slate-600">
                You are entering a secure area of the application. Re-enter your current password to verify this session.
            </div>

            <form class="space-y-5" @submit.prevent="submit">
                <div>
                    <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">
                        Current password
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
                            autofocus
                            placeholder="Enter your current password"
                        />
                    </div>
                    <InputError class="mt-2 text-sm" :message="form.errors.password" />
                </div>

                <button
                    type="submit"
                    class="btn-primary w-full justify-center py-3.5 text-sm"
                    :class="{ 'opacity-60': form.processing }"
                    :disabled="form.processing"
                >
                    Confirm password
                </button>
            </form>
        </div>
    </GuestLayout>
</template>
