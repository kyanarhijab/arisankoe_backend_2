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
        Schema::create('arisan_rounds', function (Blueprint $table) {
            $table->id();

            $table->string('group_id', 10);
            $table->string('winner_id', 50);
            $table->foreign(['group_id', 'winner_id'])
                  ->references(['group_id', 'user_id'])
                  ->on('participants')
                  ->onDelete('set null'); 
            $table->integer('round_numbers');
            $table->date('draw_date')->default(DB::raw('CURRENT_DATE'));
            $table->string('notes', 500);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arisan_rounds');
    }
};
