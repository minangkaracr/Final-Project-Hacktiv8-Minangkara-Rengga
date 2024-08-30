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
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id'); // Kolom ID dengan tipe BIGINT, auto-increment, dan sebagai primary key
            $table->unsignedBigInteger('user_id'); // Kolom user_id dengan tipe BIGINT, tidak boleh null
            $table->unsignedBigInteger('product_id'); // Kolom product_id dengan tipe BIGINT, tidak boleh null
            $table->integer('quantity'); // Kolom quantity dengan tipe INT, tidak boleh null
            $table->decimal('total_price', 10, 2); // Kolom total_price dengan tipe DECIMAL(10, 2), tidak boleh null
            $table->timestamp('order_date')->default(DB::raw('CURRENT_TIMESTAMP')); // Kolom order_date dengan default CURRENT_TIMESTAMP
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Kolom created_at dengan default CURRENT_TIMESTAMP
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP')); // Kolom updated_at dengan default CURRENT_TIMESTAMP dan update otomatis

            // Menambahkan foreign key untuk user_id
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            // Menambahkan foreign key untuk product_id
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
