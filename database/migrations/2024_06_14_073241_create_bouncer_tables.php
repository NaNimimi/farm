<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Silber\Bouncer\Database\Models;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(Models::table('abilities'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('title')->nullable();
            $table->bigInteger('entity_id')->unsigned()->nullable();
            $table->string('entity_type')->nullable();
            $table->boolean('only_owned')->default(false);
            $table->json('options')->nullable();
            $table->integer('scope')->nullable()->index();
            $table->timestamps();
        });

        Schema::create(Models::table('roles'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('title')->nullable();
            $table->integer('scope')->nullable()->index();
            $table->timestamps();

            $table->unique(
                ['name', 'scope'],
                'roles_name_unique'
            );
        });

        Schema::create(Models::table('assigned_roles'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('role_id')->unsigned()->index();
            $table->bigInteger('entity_id')->unsigned();
            $table->string('entity_type');
            $table->bigInteger('restricted_to_id')->unsigned()->nullable();
            $table->string('restricted_to_type')->nullable();
            $table->integer('scope')->nullable()->index();

            $table->index(
                ['entity_id', 'entity_type', 'scope'],
                'assigned_roles_entity_index'
            );

            $table->foreign('role_id')
                ->references('id')->on(Models::table('roles'))
                ->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create(Models::table('permissions'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ability_id')->unsigned()->index();
            $table->bigInteger('entity_id')->unsigned()->nullable();
            $table->string('entity_type')->nullable();
            $table->boolean('forbidden')->default(false);
            $table->integer('scope')->nullable()->index();

            $table->index(
                ['entity_id', 'entity_type', 'scope'],
                'permissions_entity_index'
            );

            $table->foreign('ability_id')
                ->references('id')->on(Models::table('abilities'))
                ->onUpdate('cascade')->onDelete('cascade');
        });

        // Добавление столбца role_id в таблицу users
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'role_id')) {
                $table->bigInteger('role_id')->unsigned()->nullable();

                $table->foreign('role_id')
                    ->references('id')->on(Models::table('roles'))
                    ->onUpdate('cascade')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });

        Schema::dropIfExists(Models::table('permissions'));
        Schema::dropIfExists(Models::table('assigned_roles'));
        Schema::dropIfExists(Models::table('roles'));
        Schema::dropIfExists(Models::table('abilities'));
    }
};
