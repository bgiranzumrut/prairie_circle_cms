
# Prairie Circle Platform CMS

The Prairie Circle Platform CMS is a dynamic web-based content management system designed to support newcomer organizations in Winnipeg. Built using React + Vite, this platform ensures a fast, interactive user experience while streamlining event management and community engagement.

## Table of Contents

1-Project Purpose
2-Key Features
3-Technologies Used
4-Database Design
5-User Roles
6-Installation and Setup
7-Contribution Guidelines
8-Future Enhancements
9- License
10-Acknowledgments

## Project Purpose

This CMS is tailored for Prairie Circle, a Winnipeg-based non-profit organization dedicated to helping newcomers settle and connect with the community. The platform enables easy creation and management of events, user registrations, and role-based access while ensuring scalability and user-friendliness.


## Key Features
# Event Management: 
Create, update, categorize, and delete events.

# User Authentication:
 Secure login system with role-based access control (Admin, Event Coordinator, Registered User, Public User).

# Event Registration: Users can join events, track participation, and view attendee lists.
User Profiles: Track participation history and discover other attendees.

# Category-Based Browsing: 
Events are organized into categories for easy filtering.

#Interactive Dashboard: 
Personalized user dashboard with upcoming events.

## Technologies Used

# React + Vite: 
Combined for a fast, modern development experience.

# PHP: 
Server-side logic and database interaction.

# MySQL: 
Backend database for managing event and user data.

#HTML/CSS/JavaScript:
 Core web technologies for structure, styling, and interactivity.
##  Database Design

The database structure supports efficient event and user management:

# Users Table:
 Stores user details and roles.

# Events Table: 
Manages event data (title, date, location, etc.).

# Categories Table: 
Organizes events into logical groups.

# Registrations Table: 
Tracks user-event relationships for participation.

## Key Relationships
One-to-Many: Categories to Events.

## User Roles

# Administrators:
Full access to the CMS, including managing users and categories.

# Event Coordinators:
Limited administrative privileges, focusing on event management.

# Registered Users:
Browse, join events, and manage personal participation.

# Public Users:
View event information without profile features.
## Installation and Setup

# Clone the repository:

git clone https://github.com/bgiranzumrut/prairie_circle_cms.git

# Navigate to the project directory:

cd prairie-circle

# Install dependencies:

npm install

#  Configure the database:

Import the provided SQL script (serverside.sql) into your MySQL server.

Update database credentials in the configuration file (config.php).

# Run the development server:

npm run dev

# Access the application at http://localhost:5173.

## Contribution Guidelines

Fork this repository and create a new branch for your feature or bug fix.

Follow best practices for coding and documentation.
Submit a pull request with a clear explanation of your changes.
##  Future Enhancements

Integration of notification systems for upcoming events.
Mobile-friendly design to enhance accessibility.
Advanced analytics for event participation and user engagement.
## License

This project is licensed under the MIT License.
## Acknowledgments

Frameworks and libraries: React, Vite, and MySQL.
