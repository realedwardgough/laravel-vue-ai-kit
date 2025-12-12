<script setup lang="ts">
import { onMounted, ref, nextTick, watch } from 'vue';
import { ChatPageProps } from '@/types';
import { usePage } from '@inertiajs/vue3';
import { useChat } from '@/composables/useChat';
import { connect, connectionStatus, connectionMessage } from '@/composables/useEcho';

/**
 * Handle the chat prop to push into the useChat composable,
 * this will then generate the message ref to build up the message
 * window and further handle the creation of new messages and the retrieval
 * of broadcasted messages from the AI API.
 */
const page = usePage<ChatPageProps>();
const { messages, form, sendUserMessage, receieveBotMessage } = useChat(page.props.chat);
const messagesContainer = ref<HTMLElement | null>(null);
onMounted(() => {
    connect('chat.1', receieveBotMessage);
});

/**
 * Handle the chat window scrolling down to the bottom of the 
 * message window on load of the messages component.
 * 
 * @todo Move this into it's own composable
 */
const scrollToBottom = (container: HTMLElement | null) => {
    if (!container) return;
    container.scrollTop = container.scrollHeight;
}
onMounted(async () => {
    await nextTick();
    scrollToBottom(messagesContainer.value);
});
watch(
    messages,
    async () => {
        await nextTick();
        scrollToBottom(messagesContainer.value);
    },
    { deep: true }
);

</script>

<template>
    <div class="chat-window">
        <div id="status" :class="connectionStatus">
            <div
                style="
                    background: rgb(19, 22.5, 30.5);
                    padding: 0.2rem 0.7rem;
                    border-radius: 999rem;
                "
            >
                <span class="status-indicator"></span>
                <span id="status-text">{{ connectionMessage }}</span>
            </div>
        </div>
        <div  ref="messagesContainer" id="messages">
            <div
                v-for="(m, i) in messages"
                :key="i"
                class="message"
                :class="{ 
                    'user': 1 === m.user_or_model,
                    'bot': 2 === m.user_or_model,
                 }"
            >
                {{ m.content }}
            </div>
        </div>
        <div class="input-container flex flex-row gap-1">
            <input
                v-model="form.content"
                @keypress.enter="sendUserMessage"
                type="text"
                placeholder="Type a message..."
            />
            <button @click="sendUserMessage">Send</button>
        </div>
    </div>
</template>
