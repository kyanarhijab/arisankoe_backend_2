<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('arisan_groups', function (Blueprint $table) {
            // PRIMARY KEY
            $table->id();

            // IDENTITAS GROUP
            $table->string('kode', 10)->unique();
            $table->string('name', 100);
            $table->text('description')->nullable();

            // SETTING ARISAN
            $table->integer('total_rounds');
            $table->decimal('amount', 12, 2);
            $table->date('start_date');

            // STATUS
            $table->enum('status', ['active', 'finished'])->default('active');

            // AUDIT
            $table->string('created_by', 6)->nullable();
            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arisan_groups');
    }
};
