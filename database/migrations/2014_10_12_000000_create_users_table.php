<?php

use App\Models\Location;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('birth_date')->nullable();
            $table->string('phone', 20)->nullable();
            $table->enum('status', ['active', 'inactive']);
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignIdFor(Warehouse::class)->nullable()->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Location::class)->nullable()->constrained()->cascadeOnUpdate();
            $table->enum('type', ['admin', 'manager', 'customer'])->default('customer')->nullable();
            // $table->timestamp('email_verified_at')->nullable();
            // $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
