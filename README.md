# prairie_circle_cms

A simple CMS project designed to manage categories, events, and users, built with PHP for the backend and soon to be integrated with a React frontend.

---

## Table of Contents
- [Features](#features)
- [Prerequisites](#prerequisites)
- [Project Structure](#project-structure)
- [Setup Instructions](#setup-instructions)
- [API Usage](#api-usage)
- [Future Development](#future-development)
- [Contributors](#contributors)
- [License](#license)

---

## Features
- **Backend APIs for CRUD operations**:
  - Categories
  - Events
  - Users (including login and registration)
- **Database setup** for seamless integration.
- **Frontend development (React)** to be implemented in the next phase.

---

## Prerequisites
Ensure you have the following installed:
- [XAMPP](https://www.apachefriends.org/index.html) or a similar LAMP stack.
- PHP 8.x.
- Composer (for managing PHP dependencies).
- Postman (for testing APIs).
- Git for version control.
- MySQL.

---

## Project Structure
```plaintext
prairie_circle_cms/
│
├── backend/
│   ├── categories/       # CRUD APIs for categories
│   ├── db/               # Database connection file
│   ├── events/           # CRUD APIs for events
│   ├── users/            # CRUD, login, registration APIs
│   └── test_db.php       # Database connection test script
│
├── frontend/ (Coming soon)
│
├── .gitignore            # Ignored files and directories
├── README.md             # Project information
└── ...
