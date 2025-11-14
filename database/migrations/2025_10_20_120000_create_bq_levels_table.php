<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('bq_levels')) {
            Schema::create('bq_levels', function (Blueprint $table) {
                $table->id();
                $table->foreignId('bq_document_id')->constrained()->cascadeOnDelete();
                $table->foreignId('project_id')->nullable()->constrained()->nullOnDelete();
                $table->string('name');
                $table->text('description')->nullable();
                $table->unsignedInteger('position')->default(0);
                $table->timestamps();
            });
        }

        Schema::table('bq_sections', function (Blueprint $table) {
            if (! Schema::hasColumn('bq_sections', 'bq_level_id')) {
                $table->foreignId('bq_level_id')
                    ->nullable()
                    ->after('bq_document_id')
                    ->constrained('bq_levels')
                    ->nullOnDelete();
            }
        });

        $documents = DB::table('bq_documents')
            ->whereNotNull('parent_id')
            ->get();

        foreach ($documents as $document) {
            $existingLevel = DB::table('bq_levels')
                ->where('bq_document_id', $document->id)
                ->first();

            if ($existingLevel) {
                $levelId = $existingLevel->id;
            } else {
                $levelId = DB::table('bq_levels')->insertGetId([
                    'bq_document_id' => $document->id,
                    'project_id' => $document->project_id,
                    'name' => 'Level 1',
                    'position' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('bq_sections')
                ->where('bq_document_id', $document->id)
                ->whereNull('bq_level_id')
                ->update(['bq_level_id' => $levelId]);
        }
    }

    public function down(): void
    {
        Schema::table('bq_sections', function (Blueprint $table) {
            if (Schema::hasColumn('bq_sections', 'bq_level_id')) {
                $table->dropForeign(['bq_level_id']);
                $table->dropColumn('bq_level_id');
            }
        });

        Schema::dropIfExists('bq_levels');
    }
};
