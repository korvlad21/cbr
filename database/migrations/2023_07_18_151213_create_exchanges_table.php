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
        Schema::create('exchanges', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('charCode', 3);
            $table->foreign('charCode')
                ->references('charCode')
                ->on('currencies');
            $table->unsignedMediumInteger('nominal')->default(1);
            $table->double('rate');
            $table->double('difference');
            $table->index('date');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchanges');
    }
};
