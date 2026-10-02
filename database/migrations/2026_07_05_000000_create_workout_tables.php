<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 30)->unique();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['program_id', 'name']);
        });

        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->unique()
                ->constrained()
                ->cascadeOnDelete();
            $table->string('full_name')->nullable();
            $table->unsignedTinyInteger('age')->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('weight', 6, 2)->nullable();
            $table->string('gender', 20)->nullable();
            $table->string('fitness_level', 20)->nullable();
            $table->text('goal')->nullable();
            $table->boolean('profile_completed')->default(false);
            $table->timestamps();
        });

        Schema::create('user_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('program_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('status', 30)->default('ongoing');
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'program_id']);
        });

        Schema::create('workout_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('program_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->dateTime('session_date');
            $table->unsignedSmallInteger('duration_minutes')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'program_id', 'session_date']);
        });

        Schema::create('workout_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('session_id')
                ->constrained('workout_sessions')
                ->cascadeOnDelete();
            $table->foreignId('exercise_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->boolean('completed')->default(true);
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'session_id', 'exercise_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_progress');
        Schema::dropIfExists('workout_sessions');
        Schema::dropIfExists('user_programs');
        Schema::dropIfExists('profiles');
        Schema::dropIfExists('exercises');
        Schema::dropIfExists('programs');
    }
};
