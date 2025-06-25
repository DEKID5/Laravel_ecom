<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The user sending the message
            $table->foreignId('store_id')->constrained()->onDelete('cascade'); // The store receiving the message
            $table->foreignId('product_id')->constrained()->onDelete('cascade'); // The product associated with the message
            $table->text('message'); // The content of the message
            $table->timestamps(); // Created at and updated at
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
