# Goswami Sangath: Matrimonial & Matchmaking Platform

Goswami Sangath is a community-focused matrimonial web application designed to connect and facilitate matchmaking within the Goswami community. It is built with **Laravel 12**, **Livewire 4.1**, and **AdminLTE 3**, with built-in integrations for **Razorpay** payments, **Google Socialite** authentication, and robust OTP verification.

---

## 🌟 Key Features

### 1. User Authentications
- **OTP-Based Verification:** Secure mobile login using OTP verification via SMS gateways.
- **Social Sign-In:** One-click Google login integration powered by Laravel Socialite.

### 2. Comprehensive Profile Builder
- **Biodata & Attributes:** Fields for education, career details, physical attributes, and family background.
- **Horoscope & Astro Details:** Astro compatibility details including gotra, rashi, and manglik status.
- **Document/Photo Uploads:** Secured photo upload system with automatic moderation review queues.

### 3. Matchmaking & User Interactions
- **Matches Engine:** Calculates and recommends potential matches based on user preferences.
- **Advanced Filters:** Search and filter profiles by location, age, occupation, education, and gotra.
- **Interest System:** Users can send interest requests, accept/decline proposals, and block specific profiles.
- **In-App Chat:** Real-time user-to-user messaging and notifications system.

### 4. Memberships & Razorpay Payment Integration
- **Premium Tiers:** Configure duration, contact view limits, and messaging permissions per subscription.
- **Razorpay SDK:** Integrated payment gateway supporting card, UPI, net banking, and wallets.
- **Auto-Activation:** Immediate unlocking of premium features upon successful payment confirmation.

### 5. Back-Office Admin Panel
- **AdminLTE v3 Integration:** Visual dashboard highlighting registration trends, user reports, and revenue charts.
- **Moderation Workflow:** Review user-submitted profiles, approve/reject photos, and handle user reports/complaints.
- **System Configs:** Admin control panel to configure payment API keys, SMS gateway configurations, and platform settings.

### 6. Mobile-Ready REST API
- Decoupled API controllers (using **Laravel Sanctum**) supporting mobile and external frontend clients.
- Pre-configured Postman collection included: `TrueUnion_API_Collection2.postman_collection.json`.

---

## 🛠️ Tech Stack

- **Backend:** Laravel 12.0 (PHP 8.2+)
- **Interactive UI:** Livewire 4.1
- **Styling:** Bootstrap + Tailwind CSS
- **Admin Theme:** AdminLTE 3
- **Payment Gateway:** Razorpay SDK (v2.8)
- **Auth Services:** Laravel Socialite (Google Auth) & Laravel Sanctum
- **Code Standards:** Laravel Pint

---

## ⚙️ Core Application Directory Structure

- `app/Http/Controllers/`
  - `ProfileCompletionController.php` - Wizard steps for registering users.
  - `MessagesController.php` - Chat backend logic.
  - `BlockController.php` - Restricting interactions and users blocklists.
  - `SubscriptionController.php` & `PaymentController.php` - Razorpay checkout and webhook systems.
  - `Admin/` - AdminLTE dashboards and user verification modules.
  - `Api/` - Sanctum-secured controllers for mobile clients (Auth, Search, Matches, Payments, Messages).
- `routes/`
  - `web.php` - Frontend routes, subscription checkout, and Blade actions.
  - `api.php` - API endpoints for mobile/decoupled clients.
- `TrueUnion_API_Collection2.postman_collection.json` - Comprehensive API endpoints testing collection.

---

## 💻 Getting Started & Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL Database

### Installation Steps

1. **Clone the Repository & Install Dependencies**
   ```bash
   composer install
   npm install
   ```

2. **Setup Environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Configure Database & Services in `.env`**
   ```env
   DB_DATABASE=goswami_matrimonial
   DB_USERNAME=root
   DB_PASSWORD=your_password

   # Razorpay Credentials
   RAZORPAY_KEY_ID=rzp_test_xxxxxx
   RAZORPAY_KEY_SECRET=xxxxxx

   # Google Socialite
   GOOGLE_CLIENT_ID=xxxxxx
   GOOGLE_CLIENT_SECRET=xxxxxx
   GOOGLE_REDIRECT_URI=http://localhost:8000/auth/google/callback
   ```

4. **Run Database Migrations & Seeds**
   ```bash
   php artisan migrate --seed
   ```

5. **Start the Concurrent Development Server**
   This project runs Laravel Serve, Queue Listeners, Pail logger, and Vite concurrently:
   ```bash
   npm run dev
   ```
