<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id'); // Kolom ID dengan tipe BIGINT yang auto-increment
            $table->string('name'); // Kolom name dengan tipe VARCHAR(255) yang tidak boleh null
            $table->unsignedBigInteger('category_id'); // Kolom category_id dengan tipe BIGINT yang tidak boleh null
            $table->decimal('price', 10, 2); // Kolom price dengan tipe DECIMAL(10, 2) yang tidak boleh null
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Kolom created_at dengan default CURRENT_TIMESTAMP
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Kolom updated_at dengan default CURRENT_TIMESTAMP
    
            // Menambahkan foreign key untuk category_id
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
