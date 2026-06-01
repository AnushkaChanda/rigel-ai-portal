# Rigel AI Portal

Welcome to the **Rigel AI Portal** repository! This is a PHP and MySQL-based web application designed to handle AI interview sessions, career guidance, and user management.

## Features

- **User Authentication:** Secure login, registration, and password reset functionalities.
- **AI Interview Sessions:** Upload and process interview videos.
- **Career Guidance & Dashboard:** Personalized dashboards for users (e.g., students).
- **Admin & Reports:** Admin dashboard for managing users and generating reports.

## Prerequisites

To run this project locally, you will need:
- A local web server with PHP support (e.g., XAMPP, WAMP, or MAMP).
- A MySQL database.

## Local Setup Instructions

1. **Clone the repository:**
   ```bash
   git clone https://github.com/AnushkaChanda/rigel-ai-portal.git
   ```
2. **Move files to your server directory:**
   Place the project folder inside your web server's document root (e.g., `htdocs` for XAMPP).
3. **Database Configuration:**
   - The application automatically attempts to create the database (`rigel_db2`) and necessary tables upon connection if they don't exist.
   - Open `includes/db_connect.php` to modify your database credentials. 
   - Note: By default, the current configuration points to a live Hostinger database. If you want to test locally with a local database, comment out the Hostinger settings and uncomment the local development block.
4. **Run the Application:**
   Navigate to the project directory in your browser, for example: `http://localhost/rigel-ai-portal` (or use PHP's built in server via `php -S localhost:8000`).

## Deployment

When deploying to a live server (like Hostinger):
1. Import `database/schema.sql` (if manual setup is preferred) to your live MySQL database.
2. Update the credentials in `includes/db_connect.php` under the **HOSTINGER LIVE DATABASE SETTINGS** section.
3. Ensure your web host allows the required permissions for the `uploads` directory.

## Technologies Used

- **Backend:** PHP
- **Database:** MySQL
- **Frontend:** HTML, CSS, JavaScript

---
*Created by Anushka Chanda / Rigel Foundation*
