# Rigel AI Portal

Welcome to the **Rigel AI Career Portal**! This is a modern PHP, MySQL, and AI-powered web application designed to handle automated video interview sessions, career tracking, candidate screening, and detailed reports.

---

## 🌟 Key Features

* **AI-Powered Interviews**: Performs asynchronous video recordings and automatically processes transcripts using a rotated pool of **Groq API Keys** (configured dynamically via `.env`).
* **Environment-Based Config**: Fully portable execution across different platforms/computers using environment variables loaded via a custom dynamic environment loader.
* **Access Gating & Verification**: Invite-only registration verified against a whitelist (`approved_emails.json`), with integrated time-window validity and test bypass emails.
* **Email Notifications**: Seamless email reports containing AI-generated interview summaries delivered to both administrators and candidates using **PHPMailer** over secure SMTP.
* **Responsive Interview Studio**: Dynamic web interface featuring real-time video preview, automated count-down timers per question, compulsory and optional question flows, and instant video uploads.
* **Admin Control Center**: Visual dashboards to whitelist candidates, manage internship offerings (SkillSphere, QuickPro, DevSphere, PsyEdge), view transcripts, and audit interview responses.

---

## 🛠️ Tech Stack

* **Backend**: PHP 7.4+ (with PHPMailer & PDO)
* **Frontend**: Vanilla HTML5, Premium CSS3, JavaScript (ES6)
* **Database**: MySQL / MariaDB
* **AI Engine**: Groq Cloud SDK (Llama 3 / Mixtral models)

---

## ⚙️ Prerequisites

To run this project, you need:
* A local PHP environment (e.g., **XAMPP**, **WAMP**, or **Laragon**) with PHP 7.4 or later.
* **MySQL/MariaDB** database server.
* A **Groq API Key** for processing the transcripts.
* A **Gmail App Password** for sending automated email summaries.

---

## 🚀 Local Installation & Setup

### 1. Clone the Repository
Clone the project into your local server's document root (e.g., `C:/xampp/htdocs/` for XAMPP):
```bash
git clone https://github.com/AnushkaChanda/rigel-ai-portal.git
cd rigel-ai-portal
```

### 2. Configure Environment Variables
Copy `.env.example` to a new file named `.env`:
```bash
cp .env.example .env
```
Open `.env` and fill in your details:
```ini
# Database Settings (Local Server)
DB_HOST=127.0.0.1
DB_PORT=3306
DB_NAME=rigel_db2
DB_USER=root
DB_PASS=

# Groq API Keys (Supports up to 5 rotated keys for load-balancing / rate limits)
GROQ_KEY_1=gsk_your_key_here
GROQ_KEY_2=
GROQ_KEY_3=
GROQ_KEY_4=
GROQ_KEY_5=
```

### 3. Database Initialization
The application is built with an auto-migration schema. Upon visiting the website for the first time, [includes/db_connect.php](file:///c:/Users/KIIT0001/Desktop/new%20career%20website/career%20portal%20new/career%20portal%20new/rigel-ai-portal-main/includes/db_connect.php) will automatically:
* Create the database if it does not exist.
* Generate all necessary tables (`users`, `internships`, `admin_whitelist`, `interview_questions`).
* Seed default internship listings and sample interview questions.

### 4. Run the Portal
Start Apache and MySQL in your control panel, then access:
```text
http://localhost/rigel-ai-portal
```
Alternatively, start the built-in server from the project directory:
```bash
php -S localhost:8000
```
Then navigate to `http://localhost:8000`.

---

## 🧑‍💻 Whitelisting & Testing Access

The invitation list is located in `data/approved_emails.json`.
* Regular candidates have strict `valid_from` and `valid_until` time slots (IST) outside of which they cannot enter the interview room.
* **Testing Bypass Emails**: Testing accounts such as `anushkac2504@gmail.com` and `udayaditya@rigelfoundation.org.in` have no time slots defined, allowing them unrestricted bypass access for QA and demonstrations.

---

## 📂 Project Structure

```text
├── css/                  # Styling sheets (auth.css, style.css, dashboard.css)
├── data/                 # Whitelist storage (approved_emails.json)
├── database/             # Database schemas
├── images/               # App branding assets
├── includes/             # Shared PHP layouts (header, footer, DB connects, env loaders)
├── js/                   # Frontend logic and components
├── pages/                # Main application pages
│   ├── interview_room.php        # Video studio page
│   ├── send_email.php            # SMTP PHPMailer dispatcher
│   └── process_interview.php     # AI transcript processor
├── uploads/              # Saved video files (.webm format - Git ignored)
└── .env                  # Configuration variables (Git ignored)
```

---
*Created by Anushka Chanda / Rigel Foundation*
