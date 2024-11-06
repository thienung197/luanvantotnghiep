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
            $table->unsignedBigInteger('goods_issue_detail_id');
            $table->unsignedBigInteger('warehouse_id');
            $table->unsignedBigInteger('batch_id');
            $table->integer('quantity');
            $table->timestamps();

            $table->foreign('goods_issue_detail_id')
                ->references('id')
                ->on('goods_issue_details')
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
