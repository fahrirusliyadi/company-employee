## About This Project

A comprehensive company and employee management system built with modern web technologies. This application provides a robust platform for managing companies and their employees with a clean, responsive interface and powerful backend functionality.

### 🚀 Key Features

#### Company Management
- **Complete CRUD Operations** - Create, read, update, and delete companies
- **Company Logo Upload** - Upload and manage company logos with automatic image processing
- **Company Information** - Store company name, email, website, and contact details
- **Employee Association** - View and manage all employees associated with each company

#### Employee Management
- **Employee Profiles** - Comprehensive employee information management
- **Company Assignment** - Link employees to their respective companies
- **Contact Information** - Store employee names, emails, and phone numbers
- **Advanced Filtering** - Search and filter employees by various criteria
- **Data Validation** - Robust form validation for data integrity

#### User Authentication & Authorization
- **Secure Authentication** - Powered by Laravel Breeze with session-based authentication
- **Role-Based Access Control** - Implemented using Spatie Laravel Permission
- **Admin Role Management** - First registered user automatically becomes admin
- **Protected Routes** - Secure access to administrative functions

#### Technical Features
- **Responsive Design** - Mobile-first approach with Tailwind CSS
- **Real-time Interface** - SPA experience with Inertia.js
- **Type Safety** - Full TypeScript integration for robust development
- **Media Management** - Advanced file handling with Spatie Media Library
- **API Resources** - Structured data transformation with Laravel API Resources
- **Form Validation** - Client and server-side validation
- **Queue System** - Background job processing for heavy operations

### 🛠️ Technology Stack

#### Backend
- **PHP 8.2+** - Modern PHP with latest features and performance improvements
- **Laravel 12** - Latest version of the robust PHP framework
- **Spatie Laravel Permission** - Comprehensive role and permission management
- **Spatie Media Library** - Advanced media handling and image processing
- **Laravel Tinker** - Powerful REPL for Laravel applications

#### Frontend
- **Vue.js 3** - Progressive JavaScript framework with Composition API
- **TypeScript** - Type-safe JavaScript development
- **Inertia.js** - Modern monolith approach combining SPA benefits with server-side routing
- **Ant Design Vue** - Enterprise-class UI design system
- **Tailwind CSS** - Utility-first CSS framework for rapid UI development
- **Vite** - Fast build tool and development server

#### Development Tools
- **PEST PHP** - Elegant testing framework for PHP
- **Laravel Pint** - Code style fixer for consistent formatting
- **ESLint** - JavaScript linting for code quality
- **Prettier** - Code formatting for consistent style
- **Ziggy** - Laravel named routes in JavaScript
- **Concurrently** - Run multiple development servers simultaneously

#### Database & Storage
- **SQLite** - Default lightweight database (MySQL/PostgreSQL compatible)
- **Laravel Migrations** - Version control for database schema
- **Database Seeders** - Consistent data seeding for development

#### Additional Features
- **Image Processing** - Automatic image conversion and optimization
- **Queue Workers** - Background task processing
- **Mail System** - Email notifications and communications
- **Logging** - Comprehensive application logging with Laravel Pail
- **Error Handling** - Graceful error management and reporting

## Installation

### Prerequisites
- **PHP 8.2 or higher** - Modern PHP runtime with improved performance
- **Composer** - Dependency manager for PHP packages
- **Node.js 18+** - JavaScript runtime for frontend development
- **pnpm** - Fast, disk space efficient package manager
- **Database** - SQLite (default), MySQL, or PostgreSQL
- **Git** - Version control system for cloning the repository

### Quick Start

#### 1. Clone and install dependencies:
```bash
git clone https://github.com/fahrirusliyadi/company-employee.git
cd company-employee
composer install
pnpm install
```

#### 2. Environment setup:
```bash
cp .env.example .env
php artisan key:generate
```

Required environment variables:

- `APP_KEY` - Application encryption key (auto-generated)
- `APP_ENV` - Application environment (local, production, etc.)
- `APP_DEBUG` - Debug mode toggle for development
- `APP_URL` - Base URL of your application
- `DB_CONNECTION` - Database driver (sqlite, mysql, pgsql)
- `DB_DATABASE` - Database name or file path for SQLite
- `MAIL_MAILER=smtp` - Mail driver configuration
- `MAIL_HOST=sandbox.smtp.mailtrap.io` - SMTP server host
- `MAIL_USERNAME=<your_mailtrap_username>` - SMTP authentication username  
- `MAIL_PASSWORD=<your_mailtrap_password>` - SMTP authentication password
- `QUEUE_CONNECTION` - Queue driver (sync, database, redis)
- `SESSION_DRIVER` - Session storage driver
- `CACHE_STORE` - Cache storage driver

#### 3. Database setup:
```bash
php artisan migrate --seed
```

#### 4. Link storage:
```bash
php artisan storage:link
```

