<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'products',
            function (Blueprint $table) {
                $table->id();
                $table->foreignIdFor(User::class);
                $table->string('name')->nullable();
                $table->longText('description')->nullable();
                $table->integer('quantity')->unsigned()->default(0);
                $table->double('price')->default(0.0);
                $table->string('image')->nullable();
                $table->boolean('published')->default(false);
                $table->softDeletes();
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
