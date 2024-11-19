<?php

use App\Models\ComprehensiveStockReport;
use App\Models\Product;
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
        Schema::create('comprehensive_stock_report_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('comprehensive_stock_report_id');
            $table->foreignIdFor(Product::class)->constrained();
            $table->integer('beginning_inventory');
            $table->integer('stock_in');
            $table->integer('stock_out');
            $table->integer('ending_inventory');
            $table->foreign('comprehensive_stock_report_id', 'csr_detail_csr_id_foreign')
                ->references('id')->on('comprehensive_stock_reports')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comprehensive_stock_report_details');
    }
};
