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
            $table->string('Nombre');
            $table->string('Email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('Password');
            $table->float('Salario', 10, 3);
            $table->enum('PuestoTrabajo',['Direccion','RRHH','Empleado']);
            $table->string('Biografia', 200);
            $table->string('api_token')->nullable()->unique();
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
