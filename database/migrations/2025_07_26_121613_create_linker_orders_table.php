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
        Schema::create('linker_orders', function (Blueprint $table) {
            $table->id();
            $table->string('source');
            $table->decimal('total', 12, 2);
            $table->date('date');
            $table->timestamps();

            $table->index(['source', 'date']);
            $table->index(['id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linker_orders');
    }
};
