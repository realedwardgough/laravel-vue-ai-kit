<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import { type BreadcrumbItem, ChatsPageProps } from '@/types';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { geminiChat } from '@/routes';
import NewChatWindow from '@/components/chat/NewChatWindow.vue';

//
const page = usePage<ChatsPageProps>();

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
        <div class="flex h-screen flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <ul>
                <Link 
                    v-for="chat in page.props.chats" v-key="chat.id" :href="geminiChat(chat.id).url"
                    class="flex flex-row w-full p-2.5 hover:bg-accent"
                >
                    {{ chat.name ?? chat.id }}
                </Link>
            </ul>
            <NewChatWindow />
        </div>
    </AppLayout>
</template>
