import { ref } from 'vue';

const messages = ref<{ sender: 'user' | 'bot'; text: string }[]>([]);
const input = ref('');

export function useChat() {
    const sendUserMessage = () => {
        if (!input.value.trim()) return;
        messages.value.push({ sender: 'user', text: input.value });
        input.value = '';
    };

    const receieveBotMessage = (text: string) => {
        messages.value.push({ sender: 'bot', text });
    };

    return {
        messages,
        input,
        sendUserMessage,
        receieveBotMessage,
    };
}
