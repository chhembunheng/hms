# Hotel Management System (HMS)

A comprehensive Hotel Management System built with Laravel, designed to streamline hotel operations including reservations, guest management, billing, and administrative tasks.

## 📋 Project Information

**Academic Project**
- **Institution:** Beltei International University
- **Major:** Software Engineering (Batch 5)
- **Academic Year:** Year 4, Semester 2
- **Subject:** Software Project Management
- **Lecturer:** CHEN SOVANN

### 👥 Development Team

- **CHHEM BUNHENG** - Team Lead & Backend Developer
- **LUN SOCHEAT** - Full Stack Developer
- **OURK ASDA** - Frontend Developer & UI/UX Designer

## 🚀 Features

### Core Modules
- **Dashboard** - Real-time overview of hotel operations
- **Check-In/Check-Out** - Guest arrival and departure management
- **Rooms Management** - Room inventory, status, and assignment
- **Guest Management** - Guest profiles and history
- **Billing System** - Invoice generation and payment processing
- **Seasons Management** - Pricing and availability by season
- **Exchange Rate** - Multi-currency support
- **Cash Closing** - Daily financial reconciliation
- **Extra Services** - Additional hotel services billing
- **Maintenance** - Facility maintenance tracking
- **Reports** - Comprehensive business intelligence reports

### Settings & Administration
- **User Management** - User accounts and authentication
- **Role & Permissions** - Fine-grained access control (RBAC)
- **Menu Management** - Dynamic navigation system
- **Multi-language Support** - English and Khmer (ភាសាខ្មែរ)
- **System Configuration** - Customizable system settings

## 📦 Technology Stack

### Backend
- **Framework:** Laravel 12.42.0
- **PHP Version:** 8.3.27
- **Database:** MySQL/MariaDB
- **Authentication:** JWT (tymon/jwt-auth)
- **API:** RESTful architecture

### Frontend
- **CSS Framework:** Tailwind CSS v3 + Bootstrap 5
- **JavaScript:** Vanilla JS + Alpine.js (Livewire)
- **Build Tool:** Vite
- **Icons:** FontAwesome 6

### Additional Libraries
- **DataTables:** Yajra DataTables for server-side processing
- **PDF Generation:** DomPDF
- **Excel Export:** PhpSpreadsheet
- **Cache:** Redis/Predis
- **Queue:** Laravel Queue

---

## 🚀 Quick Start

### Prerequisites
- **PHP** >= 8.3
- **Composer** (PHP dependency manager)
- **Node.js** 18+ and npm (JavaScript package manager)
- **MySQL** >= 8.0 or **MariaDB** >= 10.6
- **Git**

### Installation Steps

1. **Clone the repository**
   ```bash
   git clone https://github.com/chhembunheng/hms.git
   cd hms
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Set up environment file**
   ```bash
   cp .env.example .env
   ```

   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=hms_db
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Generate application key**
   ```bash
   php artisan key:generate
   ```

6. **Generate JWT secret**
   ```bash
   php artisan jwt:secret
   ```

7. **Create database**
   ```sql
   CREATE DATABASE hms_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

8. **Run migrations and seeders**
   ```bash
   php artisan migrate:fresh --seed
   ```

   This will create all tables and seed initial data including:
   - Admin user account
   - Default roles and permissions
   - System menus and navigation
   - Sample data for testing

9. **Build frontend assets**

   For development:
   ```bash
   npm run dev
   ```

   For production:
   ```bash
   npm run build
   ```

10. **Create storage link**
    ```bash
    php artisan storage:link
    ```

11. **Set proper permissions**
    ```bash
    chmod -R 775 storage bootstrap/cache
    chown -R www-data:www-data storage bootstrap/cache
    ```

12. **Start development server**
    ```bash
    php artisan serve
    ```

    Access the application at: `http://localhost:8000`

---

## 🔐 Default Credentials

After seeding, login with:

```
Username: admin
Email: admin@hotel.com
Password: password
```

**⚠️ Important:** Change these credentials immediately in production!

