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
        Schema::create('lost_items', function (Blueprint $table) {
            $table->increments('lost_item_id');
            $table->string('item_name', 255);
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('location_id');
            $table->string('student_id', 11)->nullable();
            $table->string('owner_first_name', 100)->nullable();
            $table->string('owner_last_name', 100)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('tel', 20)->nullable();
            $table->unsignedBigInteger('user_id');
            $table->date('found_date');
            $table->date('created_at_date')->nullable();
            $table->date('returned_date')->nullable();
            $table->enum('status', ['pending', 'returned'])->default('pending');
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->tinyInteger('is_image_hidden')->default(0);
            $table->timestamp('returned_timestamp')->nullable();
            $table->timestamps();

            $table->foreign('category_id')->references('category_id')->on('categories');
            $table->foreign('location_id')->references('location_id')->on('locations');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lost_items');
    }
};
