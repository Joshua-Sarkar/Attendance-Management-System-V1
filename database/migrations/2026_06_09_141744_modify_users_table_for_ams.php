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
        Schema::table('users', function (Blueprint $table) {

            $table->string('employee_id')
                ->unique()
                ->nullable()
                ->after('id');

            $table->enum('role', [
                'admin',
                'manager',
                'employee'
            ])->default('employee');

            $table->enum('status', [
                'active',
                'inactive',
                'resigned'
            ])->default('active');

            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->nullOnDelete();

            $table->foreignId('manager_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['department_id']);
            $table->dropForeign(['manager_id']);

            $table->dropColumn([
                'employee_id',
                'role',
                'status',
                'department_id',
                'manager_id'
            ]);
        });
    }
};