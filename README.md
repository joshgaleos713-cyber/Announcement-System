# notify.xyz — Modern Announcement & Dispatch Engine

> A high-performance, interactive announcement system built with **Laravel 12**, **Blade**, **Alpine.js**, and a **Tactile Monochrome Soft-Neumorphic** design system.

---

## 📸 Overview & Design Philosophy

**notify.xyz** is built with a **Modern Tactile Monochrome** aesthetic. It blends minimalist obsidian (`#09090b`), rich charcoals (`#18181b`, `#272e39`), and crisp white highlights on a soft slate canvas (`#eef2f7`). 

Unlike classical neumorphism, **notify.xyz** uses crisp borders (`rgba(255, 255, 255, 0.85)` / `rgba(0, 0, 0, 0.05)`) and deep dark action controls to guarantee razor-sharp contrast, accessibility, and modern elegance.

---

## ✨ Key Features

### 📡 1. Announcement Management (Full CRUD)
- **Multi-Audience Dispatching:**
  - 🌐 **Public Broadcasts:** Visible to all registered users.
  - 🔒 **Targeted / Exclusive:** Delivered only to specific assigned users via pivot relations (`announcement_user`).
- **Notice Types & Visual Priority:**
  - ℹ️ **Info Notice** — General notices and updates.
  - ⚡ **Warning Notice** — Important announcements requiring attention.
  - ⚠️ **Critical Alert** — Urgent system alerts with distinct visual treatment.
  - ✓ **Success Update** — Feature releases and resolution updates.
- **Admin Readership Audit:**
  - Inspect who has opened and acknowledged each announcement, complete with relative read timestamps (`read_at`).
- **Secure Deletion:**
  - Uses HTTP `DELETE` verb with `@csrf` token verification and a custom tactile confirmation modal.

### ⚡ 2. Real-Time Interactivity & Zero-Reload UX
- **Dynamic Top Navigation Bar (User & Admin):**
  - **Live Notification Bell with Dropdown Popover:** Shows live unread count. Clicking the bell displays recent dispatches with **1-click instant "Mark Read"** directly in the popover.
- **Zero-Reload "Mark as Read":**
  - Instant asynchronous status sync via Alpine.js & Fetch API. Announcements update visual state and decrease badge counts live without refreshing the page.
- **Instant Toast Notification Engine:**
  - Smooth animated slide-in toasts for instant action feedback.
- **Instant Search & Category Filtering:**
  - Search by keyword across titles and descriptions, or filter by *All*, *Public*, *Targeted*, *Unread*, and *Read*.
- **Discord-Style Collapsible Sidebar Dock & Responsive Drawer:**
  - **Desktop:** Collapses into a slim, icon-only dock (`w-20`) reminiscent of Discord and Slack. All navigation icons remain docked neatly on the left side with tooltips and active indicator pills. Can be toggled either from the top header or the bottom sidebar button. Full width (`w-64`) displays brand titles, section headers, label text, and profile details. State is persisted in `localStorage`.
  - **Clean Single Inline SVGs:** Sidebar toggle controls use pure, crisp inline SVGs, preventing Lucide duplicate icon artifacts.
  - **Mobile & Tablet:** Off-canvas sliding drawer with dedicated Close (`X`) button, backdrop tap-to-dismiss, and keyboard `Escape` dismissal.
  - **Fluid Grid System:** All stat cards, feeds, modals, and tables automatically adapt from 1 column on smartphones to multi-column layouts on desktop.


### 🛡️ 3. Authentication & Access Control
- Role-based separation between **Superadmin** and **Member**.
- Automated role redirection upon login.
- Clean two-step registration flow.

---

## 🔑 Default Credentials

| Role | Email | Password | Access Level |
| :--- | :--- | :--- | :--- |
| **Superadmin** | `admin@notify.xyz` | `password` | Admin Console, Full CRUD, Read Audits |
| **Member** | `test@example.com` | `password` | Member Portal, Feed, Instant Read Tracking |

*(Legacy admin `admin@careernav.com` / `password` is also preserved).*

---

## 🛠️ Tech Stack & Architecture

