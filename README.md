# ERP System – Developer Assessment

## 📋 Objective
A basic ERP system built using Laravel focused on **Inventory Management** and **Sales Orders**, with both web and API interfaces. Designed as a developer assessment project.

---

## 🚀 Features

### 1. 🔐 Authentication & Roles
- Laravel Breeze for user authentication.
- Two predefined roles:
  - **Admin** – Full access (manage products, view & manage orders).
  - **Salesperson** – Can create/view sales orders.
- Role-based access control using middleware.

### 2. 📦 Inventory Management
- Product listing with full CRUD functionality.
- Each product has: `name`, `SKU`, `price`, `quantity`.
- Quantity automatically reduced when a sales order is confirmed.
- Low stock alerts shown on dashboard.

### 3. 🧾 Sales Orders
- Create sales orders with **multiple products**.
- Automatically calculates total amount.
- On confirmation:
  - Product stock is reduced.
  - PDF invoice generated via `dompdf`.

### 4. 📊 Dashboard Summary
- Total sales amount.
- Total orders placed.
- Alerts for products with low inventory.

---

## 📱 API Endpoints

All API responses are in JSON and secured via **Laravel Sanctum**.

| Endpoint | Method | Description |
|---------|--------|-------------|
| `/api/products` | `GET` | List all products |
| `/api/sales-orders` | `POST` | Create a new sales order |
| `/api/sales-orders/{id}` | `GET` | Retrieve a sales order with products and totals |

Use the provided API token (via Sanctum) to access protected endpoints.

---

## ⚙️ Technical Stack

- **Backend:** Laravel *(latest version)*
- **Database:** MySQL
- **Auth:** Laravel Breeze + Laravel Sanctum (for APIs)
- **Frontend:** Bootstrap or Tailwind CSS
- **PDF Export:** dompdf
- **Validation:** FormRequest
- **Architecture:** MVC
- **Testing Data:** Seeders included

---

## 🛠️ Installation

```bash
git clone https://github.com/<your-username>/erp-system.git
cd erp-system

# Install dependencies
composer install
npm install && npm run dev

# Create env file and set DB credentials
cp .env.example .env

# Generate app key
php artisan key:generate

# Migrate and seed
php artisan migrate --seed

# Serve the app
php artisan serve
