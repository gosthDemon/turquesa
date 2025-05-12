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
        Schema::create('users', function (Blueprint $table) {
            // Identification
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');

            // User status
            $table->enum('status', ['inactive', 'active', 'banned', 'locked'])->default('active');
            $table->timestamp('email_verified_at')->nullable();

            // Security
            $table->integer('failed_attempts')->default(0);
            $table->timestamp('locked_until')->nullable();
            $table->string('reset_code')->nullable();
            $table->boolean('reset_password')->default(true);

            // Activity
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip')->nullable();

            // Change tracking
            $table->boolean('was_updated')->default(false);

            // Relationship
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->unsignedBigInteger('created_by_id')->nullable();
            $table->unsignedBigInteger('updated_by_id')->nullable();
            $table->foreign('created_by_id')->references('id')->on('users');
            $table->foreign('updated_by_id')->references('id')->on('users');

            // Tokens & timestamps
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->unsignedBigInteger('user_id');
            $table->string('ip_address', 45)->nullable();
            $table->enum('device_type', ["phone", "tablet", "pc/laptop", "unknow"])->default('unknow');
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('forced_expires_at')->nullable();

            $table->boolean('is_active')->default(true);
            $table->timestamp('last_activity')->nullable();
            $table->string('location')->nullable();
            $table->boolean('is_expired')->default(false);

            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->boolean('is_mobile')->default(false);

            $table->integer('failed_attempts')->default(0);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};