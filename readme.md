# Sports Equipment Borrowing System (Enterprise Edition)

## Project Overview
This project is a web-based system for managing sports equipment borrowing, developed as part of a professional workshop. It demonstrates best practices in PHP development, infrastructure management with Docker, and modern UI/UX design.

## Features
- **Secure Authentication**: Login system with session management.
- **Equipment Management**: Full CRUD (Create, Read, Update, Delete) for sports gear.
- **Modern UI**: Built with Tailwind CSS and Responsive Design.
- **Interactive Experience**: Uses AJAX for seamless updates and jQuery Confirm for polished feedback.
- **Enterprise Infrastructure**: Containerized using Docker for consistent environments.

## Technologies Used
- **Backend**: PHP 8.2 (PDO for Database Connectivity)
- **Frontend**: Tailwind CSS, jQuery, AJAX
- **Database**: MySQL 8.0
- **Infrastructure**: Docker, Docker Compose

## Getting Started
1. Clone the repository.
2. Run `docker-compose up -d` to start the services.
3. Access the application at `http://localhost:8080`.
4. Access phpMyAdmin at `http://localhost:8081`.

## Development Principles
- **Documentation First**: `task.md` and `readme.md` initialized before coding.
- **Security First**: Prepared statements with PDO.
- **UX First**: No-refresh updates with AJAX.
