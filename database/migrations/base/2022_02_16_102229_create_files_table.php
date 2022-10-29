<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{

    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uuid')->unique();

            $table->string('ref_type')->index();
            $table->integer('ref_id')->unsigned()->index();
            $table->string('ref_table')->index();

            $table->string('url')->nullable()->index();
            $table->string('name')->nullable();
            $table->string('disk', 64)->nullable();
            $table->string('mime_type')->nullable();
            $table->string('path')->nullable();
            $table->integer('size')->nullable();
            $table->string('slug', 64)->nullable()->index();
            $table->json('data')->nullable();

            $table->integer('updated_by')->unsigned()->nullable()->index();
            $table->integer('created_by')->unsigned()->nullable()->index();
            $table->integer('deleted_by')->unsigned()->nullable()->index();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::drop('files');
    }
};
