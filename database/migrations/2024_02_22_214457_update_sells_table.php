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
        Schema::table('sells', function (Blueprint $table) {
            $table->dropForeign(['good_id']);
            $table->dropColumn('good_id');
            $table->dropColumn('quantity');
            $table->dropColumn('total');
            $table->bigInteger('total_price')->default(0);
            $table->bigInteger('total_items')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
