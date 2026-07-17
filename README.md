# VU Alumni-Student ConnectBook

A web-based platform developed as a Final Year Project (FYP) to strengthen the connection between Virtual University alumni, current students, and university administration. The system enables mentorship, career opportunities, event management, discussion forums, and alumni engagement through a centralized portal.

---

## 📖 Project Overview

The **VU Alumni-Student ConnectBook** is designed to bridge the gap between alumni and students by creating a collaborative environment where alumni can mentor students, share job opportunities, participate in university events, and stay connected with their alma mater.

The platform also provides administrative tools for managing alumni records, publishing university updates, organizing events, and analyzing engagement data.

---

## ✨ Key Features

### 👤 User Management
- Alumni Registration
- Student Registration
- Secure Login Authentication
- Profile Management
- Admin Approval & Management

### 🎓 Alumni-Student Mentorship
- Alumni can volunteer as mentors
- Students can request mentorship
- Career guidance support
- Mentor-Mentee communication

### 📅 Event Management
- Create and manage university events
- Event registration
- Attendance tracking
- Feedback and survey management

### 💼 Job & Internship Portal
- Alumni and Admin can post opportunities
- Students can browse available jobs
- One-click application using profile information
- Internship management

### 🏆 Alumni Reward System
- Reward points for active participation
- Alumni of the Month
- Recognition certificates
- Special university rewards
- Event prizes for top contributors

### 💬 Discussion Forum
- Career discussions
- Educational guidance
- University announcements
- Admin moderation

### 📊 Admin Dashboard
- Alumni engagement analytics
- Event participation statistics
- User management
- Exportable reports

### 📰 News & Announcements
- University news
- Alumni success stories
- Academic updates
- Important notifications

---

# System Modules

- Student Module
- Alumni Module
- Administrator Module
- Mentorship Module
- Event Management Module
- Job & Internship Module
- Discussion Forum Module
- News Management Module
- Analytics Dashboard

---

# Technology Stack

## Frontend
- HTML5
- CSS3
- Bootstrap
- AngularJS

## Backend
- PHP

## Database
- MySQL

## Development Tools
- Visual Studio Code
- XAMPP
- phpMyAdmin

---

# Database

The application uses **MySQL** as the relational database.

Main database entities include:

- Users
- Students
- Alumni
- Admin
- Events
- Event Registrations
- Mentorship Requests
- Jobs
- Internship Opportunities
- Applications
- Discussion Posts
- Reward Points
- News

---

# User Roles

## Student
- Register/Login
- Update Profile
- Request Mentorship
- Apply for Jobs
- Register for Events
- Participate in Discussions

## Alumni
- Register/Login
- Offer Mentorship
- Post Job Opportunities
- Participate in Events
- Earn Reward Points
- Join Discussions

## Administrator
- Manage Users
- Approve Alumni
- Manage Events
- Post News
- Monitor Analytics
- Moderate Discussions
- Generate Reports

---

# Project Structure

```
VU-Alumni-Student-ConnectBook/
│
├── admin/
├── alumni/
├── student/
├── assets/
│   ├── css/
│   ├── js/
│   ├── images/
│
├── database/
│   └── connect.php
│
├── includes/
├── uploads/
├── index.php
├── login.php
├── register.php
└── README.md
```

---

# Installation

## Prerequisites

- PHP 8.x or above
- MySQL
- XAMPP/WAMP/LAMP
- Modern Web Browser

---

## Installation Steps

### 1. Clone the repository

```bash
git clone https://github.com/yourusername/VU-Alumni-Student-ConnectBook.git
```

### 2. Move the project

Copy the project folder into the **htdocs** directory (XAMPP).

### 3. Import Database

- Open phpMyAdmin
- Create a new database
- Import the provided SQL file

### 4. Configure Database

Update your database configuration in:

```php
database/connect.php
```

Example:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "connectbook";
```

### 5. Run the project

Open your browser:

```
http://localhost/VU-Alumni-Student-ConnectBook
```

---

# Future Enhancements

- Email Notifications
- Live Chat System
- Video Mentorship Sessions
- Mobile Application
- AI-based Mentor Recommendation
- Resume Builder
- Certificate Generation
- QR Code Event Attendance
- LinkedIn Integration

---

# Learning Outcomes

This project helped in gaining practical experience with:

- Full Stack Web Development
- PHP Programming
- MySQL Database Design
- CRUD Operations
- Authentication & Authorization
- Responsive UI Design
- MVC Concepts
- Database Relationships
- Form Validation

---

# Screenshots

Add screenshots here after uploading your project.

Example:

```
screenshots/

Home Page

Login

Dashboard

Job Portal

Event Management

Discussion Forum
```

---

# Contributors

**Awon Raza**

Bachelor of Science in Computer Science

Virtual University of Pakistan

---

# License

This project was developed for academic purposes as a Final Year Project at the Virtual University of Pakistan.

Feel free to use this project for learning and educational purposes.

---

# Contact

**Awon Raza**

GitHub: https://github.com/yourusername

LinkedIn: https://linkedin.com/in/yourprofile

Email: your-email@example.com

---

⭐ If you found this project useful, consider giving it a Star on GitHub.
