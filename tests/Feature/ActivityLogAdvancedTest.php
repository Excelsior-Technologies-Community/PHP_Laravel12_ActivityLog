<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class ActivityLogAdvancedTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test 1-Click Activity Rollback for updated product.
     */
    public function test_activity_rollback_updated_product(): void
    {
        $product = Product::create([
            'name' => 'Original Product',
            'price' => 1000,
        ]);

        $product->update([
            'name' => 'Original Product',
            'price' => 2000,
        ]);

        $activity = Activity::where('event', 'updated')->latest()->first();

        $this->assertNotNull($activity);

        $response = $this->post(route('activity.rollback', $activity->id));

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'price' => 1000,
        ]);
    }

    /**
     * Test 1-Click Activity Rollback for deleted product.
     */
    public function test_activity_rollback_deleted_product(): void
    {
        $product = Product::create([
            'name' => 'Deleted Item',
            'price' => 3500,
        ]);

        $productId = $product->id;
        $product->delete();

        $activity = Activity::where('event', 'deleted')->latest()->first();

        $this->assertNotNull($activity);

        $response = $this->post(route('activity.rollback', $activity->id));

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('products', [
            'name' => 'Deleted Item',
            'price' => 3500,
        ]);
    }

    /**
     * Test Security & Suspicious Activity Detector Telemetry on Dashboard.
     */
    public function test_suspicious_activity_security_detector_dashboard(): void
    {
        for ($i = 0; $i < 3; $i++) {
            $p = Product::create(['name' => "Item {$i}", 'price' => 100]);
            $p->delete();
        }

        $response = $this->get(route('activity.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Suspicious Activity');
        $response->assertSee('Security Threat Detector');
        $response->assertSee('CRITICAL THREAT DETECTED');
    }

    /**
     * Test Deep Visual Diff Comparator rendering on activity detail view.
     */
    public function test_visual_diff_comparator_rendering(): void
    {
        $product = Product::create([
            'name' => 'Initial Title',
            'price' => 500,
        ]);

        $product->update([
            'price' => 750,
        ]);

        $activity = Activity::where('event', 'updated')->latest()->first();

        $response = $this->get(route('activity.show', $activity->id));

        $response->assertStatus(200);
        $response->assertSee('Deep Visual Diff Comparator');
        $response->assertSee('MODIFIED');
    }

    /**
     * Test Log Retention & Auto-Pruning.
     */
    public function test_log_retention_and_pruning(): void
    {
        $oldActivity = Activity::create([
            'log_name' => 'default',
            'description' => 'Old activity log entry',
            'created_at' => now()->subDays(40),
        ]);

        $recentActivity = Activity::create([
            'log_name' => 'default',
            'description' => 'Recent activity log entry',
            'created_at' => now()->subDays(5),
        ]);

        $response = $this->post(route('activity.prune'), ['days' => 30]);

        $response->assertSessionHas('success');
        $this->assertDatabaseMissing('activity_log', ['id' => $oldActivity->id]);
        $this->assertDatabaseHas('activity_log', ['id' => $recentActivity->id]);
    }

    /**
     * Test Log Export Archive CSV download.
     */
    public function test_log_export_archive(): void
    {
        Activity::create([
            'log_name' => 'default',
            'description' => 'Archived log test',
            'created_at' => now()->subDays(40),
        ]);

        $response = $this->get(route('activity.export-archive', ['days' => 30]));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    }
}
