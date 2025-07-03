# Healthcare Appointment Booking API

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![Php](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySql](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)


A RESTful API for booking and managing healthcare appointments, built with Laravel.

---

## 🚀 Features

- ✅ Patient registration & login
- 📅 Appointment booking with conflict detection
- ⏰ 24-hour cancellation policy
- 👨‍⚕️ Healthcare professional directory

---

## ⚙️ Installation

### Requirements

- PHP 8.1+
- MySQL 5.7+
- Composer

### Setup

1. **Clone the repository:**

   ```bash
   git clone https://github.com/yourusername/healthcare-appointment-api.git
   cd healthcare-appointment-api
2. **Install dependencies:**

   ```bash
   composer install
3. **Configure the environment:**

   ```bash
    cp .env.example .env
    php artisan key:generate
4. **Update .env with your database credentials:**

   ```bash
    DB_DATABASE=your_db_name
    DB_USERNAME=your_db_user
    DB_PASSWORD=your_db_password

    SESSION_DRIVER=file
5. **Run migrations and seeders:**

   ```bash
   php artisan migrate
   php artisan db:seed --class=HealthcareProfessionalSeeder
6. **Start the development server:**

   ```bash
   php artisan serve

### API

    📡 API Endpoints

        | Endpoint  | Method | Description         |
        | --------- | ------ | ------------------- |
        | /register | POST   | Create new account  |
        | /login    | POST   | Login to system     |
        | /logout   | POST   | End current session |


    📅 Appointments

        | Endpoint           | Method | Description            |
        | ------------------ | ------ | ---------------------- |
        | /appointments      | GET    | List your appointments |
        | /appointments      | POST   | Book new appointment   |
        | /appointments/{id} | DELETE | Cancel appointment     |


    👨‍⚕️ Healthcare Professionals

        | Endpoint                  | Method | Description            |
        | ------------------------- | ------ | ---------------------- |
        | /healthcare-professionals | GET    | List all professionals |

### Example Requests

1. **Register a User:**

   ```bash

    POST /api/register
    Content-Type: application/json

    {
    "name": "Patient X",
    "email": "patient@example.com",
    "password": "SecurePass123",
    "password_confirmation": "SecurePass123"
    }
2. **Book an Appointment:**

   ```bash

    POST /api/appointments
    Authorization: Bearer your_token_here
    Content-Type: application/json

    {
    "healthcare_professional_id": 1,
    "appointment_start_time": "2023-12-25 09:00:00",
    "appointment_end_time": "2023-12-25 09:30:00"
    }

### Testing

   ```bash
   php artisan test