---

## 🛠️ Additional Configuration

### Cache Optimization

For better performance in production:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

To clear cache during development:

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### Queue Configuration

To run background jobs:

```bash
php artisan queue:work
```

For production, use Supervisor to manage queue workers.

### Task Scheduling

Add to your crontab for scheduled tasks:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

---

## 📋 Project Structure

```
hms/
├── app/
│   ├── Console/              # Artisan commands
│   ├── DataTables/           # DataTables definitions
│   │   ├── Frontend/         # Frontend module datatables
│   │   └── Settings/         # Settings module datatables
│   ├── Helpers/              # Helper functions (jwt.php, helpers.php)
│   ├── Http/
│   │   ├── Controllers/      # Application controllers
│   │   ├── Middleware/       # Custom middleware
│   │   └── Requests/         # Form validation requests
│   ├── Mail/                 # Mailable & queued jobs
│   ├── Models/               # Eloquent models
│   │   ├── Frontend/         # Frontend module models
│   │   └── Settings/         # Settings module models
│   ├── Traits/               # Reusable traits
│   └── Providers/            # Service providers
├── config/                   # Configuration files
│   ├── init.php             # App-wide settings (locales, formats)
│   ├── jwt.php              # JWT authentication config
│   ├── datatables.php       # DataTables configuration
│   └── ...
├── database/
│   ├── migrations/          # Database schema migrations
│   ├── seeders/             # Database seeders
│   │   └── data/            # JSON seed data
│   │       └── backend/
│   │           └── menus.json
│   └── factories/           # Model factories for testing
├── public/
│   ├── assets/              # Compiled assets
│   │   ├── css/             # Stylesheets
│   │   └── js/              # JavaScript (theme.js)
│   ├── site/                # Static files
│   └── index.php            # Application entry point
├── resources/
│   ├── css/                 # Source CSS (Tailwind + custom)
│   │   └── app.css
│   ├── js/                  # Source JavaScript
│   │   ├── app.js
│   │   └── theme.js
│   └── views/               # Blade templates
│       ├── auth/            # Authentication pages
│       ├── dashboard/       # Dashboard interface
│       ├── layouts/         # Layout templates
│       │   └── partials/    # Reusable components
│       ├── settings/        # Settings module views
│       │   ├── menus/
│       │   ├── permissions/
│       │   ├── roles/
│       │   └── users/
│       └── components/      # Blade components
├── routes/
│   ├── web.php              # Main web routes
│   ├── auth.php             # Authentication routes
│   ├── settings.php         # Settings module routes
│   └── console.php          # Console commands
├── storage/                 # File storage & logs
│   ├── app/                 # Uploaded files
│   ├── framework/           # Cache & sessions
│   └── logs/                # Application logs
├── tests/                   # Pest test suite
│   ├── Feature/             # Feature tests
│   └── Unit/                # Unit tests
├── vite.config.js           # Vite build configuration
├── tailwind.config.js       # Tailwind CSS configuration
├── phpunit.xml              # PHPUnit configuration
├── composer.json            # PHP dependencies
├── package.json             # JavaScript dependencies
└── README.md                # This file
```

---

## 🏗️ Key Features & Architecture

### Authentication & Authorization
- **JWT Authentication**: Secure token-based authentication using `tymon/jwt-auth`
- **Role-Based Access Control (RBAC)**: Fine-grained permissions system
- **Multi-level Permissions**: Menu-based permission structure
- **Password Hashing**: Bcrypt encryption for user passwords

### Multi-Language Support
- **Locales**: English (en) and Khmer (km)
- **Database Translations**: Separate translation tables for dynamic content
- **Language Switcher**: Easy switching between languages
- **RTL Support**: Right-to-left text support for applicable languages

### DataTables Integration
- **Server-side Processing**: Efficient handling of large datasets
- **Export Functionality**: PDF and Excel export capabilities
- **Custom Filters**: Advanced filtering options
- **Responsive Design**: Mobile-friendly table layouts

