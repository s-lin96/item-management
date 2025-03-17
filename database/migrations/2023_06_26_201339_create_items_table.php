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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('name', 100);
            $table->tinyInteger('is_deleted')->default(1)->unsigned();
            $table->tinyInteger('type')->unsigned();
            $table->text('detail', 500);
            $table->integer('stock')->unsigned();
            $table->integer('safe_stock')->unsigned();
            $table->tinyInteger('stock_status')->unsigned();
            $table->tinyInteger('unit')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
