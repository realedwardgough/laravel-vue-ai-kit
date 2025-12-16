# Laravel AI Chat Kit

A Laravel + Vue (Inertia) real-time chat application built to experiment with integrating multiple AI providers behind a clean, scalable architecture.

This project currently includes a working integration with **Google Gemini**, with background jobs, real-time broadcasting, and a modular AI provider system. Designed to support additional providers such as OpenAI and Grok.

---

## Features

* **Asynchronous AI processing**

  * AI requests run via Laravel queues to avoid blocking HTTP requests.
* **Real-time chat updates**

  * AI responses are broadcast to the frontend using Laravel Broadcasting + Echo.
* **Google Gemini integration**

  * Calls the Gemini API to generate AI responses.
  * Includes conversational context using recent chat history.
* **Provider-agnostic AI architecture**

  * AI logic is abstracted behind an interface and provider classes.
  * Designed to add new AI providers (OpenAI, Grok, etc.) with minimal changes.
* **AI “thinking” state**

  * The UI reflects when the AI is processing a response.
* **Separation of concerns**

  * Controllers, Jobs, Services, Providers, and Events each have a clear responsibility.

---

## Tech stack

* **Backend:** Laravel (PHP)
* **Frontend:** Vue 3 + Inertia
* **Realtime:** Laravel Broadcasting + Echo (private channels, Reverb)
* **Queue:** Laravel Queues (database driver in local dev)
* **AI Provider:** Google Gemini (current)

---

## Architecture overview

### Backend structure

```text
app/
├── Events/
│   └── Chat/
|       ├── ResponseEvent.php
│       └── ThinkingEvent.php
│
├── Http/
│   └── Controllers/
|       ├── Laravel/
|       |   └── ViewController.php
│       └── Chat/
|           ├── ChatController.php
│           └── MessageController.php
│
├── Jobs/
│   └── Chat/
│       └── SendChatMessage.php
│
└── Services/
    └── AI/
        ├── Contracts/
        │   └── ChatProviderInterface.php
        ├── DTO/
        │   └── ChatResponse.php
        ├── Factory/
        │   └── ChatProviderFactory.php
        ├── Providers/
        │   └── GeminiProvider.php
        └── ChatManager.php
```

### Request flow

1. User sends a message.
2. The message is saved immediately.
3. A queued job (`SendChatMessage`) is dispatched.
4. The job delegates to `ChatManager`.
5. `ChatManager` routes the request to the configured provider (e.g. `GeminiProvider`).
6. The provider calls the AI API and returns a normalized DTO (`ChatResponse`).
7. The response is broadcast to the client in real time and stored in the database.

---

## AI providers

| Provider      | Status        |
| ------------- | ------------- |
| Google Gemini | ✅ Implemented |
| OpenAI        | ⏳ Planned     |
| Grok          | ⏳ Planned     |

Adding a new provider is intended to be:

1. Create a provider class in `Services/AI/Providers`.
2. Implement `ChatProviderInterface`.
3. Register or route it via `ChatManager`.

---

## Configuration

### Environment variables

Add the following to your `.env` file:

```env
GEMINI_API_KEY=your_api_key_here
QUEUE_CONNECTION=database
BROADCAST_CONNECTION=pusher
```

> Broadcasting uses **private channels**, so you must configure a broadcast driver (e.g. Pusher or Redis) and channel authorization.

---

## Running the project (local)

```bash
# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Run migrations
php artisan migrate

# Start the queue worker
php artisan queue:work

# Run the frontend dev server
npm run dev

# Start reverb
php artisan reverb:start

# Serve the app
php artisan serve
```

---

## Project goals

This project exists to:

* Explore real-world AI API integration in Laravel.
* Demonstrate async + real-time architecture.
* Provide a reusable foundation for future AI-powered projects.
* Act as a portfolio piece showcasing modern Laravel patterns.

---

## Planned improvements

* Add additional AI providers (OpenAI, Grok).
* Streaming AI responses.
* Automatic continuation for long responses.
* Per-message status + error handling.
* Provider selection per chat/user.
* Token usage + cost tracking.

---

## License

This project is for learning and experimentation purposes.
