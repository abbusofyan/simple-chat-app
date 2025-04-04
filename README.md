# Simple Chat App

A real-time chat application built with Laravel, Vue 3, Inertia.js, and Pusher.

## Features
- Real-time messaging using Pusher
- User authentication with Laravel Breeze
- Inertia.js for seamless frontend-backend integration
- Vue 3 Composition API for reactive UI

## Installation

### Prerequisites
Ensure you have the following installed on your system:
- PHP 8.1+
- Composer
- Node.js & npm
- Laravel 10+
- MySQL or any other supported database
- Pusher account and credentials

### Setup
1. **Clone the repository:**
   ```sh
   git clone https://github.com/your-username/simple-chat-app.git
   cd simple-chat-app
   ```
2. **Install dependencies:**
   ```sh
   composer install
   npm install
   ```
3. **Set up environment variables:**
   ```sh
   cp .env.example .env
   ```
   - Configure database settings in `.env`
   - Add Pusher credentials in `.env`:
     ```env
     PUSHER_APP_ID=your-app-id
     PUSHER_APP_KEY=your-app-key
     PUSHER_APP_SECRET=your-app-secret
     PUSHER_APP_CLUSTER=your-app-cluster
     ```
4. **Generate application key:**
   ```sh
   php artisan key:generate
   ```
5. **Run database migrations:**
   ```sh
   php artisan migrate
   php artisan db:seed
   ```
6. **Run the development server:**
   ```sh
   php artisan serve
   npm run dev
   ```

## Usage
- Register or log in to the application.
- Start a chat with other registered users.
- Messages will be updated in real time using Pusher.
