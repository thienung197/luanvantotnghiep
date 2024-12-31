<?php

use App\Models\Customer;
use App\Models\User;
use App\Models\Warehouse;
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
        Schema::create('goods_issues', function (Blueprint $table) {
            $table->id();
            $table->string('code', 30);
            // $table->foreignIdFor(User::class, 'creator_id')->constrained('users')->cascadeOnDelete();
            // $table->foreignIdFor(Warehouse::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'approved_by')->nullable()->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['pending', 'approved']);
            // $table->decimal('discount', 10, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_issues');
    }
};
