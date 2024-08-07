<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BqDocument;

class BqDocumentSeeder extends Seeder
{
    public function run()
    {
        BqDocument::create([
            'title' => 'Sample BQ Document',
            'description' => 'A sample BQ document for demonstration purposes.',
            'user_id' => 1,
        ]);
    }
}
