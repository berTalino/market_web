<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="relative min-h-screen overflow-hidden bg-slate-950">
        <div class="absolute inset-0 bg-brand-mesh opacity-20" />
        <div class="absolute inset-x-0 top-0 h-64 bg-gradient-brand opacity-90 blur-3xl" />

        <div class="section-container relative flex min-h-screen items-center py-6 sm:py-10">
            <div
                class="grid w-full overflow-hidden rounded-brand-xl border border-white/10 bg-white/5 shadow-surface backdrop-blur lg:grid-cols-[minmax(0,1.1fr)_minmax(420px,520px)]"
            >
                <section
                    class="relative flex flex-col justify-between overflow-hidden bg-gradient-brand px-6 py-8 text-white sm:px-10 sm:py-10 lg:min-h-[780px] lg:px-12 lg:py-12"
                >
                    <div class="absolute inset-0 opacity-30">
                        <div class="absolute -left-16 top-16 h-40 w-40 rounded-full bg-white/20 blur-3xl" />
                        <div class="absolute bottom-8 right-0 h-56 w-56 rounded-full bg-brand-300/30 blur-3xl" />
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,rgba(255,255,255,0.26),transparent_34%)]" />
                    </div>

                    <div class="relative space-y-8">
                        <Link href="/" class="inline-flex items-center gap-4 text-white">
                            <span
                                class="flex h-14 w-14 items-center justify-center rounded-[1.35rem] border border-white/20 bg-white/12 shadow-soft backdrop-blur"
                            >
                                <ApplicationLogo class="h-8 w-8 fill-current text-white" />
                            </span>
                            <span>
                                <span class="block text-xs font-semibold uppercase tracking-[0.34em] text-white/70">
                                    Platio Market
                                </span>
                                <span class="mt-1 block text-xl font-semibold tracking-tight">
                                    Secure merchant access
                                </span>
                            </span>
                        </Link>

                        <div class="max-w-xl space-y-5">
                            <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/10 px-4 py-2 text-[0.72rem] font-semibold uppercase tracking-[0.3em] text-white/80 backdrop-blur">
                                Trusted by modern digital sellers
                            </span>

                            <div class="space-y-4">
                                <h1 class="max-w-lg text-4xl font-semibold tracking-tight text-white sm:text-5xl">
                                    Launch, manage and scale your storefront from one polished workspace.
                                </h1>
                                <p class="max-w-xl text-base leading-8 text-white/78 sm:text-lg">
                                    Fast sign in, frictionless checkout flows and merchant tooling designed for digital commerce teams that care about conversion.
                                </p>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2">
                                <div class="rounded-brand-lg border border-white/12 bg-white/10 p-4 backdrop-blur">
                                    <p class="text-sm font-semibold text-white">Payments-first experience</p>
                                    <p class="mt-2 text-sm leading-6 text-white/72">
                                        Auth screens aligned with the new storefront and optimized for high-intent sessions.
                                    </p>
                                </div>
                                <div class="rounded-brand-lg border border-white/12 bg-white/10 p-4 backdrop-blur">
                                    <p class="text-sm font-semibold text-white">Protected account actions</p>
                                    <p class="mt-2 text-sm leading-6 text-white/72">
                                        Verification, recovery and confirmation flows stay clear, reassuring and consistent.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="relative mt-8 space-y-5">
                        <div class="grid gap-3 sm:grid-cols-3">
                            <div class="rounded-brand-md border border-white/12 bg-white/10 p-4 backdrop-blur">
                                <p class="text-xs uppercase tracking-[0.24em] text-white/60">Availability</p>
                                <p class="mt-2 text-lg font-semibold text-white">24/7 access</p>
                            </div>
                            <div class="rounded-brand-md border border-white/12 bg-white/10 p-4 backdrop-blur">
                                <p class="text-xs uppercase tracking-[0.24em] text-white/60">Security</p>
                                <p class="mt-2 text-lg font-semibold text-white">Verified sessions</p>
                            </div>
                            <div class="rounded-brand-md border border-white/12 bg-white/10 p-4 backdrop-blur">
                                <p class="text-xs uppercase tracking-[0.24em] text-white/60">Merchant focus</p>
                                <p class="mt-2 text-lg font-semibold text-white">Conversion-ready</p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3 text-sm text-white/72">
                            <span class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/10 px-3 py-1.5 backdrop-blur">
                                <span class="h-2 w-2 rounded-full bg-emerald-300" />
                                Encrypted credential flow
                            </span>
                            <span class="inline-flex items-center gap-2 rounded-full border border-white/12 bg-white/10 px-3 py-1.5 backdrop-blur">
                                <span class="h-2 w-2 rounded-full bg-sky-300" />
                                Seamless password recovery
                            </span>
                        </div>
                    </div>
                </section>

                <section class="flex items-center bg-slate-50/96 px-4 py-5 sm:px-6 sm:py-8 lg:px-8 lg:py-10">
                    <div class="mx-auto w-full max-w-md">
                        <div class="mb-5 flex items-center justify-between lg:hidden">
                            <Link href="/" class="inline-flex items-center gap-3 text-slate-900">
                                <span class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-brand text-white shadow-brand">
                                    <ApplicationLogo class="h-6 w-6 fill-current text-white" />
                                </span>
                                <span>
                                    <span class="block text-xs font-semibold uppercase tracking-[0.28em] text-brand-700">
                                        Platio Market
                                    </span>
                                    <span class="mt-0.5 block text-sm font-semibold text-slate-900">
                                        Account access
                                    </span>
                                </span>
                            </Link>
                            <span class="badge-pill">Secure auth</span>
                        </div>

                        <div class="elevated-card rounded-[2rem] border border-white bg-white/95 p-6 sm:p-8">
                            <slot />
                        </div>

                        <p class="mt-4 px-2 text-center text-xs leading-6 text-slate-500 sm:text-sm">
                            Protected by modern session controls and the same brand system used across the refreshed marketplace.
                        </p>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>
