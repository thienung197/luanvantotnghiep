<?php

use App\Models\Category;
use App\Models\Unit;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete();
            $table->string('description')->nullable();
            $table->foreignIdFor(Unit::class)->constrained()->cascadeOnDelete();
            $table->decimal('selling_price', 10, 2)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('refrigerated');
            $table->integer('minimum_stock_level')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