#### 4. Start development servers:
```bash
composer run dev
```
This command starts all development services concurrently:
- **Laravel Development Server** (http://localhost:8000)
- **Queue Worker** - Processes background jobs
- **Laravel Pail** - Real-time log monitoring  
- **Vite Dev Server** - Hot module replacement for frontend assets

#### 5. Access the application:
- **Main Application**: http://localhost:8000
- **Admin Dashboard**: Login with your registered admin account

## 🔐 Authentication & Authorization

#### Default Admin Role
The first user to register in the application will automatically be assigned the `admin` role with full system privileges.

#### Available Roles
- **Admin** - Full system access, user management, company and employee CRUD
- **User** - Limited access based on assigned permissions

#### Security Features
- Session-based authentication with CSRF protection
- Role and permission-based access control
- Secure password hashing with bcrypt
- Protected routes and middleware guards
- Input validation and sanitization

## 🧪 Testing

Run the test suite to ensure everything works correctly:

```bash
# Run all tests
composer run test

# Run specific test types
php artisan test --feature    # Feature tests
php artisan test --unit       # Unit tests

# Run tests with coverage
php artisan test --coverage
```

## 🏗️ Development

### Code Quality Tools

```bash
# Fix code style with Laravel Pint
./vendor/bin/pint

# Lint frontend code
pnpm run lint

# Type check TypeScript
pnpm run build
```


### Queue Management

```bash
# Process queue jobs manually
php artisan queue:work

# Monitor failed jobs
php artisan queue:failed

# Retry failed jobs  
php artisan queue:retry all
```

## 📁 Project Structure

```
├── app/
│   ├── Http/
│   │   ├── Controllers/    # Application controllers
│   │   ├── Requests/       # Form request validation
│   │   └── Resources/      # API resource transformers
│   ├── Models/             # Eloquent models
│   │   ├── Company.php     # Company model with media handling
│   │   ├── Employee.php    # Employee model
│   │   └── User.php        # User authentication model
├── database/
│   ├── migrations/         # Database schema migrations
│   ├── seeders/            # Database seeders
│   └── factories/          # Model factories for testing
├── resources/
│   ├── js/
│   │   ├── Components/     # Reusable Vue components
│   │   ├── Layouts/        # Application layouts
│   │   ├── Pages/          # Inertia.js page components
│   │   └── types/          # TypeScript type definitions
│   └── css/                # Stylesheets and Tailwind config
├── routes/
│   ├── web.php             # Web routes
│   └── auth.php            # Authentication routes
└── tests/                  # PEST test suites
```

## 🤝 Contributing

Contributions are welcome! Please follow the GitHub workflow:

1. **Fork the repository** on GitHub
2. **Clone your fork** locally:
   ```bash
   git clone https://github.com/YOUR_USERNAME/company-employee.git
   cd company-employee
   ```
3. **Add upstream remote** to stay in sync:
   ```bash
   git remote add upstream https://github.com/fahrirusliyadi/company-employee.git
   ```
4. **Create a feature branch** from main:
   ```bash
   git checkout -b feature/amazing-feature
   ```
5. **Make your changes** following coding standards
6. **Run tests** to ensure nothing breaks:
   ```bash
   composer run test
   pnpm run lint
   ```
7. **Commit your changes** with a descriptive message:
   ```bash
   git add .
   git commit -m "feat: add amazing feature
   
   - Detailed description of what was added
   - Why this change was needed
   - Any breaking changes"
   ```
8. **Push to your fork**:
   ```bash
   git push origin feature/amazing-feature
   ```
9. **Create a Pull Request** on GitHub with:
   - Clear title and description
   - Reference any related issues
   - Screenshots if UI changes are involved

### Development Guidelines
- Follow PSR-12 coding standards for PHP
- Use TypeScript for all frontend development
- Write comprehensive tests for new features
- Update documentation for significant changes
- Ensure all tests pass before submitting PR

### Commit Message Convention
Follow [Conventional Commits](https://www.conventionalcommits.org/) specification:
- `feat:` - New features
- `fix:` - Bug fixes
- `docs:` - Documentation changes
- `style:` - Code formatting (no logic changes)
- `refactor:` - Code refactoring
- `test:` - Adding or updating tests
- `chore:` - Maintenance tasks

### Pull Request Process
1. **Update documentation** if you've made changes to functionality
2. **Add tests** for new features or bug fixes
3. **Ensure CI passes** - all tests and code quality checks
4. **Request review** from maintainers
5. **Address feedback** and update your PR as needed
6. **Squash commits** if requested before merge

### Code Review Guidelines
- Be respectful and constructive
- Explain the "why" behind code suggestions
- Test locally before approving
- Check for security implications
- Verify documentation is updated

## 📝 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## 🔧 Troubleshooting

### Common Issues

**Port already in use:**
```bash
# Kill process on port 8000
lsof -ti:8000 | xargs kill -9
```

**Permission issues with storage:**
```bash
chmod -R 755 storage bootstrap/cache
```

**Node modules issues:**
```bash
rm -rf node_modules pnpm-lock.yaml
pnpm install
```

**Database connection issues:**
- Ensure database file exists: `touch database/database.sqlite`
- Check `.env` database configuration
- Run migrations: `php artisan migrate`

### Getting Help

- Check the [Laravel Documentation](https://laravel.com/docs)
- Review [Vue.js Guide](https://vuejs.org/guide/)
- Consult [Inertia.js Documentation](https://inertiajs.com/)
- Open an issue for bug reports or feature requests
