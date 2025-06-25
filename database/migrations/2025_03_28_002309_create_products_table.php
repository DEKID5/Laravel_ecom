<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('category');
            $table->string('image');
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 10, 2);
            
            // Laptop fields
            $table->string('brand')->nullable();
            $table->string('model')->nullable();
            $table->string('cpu')->nullable();
            $table->string('cpu_generation')->nullable();
            $table->integer('storage_size')->nullable();
            $table->string('storage_type')->nullable();
            $table->integer('ram_size')->nullable();
            $table->string('cpu_type')->nullable();
            $table->string('gpu')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
};
