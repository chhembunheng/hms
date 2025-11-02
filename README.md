# WinTechWebsite

**WINTECH SOFTWARE DEVELOPMENT (Cambodia) Co., LTD** — A modern Laravel 12 + Vite + Tailwind CMS for managing IT solutions, services, products, and business operations.

---

## 🚀 Quick Start

### Prerequisites
- **PHP** ^8.2
- **Node.js** 18+ (for Vite)
- **Composer** (PHP dependency manager)
- **npm** or **yarn** (JavaScript package manager)

### Installation

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd WinTechWebsite
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
   php artisan key:generate
   ```

5. **Run migrations**
   ```bash
   php artisan migrate
   ```

6. **Seed database (optional)**
   ```bash
   php artisan db:seed
   ```

---

## 🛠️ Development

### Start Full Development Environment
```bash
composer run dev
```

This single command runs:
- Laravel development server (`php artisan serve`)
- Queue worker listener (`php artisan queue:listen`)
- Pail log viewer
- Vite frontend dev server (`npm run dev`)

All processes run concurrently for a seamless development experience.

### Individual Commands

**Frontend dev (Vite + Tailwind)**
```bash
npm run dev
```

**Backend dev (Laravel)**
```bash
php artisan serve
```

**Build for production**
```bash
npm run build
```

---

## 📋 Project Structure

```
WinTechWebsite/
├── app/
│   ├── Console/              # Artisan commands
│   ├── DataTables/           # Server-side DataTable logic
│   ├── Helpers/              # Global helpers (jwt.php, helpers.php)
│   ├── Http/
│   │   ├── Controllers/      # Request handlers
│   │   ├── Middleware/       # Auth, JWT, etc.
│   │   └── Requests/         # Form validation
│   ├── Mail/                 # Mailable & queued jobs
│   ├── Models/               # Eloquent models
│   └── Providers/            # Service providers
├── config/                   # Configuration files
│   ├── init.php             # App-wide settings (locales, date formats)
│   ├── services.php         # Third-party service credentials
│   ├── jwt.php              # JWT authentication config
│   └── ...
├── database/
│   ├── migrations/          # Schema changes
│   ├── seeders/             # Database seeders
│   └── factories/            # Model factories for testing
├── public/
│   ├── site/
│   │   ├── data/            # JSON files (services, products, etc.)
│   │   │   ├── en/
│   │   │   └── km/
│   │   └── assets/          # Images, CSS, JS
│   └── index.php            # Entry point
├── resources/
│   ├── css/                 # Tailwind + custom CSS
│   ├── js/                  # Vue/Alpine + app JS
│   └── views/
│       ├── auth/            # Authentication views
│       ├── dashboard/       # Dashboard pages
│       ├── frontend/        # CMS form views
│       ├── sites/sections/  # Public-facing sections
│       └── layouts/         # Layout templates
├── routes/
│   ├── web.php              # Web routes
│   ├── frontend.php         # Frontend CMS routes
│   └── landing.php          # Public landing page routes
├── tests/                   # Pest test suite
├── vite.config.js           # Vite configuration
├── tailwind.config.js       # Tailwind CSS configuration
├── phpunit.xml              # PHPUnit config (sqlite in-memory)
├── composer.json            # PHP dependencies
├── package.json             # JavaScript dependencies
└── README.md
```

---

## 🏗️ Architecture & Key Features

### MVC Pattern
- **Routes**: `routes/` map HTTP requests to controllers
- **Controllers**: `app/Http/Controllers/` handle business logic
- **Views**: `resources/views/` render Blade templates with Tailwind

### Authentication & Authorization
- **JWT Auth**: `tymon/jwt-auth` configured in `config/jwt.php`
- **Middleware**: Auth checks in `app/Http/Middleware/`
- **Helpers**: JWT utilities in `app/Helpers/jwt.php`

### Frontend (Public Site)
- **Data-driven**: JSON files in `public/site/data/{locale}/` (services.json, products.json, etc.)
- **Sections**: Reusable Blade components in `resources/views/sites/sections/`
- **Localization**: Multi-language support (en, km) via `config/init.php`

### Backend (CMS Dashboard)
- **Forms**: CRUD interfaces for Services, Products, Teams, Clients, etc.
- **DataTables**: Server-side pagination via `yajra/laravel-datatables`
- **Translations**: Multi-locale content management (translations table)
- **Meta SEO**: Automatic meta title/description generation via OpenAI

### Queue System
- **Jobs**: Background tasks in `app/Jobs/` and `app/Mail/`
- **Worker**: Started in `composer run dev` with `php artisan queue:listen`
- **Driver**: Configured in `.env` (default: sqlite)

### Caching & SEO
- **Response Cache**: `spatie/laravel-responsecache`
- **Sitemap**: `spatie/laravel-sitemap` (auto-generated from CMS content)
- **SEO Tools**: `artesaos/seotools` for meta tags

### Octane (Production)
- **Runtime**: Supports Swoole/RoadRunner for long-running processes
- **Statefulness**: Be careful with global mutable state — it persists across requests

---

## 🧪 Testing

### Run All Tests
```bash
composer test
```

### Test Configuration
- **Framework**: Pest (dev dependency)
- **Database**: SQLite in-memory (fast, no setup required)
- **Config**: See `phpunit.xml`

### Example Test (Pest style)
```php
test('can fetch services', function () {
    $services = \App\Models\Frontend\Service::all();
    expect($services)->toBeIterable();
});
```

---

## 🔧 Configuration

### Environment Variables (`.env`)
```env
APP_NAME="IT Solutions CMS"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

