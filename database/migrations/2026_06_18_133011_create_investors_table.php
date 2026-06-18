<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investors', function (Blueprint $table): void {
            $table->unsignedBigInteger('investor_id')->primary();
            $table->string('name');
            $table->unsignedTinyInteger('age');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investors');
    }
};
