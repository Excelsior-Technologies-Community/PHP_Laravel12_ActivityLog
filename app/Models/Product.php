<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Product extends Model
{
    use LogsActivity; // Enables automatic activity logging for this model

    protected $fillable = ['name', 'price']; // Mass assignable fields

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()               // Log all model attributes
            ->logOnlyDirty()         // Log only changed attributes
            ->setDescriptionForEvent(fn(string $eventName) => "Product has been {$eventName}"); // Set custom log description
    }
}
