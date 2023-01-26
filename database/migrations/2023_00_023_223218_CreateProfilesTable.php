<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration{
    
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            $table->integer('id_user')->index('id_user_foreign');
            $table->string('first_name',100);
            $table->string('last_name',100);
            $table->text('summary');
            $table->string('image',100);
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
        Schema::dropIfExists('profiles');
    }
}