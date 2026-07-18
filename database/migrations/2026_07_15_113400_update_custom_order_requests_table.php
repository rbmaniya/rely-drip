<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE custom_order_requests MODIFY piece_type VARCHAR(255) NULL');

        Schema::table('custom_order_requests', function (Blueprint $table) {
            $table->string('design_reference')->nullable()->after('vision');
        });
    }

    public function down(): void
    {
        Schema::table('custom_order_requests', function (Blueprint $table) {
            $table->dropColumn('design_reference');
        });

        DB::statement('ALTER TABLE custom_order_requests MODIFY piece_type VARCHAR(255) NOT NULL');
    }
};
