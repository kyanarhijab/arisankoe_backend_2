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
        Schema::create('arisan_payments', function (Blueprint $table) {
           $table->id();
           $table->foreignId('round_id')
                 ->references('id')
                 ->on('arisan_rounds')
                 ->onDelete('cascade');
            $table->string('user_id', 50);
            $table->string('group_id', 10);
            $table->foreign(['user_id', 'group_id'])
                  ->references(['user_id', 'group_id'])
                  ->on('participants')
                  ->onDelete('cascade');  
            $table->decimal('amount_paid', 12, 2);
            $table->date('payment_date')->nullable();
            $table->enum('status', ['paid', 'unpaid'])
                ->default('unpaid');

            $table->text('notes')->nullable();

            $table->timestamps();

            // optional: supaya 1 participant cuma bayar 1x per round
            $table->unique(['group_id', 'round_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arisan_payments');
    }
};
