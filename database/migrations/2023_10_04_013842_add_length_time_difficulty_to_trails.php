<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('trail', function (Blueprint $table) {
            $table->string('length')->nullable();
            $table->string('estimated_time')->nullable();
            $table->string('difficulty')->nullable();
        });
    }

};
