import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { sendMessage } from '@/routes';
import { Chat, Message } from '@/types';
import { isThinking } from '@/composables/useChatState';

//
const form = useForm({
    chat_id: 0,
    content: '',
});

// 
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
        isThinking.value = true;
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
        form.chat_id = chat.id;
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

export function newChat() {
    const sendUserMessage = () => {
        if (!form.content.trim()) return;
        createUserMessage();
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
        form,
        sendUserMessage,
    };
}

// 
export function formatMarkdown(text: string) {

    let html = text;

    // Escape HTML first to prevent XSS
    html = html.replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');

    // Code blocks (must come before inline code)
    html = html.replace(/```(\w+)?\n([\s\S]*?)```/g, '<pre><code>$2</code></pre>');

    // Inline code
    html = html.replace(/`([^`]+)`/g, '<code>$1</code>');

    // Bold
    html = html.replace(/\*\*([^\*]+)\*\*/g, '<strong>$1</strong>');
    html = html.replace(/__([^_]+)__/g, '<strong>$1</strong>');

    // Italic
    html = html.replace(/\*([^\*]+)\*/g, '<em>$1</em>');
    html = html.replace(/_([^_]+)_/g, '<em>$1</em>');

    // Headers
    html = html.replace(/^### (.+)$/gm, '<h3>$1</h3>');
    html = html.replace(/^## (.+)$/gm, '<h2>$1</h2>');
    html = html.replace(/^# (.+)$/gm, '<h1>$1</h1>');

    // Links
    html = html.replace(/\[([^\]]+)\]\(([^\)]+)\)/g, '<a href="$2" target="_blank">$1</a>');

    // Unordered lists
    html = html.replace(/^\* (.+)$/gm, '<li>$1</li>');
    html = html.replace(/^- (.+)$/gm, '<li>$1</li>');
    html = html.replace(/(<li>.*<\/li>\n?)+/g, '<ul>$&</ul>');

    // Ordered lists
    html = html.replace(/^\d+\. (.+)$/gm, '<li>$1</li>');

    // Blockquotes
    html = html.replace(/^&gt; (.+)$/gm, '<blockquote>$1</blockquote>');

    // Line breaks to paragraphs
    html = html.split('\n\n').map(para => {

        if (para.trim() && !para.match(/^<(h[1-3]|ul|ol|pre|blockquote)/)) {
            return '<p>' + para.trim() + '</p>';
        }

        return para;

    }).join('');

    // Single line breaks
    html = html.replace(/\n/g, '<br>');

    return html;
}