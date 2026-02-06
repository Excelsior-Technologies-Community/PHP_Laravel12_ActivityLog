#  PHP_Laravel12_ActivityLog

![Laravel](https://img.shields.io/badge/Laravel-12-red)
![PHP](https://img.shields.io/badge/PHP-8.2-blue)
![Database](https://img.shields.io/badge/Database-MySQL-orange)
![License](https://img.shields.io/badge/License-MIT-green)

---

##  Project Overview

This project demonstrates how to integrate an **Activity Logging System** in Laravel 12 using the Spatie Laravel Activity Log package.

The system automatically tracks and stores model events such as:

* Product Created
* Product Updated
* Product Deleted

All activities are stored in the `activity_log` table and displayed in a web-based interface.

---

##  Features

* Laravel 12 setup
* MySQL database integration
* Automatic activity logging
* Tracks created, updated, deleted events
* Stores old and new values
* Simple Blade UI to display logs
* Clean and structured project

---

##  Requirements

* PHP 8.2 or higher
* Composer
* MySQL
* XAMPP / Local Server
* Laravel 12

---

##  Folder Structure

```
app/
 ├── Models/
 │    └── Product.php
 ├── Http/
 │    └── Controllers/
 │         └── ProductController.php

resources/
 └── views/
      └── logs.blade.php

routes/
 └── web.php

database/
 └── migrations/
      ├── create_products_table.php
      └── create_activity_log_table.php
```

---

#  Installation Guide

## STEP 1: Create Laravel 12 Project

```bash
composer create-project laravel/laravel Laravel12_ActivityLog
```

## STEP 2: Configure Database

Update `.env` file:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=activitylog
DB_USERNAME=root
DB_PASSWORD=
```

Create database:

```sql
CREATE DATABASE activitylog;
```

Run migrations:

```bash
php artisan migrate
```

---

## STEP 3: Install Activity Log Package

```bash
composer require spatie/laravel-activitylog
```
```
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
```
```
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
```
```
php artisan migrate
```

Now the `activity_log` table is created.

---

## STEP 4: Create Product Model

```bash
php artisan make:model Product -m
```

Update migration file:

```php
public function up(): void
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->integer('price');
        $table->timestamps();
    });
}
```

Run migration:

```bash
php artisan migrate
```

---

## STEP 5: Enable Activity Logging

File: `app/Models/Product.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use LogsActivity;

    protected $fillable = ['name', 'price'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->setDescriptionForEvent(
                fn(string $eventName) => "Product has been {$eventName}"
            );
    }
}
```

---

## STEP 6: Create Controller

```bash
php artisan make:controller ProductController
```

File: `app/Http/Controllers/ProductController.php`

```php
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Spatie\Activitylog\Models\Activity;

class ProductController extends Controller
{
    public function create()
    {
        Product::create([
            'name' => 'Laptop',
            'price' => 50000
        ]);

        return "Product Created";
    }

    public function update()
    {
        $product = Product::first();
        $product->update(['price' => 55000]);

        return "Product Updated";
    }

    public function delete()
    {
        $product = Product::first();

        if ($product) {
            $product->delete();
            return "Product Deleted Successfully";
        }

        return "No Product Found To Delete";
    }

    public function logs()
    {
        $logs = Activity::orderBy('id', 'asc')->get();
        return view('logs', compact('logs'));
    }
}
```

---

## STEP 7: Define Routes

File: `routes/web.php`

```php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/create-product', [ProductController::class, 'create']);
Route::get('/update-product', [ProductController::class, 'update']);
Route::get('/delete-product', [ProductController::class, 'delete']);
Route::get('/logs', [ProductController::class, 'logs']);
```

---

## STEP 8: Create Logs Blade View

File: `resources/views/logs.blade.php`

```blade
<!DOCTYPE html>
<html>
<head>
    <title>Activity Logs</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid black; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>

<h2>Activity Logs</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Description</th>
            <th>Event</th>
            <th>Subject ID</th>
            <th>Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse($logs as $log)
            <tr>
                <td>{{ $log->id }}</td>
                <td>{{ $log->description }}</td>
                <td>{{ $log->event }}</td>
                <td>{{ $log->subject_id }}</td>
                <td>{{ $log->created_at }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5">No Activity Logs Found</td>
            </tr>
        @endforelse
    </tbody>
</table>

</body>
</html>
```

---

## ▶ Run the Application

```bash
php artisan serve
```

Visit:

```
http://127.0.0.1:8000/create-product
```
<img width="429" height="102" alt="Screenshot 2026-02-06 170129" src="https://github.com/user-attachments/assets/8616ff2b-0da1-4554-b56f-c08839a53c42" />

```
http://127.0.0.1:8000/update-product
```
<img width="389" height="105" alt="Screenshot 2026-02-06 170145" src="https://github.com/user-attachments/assets/901b8a45-f713-436d-9b19-ea6a43c260d3" />

```
http://127.0.0.1:8000/delete-product
```
<img width="434" height="125" alt="Screenshot 2026-02-06 171639" src="https://github.com/user-attachments/assets/9da46e9e-aecc-4360-b832-6b6130c06034" />

```
http://127.0.0.1:8000/logs
```
<img width="1919" height="327" alt="Screenshot 2026-02-06 170229" src="https://github.com/user-attachments/assets/df83cf23-4f16-4214-9f8d-ed75a8b76ad8" />

---

##  Conclusion

This project successfully demonstrates:

* Laravel 12 installation
* Database configuration
* Activity Log integration
* Automatic model event tracking
* Log storage in database
* Log display using Blade view

---


