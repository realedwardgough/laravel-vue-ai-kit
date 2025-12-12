import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { sendMessage } from '@/routes';
import { Chat, Message } from '@/types';

//
const form = useForm({
    content: '',
});

export function useChat(chat: Chat) {

    const messages = ref<Message[]>(chat.messages);

    const sendUserMessage = () => {
        if (!form.content.trim()) return;
        messages.value.push({ 
            id: 0,
            chat_id: chat.id,
            content: form.content,
            user_or_model: 1,
            created_at: '',
            updated_at: '',
        });
        createUserMessage();
    };

    const receieveBotMessage = (text: string) => {
        messages.value.push({ 
            id: 0,
            chat_id: chat.id,
            content: text,
            user_or_model: 2,
            created_at: '',
            updated_at: '', 
        });
    };

    const createUserMessage = () => {
        form.post(sendMessage().url, {
            preserveScroll: true,
            onSuccess: () => {
                form.reset();
            }
        });
    };

    return {
        messages,
        form,
        sendUserMessage,
        receieveBotMessage,
    };
}
