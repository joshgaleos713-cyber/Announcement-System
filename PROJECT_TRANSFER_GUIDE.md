# How to Transfer the Announcement System to Another Laptop Using CMD

This step-by-step guide explains how to package, transfer, and run this **Laravel 12 (notify.xyz)** project on a new laptop using Windows **Command Prompt (CMD)**.

---

## 📋 Table of Contents
1. [GitHub Transfer (Recommended)](#-method-0-transfer-via-github-with-database-recommended)
2. [Important: What to Include vs. Exclude](#-important-what-to-include-vs-exclude)
3. [Offline Methods (USB, ZIP, Wi-Fi SCP)](#offline-transfer-methods-usb-zip-wi-fi)
4. [Prerequisites on the New Laptop](#prerequisites-on-the-new-laptop)
5. [Restore & Run on New Laptop](#restore--run-the-project-on-the-new-laptop)
6. [Default Login Credentials](#default-login-credentials)
7. [Troubleshooting Common Issues](#troubleshooting-common-issues)

---

## 🚀 Method 0: Transfer via GitHub (with Database) [Recommended]

### On THIS Current Laptop:

1. Create a new repository on GitHub:
   - Go to [github.com/new](https://github.com/new)
   - Repository Name: `announcement-system` (or any name you choose)
   - Choose **Private** (recommended since it includes your database file)
   - **Do NOT** check "Add a README", ".gitignore", or license (they are already in this repo)
   - Click **Create repository**

2. Connect and push this project to your GitHub repository:
   Open **CMD** or **PowerShell** in `D:\Announcement System` and run:
   ```cmd
   git remote add origin https://github.com/YOUR_USERNAME/YOUR_REPO_NAME.git
   git branch -M main
   git push -u origin main
   ```
   *(Replace with your actual GitHub repository URL)*

---

## 💡 Important: What to Include vs. Exclude

To save time and space, **never copy `vendor` or `node_modules` folders**. They contain tens of thousands of small files (several gigabytes) that take forever to transfer and can easily be re-downloaded cleanly on the new laptop.

| What to KEEP ✅ | What to EXCLUDE ❌ |
| :--- | :--- |
| `app/`, `bootstrap/`, `config/` | `vendor/` (reinstalled via `composer install`) |
| `database/` (including `database.sqlite`) | `node_modules/` (reinstalled via `npm install`) |
| `public/`, `resources/`, `routes/` | `.git/` (optional for USB/ZIP transfers) |
| `.env.example` (pre-configured) | Temporary logs: `storage\logs\*.log` |
| `composer.json`, `composer.lock` | Cache files in `bootstrap\cache\*` |
| `package.json`, `package-lock.json`, `vite.config.js` | |

---

## Offline Transfer Methods (USB, ZIP, Wi-Fi)

### Method A: Copy to USB Drive Using Robocopy

Insert your USB flash drive. Note its drive letter (e.g., `E:`).

Run this single command in CMD to copy everything while automatically excluding the heavy folders:

```cmd
robocopy "D:\Announcement System" "E:\Announcement System" /E /XD vendor node_modules .git /XF *.log
```

> 💡 **What this does:**
> - `/E` copies all subdirectories, including empty ones.
> - `/XD vendor node_modules .git` excludes those folders.
> - `/XF *.log` skips large log files.

---

### Method B: Create a ZIP Archive via CMD

If you want a single `.zip` file to copy to Google Drive, USB, or send over local network:

1. Navigate to the project root:
   ```cmd
   cd /d "D:\Announcement System"
   ```

2. Run the built-in Windows `tar` tool to create a zip (Windows 10 / 11 includes `tar` by default):
   ```cmd
   tar -a -c -f "D:\Announcement_System.zip" --exclude="vendor" --exclude="node_modules" --exclude=".git" *
   ```

3. Move `D:\Announcement_System.zip` onto your USB drive or cloud drive.

---

### Method C: Transfer over Local Wi-Fi Network via SCP

If both laptops are connected to the same Wi-Fi network and the new laptop has OpenSSH Server enabled:

```cmd
tar -a -c -f "Announcement_System.zip" --exclude="vendor" --exclude="node_modules" *
scp Announcement_System.zip username@NEW_LAPTOP_IP:C:/Users/username/Downloads/
```

---

## Step 2: Prerequisites on the New Laptop

Before running the project on the new laptop, make sure the following software is installed:

1. **PHP 8.2 or 8.3**
   - Verify in CMD: `php -v`
   - Ensure the following extensions are enabled in your `php.ini` file:
     - `pdo_sqlite`
     - `sqlite3`
     - `mbstring`
     - `openssl`
     - `curl`
     - `fileinfo`
2. **Composer (PHP Dependency Manager)**
   - Download from: https://getcomposer.org/
   - Verify in CMD: `composer -v`
3. **Node.js (LTS version) & NPM**
   - Download from: https://nodejs.org/
   - Verify in CMD: `node -v` and `npm -v`

---

## Step 3: Restore & Run the Project on the New Laptop

1. **Copy or Extract the Project Folder**
   - Place the project in your desired location, for example: `C:\Announcement System` or `D:\Announcement System`.
   - If you used a `.zip`, extract it using CMD:
     ```cmd
     tar -xf "D:\Announcement_System.zip" -C "C:\Announcement System"
     ```

2. **Open Command Prompt (CMD)** and navigate into the project directory:
   ```cmd
   cd /d "C:\Announcement System"
   ```

3. **Check the `.env` Environment File**
   - If your `.env` file transferred over, keep it.
   - If `.env` is missing, copy the example:
     ```cmd
     copy .env.example .env
     ```
   - Generate an application key:
     ```cmd
     php artisan key:generate
     ```

4. **Install PHP Dependencies**
   ```cmd
   composer install
   ```

5. **Install Node/Vite Dependencies & Build Assets**
   ```cmd
   npm install
   npm run build
   ```

6. **Set Up the SQLite Database**
   - Check if `database\database.sqlite` exists. If not, create an empty one:
     ```cmd
     if not exist "database\database.sqlite" type nul > "database\database.sqlite"
     ```
   - Run database migrations and populate default seed data:
     ```cmd
     php artisan migrate:fresh --seed
     ```
     *(Note: If you copied over the existing `database.sqlite` with your real data, you do not need `migrate:fresh` unless you want a clean reset).*

7. **Create the Storage Symlink**
   ```cmd
   php artisan storage:link
   ```

8. **Start the Laravel Development Server**
   ```cmd
   php artisan serve
   ```

9. **Open the App in your Browser**
   - Go to: `http://127.0.0.1:8000`

---

## Default Login Credentials

If you ran `php artisan db:seed` or `migrate:fresh --seed`:

| Role | Email | Password |
| :--- | :--- | :--- |
| **Superadmin** | `admin@notify.xyz` | `password` |
| **Member / User** | `user@notify.xyz` | `password` |

---

## Troubleshooting Common Issues

### 1. `'php'` or `'composer'` is not recognized
- **Solution:** Add the installation path of PHP (e.g., `C:\php` or `C:\tools\php83`) and Composer to your Windows System Environment Variable `Path`. Restart CMD after updating.

### 2. Missing SQLite Driver Error (`could not find driver`)
- Open your `php.ini` file (found by typing `php --ini` in CMD).
- Search for `;extension=pdo_sqlite` and `;extension=sqlite3`.
- Remove the leading semicolon `;` to uncomment them:
  ```ini
  extension=pdo_sqlite
  extension=sqlite3
  ```
- Save the file and restart your terminal.

### 3. Port 8000 is already in use
- Run artisan serve on another port:
  ```cmd
  php artisan serve --port=8080
  ```
- Then open `http://127.0.0.1:8080`.

### 4. Blank styles or icons not showing
- Rebuild frontend assets:
  ```cmd
  npm install
  npm run build
  ```
- Clear cached views and configuration:
  ```cmd
  php artisan optimize:clear
  ```
