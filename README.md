# 📬 WhatsApp Business API Bridge - Laravel 10

This project is a lightweight **Laravel 10 API** designed to act as a bridge between **WhatsApp Business** and a **local message processing system**. It receives incoming messages from WhatsApp, stores them in a local database, and allows a background daemon process to pick them up, process them with AI or business logic, and send appropriate responses.

## ⚙️ Features

- 📩 Receives and stores incoming messages from WhatsApp Business.
- 🗃️ Persists data in a local MySQL database.
- 🧠 Designed for integration with background processing (e.g., AI via Ollama, custom message handlers).
- 🔐 Secure API endpoints with basic validation.
- 🧱 Built using Laravel 10 (PHP 8.2+).

## 🧩 Tech Stack

- **Backend**: Laravel 10 (REST API)
- **Database**: MySQL
- **Messaging**: WhatsApp Business API
- **Message Handling**: External background daemon (e.g., Python script, AI inference)

## 🛠️ How It Works

1. WhatsApp Business sends a webhook POST request to the Laravel API.
2. The API receives and validates the message, then stores it in the database.
3. A separate daemon polls or listens for new messages.
4. The daemon processes the message (e.g., using AI, rules, etc.).
5. The response is sent back to WhatsApp using the appropriate Business API endpoint.

## 🚀 API Endpoints

| Method | Endpoint        | Description                 |
|--------|-----------------|-----------------------------|
| POST   | `/api/messages` | Receives WhatsApp messages  |

Example payload:
```json
{
  "phone": "+5491112345678",
  "message": "Hello, I need help with my order."
}
```

## 🧪 Testing

You can test the API locally using tools like **Postman** or **cURL**:

```bash
curl -X POST https://yourdomain.com/api/messages \
  -H "Content-Type: application/json" \
  -d '{"phone":"+5491112345678", "message":"Test message"}'
```

## 📌 Future Improvements

- Add authentication via API tokens.
- Add message reply endpoint.
- Implement message queuing (e.g., Laravel queues or Redis).
- Web UI for message log review.

## 🤝 Contributing

Feel free to fork this repo and open a pull request. Feedback and improvements are welcome!

## 📄 License

MIT License
