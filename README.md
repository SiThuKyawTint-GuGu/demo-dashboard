### Full Stack Laravel Developer Challenge

This project implements an admin authentication system with Multi-Factor Authentication (MFA) and a feature to handle database export and import. The application is built with Laravel 10.x and PHP 8.x, following modern development standards.

### Table of Contents

- Features
- Environment Requirements
- Setup Instructions
- Database Schema
- Sample Data
- Deployment Details

### Features
- Authentication:
    Admin login functionality.
    Password reset option via email.
    Multi-Factor Authentication (MFA) through email for enhanced security.

- Admin Portal:
    Export data from DynamoDB to Excel for offline edits.
    Import edited Excel files back to DynamoDB.
    Optimized for large datasets (up to 50,000 records with 150 fields).

### Environment Requirements
- PHP: 8.2 or higher
- Laravel: 10.x

### Setup Instructions
Step 1: Clone the Repository
- git clone https://github.com/your-username/your-repo-name.git  
- cd your-repo-name  

Step 2: Install Dependencies
- composer install  
- npm install && npm run dev  

Step 3: Configure Environment

Copy the .env.example file:
- cp .env.example .env  

Update the following environment variables in the .env file:
APP_NAME=Laravel  
APP_URL=http://your-server-domain  
DB_CONNECTION=sqlite  
DYNAMODB_REGION=your-region  
DYNAMODB_ACCESS_KEY_ID=your-access-key  
DYNAMODB_SECRET_ACCESS_KEY=your-secret-key  
MAIL_MAILER=smtp  
MAIL_HOST=smtp.gmail.com  
MAIL_PORT=587  
MAIL_USERNAME=your-email@gmail.com  
MAIL_PASSWORD=your-email-password  
MAIL_ENCRYPTION=tls  

Step 4: Run Migrations
- php artisan migrate --seed 

Step 5: Serve the Application
- php artisan serve


### Database Schema

User
- id (primary key)
- name
- email
- password
- mfa_code
- mfa_expires_at
- role 
- created_at
- updated_at

### Sample Data

- For Admin
email - admin@admin.com
password - password

### Admin doesn't need MFA and Admin can only see ExcelExport/Import

- Run Command for Seed
- php artisan db:seed


### Deployment Details