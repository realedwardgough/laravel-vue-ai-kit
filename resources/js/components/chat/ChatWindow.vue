<script setup lang="ts">
import { useChat } from '@/composables/useChat';
import { connect } from '@/composables/useEcho';
import { onMounted } from 'vue';

const { messages, input, sendUserMessage, receieveBotMessage } = useChat();

onMounted(() => {
    connect('chat.1', receieveBotMessage);
});
</script>

<template>
    <div class="chat-window">
        <div id="status" class="disconnected">
            <div
                style="
                    background: rgb(19, 22.5, 30.5);
                    padding: 0.2rem 0.7rem;
                    border-radius: 999rem;
                "
            >
                <span class="status-indicator"></span>
                <span id="status-text">Trying to connect...</span>
            </div>
            <hr />
        </div>
        <div id="messages">
            <div
                v-for="(m, i) in messages"
                :key="i"
                :class="['message', m.sender]"
            >
                {{ m.text }}
            </div>
        </div>
        <div class="input-container flex flex-row gap-1">
            <input
                v-model="input"
                @keypress.enter="sendUserMessage"
                type="text"
                placeholder="Type a message..."
            />
            <button @click="sendUserMessage">Send</button>
        </div>
    </div>
</template>
