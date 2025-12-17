<?php

declare (strict_types= 1);

namespace App\Services\AI\Providers;

use App\Services\AI\Contracts\ChatProviderInterface;
use App\Services\AI\DTO\ChatResponse;
use App\Models\Chat;

class GeminiProvider implements ChatProviderInterface
{

    public function __construct(
        private string $apiModel = 'gemini-2.0-flash',
        private string $apiURL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent',
        public ?string $apiKey = null,
        private ?array $conversationData = null,
    )
    {
        $this->apiKey = config('services.gemini.key');
    }

    public function send(Chat $chat, string $message): ChatResponse
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
        $formattedResponse = $this->handleResponse($response);

        \Log::info("Gemini Service: ", [
            'Formatted_Response: ' => $formattedResponse,
            'Response: ' => $response,
        ]);
        
        return new ChatResponse($formattedResponse['reply'], $formattedResponse['title'],'gemini');
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

        // Fetch chat data (Only take the last 10 messages in the chat)
        $handleChat = Chat::where('id', $chat->id)
            ->with(['messages' => fn ($q) => $q->orderBy('created_at')])
            ->first();
        $messages = $handleChat->messages->take(-10);
        
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

        // Append confiuration and additional instructions
        $this->config();
        $this->systemInstructions();

    }

    /**
     * Configure the Gemini service. This method will setup the maxoutput tokens. 
     * If you wish to limit the potential costs of running this service, set a lower tokens output.
     * 
     * @return void
     */
    private function config(): void
    {
        $this->conversationData['generationConfig'] = [
            'temperature' => 0.7,
            'topP' => 0.9,
            'maxOutputTokens' => 4096,
            'responseMimeType' => 'application/json',
        ];
    }

    /**
     * Apply system instructions for the gemini service. This will accept pure text, and can be used to guide
     * the AI how to respond, or the type of acions the service can handle. 
     * 
     * @return void
     */
    private function systemInstructions(): void
    {
        $path = base_path('/Settings/GeminiSystemInstructions.txt');

        $this->conversationData['systemInstruction'] = [
            'parts' => [[
                'text' => <<<TEXT
                        You are a chat assistant.

                        For every response:
                        1. Answer the user's message naturally.
                        2. Generate a short, descriptive chat title (3–6 words) summarising the conversation so far.
                        3. Respond ONLY in valid JSON with the following shape:

                        {
                        "reply": "string",
                        "title": "string"
                        }

                        Do not include any additional text outside the JSON.
                        TEXT
            ]],
        ];
    }

    /**
     * Handle the gemini service response JSON. Format and handle the additional
     * JSON data to accept the conversation response, plus the updated chat title.
     * 
     * @param string $rawResponse
     * @return array
     */
    private function handleResponse(string $rawResponse): array
    {
        $responseArray = json_decode($rawResponse, true);
        $text = $responseArray['candidates'][0]['content']['parts'][0]['text'] ?? '';
        $json = json_decode($text, true);

        if (is_array($json)) {
            return [
                'title' => $json['title'] ?? null,
                'reply' => $json['reply'] ?? '',
            ];
        }

        // Treat as plain text response
        return [
            'title' => null,
            'reply' => $text,
        ];

    }

}