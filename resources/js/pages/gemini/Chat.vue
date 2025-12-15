<script setup lang="ts">
import ChatWindow from '@/components/chat/ChatWindow.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { geminiShow, geminiChat } from '@/routes';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ChatPageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';

const page = usePage<ChatPageProps>();

// Laravel generic breadcrumbs for the page
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Gemini',
        href: geminiShow().url,
    },
    {
        title: 'Chat',
        href: geminiChat(page.props.chat.id).url,
    },
];
</script>

<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-screen flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <ChatWindow />
        </div>
    </AppLayout>
</template>