- **Backend:** [Laravel 12](https://laravel.com) (PHP 8.2+)
- **Database:** SQLite (Relational models with cascade deletes & pivot tracking)
- **Frontend / Templating:** Laravel Blade
- **Styling:** Tailwind CSS (Tactile Soft-Neumorphic Tokens)
- **Interactivity:** [Alpine.js 3.x](https://alpinejs.dev) + Fetch API
- **Icons:** [Lucide Icons](https://lucide.dev)

---

## 📁 Project Structure

```
d:/Announcement System/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   │   ├── NotificationController.php  # Store, Update, Destroy, Read Audits
│   │   │   │   └── PageController.php          # Admin Dashboard & Metrics
│   │   │   ├── User/
│   │   │   │   ├── NotificationController.php  # Feed, AJAX Mark-Read, Search
│   │   │   │   └── PageController.php          # User Dashboard & Overview
│   │   │   └── Auth/
│   │   │       ├── LoginController.php         # Authentication & role routing
│   │   │       └── RegisterController.php      # New member registration
│   │   └── Middleware/
│   │       └── AdminMiddleware.php             # Admin route guard
│   └── Models/
│       ├── Announcement.php                    # Scopes, creators, recipients, reads
│       └── User.php                            # User model & isAdmin check
├── database/
│   ├── migrations/                             # Users, announcements, pivot tables
│   ├── seeders/
│   │   ├── AdminSeeder.php                     # Default admin provisioning
│   │   └── DatabaseSeeder.php                  # Database seeder runner
│   └── database.sqlite                         # Active SQLite storage
├── resources/
│   └── views/
│       ├── admin/
│       │   ├── dashboard.blade.php             # Admin overview & statistics
│       │   └── notifications.blade.php         # Announcements manager + modals
│       ├── user/
│       │   ├── dashboard.blade.php             # User overview & priority list
│       │   └── notifications.blade.php         # Member announcements feed
│       ├── auth/
│       │   ├── login.blade.php                 # Sleek tactile login screen
│       │   └── register.blade.php              # Multi-step sign-up screen
│       └── layouts/
│           ├── admin.blade.php                 # Admin layout with interactive bell
│           └── user.blade.php                  # User layout with interactive bell
└── routes/
    ├── admin/
    │   └── notifications.php                   # Admin prefix routes
    ├── user/
    │   └── notifications.php                   # User prefix routes
    └── web.php                                 # Root & authentication routes
```

---

## 🚀 Getting Started

### 1. Requirements
- PHP 8.2 or higher
- SQLite extension enabled
- Composer

### 2. Installation & Setup
```bash
# Clone or open the repository
cd "d:/Announcement System"

# Install dependencies
composer install

# Generate application encryption key
php artisan key:generate

# Run database migrations & seeders
php artisan migrate --seed

# Start the local development server
php artisan serve
```

### 3. Accessing the System
- **Web Address:** [http://localhost:8000](http://localhost:8000)
- Sign in with `admin@notify.xyz` / `password` to manage dispatches.
- Sign in with `test@example.com` / `password` to view the member portal.

---

## 📖 How to Use the System

### 👑 Administrator Workflow (`admin@notify.xyz`)
1. **Monitor System Activity (Dashboard):**
   - View high-level metrics: Total Dispatches, Public vs. Targeted distribution, Registered Recipients, and Total Reads.
   - Access quick links to publish announcements or audit targeted posts.
2. **Publish Announcements:**
   - Click **"+ New Announcement"** on either the Dashboard or Announcements page.
   - Choose notice type (`Info`, `Warning`, `Critical Alert`, `Success Update`).
   - Select audience:
     - **Public:** Broadcasts immediately to all users.
     - **Targeted:** Choose specific recipients using the user checklist (or "Select All").
3. **Edit & Maintain Existing Dispatches:**
   - Click the **Pencil (Edit)** button on any announcement card to alter the title, body, notice type, or recipient list.
4. **Audit Readership:**
   - Click the **Users (Read Audit)** button on any card to view exactly who has read the announcement and their relative read timestamp.
5. **Manage & Delete Users (`/admin/users`):**
   - Click **"User Directory"** in the sidebar.
   - Search accounts by name or email with live updates.
   - Inspect individual read activity per member.
   - **Zero-Reload AJAX Deletion:** Click the **Delete** button to remove a user without a page refresh; the row fades out smoothly and the counter updates in real time.
   - **Multi-Select Batch Deletion:** Use row checkboxes and the header "Select All" toggle to select multiple members at once, revealing a floating bulk action bar to purge selected accounts simultaneously.
   - **Safety Protections:** All administrator accounts (`role === 'admin'`) are strictly shielded both on frontend (checkbox removed, excluded from Select All, delete button replaced with "Admin Protected") and on the backend (abort 403 on single delete, filtered out during bulk delete). Admins can never be deleted.


---

### 👤 Member Workflow (`test@example.com`)
1. **Review Priority Updates (Dashboard):**
   - See how many unread notices require attention.
   - Click **"Mark as read"** directly on dashboard cards to acknowledge notices with real-time feedback (no page reload).
2. **Browse & Search Announcements (`/user/notifications`):**
   - Use the search bar to find dispatches by keyword.
   - Filter between *All*, *Unread*, and *Read* notices.
   - Click any card to open a full distraction-free **Details Modal**.
3. **Use the Real-Time Notification Bell:**
   - Click the bell icon in the top navigation bar from any page to see recent notices and acknowledge them on the fly.
4. **Bulk Acknowledgment:**
   - Click **"Mark all read"** in the top bar to mark all pending dispatches in a single click.

---

## 🔒 Security & Best Practices Implemented
1. **CSRF Protection:** Every state-mutating request (POST, PUT, DELETE) verifies the `X-CSRF-TOKEN`.
2. **Self-Deletion Guard:** Admins cannot delete their own active session.
3. **Encapsulated Queries:** All data retrieval and counts are managed inside Controllers; Blade views remain strictly presentational.
4. **Optimized Scopes:** Announcement visibility uses Eloquent `visibleTo($user)` to prevent data leakage of targeted dispatches.
5. **Idempotent Read Tracking:** Database `insertOrIgnore` ensures read events never throw duplicate key exceptions.

