# Task Management System 📋

A Laravel-based task management system with a calendar view, priority-based task categorization, and role-based access control.

## 🚀 Features
- **CRUD Operations** for tasks (Add, Edit, Delete)
- **Task Filtering & Search**
- **Role-Based Access Control**
- **Task Calendar View** (FullCalendar integration)
- **Commenting System** for tasks
- **Dynamic UI Improvements** (Responsive design, styling)

## 🛠️ Setup Instructions

### 1️⃣ Clone the Repository
```bash
git clone https://github.com/yourusername/task-management.git
cd task-management
```

### 2️⃣ Install Dependencies
```bash
composer install
npm install
```

### 3️⃣ Update Database Credentials in the .env file:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
```

### 4️⃣ Run Migrations
```bash
php artisan migrate
```

### 5️⃣ Start the Application
```bash
php artisan serve
```

Visit: http://127.0.0.1:8000 🎉

## Default Credentials
### Admin
- Email: admin@portal.com
- Password: admin123
### Manager
- Email: manager@portal.com
- Password: manager123
