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
        Schema::create('participants', function (Blueprint $table) {
    $table->id();

    // relasi ke users
    $table->string('user_id', 50);
    $table->foreign('user_id')
      ->references('username')
      ->on('users')
      ->onDelete('cascade');

    // relasi ke users
    $table->string('group_id', 10);
    $table->foreign('group_id')
      ->references('kode')
      ->on('arisan_groups')
      ->onDelete('cascade');

    $table->date('join_date')->default(DB::raw('CURRENT_DATE'));
    $table->enum('status', ['active', 'resign'])->default('active');

    $table->timestamps();

    // mencegah user join group yang sama 2x
    $table->unique(['user_id', 'group_id']);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
    }
};
