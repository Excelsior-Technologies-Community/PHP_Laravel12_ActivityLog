<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ProductController extends Controller
{
    public function create()
    {
        // Create a new product record
        $product = Product::create([
            'name' => 'Laptop',
            'price' => 50000
        ]);

        return "Product Created";
    }

    public function update()
    {
        // Update the first available product
        $product = Product::first();
        $product->update([
            'price' => 55000
        ]);

        return "Product Updated";
    }

    public function delete()
    {
        // Delete the first available product
        $product = Product::first();

        if ($product) {
            $product->delete();
            return "Product Deleted Successfully";
        }

        return "No Product Found To Delete";
    }

    public function logs()
    {
        // Retrieve all activity logs in ascending order
        $logs = \Spatie\Activitylog\Models\Activity::orderBy('id', 'asc')->get();
        return view('logs', compact('logs'));
    }
}
