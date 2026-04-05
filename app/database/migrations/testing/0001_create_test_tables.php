<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('image_hashes', function (Blueprint $table) {
            $table->id();
            $table->string('hash', 256);
            $table->text('comment')->nullable();
            $table->boolean('ng')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('image_hash_id')->nullable();
            $table->string('basename', 100)->index();
            $table->string('ext', 10)->nullable();
            $table->string('t_ext', 10)->nullable()->default('jpg');
            $table->string('original')->nullable();
            $table->string('delkey', 20)->nullable();
            $table->string('mimetype', 100)->nullable();
            $table->integer('size')->nullable();
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->text('comment')->nullable();
            $table->string('ip', 40)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('image_hash_id')->references('id')->on('image_hashes');
        });

        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('image_id');
            $table->text('comment');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('image_id')->references('id')->on('images');
        });

        Schema::create('deny_words', function (Blueprint $table) {
            $table->id();
            $table->string('word', 200);
            $table->timestamps();
        });

        Schema::create('deny_ips', function (Blueprint $table) {
            $table->id();
            $table->string('ip', 40)->nullable();
            $table->boolean('is_tor')->default(false);
            $table->timestamps();
        });

        Schema::create('chunk_files', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->boolean('is_uploaded')->default(false)->index();
            $table->boolean('is_fail')->default(false)->index();
            $table->string('ip', 40)->nullable();
            $table->string('original', 128)->nullable();
            $table->integer('number')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('comments');
        Schema::dropIfExists('images');
        Schema::dropIfExists('image_hashes');
        Schema::dropIfExists('deny_words');
        Schema::dropIfExists('deny_ips');
        Schema::dropIfExists('chunk_files');
        Schema::dropIfExists('users');
    }
};
