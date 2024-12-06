<?php

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
        Schema::create('goods_issue_batches', function (Blueprint $table) {
            $table->id();
            $table->string('code', 100);
            $table->unsignedBigInteger('goods_issue_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('batch_id');
            $table->integer('quantity');
            $table->decimal('unit_price', 10, 2);
            $table->decimal('discount', 10, 2);
            $table->enum('status', ['processing', 'shipping', 'delivered']);
            $table->timestamps();

            $table->foreign('goods_issue_id')
                ->references('id')
                ->on('goods_issues')
                ->onDelete('cascade');

            $table->foreign('warehouse_id')
                ->references('id')
                ->on('warehouses')
                ->onDelete('cascade');

            $table->foreign('batch_id')
                ->references('id')
                ->on('batches')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('goods_issue_batches');
    }
};
