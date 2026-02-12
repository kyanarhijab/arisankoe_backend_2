<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menu_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')
                  ->constrained('menus')
                  ->cascadeOnDelete();
            $table->string('role', 20);
            $table->timestamps();

            // cegah duplikat menu + role
            $table->unique(['menu_id', 'role']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_permissions');
    }
};