### Database Architecture
- **Migrations**: Version-controlled database schema
- **Seeders**: Automated data population for testing
- **Soft Deletes**: Preserve deleted records for auditing
- **Relationships**: Eloquent ORM for complex data relationships

---

## 🚢 Production Deployment

### Using Laravel Sail (Docker)

```bash
./vendor/bin/sail up -d
./vendor/bin/sail artisan migrate:fresh --seed
./vendor/bin/sail npm run build
```

### Traditional Server Deployment

1. **Configure environment**
   ```bash
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Optimize application**
   ```bash
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   npm run build
   ```

3. **Web server configuration**
   - Point document root to `public/` directory
   - Enable mod_rewrite (Apache) or equivalent
   - Configure SSL certificate for HTTPS

4. **File permissions**
   ```bash
   chmod -R 755 storage bootstrap/cache
   chown -R www-data:www-data storage bootstrap/cache
   ```

5. **Additional setup**
   - Configure queue worker (Supervisor recommended)
   - Set up automated database backups
   - Configure log rotation
   - Enable OPcache for PHP

---

## 🧪 Testing

### Run All Tests
```bash
php artisan test
```

### Using Pest
```bash
./vendor/bin/pest
```

### Test Coverage
```bash
./vendor/bin/pest --coverage
```

### Test Configuration
- **Framework**: Pest PHP Testing Framework
- **Database**: SQLite in-memory for fast testing
- **Configuration**: See `phpunit.xml`

---

## 🔧 Configuration Tips

### Environment Variables

Key `.env` configurations for HMS:

```env
APP_NAME="Hotel Management System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=hms_db
DB_USERNAME=your_username
DB_PASSWORD=your_password

JWT_SECRET=your-jwt-secret-key
JWT_TTL=60

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### Important Config Files
- **`config/init.php`**: Locales, date/time formats, system settings
- **`config/jwt.php`**: JWT authentication configuration
- **`config/datatables.php`**: DataTables engine settings
- **`tailwind.config.js`**: Tailwind theme customization
- **`vite.config.js`**: Asset build configuration

---

## 📦 Key Dependencies

### Backend (Composer)
- **laravel/framework** ^12.0 — Core framework
- **laravel/livewire** — Dynamic UI components
- **tymon/jwt-auth** — JWT authentication
- **yajra/laravel-datatables** — Server-side DataTables
- **spatie/laravel-permission** — Role & permission management
- **barryvdh/laravel-dompdf** — PDF generation
- **maatwebsite/excel** — Excel import/export
- **predis/predis** — Redis client

### Frontend (npm)
- **vite** ^5.0 — Fast build tool
- **tailwindcss** ^3.0 — Utility-first CSS
- **bootstrap** ^5.3 — UI components
- **@fortawesome/fontawesome-free** — Icon library
- **alpinejs** — Lightweight JavaScript framework

### Development
- **pestphp/pest** — Modern PHP testing
- **laravel/pint** — Code style formatter
- **nunomaduro/collision** — Error handler

---

## 📁 Common Development Tasks

### Creating New Modules

**1. Create a new controller**
```bash
php artisan make:controller Settings/MyModuleController
```

**2. Add routes**
Edit `routes/settings.php` or `routes/web.php`

**3. Create views**
```bash
mkdir -p resources/views/settings/mymodule
```

**4. Add to menu**
Edit `database/seeders/data/backend/menus.json` and reseed

### Database Operations

**Create migration**
```bash
php artisan make:migration create_my_table
```

**Create model with migration**
```bash
php artisan make:model Models/MyModel -m
```

**Create seeder**
```bash
php artisan make:seeder MySeeder
```

**Refresh database**
```bash
php artisan migrate:fresh --seed
```

### Adding DataTable

**1. Create DataTable class**
```bash
php artisan datatables:make MyDataTable
```

**2. Configure in controller**
```php
public function index(MyDataTable $dataTable)
{
    return $dataTable->render('mymodule.index');
}
```

**3. Use in view**
```blade
<x-datatables title="My List" :data="$dataTable" />
```