JWT_SECRET=your-secret-key
OPENAI_API_KEY=your-openai-key
```

### Key Config Files
- **`config/init.php`**: Locales, date/time formats, languages
- **`config/jwt.php`**: JWT expiration, algorithm
- **`config/services.php`**: Third-party services (Slack, AWS, etc.)
- **`tailwind.config.js`**: Tailwind theme, colors, plugins

---

## 🌍 Multi-Language Support

### Locales
- **English (en)**: `en-US`
- **Khmer (km)**: `km-KH`

### Language Files
```
lang/
├── en/
│   ├── root.php          # Core UI strings
│   └── global.php        # Public site strings
└── km/
    ├── root.php
    └── global.php
```

### Usage in Blade
```blade
{{ __('global.our_services') }}
{{ __('form.meta.title') }}
```

---

## 📦 Key Dependencies

### Backend (Composer)
- **laravel/framework** ^12.0 — Core framework
- **laravel/octane** — High-performance request handler
- **tymon/jwt-auth** — JWT authentication
- **yajra/laravel-datatables** — Server-side DataTables
- **spatie/laravel-responsecache** — HTTP response caching
- **spatie/laravel-sitemap** — XML sitemap generation
- **artesaos/seotools** — SEO meta tag management
- **laravel/pint** — PHP code formatter

### Frontend (npm)
- **vite** — Module bundler
- **tailwindcss** — Utility-first CSS framework
- **postcss** — CSS transformation
- **autoprefixer** — CSS vendor prefixes

### Development
- **pest** — Modern PHP testing framework
- **phpunit** — Unit/feature testing

---

## 📁 Common Tasks

### Add a New Page + Controller
1. Create controller: `app/Http/Controllers/Frontend/MyPageController.php`
2. Add route: `routes/frontend.php`
3. Create view: `resources/views/frontend/my-page/index.blade.php`

### Add API Endpoint
1. Create controller: `app/Http/Controllers/Api/MyApiController.php`
2. Add validation: `app/Http/Requests/MyApiRequest.php`
3. Add route: `routes/api.php`

### Add Database Migration
```bash
php artisan make:migration create_my_table
php artisan migrate
```

### Create a Seeder
```bash
php artisan make:seeder MySeeder
```

### Format PHP Code
```bash
./vendor/bin/pint
```

---

## 🚀 Deployment

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

## 📄 License

This project is part of WINTECH SOFTWARE DEVELOPMENT (Cambodia) Co., LTD. All rights reserved.

---

**Last Updated**: 2025-10-23  
**Version**: 1.0.0

