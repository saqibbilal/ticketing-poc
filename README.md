# Ticketing System PoC

Laravel 12 proof-of-concept ticketing system with role-based access control.

## Features

- Role-based authentication (Admin, Technician, Regular)
- Ticket CRUD with soft deletes
- Comments on tickets (Admin & Technician only)
- User management (Admin only)
- Dashboard with statistics

## Requirements

- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL 5.7+

## Installation
```bash
# Clone repository
git clone https://github.com/saqibbilal/ticketing-poc
cd ticketing-poc

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure database in .env
DB_DATABASE=ticketing_poc
DB_USERNAME=root
DB_PASSWORD=your_password

# Run migrations & seed
php artisan migrate:fresh --seed

# Build assets
npm run dev

# Start server
php artisan serve
