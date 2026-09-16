# 🚀 How to Clone & Run on Another PC

This guide walks you through cloning and running the **notify.xyz (Announcement System)** on a new computer from GitHub.

---

## 📋 Step 1: Prerequisites Checklist

Before you begin, make sure the new PC has the following software installed:

| Software | Required Version | Download Link | Verification Command |
| :--- | :--- | :--- | :--- |
| **Git** | Any modern version | [git-scm.com](https://git-scm.com/downloads) | `git --version` |
| **PHP** | 8.2 or 8.3 | [windows.php.net](https://windows.php.net/download/) | `php -v` |
| **Composer** | 2.x | [getcomposer.org](https://getcomposer.org/download/) | `composer -v` |
| **Node.js & NPM** | 18+ or 20+ (LTS) | [nodejs.org](https://nodejs.org/) | `node -v` && `npm -v` |

> [!IMPORTANT]
> **PHP Extensions Required:**
> Open your `php.ini` file on the new PC and ensure the following extensions are uncommented (remove the `;` in front):
> ```ini
> extension=curl
> extension=fileinfo
> extension=mbstring
> extension=openssl
> extension=pdo_sqlite
> extension=sqlite3
> ```

---

## ⚡ Quick Start (Copy & Paste)

Open **Command Prompt (CMD)** or **PowerShell** on the other PC and run:

```cmd
:: 1. Navigate to where you want to store the project
cd /d D:\

:: 2. Clone the repository
git clone https://github.com/joshgaleos713-cyber/Announcement-System.git

:: 3. Enter the project directory
cd Announcement-System

:: 4. Install PHP dependencies
composer install

:: 5. Create your environment configuration file
copy .env.example .env

:: 6. Generate the application encryption key
php artisan key:generate

:: 7. Install frontend dependencies & build assets
npm install
npm run build

:: 8. Start the local server
php artisan serve
```

---

## 🌐 Opening the Application

Once `php artisan serve` starts, open your browser and navigate to:

👉 **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔑 Default Login Credentials

Because the SQLite database (`database/database.sqlite`) is included in the repository, all sample data, announcements, and accounts will work immediately:

### 👑 Administrator Account
- **Email:** `admin@notify.xyz`
- **Password:** `password`
- **Access:** Admin Dashboard, Broadcast Announcements, User Management, Reader Analytics

### 👤 Standard User Account
- **Email:** `test@example.com`
- **Password:** `password`
- **Access:** User Portal, Incoming Alerts, Mark Announcements as Read

---

## 🛠️ Common Troubleshooting

<details>
<summary><b>1. "could not find driver" (SQLite error)</b></summary>

Open your `php.ini` file and make sure these two lines are enabled (no semicolon `;` at the beginning):
```ini
extension=pdo_sqlite
extension=sqlite3
```
Then restart your terminal.
</details>

<details>
<summary><b>2. "Port 8000 is already in use"</b></summary>

You can specify a different port when starting the server:
```cmd
php artisan serve --port=8080
```
Then visit `http://127.0.0.1:8080`.
</details>

<details>
<summary><b>3. Composer memory limit or timeout</b></summary>

Run Composer with unlimited memory:
```cmd
php -d memory_limit=-1 "C:\ProgramData\ComposerSetup\bin\composer.phar" install
```
or
```cmd
composer install --no-interaction --prefer-dist
```
</details>
