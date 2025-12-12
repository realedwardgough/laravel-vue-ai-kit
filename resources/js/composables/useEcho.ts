import { echo } from '@laravel/echo-vue';
import { ref } from 'vue';

type ConnectionStatus = 'connected' | 'diconnected' | 'error';
type ConnectionMessage = 'Trying to connect...' | 'Connected' | 'Connection error';

export const connectionStatus = ref<ConnectionStatus>('diconnected');
export const connectionMessage = ref<ConnectionMessage>('Trying to connect...');

export function connect(
    channel: string,
    ReceieveBotMessage: (msg: string) => void,
) {
    const connection = echo().private(channel);

    // Connection error
    connection.error(() => {
        connectionStatus.value = 'diconnected';
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
    });

    return {
        connection,
    };
}
