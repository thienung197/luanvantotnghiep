<?php

use App\Models\Provider;
use App\Models\ReceivingDetail;
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
        Schema::create('goods_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ReceivingDetail::class)->constrained()->cascadeOnDelete();
            $table->string('code', 30);
            $table->foreignIdFor(Warehouse::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(User::class, 'creator_id')->constrained('users')->cascadeOnDelete();
            $table->foreignIdFor(Provider::class)->constrained()->cascadeOnDelete();
            // $table->decimal('discount')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_receipts');
    }
};
