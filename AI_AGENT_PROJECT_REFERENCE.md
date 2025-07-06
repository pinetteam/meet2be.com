# MEET2BE.COM - AI AGENT REFERENCE DOCUMENTATION
## 🚨 CRITICAL: THIS FILE IS A REFERENCE DOCUMENT FOR AI AGENTS

### 📌 PROJECT OVERVIEW
- **Project**: Meet2Be Event Management System
- **Technology**: Laravel 12, FluxUI Pro v2, Alpine.js, Tailwind CSS v4
- **Database**: MySQL/PostgreSQL (UUID primary keys mandatory)
- **Architecture**: Multi-tenant (Site + Portal structure)

### 🎯 CORE RULES - NEVER COMPROMISE

#### 1. TECHNOLOGY STACK - IMMUTABLE
```
✅ MANDATORY:
- Laravel 12 (Symfony/CodeIgniter forbidden)
- FluxUI Pro v2 (React/Vue/Livewire forbidden)
- Alpine.js (jQuery/Vanilla JS forbidden)
- FontAwesome Pro (Heroicons/Lucide forbidden)
- Tailwind CSS (Bootstrap/Bulma forbidden)
- Vite (Webpack/Mix forbidden)
```

#### 2. UUID USAGE - NO EXCEPTIONS
```php
// ✅ CORRECT - Mandatory in every model
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class User extends Model
{
    use HasUuids;
    protected $keyType = 'string';
    public $incrementing = false;
}

// ❌ WRONG - Never use
$table->id(); // FORBIDDEN
$table->bigIncrements('id'); // FORBIDDEN
```

#### 3. DIRECTORY STRUCTURE - HIERARCHICAL MANDATORY
```
Controllers:     Site/Auth/LoginController
Views:          site/auth/login.blade.php
Requests:       Site/Auth/LoginRequest
Routes:         site.auth.login
JS/CSS:         resources/js/site/auth.js
```

### 🏗️ PROJECT ARCHITECTURE

#### SECTIONS
1. **Site**: Public area (login, homepage)
2. **Portal**: Admin panel (auth required)

#### USER TYPES
```php
const USER_TYPE_ADMIN = 'admin';      // Portal access
const USER_TYPE_SCREENER = 'screener'; // Portal access  
const USER_TYPE_OPERATOR = 'operator'; // Portal access
const USER_TYPE_USER = 'user';         // Site user
```

#### ROUTE STRUCTURE
```php
// routes/site.php
Route::get('/', [Site\Home\HomeController::class, 'index'])->name('home.index');
Route::get('/login', [Site\Auth\LoginController::class, 'create'])->name('auth.login');
Route::post('/login', [Site\Auth\LoginController::class, 'store']);
Route::post('/logout', [Site\Auth\LogoutController::class, 'destroy'])->name('auth.logout');

// routes/portal.php  
Route::middleware(['auth'])->group(function () {
    Route::get('/', [Portal\Dashboard\DashboardController::class, 'index'])->name('dashboard.index');
});
```

### 📂 IMPORTANT DIRECTORIES AND FILES

#### CSS/JS ORGANIZATION
```
resources/css/
├── site/
│   └── site.css      # Site section styles
└── portal/
    └── portal.css    # Portal section styles

resources/js/
├── site/
│   └── site.js       # Site section JS
└── portal/
    └── portal.js     # Portal section JS
```

#### LANGUAGE FILES (Laravel 12 standard) - JSON FORMAT
```
lang/                 # NOT resources/lang!
├── en.json          # English translations (JSON format)
├── tr.json          # Turkish translations (JSON format)
├── en/              # Laravel default PHP files
│   ├── auth.php
│   ├── validation.php
│   ├── passwords.php
│   └── pagination.php
└── tr/              # Laravel default PHP files (Turkish)
    ├── auth.php
    ├── validation.php
    ├── passwords.php
    └── pagination.php
```

### 🔐 AUTHENTICATION SYSTEM

#### IMPORTANT NOTES
1. **SINGLE AUTH**: Only Site has login, NO separate Portal login
2. **REDIRECTION**: After login based on user type:
   - admin/screener/operator → Portal dashboard
   - user → Site homepage
3. **MIDDLEWARE**: Laravel 12 now defines it in route files
4. **LOGIN PAGE**: Standalone page, doesn't use site layout
5. **DARK MODE**: Forced dark mode across all pages

#### LOGIN PAGE FEATURES
- Modern split-screen design (form left, testimonial right)
- Dark mode only (forced via `class="dark"` on html tag)
- Responsive: Image side hidden on mobile
- Full localization support
- Remember me for 30 days option
- No social login buttons (removed)

#### LOGIN CONTROLLER EXAMPLE
```php
namespace App\Http\Controllers\Site\Auth;

class LoginController extends Controller
{
    public function store(LoginRequest $request)
    {
        if (!Auth::attempt($request->validated())) {
            return back()->withErrors(['email' => __('auth.failed')]);
        }

        $user = Auth::user();
        $user->updateLoginInfo($request);

        return redirect()->intended(
            in_array($user->type, ['admin','screener','operator']) 
                ? route('portal.dashboard.index') 
                : route('site.home.index')
        );
    }
}
```

