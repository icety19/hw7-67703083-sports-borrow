# 🏆 Sports Central: Enterprise Equipment Management

<div align="center">
  <img src="https://img.icons8.com/illustrations/external-fauzidea-flat-fauzidea/128/external-sports-equipment-fitness-gym-fauzidea-flat-fauzidea.png" width="128" />
  <h3>Modern. Secure. Efficient.</h3>
  
  [![PHP](https://img.shields.io/badge/PHP-8.2-777bb4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
  [![MySQL](https://img.shields.io/badge/MySQL-8.0-4479a1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
  [![Tailwind](https://img.shields.io/badge/Tailwind_CSS-3.0-38bdf8?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
  [![Docker](https://img.shields.io/badge/Docker-Enabled-2496ed?style=for-the-badge&logo=docker&logoColor=white)](https://docker.com)
</div>

---

## 🌟 Project Overview
**Sports Central** is a premium, web-based management system designed for tracking and lending sports equipment. Built with an enterprise-first mindset, it combines robust backend security (PDO) with a high-end, interactive user experience (Tailwind + AJAX).

### ✨ Key Features
- **🛡️ Secure Authentication**: Multi-role login system with session protection and PDO prepared statements.
- **🚀 Real-time Dashboard**: Dynamic inventory tracking with instant AJAX-powered updates.
- **💎 Premium UI/UX**: Professional sidebar layout, interactive glassmorphism components, and smooth animations.
- **📦 Full CRUD**: Complete lifecycle management for sports gear (Add, View, Edit, Delete).
- **🐳 Dockerized Architecture**: One-click deployment with containerized PHP, MySQL, and phpMyAdmin.

---

## 🛠️ Tech Stack & Architecture

- **Backend**: PHP 8.2 (Structured with PDO for Database abstraction)
- **Frontend**: Tailwind CSS (Utility-first styling), jQuery, AJAX
- **Infrastructure**: Docker & Docker Compose
- **Database**: MySQL 8.0 (Optimized schema)
- **UI Components**: jQuery Confirm (Modals), FontAwesome 6 (Icons), Google Fonts (Outfit & Prompt)

---

## 📸 System Preview

| Login Page (Premium Glass) | Dashboard (Enterprise Layout) |
| :---: | :---: |
| ![Login Preview](https://placehold.co/400x250/1e1b4b/white?text=Premium+Login+UI) | ![Dashboard Preview](https://placehold.co/400x250/f8fafc/3b82f6?text=Enterprise+Dashboard) |

| Edit Modal (AJAX) | Success Notifications |
| :---: | :---: |
| ![Edit Preview](https://placehold.co/400x250/ebf5ff/3b82f6?text=Live+Editing+Modal) | ![Notification Preview](https://placehold.co/400x250/f0fdf4/16a34a?text=Interactive+Feedback) |

---

## 🚀 Getting Started

### 1. Prerequisites
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) installed on your machine.

### 2. Installation
```bash
# Clone the repository
git clone https://github.com/icety19/hw7-67703083-sports-borrow.git

# Navigate to project folder
cd hw7-67703083-sports-borrow

# Start the fleet
docker-compose up -d
```

### 3. Access
- **Application**: [http://localhost:8080](http://localhost:8080)
- **phpMyAdmin**: [http://localhost:8081](http://localhost:8081)
- **Default Login**: `admin` / `admin123`

---

## 📂 Project Structure
```text
├── src/
│   ├── assets/       # Styles & Scripts
│   ├── config/       # DB Connection (PDO)
│   ├── dashboard.php # Main View (CRUD)
│   ├── index.php     # Login (Premium UI)
│   └── database.sq   # SQL Schema
├── Dockerfile        # PHP Configuration
└── docker-compose.yml# Service Definition
```

---

## 👨‍💻 Developed By
- **Student ID**: 67703083
- **Name**: [Your Name]
- **Workshop**: Advanced Web Application Development (Phase 7)

---
<div align="center">
  <p>Made with ❤️ for a better sports experience.</p>
</div>
