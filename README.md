



This project is a simple banking system built with Laravel 10, where users can register, log in, view their transactions, deposit money, and withdraw money with specific rules for withdrawal fees.
## Features
- User registration and login
- View all transactions and current balance
- Deposit money
- Withdraw money with specific conditions for fees

## Requirements
PHP >= 8.1
Composer
MySQL
Node.js & npm

## Setup Instructions
- Clone the Repository
- npm install
- npm run build
- Rename .env.example to .env
- Update the .env
  - DB_DATABASE=your_database_name
  - DB_USERNAME=your_database_user
  - DB_PASSWORD=your_database_password
- php artisan migrate
- php artisan serve
