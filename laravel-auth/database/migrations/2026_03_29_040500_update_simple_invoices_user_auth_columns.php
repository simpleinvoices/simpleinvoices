<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('si_user')) {
            return;
        }

        if (! Schema::hasColumn('si_user', 'remember_token')) {
            Schema::table('si_user', function (Blueprint $table) {
                $table->string('remember_token', 100)->nullable()->after('password');
            });
        }

        DB::statement('ALTER TABLE si_user MODIFY password VARCHAR(255) NULL');
    }

    public function down(): void
    {
        if (! Schema::hasTable('si_user')) {
            return;
        }

        if (Schema::hasColumn('si_user', 'remember_token')) {
            Schema::table('si_user', function (Blueprint $table) {
                $table->dropColumn('remember_token');
            });
        }

        DB::statement('ALTER TABLE si_user MODIFY password VARCHAR(64) NULL');
    }
};
