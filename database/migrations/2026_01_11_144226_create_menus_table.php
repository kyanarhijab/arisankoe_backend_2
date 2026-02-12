<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id(); // ✅ PK teknis
            $table->string('kode_menu', 10)->unique(); // kode bisnis
            $table->string('title', 100);
            $table->string('icon', 100)->nullable();
            $table->string('path', 100)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('order_no')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('parent_id')
                  ->references('id')
                  ->on('menus')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