### 🎨 FLUXUI V2 USAGE

#### CORRECT USAGE
```blade
{{-- ✅ CORRECT --}}
<flux:input name="email" :label="__('auth.email')" />
<flux:button type="submit">{{ __('auth.login') }}</flux:button>

{{-- ❌ WRONG - Old version --}}
<x-flux::input /> {{-- v1 syntax, don't use --}}
```

#### DARK MODE CONFIGURATION
```blade
{{-- All layouts must have dark class --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

{{-- Body styling for dark mode --}}
<body class="font-sans antialiased bg-stone-900 text-stone-100">

{{-- Form elements dark mode styling --}}
<flux:input class="bg-stone-800 text-white border-stone-700 focus:border-indigo-500" />

{{-- NO NEED for these directives in Livewire 3 + Flux UI v2 --}}
{{-- They are loaded automatically via composer --}}
{{-- @livewireStyles --}}
{{-- @fluxStyles --}}
{{-- @livewireScripts --}}
{{-- @fluxScripts --}}
```

#### FLUX PUBLISH COMMAND
```bash
php artisan flux:publish
```

### 🌍 MULTI-LANGUAGE STRUCTURE - JSON FORMAT

#### JSON FILE USAGE (Recommended by Laravel 12)
```blade
{{-- ✅ CORRECT - Using JSON translations --}}
<h1>{{ __('Welcome') }}</h1>
<p>{{ __('Sign In') }}</p>
<button>{{ __('Save') }}</button>

{{-- ❌ WRONG - Old PHP file format --}}
<h1>{{ __('site.common.welcome') }}</h1>
```

#### JSON FILE STRUCTURE
```json
// lang/en.json
{
    "Welcome": "Welcome",
    "Sign In": "Sign In",
    "Email Address": "Email Address",
    "Password": "Password",
    "Remember me": "Remember me"
}

// lang/tr.json
{
    "Welcome": "Hoş geldiniz",
    "Sign In": "Giriş Yap",
    "Email Address": "E-posta Adresi",
    "Password": "Şifre",
    "Remember me": "Beni hatırla"
}
```

### 📊 DATABASE STRUCTURE

#### MAIN TABLES
```
1. System Tables (system_ prefix):
   - system_countries
   - system_languages  
   - system_currencies
   - system_timezones
   - system_country_language (pivot)

2. User Tables:
   - users (UUID, type, status, login tracking)
   - user_password_reset_tokens

3. Tenant Tables:
   - tenants (multi-database support)

4. Event Tables:
   - events
   - event_venues
```

### ⚠️ IMPORTANT CONSIDERATIONS

1. **NEVER USE AUTO-INCREMENT ID**: All tables must use UUID
2. **USE ROUTE::RESOURCE**: Don't define manual routes
3. **CREATE REQUEST CLASS**: Don't use $request->input() directly
4. **USE LANGUAGE VARIABLES**: Don't write hardcoded text
5. **HIERARCHICAL NAMESPACE**: Don't use flat controller/model structure
6. **USE JSON TRANSLATIONS**: Laravel 12 recommends JSON format for translations

### 🚀 QUICK START COMMANDS

```bash
# Project setup
composer install
npm install
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate
php artisan db:seed

# Frontend build
npm install @tailwindcss/postcss autoprefixer --save-dev
npm run dev

# FluxUI setup
php artisan flux:publish

# Language files (if needed)
php artisan lang:publish
```

### 📝 CURRENT STATUS

1. **Completed Tasks**:
   - Multi-tenant structure established
   - UUID migration system completed
   - Site/Portal separation done
   - Authentication system set up
   - Language files prepared in TR/EN (JSON format)
   - Laravel default language files translated to Turkish
   - FluxUI v2 integration completed
   - Login page redesigned as standalone (no site layout)
   - Dark mode forced across all pages
   - Social login buttons removed from login page
   - FluxUI stone theme implemented
   - Removed unnecessary Livewire/Flux directives (auto-loaded in v3)

2. **Active Routes**:
   - `/` - Site homepage
   - `/login` - Login page (standalone design)
   - `/portal` - Portal dashboard (auth required)

3. **Test Users**:
   - admin@meet2be.com / password (admin)
   - user@meet2be.com / password (user)

### 🔴 CRITICAL REMINDERS

1. **Laravel 12 Differences**:
   - `lang/` directory (not under resources)
   - Middleware defined in route files
   - Livewire comes automatically (includes Alpine.js)
   - JSON format recommended for translations

2. **FluxUI v2 Differences**:
   - `<flux:` prefix usage
   - `flux:publish` command required
   - Old `x-flux::` syntax not used

3. **Project Standards**:
   - Every change must comply with @pinet-standards.md
   - KISS and DRY principles are absolute
   - Comments FORBIDDEN (code must be self-explanatory)

### 🎯 SUGGESTIONS FOR NEXT STEPS

1. Event CRUD operations can be added
2. Tenant switching mechanism can be set up
3. API endpoints can be added
4. Role/Permission system can be expanded
5. Notification system can be added
6. Social login integration (Google, GitHub)

---
**NOTE**: This document is prepared for AI agents. Use and update this reference when continuing the project. 