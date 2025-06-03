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
        Schema::create('info_tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sumary');
            $table->integer('easy');
            $table->integer('medium');
            $table->integer('hard');
            $table->integer('admin');
            $table->integer('recon');
            $table->integer('crypto');
            $table->integer('stegano');
            $table->integer('ppc');
            $table->integer('pwn');
            $table->integer('web');
            $table->integer('forensic');
            $table->integer('joy');
            $table->integer('misc');
            $table->integer('osint');
            $table->integer('reverse');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('info_tasks');
    }
};
