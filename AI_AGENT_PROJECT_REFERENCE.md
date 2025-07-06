# MEET2BE.COM - AI AGENT REFERENCE DOCUMENTATION
## 🚨 CRITICAL: THIS FILE IS A REFERENCE DOCUMENT FOR AI AGENTS

### 📌 PROJECT OVERVIEW
- **Project**: Meet2Be Event Management System
- **Technology**: Laravel 12, Alpine.js, Tailwind CSS v4
- **Database**: MySQL/PostgreSQL (UUID primary keys mandatory)
- **Architecture**: Multi-tenant (Site + Portal structure)

### 🎯 CORE RULES - NEVER COMPROMISE

#### 1. TECHNOLOGY STACK - IMMUTABLE
```
✅ MANDATORY:
- Laravel 12 (Symfony/CodeIgniter forbidden)
- Alpine.js 3.x (React/Vue/Livewire/jQuery forbidden)
- Tailwind CSS v4 (Bootstrap/Bulma/FluxUI forbidden)
- FontAwesome Pro (Heroicons/Lucide forbidden)
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
Controllers:     Panel/User/UserController
Views:          portal/user/index.blade.php
Requests:       Panel/User/StoreUserRequest
Routes:         panel.users.index
JS/CSS:         resources/js/portal/user.js
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

// routes/panel.php  
Route::middleware(['auth'])->group(function () {
    Route::get('/', [Panel\Dashboard\DashboardController::class, 'index'])->name('dashboard.index');
    Route::resource('users', Panel\User\UserController::class);
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
│   └── site.js       # Site section JS + Alpine.js
└── portal/
    └── portal.js     # Portal section JS + Alpine.js
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

### 🎨 FRONTEND STACK - TAILWIND + ALPINE.JS

#### HTML STRUCTURE
```blade
{{-- ✅ CORRECT - Pure Tailwind + Alpine.js --}}
<div class="bg-white dark:bg-zinc-800 rounded-lg shadow-md p-6" x-data="{ open: false }">
    <button @click="open = !open" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
        {{ __('Toggle') }}
    </button>
    <div x-show="open" x-transition class="mt-4">
        <p class="text-gray-700 dark:text-gray-300">{{ __('Content') }}</p>
    </div>
</div>

{{-- ❌ WRONG - No FluxUI components --}}
<flux:card>
    <flux:button>Button</flux:button>
</flux:card>
```

#### ALPINE.JS PATTERNS
```javascript
// ✅ CORRECT - Clean Alpine.js components
Alpine.data('userForm', () => ({
    form: {
        name: '',
        email: '',
        password: ''
    },
    loading: false,
    errors: {},
    
    async submit() {
        this.loading = true;
        this.errors = {};
        
        try {
            const response = await fetch('/api/users', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(this.form)
            });
            
            if (!response.ok) {
                const data = await response.json();
                this.errors = data.errors || {};
                return;
            }
            
            // Success handling
            this.form = { name: '', email: '', password: '' };
            
        } catch (error) {
            console.error('Error:', error);
        } finally {
            this.loading = false;
        }
    }
}));
```

#### FORM COMPONENTS
```blade
{{-- ✅ CORRECT - Custom Blade components with Tailwind --}}
<div class="space-y-6">
    <x-input 
        name="name" 
        :label="__('Name')" 
        :value="old('name')"
        :error="$errors->first('name')"
    />
    
    <x-input 
        name="email" 
        type="email"
        :label="__('Email')" 
        :value="old('email')"
        :error="$errors->first('email')"
    />
    
    <x-button type="submit" class="w-full">
        {{ __('Save') }}
    </x-button>
</div>
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
    "Remember me": "Remember me",
    "Save": "Save",
    "Edit": "Edit",
    "Delete": "Delete",
    "Create": "Create"
}

// lang/tr.json
{
    "Welcome": "Hoş geldiniz",
    "Sign In": "Giriş Yap",
    "Email Address": "E-posta Adresi",
    "Password": "Şifre",
    "Remember me": "Beni hatırla",
    "Save": "Kaydet",
    "Edit": "Düzenle",
    "Delete": "Sil",
    "Create": "Oluştur"
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
7. **NO LIVEWIRE/FLUXUI**: Only Tailwind CSS + Alpine.js for frontend

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
   - Dark mode forced across all pages
   - Social login buttons removed from login page
   - Livewire/FluxUI completely removed
   - Pure Tailwind CSS + Alpine.js setup

2. **Active Routes**:
   - `/` - Site homepage
   - `/login` - Login page (standalone design)
   - `/portal` - Portal dashboard (auth required)

3. **Test Users**:
   - admin@example.com / password (admin)
   - Users are created for each tenant
   - Password for all test users: password

### 🔴 CRITICAL REMINDERS

1. **Laravel 12 Differences**:
   - `lang/` directory (not under resources)
   - Middleware defined in route files
   - JSON format recommended for translations

2. **Frontend Stack**:
   - ONLY Tailwind CSS for styling
   - ONLY Alpine.js for interactivity
   - NO FluxUI, NO Livewire, NO React/Vue

3. **Project Standards**:
   - Every change must comply with PINET standards
   - KISS and DRY principles are absolute
   - Comments FORBIDDEN (code must be self-explanatory)

### 🎯 SUGGESTIONS FOR NEXT STEPS

1. User CRUD operations with Tailwind + Alpine.js
2. Event CRUD operations can be added
3. Tenant switching mechanism can be set up
4. API endpoints can be added
5. Role/Permission system can be expanded
6. Notification system can be added

---
**NOTE**: This document is prepared for AI agents. Use and update this reference when continuing the project. 