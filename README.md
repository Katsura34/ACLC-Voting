# ACLC Voting System

A comprehensive online voting management system built with Laravel 12 for managing student elections at ACLC (Asian College of Science and Technology Foundation, Inc.).

## 📋 Table of Contents

- [Features](#features)
- [Requirements](#requirements)
- [Installation](#installation)
- [Configuration](#configuration)
- [Usage](#usage)
- [User Roles](#user-roles)
- [Database Schema](#database-schema)
- [Development](#development)
- [Testing](#testing)
- [Security](#security)
- [License](#license)

## ✨ Features

### For Administrators
- **Election Management**: Create, edit, and manage multiple elections
- **Position Management**: Define positions with configurable number of winners
- **Party Management**: Create and manage political parties
- **Candidate Management**: Add candidates with detailed information (name, course, year level, bio, photo)
- **Real-time Analytics**: Monitor voter turnout and voting statistics
- **Results Publishing**: Control when election results are visible to students
- **Vote Reset**: Ability to reset votes for testing or re-voting scenarios
- **Live Results Toggle**: Option to show/hide live results during voting

### For Students
- **Secure Authentication**: Login using University Student Number (USN)
- **Vote Casting**: Select candidates for each position
- **Abstain Option**: Students can abstain from voting for specific positions
- **Multi-winner Support**: Vote for multiple candidates when positions allow
- **One Vote Per Election**: System ensures each student can only vote once
- **Vote Confirmation**: Clear feedback after successful vote submission

### System Features
- **Role-based Access Control**: Separate admin and student interfaces
- **Data Validation**: Comprehensive input validation on all forms
- **CSRF Protection**: All POST requests are protected against CSRF attacks
- **XSS Prevention**: Automatic output escaping in all views
- **Session Management**: Secure session handling with database storage
- **Responsive Design**: Works on desktop, tablet, and mobile devices

## 📦 Requirements

- PHP >= 8.2
- Composer
- Node.js >= 18.x & NPM
- MySQL >= 8.0 or MariaDB >= 10.3
- Web Server (Apache/Nginx)

## 🚀 Installation

### 1. Clone the Repository

```bash
git clone https://github.com/Katsura34/ACLC-Voting.git
cd ACLC-Voting
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install JavaScript dependencies
npm install
```

### 3. Environment Setup

```bash
# Copy the example environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Configuration

Edit your `.env` file and configure your database connection:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aclc_db
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. Run Migrations

```bash
# Create database tables
php artisan migrate
```

### 6. Build Assets

```bash
# Build frontend assets for production
npm run build

# Or for development with hot reload
npm run dev
```

### 7. Start the Application

```bash
# Start the Laravel development server
php artisan serve
```

The application will be available at `http://localhost:8000`

## ⚙️ Configuration

### Quick Setup (Automated)

Run the automated setup script:

```bash
composer setup
```

This will:
- Install all dependencies
- Copy `.env.example` to `.env`
- Generate application key
- Run database migrations
- Install and build frontend assets

### Development Environment

For a complete development environment with all services:

```bash
composer dev
```

This starts:
- Laravel development server
- Queue worker
- Laravel Pail (log viewer)
- Vite development server with hot reload

### Session Configuration

The application uses database sessions by default. Ensure you have run migrations to create the `sessions` table.

## 👥 User Roles

### Admin
- Full access to election management
- Can create/edit/delete elections, positions, parties, and candidates
- View real-time voting statistics
- Publish or unpublish results
- Reset votes when needed

**Default Admin Access**: Create an admin account via the registration page with user_type set to "admin"

### Student
- Can view active elections
- Cast votes for candidates
- View published results
- One vote per election

**Student Access**: Register with user_type set to "student" and use your USN to login

## 🗄️ Database Schema

### Main Tables

- **users**: Student and admin accounts
  - `usn`: University Student Number (unique identifier)
  - `user_type`: 'student' or 'admin'
  - `has_voted`: Boolean flag to track voting status

- **elections**: Election events
  - `title`: Election name
  - `description`: Details about the election
  - `is_active`: Controls if election is currently active
  - `start_date`, `end_date`: Election schedule
  - `allow_abstain`: Whether students can abstain
  - `show_live_results`: Display results in real-time

- **positions**: Positions to be filled
  - `name`: Position title (e.g., President, Vice President)
  - `max_winners`: Number of candidates that can win
  - `order`: Display order in voting form

- **parties**: Political parties
  - `name`: Party name
  - `slug`: URL-friendly identifier
  - `color`: Display color
  - `description`: Party platform

- **candidates**: Candidate information
  - `first_name`, `last_name`: Candidate name
  - `position_id`: Linked position
  - `party_id`: Linked party (optional)
  - `course`, `year_level`: Student information
  - `bio`: Candidate biography
  - `photo_path`: Candidate photo

- **votes**: Cast votes
  - `election_id`: Election reference
  - `user_id`: Voter reference
  - `position_id`: Position voted for
  - `candidate_id`: Selected candidate
  - `is_abstain`: Whether vote was abstained
  - `voted_at`: Timestamp of vote

## 🛠️ Development

### Code Style

This project follows PSR-12 coding standards. Laravel Pint is configured for automatic code formatting:

```bash
# Check code style
./vendor/bin/pint --test

# Fix code style issues
./vendor/bin/pint
```

### Running Tests

```bash
# Run all tests
php artisan test

# Or using composer
composer test
```

### Directory Structure

```
app/
├── Http/
│   ├── Controllers/        # Application controllers
│   └── Middleware/         # Custom middleware
├── Models/                 # Eloquent models
└── Providers/             # Service providers

database/
├── migrations/            # Database migrations
└── seeders/              # Database seeders

resources/
├── views/                # Blade templates
│   ├── admin/           # Admin views
│   ├── student/         # Student views
│   └── auth/            # Authentication views
└── css/                 # Stylesheets

routes/
└── web.php              # Web routes
```

### Adding a New Election

1. Login as an admin
2. Navigate to "Manage Elections"
3. Click "Create Election"
4. Fill in election details:
   - Title and description
   - Start and end dates
   - Toggle active status
   - Enable/disable abstain option
   - Configure live results display
5. Add positions for the election
6. Add parties (optional)
7. Add candidates for each position
8. Activate the election when ready

## 🧪 Testing

### Manual Testing

1. **Admin Workflow**:
   - Create a test election
   - Add positions and candidates
   - Activate the election
   - Monitor voting statistics

2. **Student Workflow**:
   - Login as a student
   - Navigate to voting page
   - Cast votes
   - Verify one-time voting restriction

### Automated Tests

```bash
# Run feature tests
php artisan test --testsuite=Feature

# Run unit tests
php artisan test --testsuite=Unit
```

## 🔒 Security

### Security Features

- **CSRF Protection**: All forms include CSRF tokens
- **XSS Prevention**: Blade template auto-escaping
- **SQL Injection Prevention**: Eloquent ORM with parameter binding
- **Password Hashing**: Bcrypt hashing with configurable rounds
- **Session Security**: Database-backed sessions with encryption
- **Input Validation**: Comprehensive validation rules on all inputs
- **Authorization**: Role-based middleware protection

### Reporting Security Vulnerabilities

If you discover a security vulnerability, please send an email to the repository maintainer. All security vulnerabilities will be promptly addressed.

See [SECURITY.md](SECURITY.md) for more details.

## 📝 Environment Variables

Key environment variables to configure:

```env
# Application
APP_NAME=ACLC-Voting
APP_ENV=production
APP_DEBUG=false
APP_URL=http://your-domain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=aclc_db
DB_USERNAME=root
DB_PASSWORD=

# Session
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Mail (for notifications)
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@aclc-voting.com
```

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run code style checks (`./vendor/bin/pint`)
5. Commit your changes (`git commit -m 'Add amazing feature'`)
6. Push to the branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

### Code Quality Standards

- Follow PSR-12 coding standards
- Add return type hints to all methods
- Write meaningful commit messages
- Include tests for new features
- Update documentation as needed

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com) - The PHP Framework for Web Artisans
- UI components powered by [Bootstrap](https://getbootstrap.com)
- Icons from [Font Awesome](https://fontawesome.com)
- Development tools from [Vite](https://vitejs.dev) and [Tailwind CSS](https://tailwindcss.com)

## 📞 Support

For support, please open an issue in the GitHub repository or contact the development team.

---

**ACLC Voting System** - Making student elections simple, secure, and accessible.
