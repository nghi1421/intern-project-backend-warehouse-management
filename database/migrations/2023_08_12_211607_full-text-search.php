<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function () {
            DB::statement('ALTER TABLE users ADD FULLTEXT `username` (`username`)');
        });
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE users DROP INDEX username');
    }
};
