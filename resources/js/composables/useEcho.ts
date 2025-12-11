import { echo } from '@laravel/echo-vue';

export function connect(
    channel: string,
    ReceieveBotMessage: (msg: string) => void,
) {
    const connection = echo().private(channel);

    connection.listen('.BasicMessageEvent', (e: any) => {
        ReceieveBotMessage(e.Message);
    });

    return connection;
}
