<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('address', 300)->nullable();
            $table->string('city', 80)->nullable();
            $table->string('state', 50)->nullable();
            $table->string('zip', 10)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('fax', 50)->nullable();
            $table->boolean('customer')->default(0);
            $table->boolean('distributor')->default(0);
            $table->text('notes')->nullable();
            $table->integer('status')->default(1);
            $table->foreignId('country_id')->nullable()->constrained();
            $table->foreignId('company_id')->nullable()->constrained();
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
        Schema::dropIfExists('companies');
    }
}
