<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('couriers', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('phone', 20)->unique();
            $table->unsignedTinyInteger('level');
            $table->date('registered_at');
            $table->timestamps();

            $table->index('level');
            $table->index('registered_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('couriers');
    }
};
