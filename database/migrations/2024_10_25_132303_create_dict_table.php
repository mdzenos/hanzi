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
        Schema::create('dict', function (Blueprint $table) {
            $table->id();
            $table->integer('id_hanzi');
            $table->string('hanzi')->nullable();
            $table->string('pinyin')->nullable();
            $table->string('hanviet')->nullable();
            $table->json('mean')->nullable();
            $table->string('rank')->nullable();
            $table->string('sound')->nullable();
            $table->string('from')->nullable();
            $table->integer('count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dict');
    }
};
