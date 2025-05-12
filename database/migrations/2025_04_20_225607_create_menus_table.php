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
        Schema::create('menus', function (Blueprint $table) {
            $table->id();

            $table->string('label');
            $table->string('path')->unique()->nullable();
            $table->string('icon')->nullable();
            $table->integer('order')->default(0);
            $table->enum('status', ['inactive', 'active', 'disabled'])->default('active');

            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('menus');

            $table->boolean('is_routable')->default(true);
            $table->boolean('is_report')->default(false);

            $table->unsignedBigInteger('entity_id')->nullable();
            $table->string('report_name')->unique()->nullable();
            $table->foreign('entity_id')->references('id')->on('entities');

            $table->unsignedBigInteger('created_by_id');
            $table->unsignedBigInteger('updated_by_id');
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('updated_by_id')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};