### Code Quality

**Format PHP code**
```bash
./vendor/bin/pint
```

**Run tests**
```bash
php artisan test
```

**Check for issues**
```bash
./vendor/bin/phpstan analyse
```

---

## 🤝 Contributing

This is an academic project for Beltei International University. Contributions are welcome!

### Development Workflow

1. **Fork the repository**
2. **Create feature branch**
   ```bash
   git checkout -b feature/amazing-feature
   ```
3. **Make your changes**
4. **Format code**
   ```bash
   ./vendor/bin/pint
   ```
5. **Test your changes**
   ```bash
   php artisan test
   ```
6. **Commit changes**
   ```bash
   git commit -m 'Add amazing feature'
   ```
7. **Push to branch**
   ```bash
   git push origin feature/amazing-feature
   ```
8. **Open Pull Request**

### Code Style
- Follow PSR-12 coding standards
- Use Laravel best practices
- Write descriptive commit messages
- Add tests for new features

---

## 📄 License

This project is developed for educational purposes as part of the Software Project Management course at Beltei International University.

**Academic License** - Free to use for educational and learning purposes.

---

## 🐛 Troubleshooting

### Common Issues

**1. Permission denied errors**
```bash
chmod -R 775 storage bootstrap/cache
chown -R $USER:www-data storage bootstrap/cache
```

**2. JWT secret not set**
```bash
php artisan jwt:secret
```

**3. Vite not building assets**
```bash
rm -rf node_modules package-lock.json
npm install
npm run build
```

**4. Database connection refused**
- Check MySQL/MariaDB is running
- Verify database credentials in `.env`
- Ensure database exists

**5. Class not found errors**
```bash
composer dump-autoload
php artisan clear-compiled
php artisan optimize:clear
```

---

## 📞 Support & Contact

For questions, issues, or collaboration:

### Development Team

- **CHHEM BUNHENG**
  - Role: Team Lead & Backend Developer
  - GitHub: [@chhembunheng](https://github.com/chhembunheng)

- **LUN SOCHEAT**
  - Role: Full Stack Developer

- **OURK ASDA**
  - Role: Frontend Developer & UI/UX Designer

### Academic Supervisor

- **Lecturer:** CHEN SOVANN
- **Course:** Software Project Management
- **Institution:** Beltei International University

---

## 🙏 Acknowledgments

- Laravel Framework Team
- Tailwind CSS Team
- FontAwesome
- Yajra DataTables
- All open-source contributors

---

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)
- [DataTables Documentation](https://datatables.net/)
- [JWT Auth Documentation](https://jwt-auth.readthedocs.io/)
- [Pest PHP Documentation](https://pestphp.com/)

---

**Built with ❤️ by Software Engineering Students**

**Beltei International University - Batch 5**

*Year 4, Semester 2 - December 2025*

---

**⭐ If you find this project helpful, please consider giving it a star!**

### Build Frontend
```bash
npm run build
```

### Optimize Production
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### Clear Caches
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 🐛 Troubleshooting

### Database not found
```bash
touch database/database.sqlite
php artisan migrate
```

### Clear all caches
```bash
composer run dev  # Includes cache clearing in the startup process
```

### Vite hot reload not working
- Ensure `npm run dev` is running
- Check `vite.config.js` HMR configuration
- Clear browser cache and refresh

### JWT token expired
- Check `.env` `JWT_TTL` (time-to-live in minutes)
- Refresh token endpoint: typically `POST /auth/refresh`

---

## 📚 Documentation & Resources

- **Laravel**: https://laravel.com/docs/12
- **Vite**: https://vitejs.dev
- **Tailwind CSS**: https://tailwindcss.com
- **Pest**: https://pestphp.com
- **JWT Auth**: https://jwt-auth.readthedocs.io

---

## 📞 Support & Contributing

For issues, feature requests, or contributions, please refer to [SECURITY.md](SECURITY.md).

---

**Last Updated**: 2025-10-23
**Version**: 1.0.0
