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
            $table->id();
            $table->string('gender')->nullable();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone')->nullable();
            $table->string('avatar')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->text('address')->nullable();
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->rememberToken();
            $table->author();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->author();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('passkeys', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('name');
            $table->string('credential_id');
            $table->json('data');
            $table->author();
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('administrator')->default(0);
            $table->tinyInteger('sort')->default(0);
            $table->author();
        });
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable();
            $table->tinyInteger('sort')->default(0);
            $table->string('route')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->author();
        });
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('action_route')->nullable();
            $table->string('icon')->nullable();
            $table->enum('target', ['navbar', 'index', 'action', 'self', 'confirm', 'modal'])->default('index');
            $table->string('action')->nullable();
            $table->string('slug')->unique()->index()->nullable();
            $table->tinyInteger('sort')->default(0);
            $table->unsignedBigInteger('menu_id')->index();
            $table->author();
        });
        Schema::create('role_permission', function (Blueprint $table) {
            $table->unsignedBigInteger('role_id')->index();
            $table->unsignedBigInteger('permission_id')->index();
        });
        Schema::create('user_role', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('role_id')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_role');
        Schema::dropIfExists('role_permission');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
    }
};
