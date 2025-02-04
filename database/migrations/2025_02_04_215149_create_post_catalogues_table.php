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
        Schema::create('post_catalogues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->default(1)->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('canonical')->unique();
            $table->string('description')->nullable();
            $table->string('content')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_description')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->longText('album')->nullable();
            $table->tinyInteger('publish')->default(2);
            $table->integer('order')->default(0);
            $table->integer('lft')->nullable();
            $table->integer('rgt')->nullable();
            $table->integer('level')->nullable();
            $table->integer('parent_id')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_catalogues');
    }
};
