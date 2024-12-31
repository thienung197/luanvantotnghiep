<?php

use App\Models\Product;
use App\Models\RestockRequest;
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
        Schema::create('restock_request_details', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(RestockRequest::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Product::class)->constrained()->cascadeOnDelete();
            $table->integer('quantity');
            $table->enum('status', ['pending', 'approved', 'rejected', 'ordered', 'fulfilled']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restock_request_details');
    }
};
