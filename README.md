# Laravel SSO

## Introduction

This project is a web application utilizing Laravel with Vue.js integration, designed to offer a comprehensive user authentication system. It features user registration, login, and password recovery, extended with Single Sign-On (SSO) functionality via Google and GitHub using Laravel Socialite. Laravel Breeze is employed to scaffold basic authentication. This README provides detailed setup instructions and insights into the development process, tool choices, and challenges encountered.

## Pre-requisites

Ensure you have the following installed to set up the project:
- PHP >= 8.2
- Composer
- Node.js and npm
- A web server (Apache/Nginx)
- Laravel 11
- MySQL
- Git

## Installation

To set up the project locally, follow these steps:

1. **Clone the Repository**
   ```sh
   git clone https://github.com/saurabhshukla-developer/ssotest
   cd ssotest

2. **Install Dependencies**
    ```sh
    composer install
    npm install

3. **Copy .env.example to .env**
   ```sh
   cp .env.example .env

4. **Generate an Application Key**
   ```sh
   php artisan key:generate
   
5. **Configure the Database**
   <br>
    Edit the .env file with your database details (DB_DATABASE, DB_USERNAME, DB_PASSWORD).

7. **Run Migrations**
   ```sh
   php artisan migrate

8. **Set up Laravel Socialite**
   <br>
   add following lines to .env file
   ```sh
   GOOGLE_CLIENT_ID=your_google_client_id
   GOOGLE_CLIENT_SECRET=your_google_client_secret
   GITHUB_CLIENT_ID=your_github_client_id
   GITHUB_CLIENT_SECRET=your_github_client_secret

9. **Set up email functionality for password recovery**
   <br>
   update following line in .env file based on your mail service
   ```sh
   MAIL_MAILER=log
   MAIL_HOST=127.0.0.1
   MAIL_PORT=2525
   MAIL_USERNAME=null
   MAIL_PASSWORD=null
   MAIL_ENCRYPTION=null
   MAIL_FROM_ADDRESS="hello@example.com"
   MAIL_FROM_NAME="${APP_NAME}"

10. **Run the application**
    ```sh
    php artisan serve
    ```

    Visit http://localhost:8000 in your browser.

## Running Tests
Execute PHPUnit tests, including those for SSO functionality, with:
```sh
php artisan test
```

## Development Process and Tool Choices
The project leverages Laravel for its comprehensive ecosystem and Vue.js for a dynamic UI. Laravel Breeze was selected for scaffolding basic authentication, providing a simple, yet powerful, starting point. Laravel Socialite facilitates SSO, chosen for its straightforward integration and support for multiple providers.

