<?php

use App\Models\Location;
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
        Schema::table('providers', function (Blueprint $table) {
            if (!Schema::hasColumn('providers', 'location_id')) {
                $table->foreignId('location_id')->constrained('locations');
            }
        });
        Schema::table('providers', function (Blueprint $table) {
            $table->dropColumn("address");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('providers', function (Blueprint $table) {
            $table->dropForeign('[location_id]');
            $table->dropColumn('location_id');
        });
        Schema::table('providers', function (Blueprint $table) {
            $table->string('address', 100);
        });
    }
};
