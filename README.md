# 📈 Laravel Stock API

A simple Laravel application that allows you to search for stock data (via Twelve Data or another API), store history, and send JSON-based email reports — built with clean code and RESTful endpoints.

---

## 🚀 Features

- 🔍 **GET /api/stock?q=AAPL** – Fetches stock data.
- 📜 **GET /api/history** – Shows history of searched stocks.
- 📧 **Emails** – Sends stock info via email using `Mail::raw`.
- ⏱️ **Queue Support** – Emails are sent asynchronously.
- ✅ **PHPUnit Tests** – Tests for both `/stock` and `/history` endpoints.

---

## 🛠 Requirements

- PHP >= 8.1
- Composer
- Laravel >= 10 (Laravel 12+ supported)
- MySQL or other DB
- Mailtrap account (for dev email testing)
- Twelve Data API key (or your stock data provider)

---

## ⚙️ Installation

```bash
# Clone the repo
git clone https://github.com/your-user/your-stock-api.git
cd your-stock-api

# Install dependencies
composer install

# Copy and update .env
cp .env.example .env

# Generate app key
php artisan key:generate

# Set permissions (optional)
chmod -R 775 storage bootstrap/cache

php artisan migrate

php artisan queue:work

php artisan test
