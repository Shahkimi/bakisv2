Here is the complete single `.mdc` file:

````mdc
---
alwaysApply: true
---

# Laravel PHP Project Rules for AI Code Generation

## Core Principles
- Write concise, technical responses with accurate PHP examples
- Follow Laravel best practices and modern conventions (**Laravel 12.x**)
- Prioritize **SECURITY**, **PERFORMANCE**, and **MAINTAINABILITY** in all code
- Use object-oriented programming with strict SOLID principles
- Prefer composition over inheritance
- Use descriptive, self-documenting code with clear naming conventions
- Follow PSR-12 coding standards with strict typing
- Implement dependency injection and service container patterns
- Use **PHP 8.2+ features**: readonly classes, enums with methods, fibers, DNF types, `never` return type

## Security First Approach
- **Always use `declare(strict_types=1);`** at the top of every PHP file
- Use Laravel's built-in security features (CSRF, authentication, authorization)
- Implement **input validation on ALL user inputs** using Form Requests
- Use **parameterized queries** and Eloquent ORM (never raw SQL with user input)
- Implement **rate limiting** on all API endpoints and critical routes
- Use **Laravel Sanctum** for API authentication
- **Hash all sensitive data** using Laravel's Hash facade
- Implement **proper exception handling** with custom exceptions (never expose system errors)
- Use **Laravel's built-in CSRF protection** and validate all forms
- **Sanitize file uploads** — validate file types/sizes; `image` rule **blocks SVGs by default** in Laravel 12:
  ```php
  // To allow SVGs explicitly:
  'photo' => ['required', File::image(allowSvg: true)],
````

- Use **HTTPS only** in production with proper SSL configuration
- Implement **content security policies** and security headers

## PHP 8.2+ Modern Features (Required)

- Use **`readonly` classes** for immutable value objects and DTOs:
    ```php
    readonly class UserDTO
    {
        public function __construct(
            public string $name,
            public string $email,
            public string $role,
        ) {}
    }
    ```
- Use **`readonly` properties** on models/services where mutation is not needed
- Use **DNF types** for complex union/intersection types:
    ```php
    function process((Countable&Iterator)|array $value): void {}
    ```
- Use **`never`** return type for methods that always throw or exit
- Use **`enum` with methods and interfaces** — never use plain constants for status/type values:

    ```php
    enum UserStatus: string
    {
        case Active   = 'active';
        case Inactive = 'inactive';

        public function label(): string
        {
            return match($this) {
                self::Active   => 'Active User',
                self::Inactive => 'Inactive User',
            };
        }
    }
    ```

## Performance Optimization Rules

- **Database Queries**: Always use eager loading (`with()`, `load()`), avoid N+1 problems
- **Caching**: Implement Redis for sessions, cache, and queues
- **Query Optimization**: Use indexes, `select()` only needed columns
- Use **`reorderAsc()`/`reorderDesc()`** for expressive ordering (Laravel 12):
    ```php
    $users = User::reorderDesc('created_at')->get();
    ```
- Use **`nestedWhere()`** for complex conditional query logic:
    ```php
    $query->nestedWhere(fn($q) => $q->where('status', 'active')->orWhere('role', 'admin'));
    ```
- Use **`request()->clamp()`** to safely bound numeric input:
    ```php
    $perPage = request()->clamp('per_page', 1, 100, 50);
    ```
- **Asset Optimization**: Use **Laravel Vite** for asset bundling and optimization
- **Queue System**: Use queues for all time-consuming tasks (emails, file processing, API calls)
- **Response Caching**: Implement HTTP caching headers and response caching
- **Database Optimization**: Use migrations with proper indexes and constraints
- **Memory Management**: Use generators for large datasets, unset variables when done
- **DataTables**: Use server-side processing for large datasets to avoid memory issues
- **Cursor Pagination**: Use `cursorPaginate()` with API Resources (natively supported in Laravel 12):
    ```php
    return UserResource::collection(User::cursorPaginate(15));
    ```

## Controller Architecture (Ultra-Slim Controllers)

### Controller Rules

- **Max 15 lines** per method (strictly enforced)
- **Single Action Controllers** for specific functionality
- **Form Requests** for ALL validation (no validation in controllers)
- **Service Classes** for all business logic
- **Policies** for all authorization
- **Jobs/Events** for side effects and async operations
- **Resources/Transformers** for data formatting
- **Repository Pattern** for data access
- **Constructor injection** for all dependencies

### Controller Refactoring Matrix

```
Method > 15 lines          → Extract to Service
Validation present         → Move to Form Request
DB operations              → Move to Repository
Business logic             → Move to Service
File operations            → Create FileService
Email/notifications        → Use Jobs/Events
Authorization              → Move to Policy
Data formatting            → Use API Resources
DataTables processing      → Use Service class
```

### Example Controller Structure

```php
declare(strict_types=1);

