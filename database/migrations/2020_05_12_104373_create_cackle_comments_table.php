<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCackleCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('laravel-cackle-sync.comment_table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("channel_id")->nullable();
            $table->string("ip")->nullable();
            $table->string("status")->nullable();
            $table->string("name")->nullable();
            $table->string("email")->nullable();
            //$table->string("title")->nullable();
            $table->text("comment")->nullable();
            //$table->tinyInteger("star")->unsigned()->nullable();
            $table->unsignedBigInteger('created')->nullable();
            $table->unsignedBigInteger('modified')->nullable();
            $table->timestamps();
        });

        Schema::create(config('laravel-cackle-sync.review_table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer("channel_id")->nullable();
            $table->text("comment")->nullable();
            $table->string("media")->nullable();
            $table->string("ip")->nullable();
            $table->tinyInteger("star")->unsigned()->nullable();
            $table->string("status")->nullable();
            $table->unsignedBigInteger('created')->nullable();
            $table->unsignedBigInteger('modified')->nullable();
            $table->string("name")->nullable();
            $table->string("email")->nullable();
            $table->string("title")->nullable();
            $table->text("pros")->nullable();
            $table->text("cons")->nullable();
            $table->timestamps();
        });

        Schema::create(config('laravel-cackle-sync.channel_table'), function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("channel")->nullable();
            $table->string("url")->nullable();
            $table->string("title")->nullable();
            $table->unsignedBigInteger('created')->nullable();
            $table->unsignedBigInteger('modified')->nullable();
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
        Schema::dropIfExists(config('laravel-cackle-sync.channel_table'));
        Schema::dropIfExists(config('laravel-cackle-sync.review_table'));
        Schema::dropIfExists(config('laravel-cackle-sync.comment_table'));
    }
}
