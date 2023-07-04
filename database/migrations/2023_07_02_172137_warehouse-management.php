<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unque();
            $table->timestamps();
        });

        Schema::create('user_actions', function (Blueprint $table) {
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreignId('action_id');
            $table->foreign('action_id')->references('id')->on('actions');
            $table->primary(['user_id', 'action_id']);
        });

        Schema::create('position_actions', function (Blueprint $table) {
            $table->foreignId('position_id');
            $table->foreign('position_id')->references('id')->on('positions');
            $table->foreignId('action_id');
            $table->foreign('action_id')->references('id')->on('actions');
            $table->primary(['position_id', 'action_id']);
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

        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number', 15)->unique();
            $table->string('avatar')->nullable();
            $table->string('address', 100);
            $table->tinyInteger('gender'); //0: nu //1:nam 2: khac
            $table->foreignId('position_id');
            $table->foreign('position_id')->references('id')->on('positions');
            $table->foreignId('warehouse_branch_id');
            $table->foreign('warehouse_branch_id')->references('id')->on('warehouse_branches');
            $table->date('dob')->nullable();
            $table->boolean('working', true);
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

        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('block_name', 50);
            $table->string('shelf_name', 50);
            $table->unique(['block_name', 'shelf_name']);
            $table->text('description');
            $table->timestamps();
        });

        Schema::create('imports', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->foreign('provider_id')->references('id')->on('providers');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreignId('warehouse_branch_id');
            $table->foreign('warehouse_branch_id')->references('id')->on('warehouse_branches');
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
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreignId('warehouse_branch_id');
            $table->foreign('warehouse_branch_id')->references('id')->on('warehouse_branches');
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
            $table->foreignId('warehouse_branch_id');
            $table->foreign('warehouse_branch_id')->references('id')->on('warehouse_branches');
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
        Schema::dropIfExists('actions');
        Schema::dropIfExists('user_actions');
        Schema::dropIfExists('position_actions');
        Schema::dropIfExists('positions');
        Schema::dropIfExists('users');
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