namespace App\Http\Controllers\Admin\Laporan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Laporan\LampiranRequest;
use App\Services\Laporan\LaporanService;

class AdminLaporanController extends Controller
{
    public function __construct(
        private readonly LaporanService $laporanService
    ) {}

    public function lampiran_1_a(LampiranRequest $request)
    {
        $ptjId = $request->validated()['ptj_id'];

        if ($request->ajax()) {
            return $this->laporanService->getDataTableData($ptjId, $request);
        }

        $ptj = $this->laporanService->getPtj($ptjId);

        return view('superadmin.laporan.lampiran_1_a', compact('ptj'));
    }
}
```

## Service Layer Architecture

- **Service Classes**: Handle all business logic
- **Repository Pattern**: Abstract data access
- **Action Classes**: Single-purpose operations
- **Value Objects**: Use `readonly` classes for immutable data containers
- **DTOs**: Use `readonly` classes for data transfer between layers

### Code Quality Standard

```php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\UserRepositoryInterface;
use App\Exceptions\UserCreationException;
use App\DTOs\UserDTO;
use Illuminate\Support\Facades\Log;

final class UserService
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {}

    public function createUser(UserDTO $dto): User
    {
        try {
            return $this->userRepository->create((array) $dto);
        } catch (\Exception $e) {
            Log::error('User creation failed', ['error' => $e->getMessage()]);
            throw new UserCreationException('Failed to create user');
        }
    }
}
```

## Modern Laravel 12 Features

- Use **`HasUuids` trait** — now generates **UUIDv7** (ordered) by default in Laravel 12
    - For legacy UUIDv4 behavior, use `HasVersion4Uuids` explicitly:
        ```php
        use Illuminate\Database\Eloquent\Concerns\HasVersion4Uuids as HasUuids;
        ```
- Use **`HasUlids`** for ULID-based primary keys where lexicographic ordering is desired
- Use **Attribute Casting** for model data transformation
- Use **Model Factories** with relationships for testing
- Use **Model Observers** for model events
- Use **Global Scopes** for default filtering
- Use **Contextual Binding with PHP Attributes** (Laravel 12+):

    ```php
    use Illuminate\Container\Attributes\Bind;

    class OrderController
    {
        public function __construct(
            #[Bind(StripePaymentService::class)]
            private readonly PaymentServiceInterface $paymentService,
        ) {}
    }
    ```

- Use **`Concurrency::run()`** with associative arrays — results keyed by task name:
    ```php
    $results = Concurrency::run([
        'users'  => fn() => User::count(),
        'orders' => fn() => Order::count(),
    ]);
    // ['users' => 100, 'orders' => 250]
    ```

## Database & Eloquent Optimization

### Database Rules

- **Always use migrations** with proper `up()`/`down()` methods
- **Add indexes** for all foreign keys and frequently queried columns
- **Use database transactions** for multi-step operations
- **Implement soft deletes** where appropriate
- **Use eager loading** to prevent N+1 query problems
- **Multi-schema support** — use `Schema::getTables(schema: 'main')` to scope queries
- **Note**: `Connection::withTablePrefix()` removed in Laravel 12; use `$connection->getTablePrefix()` instead

### Eloquent Best Practices

- Use **Resource Controllers** with implicit model binding
- Use **Mutators/Accessors** with modern attribute syntax for data transformation
- Use **`select()`** to limit columns when querying large tables
- Use **`chunk()`** or **`cursor()`** for processing large datasets
- Use **Policy Classes** for model authorization
- Use **`reorderAsc()`/`reorderDesc()`** for expressive query reordering

### DataTables Server-Side Processing

```php
public function getDataTableData(Request $request): JsonResponse
{
    $query = Model::with(['relationship'])
        ->whereHas('evaluations', fn($q) => $q->where('c_panel', 1));

    if ($request->filled('search.value')) {
        $search = $request->input('search.value');
        $query->where(fn($q) => $q->where('name', 'like', "%{$search}%"));
    }

    $totalRecords    = Model::count();
    $filteredRecords = $query->count();

    $start  = $request->clamp('start', 0, PHP_INT_MAX, 0);
    $length = $request->clamp('length', 1, 100, 10);

    if ($request->filled('order')) {
        $columns = ['name', 'email', 'created_at'];
        $query->orderBy(
            $columns[$request->input('order.0.column')] ?? 'id',
            $request->input('order.0.dir')
        );
    }

    $data = $query->skip($start)->take($length)->get();

    return response()->json([
        'draw'            => intval($request->draw),
        'recordsTotal'    => $totalRecords,
        'recordsFiltered' => $filteredRecords,
        'data'            => $data->map(fn($item) => $this->formatDataTableRow($item)),
    ]);
}
```

## Testing Requirements

- **Feature Tests**: Test all endpoints and user workflows
- **Unit Tests**: Test all service classes and business logic
- **Database Testing**: Use `RefreshDatabase` and factories
- **Minimum 80% code coverage** for all business logic
- **Test-Driven Development** for complex features
- **PHPUnit**: Requires `^11.0` in Laravel 12 — or use **Pest `^3.0`** (recommended)
- **Carbon**: Must use **Carbon 3.x** (Carbon 2.x dropped in Laravel 12)

## API Design Standards

- Use **JSON:API** format for complex APIs
- Implement **API versioning** (v1, v2) from the start
- Use **rate limiting** on all endpoints
- Implement **proper HTTP status codes**
- Use **API Resources** for response transformation with `cursorPaginate()` support
- Add **API documentation** using Laravel Scribe or OpenAPI
- Implement **CORS** properly for frontend integration

## Starter Kits (Laravel 12)

- **New Official Starter Kits**: React, Svelte, Vue (Inertia 2 + TypeScript + shadcn/ui + Tailwind), Livewire (Flux UI + Volt)
- **WorkOS AuthKit variant** available for social auth, passkeys, and SSO (free up to 1M MAU)
- **Laravel Breeze and Jetstream**: No longer receive updates — migrate to new starter kits for new projects

## File Structure & Organization

```
app/
├── Http/
│   ├── Controllers/      # Ultra-slim controllers (max 15 lines per method)
│   ├── Requests/         # Form request validation
│   ├── Resources/        # API response transformation
│   └── Middleware/       # Custom middleware
├── Services/             # Business logic layer
├── Repositories/         # Data access layer
├── Models/               # Eloquent models
├── DTOs/                 # Readonly class DTOs (PHP 8.2+)
├── Jobs/                 # Queue jobs
├── Events/               # Event classes
├── Listeners/            # Event listeners
├── Policies/             # Authorization policies
├── Rules/                # Custom validation rules
└── Exceptions/           # Custom exceptions
```

## Development Workflow

- Use **Laravel Pint** for code formatting
- Use **PHPStan** (level 8+) for static analysis
- Implement **GitHub Actions** or **GitLab CI** for automated testing
- Use **Laravel Telescope** for debugging in development
- Implement **error tracking** with Sentry or Bugsnag

## Performance Monitoring

- Use **Laravel Horizon** for queue monitoring
- Implement **APM** (Application Performance Monitoring)
- Monitor **database query performance**
- Implement **log aggregation** and monitoring
- Use **health checks** for application monitoring

## Environment Configuration

- Use **environment-specific configuration**
- Implement **proper secret management**
- Configure **Redis** for sessions, cache, and queues
- Set up **queue workers** with proper supervision (Supervisor recommended)
- Configure **proper logging** with log rotation
- **Storage**: Default local disk root is now `storage/app/private` in Laravel 12
    - Define `local` disk explicitly in `filesystems.php` if your app depends on the old `storage/app` path

## Blade Template Best Practices

- Use **`@push('scripts')`** and **`@push('styles')`** for page-specific assets
- Use **`@include`** for reusable components
- Use **`@component`** for complex reusable UI elements
- Always escape output with **`{{ }}`**; use **`{!! !!}`** only when absolutely necessary
- Use **`@csrf`** token in all forms
- Use **`@method('PUT/PATCH/DELETE')`** for RESTful routes
- Implement **server-side DataTables** for tables with >100 rows
- Use **responsive design** principles in all views

## Project-Specific Context


- **Do NOT use** `php artisan serve` to run the application
- **DataTables**: Uses `yajra/laravel-datatables-oracle` package
- **Always use server-side processing** for DataTables with large datasets
- **Controller limit**: Strictly enforce 15-line maximum per method
- **Extract all business logic** to service classes
- **Use Form Requests** for all validation
- **Use Repository pattern** for data access when needed
- **Follow existing code patterns** in the codebase for consistency
- **UUIDs**: `HasUuids` now generates UUIDv7 — use `HasVersion4Uuids` for legacy UUIDv4 behavior
- **SVG uploads**: `image` rule blocks SVGs by default — use `File::image(allowSvg: true)` explicitly
- **Storage path**: Local disk root changed to `storage/app/private` — update `filesystems.php` if needed
- **`withTablePrefix()`**: Removed — use `$connection->getTablePrefix()` instead

```

***

Save this as `.cursor/rules/laravel.mdc` in your project root. The `alwaysApply: true` frontmatter ensures Cursor injects these rules into every AI request automatically without needing to reference it manually.
```
