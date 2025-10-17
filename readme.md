# 🎯 JOB BOARD

A full-stack web application for managing and browsing job advertisements with an administrative dashboard.

## 📋 Table of Contents

- [About](#about)
- [Features](#features)
- [Technologies](#technologies)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Database Structure](#database-structure)
- [Project Steps](#project-steps)
- [API Routes](#api-routes)
- [Usage](#usage)
- [Contributors](#contributors)

## 🎓 About

This project is a comprehensive job board platform that allows users to browse job advertisements, apply for positions, and provides administrators with full CRUD management capabilities. Built as part of an academic project, it demonstrates proficiency in full-stack development, database design, and RESTful API architecture.

## ✨ Features

- **User Features:**
  - Browse job advertisements with pagination
  - View detailed job information without page reload
  - Apply to jobs with personal information
  - User authentication and profile management
  - Auto-fill application forms when logged in

- **Admin Features:**
  - Complete CRUD operations on all database tables
  - Manage job advertisements, companies, and applications
  - User management dashboard
  - Paginated data views for easy navigation

## 🛠 Technologies


### `offers`
Stocke les offres d'emploi avec les champs :
- `offers_id` (Primary Key)
- `id_companies` (Foreign Key vers companies)
- `title`
- `description`
- `long_description`
- `location`
- `contract_type` (CDI, CDD, Stage, Freelance, Alternance)
- `salary`
- `published_date`
- `job_requirements` (JSON)
- `company_info` (JSON)
- `created_at`

### `companies`
Stocke les informations sur les entreprises :
- `id_companies` (Primary Key)
- `name`
- `email`
- `phone`
- `location`
- `description`
- `website`
- `created_at`
- phpMyAdmin (recommended)
- A local server environment (XAMPP, WAMP, MAMP, or similar)
- A web browser

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/JOB-BOARD.git
   cd JOB-BOARD
   ```

2. **Configure the environment**
   ```bash
   cp .env.example .env
   ```
   
   Edit the `.env` file with your database credentials:
   ```env
   DB_HOST=localhost
   DB_NAME=job_board
   DB_USER=root
   DB_PASSWORD=your_password
   ```

3. **Import the database**
   - Open phpMyAdmin
   - Create a new database named `job_board`
   - Import the SQL file located in `database/job_board.sql`

4. **Start your local server**
   - If using XAMPP: Place the project in `htdocs/`
   - If using WAMP: Place the project in `www/`
   - Start Apache and MySQL services

5. **Access the application**
   ```
   http://localhost/JOB-BOARD
   ```

## 🗄 Database Structure

The database consists of the following main tables:

### `jobs`
Stores job advertisements with fields:
- `id` (Primary Key)
- `title`
- `short_description`
- `full_description`
- `company_id` (Foreign Key)
- `salary`
- `location`
- `working_time`
- `created_at`
- `updated_at`

### `companies`
Stores company information:
- `id` (Primary Key)
- `name`
- `description`
- `logo`
- `website`
- `created_at`


### `users`
Stocke les comptes utilisateurs et admin :
- `user_id` (Primary Key)
- `first_name`
- `last_name`
- `email`
- `phone`
- `password` (hashed)
- `role` (candidate/recruiter/admin)
- `created_at`


### `applications`
Stocke les candidatures :
- `id` (Primary Key)
- `job_id` (Foreign Key vers offers)
- `applicant_name`
- `applicant_email`
- `applicant_phone`
- `cover_letter`
- `application_date`
- `status` (pending/reviewed/accepted/rejected)

## 📝 Project Steps

### Step 01: Database Design ✅
Created a relational MySQL database with normalized tables for jobs, companies, users, and applications.

### Step 02: Job Listing Page ✅
Developed an HTML/CSS page displaying job advertisements with title, short description, and a "Learn More" button.

### Step 03: Dynamic Job Details ✅
Implemented JavaScript functionality to display full job details without page reload when clicking "Learn More".

### Step 04: RESTful API ✅
Built a PHP backend API with CRUD operations:
- RESTful routing architecture
- Appropriate HTTP verbs (GET, POST, PUT, DELETE)
- Database interaction layer

### Step 05: Job Application Feature ✅
Added application functionality:
- "Apply" button for each job
- Application form with validation
- Data persistence in database
- Email notification system

### Step 06: Authentication System ✅
Implemented user authentication:
- Login page with session management
- Registration page with password hashing
- User profile management
- Auto-fill for logged-in users

### Step 07: Admin Dashboard ✅
Created administrative interface:
- CRUD operations on all tables
- Pagination for large datasets
- Role-based access control
- User-friendly interface

### Step 08: Design Polish ✅
Enhanced user experience:
- Responsive design
- Improved CSS styling
- Consistent color scheme and typography
- Loading states and error handling

## 🔌 API Routes

### Jobs
- `GET /api/jobs.php` - Get all jobs
- `GET /api/jobs.php?id={id}` - Get specific job
- `POST /api/jobs.php` - Create new job (admin only)
- `PUT /api/jobs.php?id={id}` - Update job (admin only)
- `DELETE /api/jobs.php?id={id}` - Delete job (admin only)

### Companies
- `GET /api/companies.php` - Get all companies
- `GET /api/companies.php?id={id}` - Get specific company
- `POST /api/companies.php` - Create company (admin only)
- `PUT /api/companies.php?id={id}` - Update company (admin only)
- `DELETE /api/companies.php?id={id}` - Delete company (admin only)

### Applications
- `GET /api/applications.php` - Get all applications (admin only)
- `POST /api/applications.php` - Submit job application
- `PUT /api/applications.php?id={id}` - Update application status (admin only)
- `DELETE /api/applications.php?id={id}` - Delete application (admin only)

### Users
- `POST /api/register.php` - Register new user
- `POST /api/login.php` - User login
- `GET /api/user.php` - Get current user info
- `PUT /api/user.php` - Update user profile
- `POST /api/logout.php` - User logout

### Admin
- `GET /api/admin/jobs.php` - Admin job management
- `GET /api/admin/companies.php` - Admin company management
- `GET /api/admin/users.php` - Admin user management
- `GET /api/admin/applications.php` - Admin application management

## 💻 Usage

### For Users
1. Navigate to the homepage
2. Browse available job listings
3. Click "Learn More" to see full job details
4. Register or login to apply for jobs
5. Fill in the application form and submit

### For Administrators
1. Login with admin credentials
2. Access the admin dashboard
3. Manage jobs, companies, users, and applications
4. Use CRUD operations to maintain the platform

### Default Admin Credentials (Change after first login!)
```
Email: admin@jobboard.com
Password: admin123
```

## 👥 Contributors

- **Valadeau Pierre** - Full Stack Development
- **# 🎯 JOB BOARD

A full-stack web application for managing and browsing job advertisements with an administrative dashboard.

## 📋 Table of Contents

- [About](#about)
- [Features](#features)
- [Technologies](#technologies)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Database Structure](#database-structure)
- [Project Steps](#project-steps)
- [API Routes](#api-routes)
- [Usage](#usage)
- [Contributors](#contributors)

## 🎓 About

This project is a comprehensive job board platform that allows users to browse job advertisements, apply for positions, and provides administrators with full CRUD management capabilities. Built as part of an academic project, it demonstrates proficiency in full-stack development, database design, and RESTful API architecture.

## ✨ Features

- **User Features:**
  - Browse job advertisements with pagination
  - View detailed job information without page reload
  - Apply to jobs with personal information
  - User authentication and profile management
  - Auto-fill application forms when logged in

- **Admin Features:**
  - Complete CRUD operations on all database tables
  - Manage job advertisements, companies, and applications
  - User management dashboard
  - Paginated data views for easy navigation

## 🛠 Technologies

### Frontend
- HTML5
- CSS3
- JavaScript (Vanilla)

### Backend
- PHP
- MySQL

### Database
- MySQL via phpMyAdmin

### Environment
- `.env` configuration file for sensitive data

## 📦 Prerequisites

Before running this project, ensure you have:

- PHP 7.4 or higher
- MySQL 5.7 or higher
- phpMyAdmin (recommended)
- A local server environment (XAMPP, WAMP, MAMP, or similar)
- A web browser

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/your-username/JOB-BOARD.git
   cd JOB-BOARD
   ```

2. **Configure the environment**
   ```bash
   cp .env.example .env
   ```
   
   Edit the `.env` file with your database credentials:
   ```env
   DB_HOST=localhost
   DB_NAME=job_board
   DB_USER=root
   DB_PASSWORD=your_password
   ```

3. **Import the database**
   - Ouvre phpMyAdmin
   - Crée une base nommée `job`
   - Importe le fichier SQL situé dans `sql/create_job_modified_database.sql`

4. **Start your local server**
   - If using XAMPP: Place the project in `htdocs/`
   - If using WAMP: Place the project in `www/`
   - Start Apache and MySQL services

5. **Access the application**
   ```
   http://localhost/JOB-BOARD
   ```

## 🗄 Database Structure

The database consists of the following main tables:

### `jobs`
Stores job advertisements with fields:
- `id` (Primary Key)
- `title`
- `short_description`
- `full_description`
- `company_id` (Foreign Key)
- `salary`
- `location`
- `working_time`
- `created_at`
- `updated_at`

### `companies`
Stores company information:
- `id` (Primary Key)
- `name`
- `description`
- `logo`
- `website`
- `created_at`

### `users`
Stores user and admin accounts:
- `id` (Primary Key)
- `name`
- `email`
- `phone`
- `password` (hashed)
- `role` (user/admin)
- `created_at`

### `applications`
Tracks job applications:
- `id` (Primary Key)
- `job_id` (Foreign Key)
- `user_id` (Foreign Key)
- `message`
- `status` (pending/reviewed/accepted/rejected)
- `applied_at`

## 📝 Project Steps

### Step 01: Database Design ✅
Created a relational MySQL database with normalized tables for jobs, companies, users, and applications.

### Step 02: Job Listing Page ✅
Developed an HTML/CSS page displaying job advertisements with title, short description, and a "Learn More" button.

### Step 03: Dynamic Job Details ✅
Implemented JavaScript functionality to display full job details without page reload when clicking "Learn More".

### Step 04: RESTful API ✅
Built a PHP backend API with CRUD operations:
- RESTful routing architecture
- Appropriate HTTP verbs (GET, POST, PUT, DELETE)
- Database interaction layer

### Step 05: Job Application Feature ✅
Added application functionality:
- "Apply" button for each job
- Application form with validation
- Data persistence in database
- Email notification system

### Step 06: Authentication System ✅
Implemented user authentication:
- Login page with session management
- Registration page with password hashing
- User profile management
- Auto-fill for logged-in users

### Step 07: Admin Dashboard ✅
Created administrative interface:
- CRUD operations on all tables
- Pagination for large datasets
- Role-based access control
- User-friendly interface

### Step 08: Design Polish ✅
Enhanced user experience:
- Responsive design
- Improved CSS styling
- Consistent color scheme and typography
- Loading states and error handling


## 🔌 API Routes

Les principaux endpoints sont :
- `/api/admin/users.php` : gestion des utilisateurs (CRUD)
- `/api/admin/jobs.php` : gestion des offres d'emploi (CRUD)
- `/api/admin/companies.php` : gestion des entreprises (CRUD)
- `/api/admin/applications.php` : gestion des candidatures (CRUD)

Les modèles sont dans `model/`, les vues dans `view/`, les scripts SQL dans `sql/`.

## 💻 Usage

### For Users
1. Navigate to the homepage
2. Browse available job listings
3. Click "Learn More" to see full job details
4. Register or login to apply for jobs
5. Fill in the application form and submit

### For Administrators
1. Login with admin credentials
2. Access the admin dashboard
3. Manage jobs, companies, users, and applications
4. Use CRUD operations to maintain the platform

### Default Admin Credentials (Change after first login!)
```
Email: admin@jobboard.com
Password: admin123
```

## 👥 Contributors

- **Your Name** -
- **Colleague Name** - 

## 📄 License

This project is part of an academic curriculum and is intended for educational purposes.

## 🔗 Links

- Repository: [GitHub](https://github.com/PierreValadeau/JOB-BOARD)

---

**Note**: All files have been tested on localhost. Ensure your local server environment is properly configured before running the application.** - Backend Development

## 📄 License

This project is part of an academic curriculum and is intended for educational purposes.
