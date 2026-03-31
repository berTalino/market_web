<script setup>
import { computed } from 'vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({});

const submit = () => {
    form.post(route('verification.send'));
};

const verificationLinkSent = computed(
    () => props.status === 'verification-link-sent',
);
</script>

<template>
    <GuestLayout>
        <Head title="Email Verification" />

        <div class="space-y-6">
            <div class="space-y-4">
                <span class="badge-pill">Email verification</span>
                <div class="space-y-2">
                    <h1 class="text-3xl font-semibold tracking-tight text-slate-950">
                        Verify your email address
                    </h1>
                    <p class="text-sm leading-7 text-slate-600 sm:text-base">
                        Confirm your inbox before getting started. We’ve sent a verification link to the email you used during registration.
                    </p>
                </div>
            </div>

            <div class="rounded-brand-md border border-brand-100 bg-brand-50/70 px-4 py-4 text-sm leading-7 text-slate-600">
                Didn’t receive the email yet? Check your spam folder first, then request another verification message below.
            </div>

            <div
                v-if="verificationLinkSent"
                class="rounded-brand-md border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700"
            >
                A new verification link has been sent to the email address you provided during registration.
            </div>

            <form class="space-y-4" @submit.prevent="submit">
                <button
                    type="submit"
                    class="btn-primary w-full justify-center py-3.5 text-sm"
                    :class="{ 'opacity-60': form.processing }"
                    :disabled="form.processing"
                >
                    Resend verification email
                </button>

                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="btn-secondary w-full justify-center py-3.5 text-sm"
                >
                    Log out
                </Link>
            </form>
        </div>
    </GuestLayout>
</template>
