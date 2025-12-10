<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref } from "vue";
import { echo } from '@laravel/echo-vue';

// Laravel generic breadcrumbs for the page
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Gemini',
        href: dashboard().url,
    },
];

/**
 * Setup the variables required for the web socket
 */
const chatId = 1;
const messages = ref<string[]>([]);
const channel = echo().private(`chat.${chatId}`);

// Confirm subscription to listener channel and begin listening
channel.subscribed(() => {
    console.log("Connected to channel.");
});
channel.listen(".BasicMessageEvent", (e: any) => {
    console.log("Recieved:", e);
    messages.value.push(e.Message);
});

</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <h1 class="text-xl">Gemini Dashboard</h1>
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