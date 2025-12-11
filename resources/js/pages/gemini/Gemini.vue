<script setup lang="ts">
import ChatWindow from '@/components/chat/ChatWindow.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

// Laravel generic breadcrumbs for the page
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Gemini',
        href: dashboard().url,
    },
];
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <h1 class="text-xl">Gemini Dashboard</h1>
            <div>
                <p>
                    A lightweight Laravel + Vue starter kit for building
                    AI-powered apps using Google Gemini.
                </p>
            </div>
            <ChatWindow />
        </div>
    </AppLayout>
</template>
