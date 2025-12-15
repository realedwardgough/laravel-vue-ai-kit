<?php

declare(strict_types=1);

namespace App\Services\Google\Gemini;

use App\Events\BasicMessageEvent;
use App\Models\Chat;
use App\Models\Message;

class GoogleGeminiAI
{
    
    public function __construct(
        public string $apiKey = '',
        private string $apiModel = 'gemini-2.0-flash',
        private string $apiURL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent',
        private array $conversationData = [],
    )
    {
        $this->apiKey = config('services.gemini.key');
    }

    /**
     * Handle the submission of the conversation to the gemini
     * api endpoint, using a cURL method. 
     * @return 
     */
    public function sendRequest(Chat $chat = null, string $message = '')
    {

        // Handle the gemini data context
        $this->formatGeminiData($chat, $message);

        // Process the request
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $this->apiURL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'x-goog-api-key:' . $this->apiKey,
            ],
            CURLOPT_POSTFIELDS => json_encode($this->conversationData),
        ]);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        // Unsuccessful
        if (200 !== $httpCode) {
            throw new \RuntimeException("Gemini API failed ({$httpCode})");
        }

        // Format response to array
        $responseArray = json_decode($response,true);
        $geminiResponse = $responseArray['candidates'][0]['content']['parts'][0]['text']
            ?? 'Sorry, I could not generate a response.';

        // Broadcast message from gemini to the user
        broadcast(new BasicMessageEvent($chat->id, $geminiResponse));

        // Create message in the message table
        Message::create([
            'chat_id' => $chat->id,
            'content' => $geminiResponse,
            'user_or_model' => 2,
        ]);

    }

    /**
     * Handle the gemini data context which is to be sent via cURL.
     * This will include all available previous messages, if the exist.
     * 
     * @param Chat $chat
     * @param string $message
     * @return void
     */
    private function formatGeminiData(Chat $chat, string $message): void
    {

        // Fetch chat data
        $handleChat = Chat::where('id', $chat->id)
            ->with(['messages' => fn ($q) => $q->orderBy('created_at')])
            ->first();
        $messages = $handleChat->messages->take(-20);
        
        // If previous messages are available include them in the data context
        if (!empty($messages) && $handleChat->messages->count() > 0) {
            foreach ($messages as $previousMessage) {
                $this->conversationData['contents'][] = [
                    'role' => 1 === $previousMessage['user_or_model'] ? 'user' : 'model',
                    'parts' => [
                        ['text' => $previousMessage['content']]
                    ]
                ];
            }
        }

        // Append the latest message into the data context from the user
        $this->conversationData['contents'][] = [
            'role' => 'user',
            'parts' => [
                ['text' => $message]
            ]
        ];

        // Configure the gemini service
        $this->conversationData['generationConfig'] = [
            'temperature' => 0.7,
            'topP' => 0.9,
            'maxOutputTokens' => 1024,
        ];

    }
}