<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WorkoutCatalogSeeder extends Seeder
{
    public function run(): void
    {
        $timestamp = now();

        DB::table('programs')->upsert([
            [
                'id' => 1,
                'name' => 'Gym Training',
                'type' => 'gym',
                'description' => 'Latihan kekuatan menggunakan alat dan beban.',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'id' => 2,
                'name' => 'Cardio Training',
                'type' => 'cardio',
                'description' => 'Latihan untuk meningkatkan kebugaran kardiovaskular.',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
            [
                'id' => 3,
                'name' => 'Calisthenics Training',
                'type' => 'calisthenic',
                'description' => 'Latihan kekuatan menggunakan berat badan.',
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ],
        ], ['id'], ['name', 'type', 'description', 'updated_at']);

        $exercises = [
            [1, 1, 'Bench Press'],
            [2, 1, 'Lat Pulldown'],
            [3, 1, 'Shoulder Press'],
            [4, 1, 'Bicep Curl'],
            [5, 1, 'Tricep Pushdown'],
            [6, 1, 'Squat'],
            [7, 1, 'Leg Press'],
            [8, 1, 'Leg Curl'],
            [9, 1, 'Calf Raise'],
            [10, 1, 'Deadlift'],
            [11, 1, 'Push Up'],
            [12, 1, 'Plank'],
            [13, 2, 'Jogging'],
            [14, 2, 'Jump Rope'],
            [15, 2, 'Running'],
            [16, 2, 'HIIT (Sprint 30 detik + Jalan 1 menit)'],
            [17, 3, 'Push Up'],
            [18, 3, 'Pull Up (assist)'],
            [19, 3, 'Squat'],
            [20, 3, 'Plank'],
            [21, 3, 'Pull Up'],
            [22, 3, 'Dips'],
            [23, 3, 'Hanging Leg Raise'],
            [24, 3, 'Pike Push Up'],
        ];

        DB::table('exercises')->upsert(
            array_map(
                static fn (array $exercise): array => [
                    'id' => $exercise[0],
                    'program_id' => $exercise[1],
                    'name' => $exercise[2],
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ],
                $exercises
            ),
            ['id'],
            ['program_id', 'name', 'updated_at']
        );
    }
}
