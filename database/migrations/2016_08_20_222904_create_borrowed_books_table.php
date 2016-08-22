<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBorrowedBooksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrowed_books', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('book_id')->nullable()->unsigned();

            $table->foreign('book_id')
              ->references('id')
              ->on('books')
              ->onUpdate('cascade')
              ->onDelete('cascade');

            $table->integer('user_id')->nullable()->unsigned();

            $table->foreign('user_id')
              ->references('id')
              ->on('users')
              ->onUpdate('cascade')
              ->onDelete('cascade');

            $table->integer('quantity')->unsigned();
            $table->enum('type', ['reserved', 'borrowed']);
            $table->timestamp('borrowed_at')->index();
            $table->timestamp('returned_at')->index();
            $table->integer('is_expired')->index();
            $table->integer('is_overdue')->index();
            $table->integer('is_lost')->index();

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
        Schema::drop('books');
    }
}
