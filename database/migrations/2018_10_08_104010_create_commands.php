<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommands extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commands', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->decimal('rank', 2, 2);
        });

        $data = [
            ['name' => 'Manchester City', 'rank' => 0.3],
            ['name' => 'Liverpool', 'rank' => 0.4],
            ['name' => 'Arsenal', 'rank' => 0.5],
            ['name' => 'Chelsea', 'rank' => 0.6],
        ];

        DB::table('commands')->insert($data);

        Schema::create('matches', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('command1')->index('command1');
            $table->integer('command2')->index('command2');
            $table->integer('week')->index('week');
        });

        $data = [
            ['command1' => 1, 'command2' => 2, 'week' => 1],
            ['command1' => 3, 'command2' => 4, 'week' => 1],
            ['command1' => 1, 'command2' => 3, 'week' => 2],
            ['command1' => 2, 'command2' => 4, 'week' => 2],
            ['command1' => 1, 'command2' => 4, 'week' => 3],
            ['command1' => 2, 'command2' => 3, 'week' => 3],

            ['command1' => 2, 'command2' => 1, 'week' => 4],
            ['command1' => 4, 'command2' => 3, 'week' => 4],
            ['command1' => 3, 'command2' => 1, 'week' => 5],
            ['command1' => 4, 'command2' => 2, 'week' => 5],
            ['command1' => 4, 'command2' => 1, 'week' => 6],
            ['command1' => 3, 'command2' => 2, 'week' => 6],
        ];

        DB::table('matches')->insert($data);

        Schema::create('info', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('match')->index('match');
            $table->integer('command')->index('command');
            $table->integer('goals');
            $table->integer('win');
            $table->integer('drawn');
            $table->integer('lost');
            $table->integer('gd');
            $table->integer('pts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('info');
        Schema::drop('matches');
        Schema::drop('commands');
    }
}
