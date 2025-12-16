<script setup lang="ts">
import { newChat } from '@/composables/useChat';

/**
 * Handle the chat prop to push into the useChat composable,
 * this will then generate the message ref to build up the message
 * window and further handle the creation of new messages and the retrieval
 * of broadcasted messages from the AI API.
 */
const { form, sendUserMessage } = newChat();

</script>

<template>
    <div class="chat-window">
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
