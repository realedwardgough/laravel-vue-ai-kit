import { echo } from '@laravel/echo-vue';
import { ref } from 'vue';
import { isThinking } from '@/composables/useChatState';

type ConnectionStatus = 'connected' | 'disconnected' | 'error';
type ConnectionMessage = 'Trying to connect...' | 'Connected' | 'Connection error';

export const connectionStatus = ref<ConnectionStatus>('disconnected');
export const connectionMessage = ref<ConnectionMessage>('Trying to connect...');

export function connect(
    channel: string,
    ReceieveBotMessage: (msg: string) => void,
) {
    const connection = echo().private(channel);

    // Connection error
    connection.error(() => {
        connectionStatus.value = 'disconnected';
        connectionMessage.value = 'Connection error';
    });

    // Connection succesfully subscribed to the broadcast
    connection.subscribed(() => {
        connectionStatus.value = 'connected';
        connectionMessage.value = 'Connected';
    });

    // On broadcast recieved
    connection.listen('.BasicMessageEvent', (e: any) => {
        ReceieveBotMessage(e.Message);
        isThinking.value = false;
    });

    // On broadcast recieved
    connection.listen('.gemini.thinking', (e: any) => {
        console.log(e);
        isThinking.value = Boolean(e.thinking);
    });

    return {
        connection,
    };
}
