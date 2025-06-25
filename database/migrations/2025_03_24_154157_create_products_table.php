<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('productType');
        $table->string('image');
        $table->string('name')->nullable();
        $table->text('description');
        $table->decimal('price', 10, 2);
        $table->string('brand')->nullable();
        $table->string('model')->nullable();
        $table->string('cpu')->nullable();
        $table->string('storage')->nullable();
        $table->string('ram')->nullable();
        $table->string('gpu')->nullable();
        $table->timestamps();
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
