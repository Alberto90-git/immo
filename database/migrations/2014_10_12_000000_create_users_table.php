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

            $table->unsignedBigInteger('iddirection_ref');
            $table->foreign('iddirection_ref')->references('iddirection')->on('directions');

            $table->unsignedBigInteger('idannexe_ref');
            $table->foreign('idannexe_ref')->references('idannexes')->on('annexes');

            $table->string('nom');
            $table->string('prenom');
            $table->string('grade');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_verification_token')->nullable();
            $table->string('password'); 
            $table->dateTime('status')->nullable();
            $table->dateTime('blocage_entreprise')->nullable();
            $table->dateTime('last_login')->nullable();  
            $table->dateTime('mail_token_at')->nullable();  
            $table->integer('code_login')->nullable();
            $table->tinyInteger('is_verified')->nullable(); 
            $table->string('token')->nullable();
            $table->boolean('is_admin')->nullable();
            $table->string('type_compte');
            $table->dateTime('password_changed_at')->nullable();
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
