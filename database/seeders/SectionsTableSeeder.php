<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionsTableSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['id' => 1, 'name' => 'Preliminaries', 'created_at' => '2025-01-16 17:29:54', 'updated_at' => '2025-03-13 13:07:02'],
            ['id' => 2, 'name' => 'Substructure (All Provisional)', 'created_at' => '2025-01-16 17:29:54', 'updated_at' => '2025-01-16 22:37:37'],
            ['id' => 3, 'name' => 'Reinforced Concrete Superstructure', 'created_at' => '2025-01-16 17:29:54', 'updated_at' => '2026-01-31 19:44:34'],
            ['id' => 4, 'name' => 'Walling and Partitions', 'created_at' => '2025-01-16 17:29:54', 'updated_at' => '2025-03-08 21:48:25'],
            ['id' => 5, 'name' => 'Roofing', 'created_at' => '2025-01-16 17:29:54', 'updated_at' => '2025-01-16 17:29:54'],
            ['id' => 6, 'name' => 'External  Wall Finishes', 'created_at' => '2025-01-16 17:29:54', 'updated_at' => '2026-02-08 21:25:25'],
            ['id' => 7, 'name' => 'Internal Finishes', 'created_at' => '2025-01-16 17:29:54', 'updated_at' => '2025-01-16 22:38:39'],
            ['id' => 8, 'name' => 'Windows', 'created_at' => '2025-01-16 17:29:54', 'updated_at' => '2025-01-16 17:29:54'],
            ['id' => 9, 'name' => 'Doors', 'created_at' => '2025-01-16 17:29:54', 'updated_at' => '2025-01-16 17:29:54'],
            ['id' => 11, 'name' => 'Electrical Works', 'created_at' => '2025-01-16 17:29:54', 'updated_at' => '2025-01-16 17:29:54'],
            ['id' => 12, 'name' => 'Plumbing & Drainage Works', 'created_at' => '2025-01-16 17:29:54', 'updated_at' => '2025-12-17 14:34:22'],
            ['id' => 13, 'name' => 'Storm Water Drainage', 'created_at' => '2025-01-16 17:29:54', 'updated_at' => '2025-03-13 12:06:06'],
            ['id' => 44, 'name' => 'Driveway -Pavements&Parkings', 'created_at' => '2025-03-13 12:06:41', 'updated_at' => '2025-03-13 12:06:41'],
            ['id' => 45, 'name' => 'Cabinetry-Joinery and Fittings', 'created_at' => '2025-03-29 16:32:22', 'updated_at' => '2025-05-08 21:12:38'],
            ['id' => 46, 'name' => 'Balustrading and railling', 'created_at' => '2025-05-08 00:04:09', 'updated_at' => '2025-05-08 00:04:09'],
            ['id' => 47, 'name' => 'Demolitions and Alterations', 'created_at' => '2025-11-23 15:37:25', 'updated_at' => '2025-11-23 15:37:25'],
            ['id' => 48, 'name' => 'External Floor Finishes', 'created_at' => '2026-02-10 17:56:03', 'updated_at' => '2026-02-10 17:56:03'],
            ['id' => 49, 'name' => 'Internal Ceiling Finishes', 'created_at' => '2026-02-10 17:57:17', 'updated_at' => '2026-02-10 17:57:17'],
            ['id' => 50, 'name' => 'Internal Wall Finishes', 'created_at' => '2026-02-10 18:04:20', 'updated_at' => '2026-02-10 18:04:20'],
            ['id' => 51, 'name' => 'Internal Floor Finishes', 'created_at' => '2026-02-10 18:10:29', 'updated_at' => '2026-02-10 18:10:29'],
            ['id' => 52, 'name' => 'Worktops & Vanities', 'created_at' => '2026-02-10 18:14:42', 'updated_at' => '2026-02-10 18:14:42'],
        ];

        foreach ($rows as $row) {
            Section::create($row);
        }
    }
}