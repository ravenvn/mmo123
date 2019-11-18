<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email');
            $table->string('password');
            $table->string('recovery_email');
            $table->string('notes')->nullable();
            $table->boolean('status')->nullable();
            $table->tinyInteger('detail_reason')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['email', 'user_id']);

            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('accounts');
    }
}
