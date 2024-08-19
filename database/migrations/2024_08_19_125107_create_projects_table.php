<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->bigIncrements('id'); // Auto-incrementing primary key
            $table->string('name'); // Project name
            $table->text('description')->nullable(); // Project description
            $table->decimal('budget', 15, 2)->nullable(); // Project budget
            $table->date('start_date')->nullable(); // Project start date
            $table->date('end_date')->nullable(); // Project end date
            $table->enum('status', ['pending', 'in_progress', 'completed', 'on_hold'])->default('pending'); // Project status
            $table->unsignedBigInteger('user_id'); // Foreign key for user (assuming projects are associated with a user)
            $table->timestamps(); // Created and updated timestamps

            // Define foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
