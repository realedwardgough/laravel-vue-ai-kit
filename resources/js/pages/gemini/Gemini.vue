<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from "vue";
import { useEcho } from "@laravel/echo-vue";

import { echo } from '@laravel/echo-vue';

console.log("Echo instance:", echo());

const messages = ref<string[]>([]);

// For now we'll hardcode the same chatId we used in the test route.
const chatId = 1;

useEcho(
    `chat.${chatId}`,
    "basic.message",
    (e: { message: string }) => {
        messages.value.push(e.message);
        console.log(e);
    },
);
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
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1>Gemini Dashboard</h1>
            <div>
                <p>A lightweight Laravel + Vue starter kit for building AI-powered apps using Google Gemini.</p>
            </div>

            <p v-if="messages.length === 0">
                No messages yet...
            </p>

            <ul v-else>
                <li v-for="(m, i) in messages" :key="i">
                    {{ m }}
                </li>
            </ul>

        </div>
    </AppLayout>
</template>