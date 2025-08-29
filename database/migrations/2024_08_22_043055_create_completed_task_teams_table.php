<?php

use App\Models\Tasks;
use App\Models\Teams;
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
        Schema::create('completed_task_teams', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Teams::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Tasks::class)->constrained()->onDelete('cascade');
            $table->longText('StyleTask');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('completed_task_team');
    }
};
