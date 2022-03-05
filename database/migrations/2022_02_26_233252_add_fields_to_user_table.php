<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //

            $table->string('uuid')->nullable();
            $table->string( 'first_name');
            $table->string( 'last_name');
            $table->boolean( 'is_admin')->default(0);
            $table->text( 'address')->nullable();
            $table->string( 'phone_number')->nullable();
            $table->boolean( 'is_marketing')->default(0);
            $table->timestamp( 'last_login_at')->nullable();

            $table->foreignUuid('avatar')->nullable();
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
            //

            $table->removeColumn('uuid');
            $table->removeColumn( 'first_name');
            $table->removeColumn( 'last_name');
            $table->removeColumn( 'is_admin');
            $table->removeColumn( 'avatar');
            $table->removeColumn( 'address');
            $table->removeColumn( 'phone_number');
            $table->removeColumn( 'is_marketing');
            $table->removeColumn( 'last_login_at');

            $table->dropForeign('avatar');
        });
    }
};
