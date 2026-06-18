<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investments', function (Blueprint $table): void {
            $table->id();
            $table->unsignedBigInteger('investor_id');
            $table->decimal('investment_amount', 15);
            $table->date('investment_date');

            $table->foreign('investor_id')
                ->references('investor_id')
                ->on('investors')
                ->cascadeOnDelete();

            $table->unique(['investor_id', 'investment_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
