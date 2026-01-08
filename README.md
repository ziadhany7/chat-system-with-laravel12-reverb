# Real-Time Chat & Broadcast System (Laravel 12 + Reverb)

![Laravel 12](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel)
![Reverb](https://img.shields.io/badge/Laravel_Reverb-Websocket-blue?style=for-the-badge)
![Alpine.js](https://img.shields.io/badge/Alpine.js-8BC0D0?style=for-the-badge&logo=alpinedotjs&logoColor=white)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)

A high-performance, real-time communication platform built with **Laravel 12** and **Laravel Reverb**. This project demonstrates a seamless chat experience, allowing users to engage in both private one-on-one conversations and a global public broadcast channel.

## 📝 Description
The system leverages **Alpine.js** for a reactive frontend and **Tailwind CSS** for a modern, responsive UI. By utilizing Laravel's newest first-party WebSocket server (**Reverb**), the application ensures ultra-low latency and scalable real-time messaging without the need for third-party services like Pusher.

## 🚀 Key Features
- **Real-Time Messaging:** Instant message delivery using WebSockets.
- **Public Broadcast Channel:** A "Public Podcast" room where messages are sent to all online users.
- **Private One-on-One Chat:** Secure private messaging between authenticated users.
- **Persistent State:** Automatically loads the global broadcast history as the default view upon refresh/login.
- **Reactive UI:** Built with Alpine.js for smooth interactions without full page reloads.
- **Smart Auto-Scroll:** Automatically scrolls to the latest message in any active conversation.

## 🛠 Tech Stack
- **Backend:** Laravel 12 (PHP 8.2+)
- **WebSocket Server:** Laravel Reverb (First-party)
- **Frontend:** Alpine.js & Tailwind CSS
- **Broadcasting:** Laravel Echo
- **Auth:** Laravel Breeze (Optional/Integrated)

## 📥 Installation

1. **Clone the repository:**
   ```bash
   git clone [https://github.com/ziadhany7/chat-system-with-laravel12-reverb.git](https://github.com/ziadhany7/chat-system-with-laravel12-reverb.git)
   cd chat-system-with-laravel12-reverb
2. **Install PHP dependencies:**
    ```bash
    composer install
    npm install && npm run dev
3. **Environment Configuration**
    ```bash
    cp .env.example .env
    php artisan key:generate
4. **Database & Migrations**
    ```bash
    php artisan migrate
5. **5. Running the Application**
    You need to run the following commands in separate terminals:
    
    - Start the Reverb server:
        ```bash
        php artisan reverb:start

    - Start the Reverb server:
        ```bash
        php artisan queue:listen
        
    - Serve the application:
        ```bash
        php artisan serve
📄 License
This project is open-source software licensed under the MIT license.

Developed with ❤️ By Eng Ziad Hany
using Laravel 12.❤️
