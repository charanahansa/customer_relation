<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
			$table->string('officer_id')->unique();
            $table->string('email')->unique();
			$table->boolean('head_of_dept');
			$table->boolean('team_lead');
			$table->string('action_station');
			$table->string('job_roles');
			$table->boolean('courier');
			$table->string('phone');
			$table->boolean('courier_print');
			$table->boolean('active');
			$table->string('address');
			$table->string('vc_name');
			$table->boolean('job_roll_change_ability');
			$table->string('jobrolls');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
