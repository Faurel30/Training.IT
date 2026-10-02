<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private function getForeignKeyName(string $tableName): ?string
    {
        $result = DB::selectOne(
            "SELECT CONSTRAINT_NAME AS constraint_name
             FROM information_schema.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = DATABASE()
               AND TABLE_NAME = ?
               AND COLUMN_NAME = 'user_id'
               AND REFERENCED_TABLE_NAME = 'users'
             LIMIT 1",
            [$tableName]
        );

        return $result->constraint_name ?? null;
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (['profiles', 'user_programs', 'workout_progress', 'workout_sessions'] as $tableName) {
            if (!Schema::hasTable($tableName) || !Schema::hasColumn($tableName, 'user_id')) {
                continue;
            }

            $foreignKeyName = $this->getForeignKeyName($tableName);

            Schema::table($tableName, function (Blueprint $table) use ($foreignKeyName) {
                if ($foreignKeyName) {
                    $table->dropForeign($foreignKeyName);
                }
                $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (['profiles', 'user_programs', 'workout_progress', 'workout_sessions'] as $tableName) {
            if (!Schema::hasTable($tableName) || !Schema::hasColumn($tableName, 'user_id')) {
                continue;
            }

            $foreignKeyName = $this->getForeignKeyName($tableName);

            Schema::table($tableName, function (Blueprint $table) use ($foreignKeyName) {
                if ($foreignKeyName) {
                    $table->dropForeign($foreignKeyName);
                }

                $table->foreign('user_id')
                    ->references('id')
                    ->on('users');
            });
        }
    }
};