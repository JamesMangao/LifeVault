# 🔒 LifeVault — Your Personal Space & AI Growth Partner

[![Laravel v12](https://img.shields.io/badge/Laravel-v12.x-red.svg?style=flat-square&logo=laravel)](https://laravel.com)
[![Tailwind CSS v4](https://img.shields.io/badge/Tailwind_CSS-v4.0-blue.svg?style=flat-square&logo=tailwind-css)](https://tailwindcss.com)
[![Firebase Integrated](https://img.shields.io/badge/Firebase-Firestore-amber.svg?style=flat-square&logo=firebase)](https://firebase.google.com)
[![Docker Support](https://img.shields.io/badge/Docker-Enabled-cyan.svg?style=flat-square&logo=docker)](https://www.docker.com)
[![LLM Integrations](https://img.shields.io/badge/AI-OpenRouter%20%7C%20Groq%20%7C%20Cerebras-purple.svg?style=flat-square)](https://cloud.cerebras.ai)

> **LifeVault** is a secure, premium web application designed for deep personal reflection, self-discovery, and professional alignment. Combining standard journaling, mood tracking, and task management with state-of-the-art AI analysis, LifeVault serves as your personal vault and growth mentor.

---

## 🔮 Core Features

### 📓 Private Journaling & Mood Tracker
The foundation of LifeVault. Securely capture your raw daily thoughts, emotions, and life events. Log mood ratings (1-5) and assign custom categories to review your emotional trajectory.

### 📄 Resume Analyzer
Upload your resume (`.pdf`, `.docx`, `.doc`, `.txt`) along with a job description. LifeVault will:
- Extract text directly (falling back to **Tesseract OCR via pdftoppm** for image-based PDFs).
- Connect to **Groq / Cerebras AI** high-throughput inference to produce a detailed ATS optimization suggestions report.
- Deliver an ATS match score, keyword recommendations, quick wins, and section-by-section rewrites.
- Allow downloading the entire AI-optimized feedback report as a styled `.docx` file.

### 🔮 Shadow Self Analyzer
Leverage **Groq API** (`llama-3.3-70b-versatile`) to perform Jungian shadow work. The analyzer reads a series of journal entries to safely identify recurring negative loops, self-limiting beliefs, and emotional blind spots. It maps them to compassionate reframes, hidden personal strengths, and concrete growth tasks.

### 📖 Life Story Memoir Generator
Transform raw journal entries into elegant, structured biography drafts. Using **OpenRouter**, the model compiles entries chronologically, weaving them into narrative chapters that preserve your personal history.

### ✨ Holistic Career Advisor
Matches your professional profile (resume) against your inner world (journals). Powered by **OpenRouter** with a sequence of robust fallback models, this tool highlights the alignment between your skills and values, discovers career paths that honor your whole self, and constructs a 30-day authentic action plan.

### 🌐 Community Space
Share your thoughts, articles, or select AI reports in a shared feed. Features support for likes, comments, reposts, user tagging, and automated email mention notifications (powered by Firebase Firestore client triggers and PHP mailer).

### 📈 Insights & Analytics
Visualize your mental health and journaling habits. View mood frequency graphs, journaling streaks, word count trends, and analytical insights.

### 🤖 Sentinel AI Chatbot
An interactive floating chat assistant located on the page to resolve user FAQs, assist with prompt ideas, and guide users through self-reflection.

---

## 🛠️ Technology Stack

| Component | Technology | Description |
|---|---|---|
| **Backend Framework** | Laravel 12.x (PHP 8.3+) | High-performance API routes, Guzzle clients, layout views, and mail templates. |
| **Frontend Style** | Tailwind CSS v4.0 | Next-generation CSS framework for fluid, glassmorphic dark-mode aesthetics. |
| **Frontend Utilities** | Bootstrap JS (bundle), Marked.js | Handled overlay popups, UI modals, and markdown parsing/rendering. |
| **Database & Auth** | Firebase (Client SDK + Firestore) | Direct client-side Firestore database synchronization and Firebase Auth. |
| **Admin SDK** | Kreait Firebase PHP SDK | Server-side Firestore querying for background notifications and mentions. |
| **File Parsing** | `Smalot/PdfParser`, `PhpOffice/PhpWord` | Extracts text from PDFs and handles styled Word `.docx` file exports. |
| **OCR Engines** | Tesseract OCR, Poppler (`pdftoppm`) | PDF page rendering and optical character recognition for scanned resumes. |
| **AI LLMs** | Cerebras, Groq, OpenRouter | Fast, customized model inference for deterministic score outputs and natural reports. |

---

## ⚙️ Setup & Configuration

### 1. Requirements
Ensure your local host contains:
- **PHP >= 8.2** (with `ext-gd` and `ext-zip` enabled)
- **Composer** (PHP Package Manager)
- **Node.js >= 20.x** & **NPM**
- **MySQL / MariaDB** (required to host resume storage procedures)
- **Tesseract OCR** and **Poppler utilities** (for PDF parsing)

### 2. File Environment (`.env`)
Copy the template file to `.env` and fill out your specific API keys:
```bash
cp .env.example .env
```

### 3. Firebase Project Configuration
Create a project on the [Firebase Console](https://console.firebase.google.com/):
1. Enable **Authentication** (Google Sign-In).
2. Enable **Cloud Firestore** in test mode or with rules configured (`firestore.rules` is provided in the repository).
3. Create a Web App within your Firebase project and copy the configuration details into `.env` under `VITE_FIREBASE_*`.
4. Generate a Service Account Key from project settings -> Service Accounts, save it in the root folder as `firebase-adminsdk.json`, and set `FIREBASE_CREDENTIALS="firebase-adminsdk.json"`.

---

## 🗄️ Database & Stored Procedures Setup

The Resume Analyzer uses stored procedures for logging and retrieving parsed contents. Run the following SQL queries in your MySQL database (specified in your `.env`) to set up the structure:

```sql
-- 1. Create Resumes Table
CREATE TABLE IF NOT EXISTS resumes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    resume_content LONGTEXT NOT NULL,
    job_description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DELIMITER //

-- 2. Stored Procedure for Inserting Resumes
CREATE PROCEDURE IF NOT EXISTS usp_insert_resume(
    IN p_user_id INT,
    IN p_resume_content LONGTEXT,
    IN p_job_description TEXT
)
BEGIN
    INSERT INTO resumes (user_id, resume_content, job_description)
    VALUES (p_user_id, p_resume_content, p_job_description);
    
    SELECT LAST_INSERT_ID() AS inserted_id;
END //

-- 3. Stored Procedure for Fetching Resumes
CREATE PROCEDURE IF NOT EXISTS usp_get_resume(
    IN p_user_id INT,
    IN p_resume_id INT
)
BEGIN
    SELECT * FROM resumes 
    WHERE id = p_resume_id AND user_id = p_user_id;
END //

DELIMITER ;
```

---

## 📄 OCR Configuration & Precompiled Binaries

### For Windows:
The repository bundles precompiled Windows binaries inside `poppler and tesseract/`. Set your absolute system paths inside `.env`:
```env
PDFTOPPM_PATH="D:\\LifeVault\\LifeVault\\poppler and tesseract\\poppler\\Library\\bin\\pdftoppm.exe"
TESSERACT_PATH="D:\\LifeVault\\LifeVault\\poppler and tesseract\\Tesseract-OCR\\tesseract.exe"
```
*(Ensure paths reflect the location where the repository is cloned, using double backslashes for path escaping in PHP).*

### For Linux / Debian / Ubuntu:
Install dependencies using your package manager:
```bash
sudo apt-get update
sudo apt-get install -y tesseract-ocr poppler-utils
```
Then update `.env` to point to global system commands:
```env
TESSERACT_PATH="tesseract"
PDFTOPPM_PATH="pdftoppm"
```

---

## 🚀 Local Installation

1. Clone the repository and navigate inside:
   ```bash
   git clone https://github.com/JamesMangao/LifeVault.git
   cd LifeVault
   ```
2. Install PHP and Composer dependencies:
   ```bash
   composer install
   ```
3. Generate App Key:
   ```bash
   php artisan key:generate
   ```
4. Install Node modules and compile assets:
   ```bash
   npm install
   npm run build
   ```
5. Run migrations:
   ```bash
   php artisan migrate
   ```
6. Start the local server and the concurrent tasks (Vite server, Queue listener, and Pail logs tracker):
   ```bash
   php run dev
   # OR
   npm run dev
   # OR start them manually:
   php artisan serve
   ```

---

## 🐳 Docker Deployment

The application includes a ready-to-run container configuration configured for production serving:
- It configures **PHP 8.3 with Apache**.
- Automatically compiles system dependencies (`tesseract-ocr`, `poppler-utils`).
- Disables Apache `event/worker` MPM modules to run safe pre-fork scripts with PHP.
- Forces **COOP (Cross-Origin-Opener-Policy)** headers: `Header set Cross-Origin-Opener-Policy "same-origin-allow-popups"` to ensure Firebase authentication callbacks work correctly across iframe environments.

Build and run the container:
```bash
docker build -t lifevault .
docker run -p 8080:8080 --env-file .env lifevault
```
Navigate to `http://localhost:8080` to interact with your secure containerized environment.