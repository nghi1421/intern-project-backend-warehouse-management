<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('description');
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->default(1);
            $table->foreign('role_id')->references('id')->on('roles');
        });

        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unque();
            $table->timestamps();
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->foreignId('role_id');
            $table->foreign('role_id')->references('id')->on('roles');
            $table->foreignId('permission_id');
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->primary(['role_id', 'permission_id']);
        });

        Schema::create('warehouse_branches', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('email');
            $table->string('address');
            $table->boolean('opening')->default(1);
            $table->string('phone_number', 15)->unique();
            $table->timestamps();
        });

        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone_number', 15)->unique();
            $table->string('avatar')->nullable();
            $table->string('address', 100);
            $table->tinyInteger('gender'); //0: nu //1:nam 2: khac
            $table->foreignId('position_id');
            $table->foreign('position_id')->references('id')->on('positions');
            $table->foreignId('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->date('dob')->nullable();
            $table->boolean('working', true);
            $table->timestamps();
        });

        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('description')->nullable();
            $table->string('unit', 20);
            $table->timestamps();
        });

        Schema::create('principles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('category_principles', function (Blueprint $table) {
            $table->foreignId('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreignId('principle_id');
            $table->foreign('principle_id')->references('id')->on('principles');
            $table->primary(['category_id', 'principle_id']);
        });

        Schema::create('providers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('email');
            $table->string('address');
            $table->string('phone_number', 15);
            $table->timestamps();
        });

        Schema::create('shelves', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50)->unique();
            $table->string('description')->nullable();
            $table->timestamps();
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('block_id');
            $table->foreignId('shelf_id');
            $table->foreignId('warehouse_branch_id');
            $table->foreign('block_id')->references('id')->on('blocks');
            $table->foreign('shelf_id')->references('id')->on('shelves');
            $table->foreign('warehouse_branch_id')->references('id')->on('warehouse_branches');
            $table->unique(['block_id', 'shelf_id', 'warehouse_branch_id']);
            $table->timestamps();
        });

        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->unsignedBigInteger('staff_id');
            $table->foreign('staff_id')->references('id')->on('staffs');
            $table->tinyInteger('status'); //status 0: huy, 1 kiem tra, 2 hoan tat
            $table->timestamps();
        });

        Schema::create('import_details', function (Blueprint $table) {
            $table->unsignedBigInteger('import_id');
            $table->foreign('import_id')->references('id')->on('imports');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->integer('quantity');
            $table->decimal('unit_price', 19, 2)->default(0);
            $table->primary(['import_id', 'category_id']);
        });

        Schema::create('exports', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('cause')->default(1);
            $table->unsignedBigInteger('staff_id');
            $table->foreign('staff_id')->references('id')->on('staffs');
            $table->tinyInteger('status'); //status 0: huy, 1 kiem tra, 2 hoan tat
            $table->timestamps();
        });

        Schema::create('export_details', function (Blueprint $table) {
            $table->unsignedBigInteger('export_id');
            $table->foreign('export_id')->references('id')->on('exports');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->integer('quantity');
            $table->primary(['export_id', 'category_id']);
        });


        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('import_id');
            $table->foreign('import_id')->references('id')->on('imports');
            $table->foreignId('category_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->foreign('location_id')->references('id')->on('locations');
            $table->date('expiry_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('actions');
        Schema::dropIfExists('staff_actions');
        Schema::dropIfExists('position_actions');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('staffs');
        Schema::dropIfExists('principles');
        Schema::dropIfExists('category_principles');
        Schema::dropIfExists('categories');
        Schema::dropIfExists('customers');
        Schema::dropIfExists('imports');
        Schema::dropIfExists('import_details');
        Schema::dropIfExists('exports');
        Schema::dropIfExists('export_details');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('stocks');
    }
};