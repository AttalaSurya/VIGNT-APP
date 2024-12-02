<?php

class Vignt20240909062828Aaa extends BaseMigration {
    public function up() {
        VigntMigrate::connection('local')->create('users', function (Table $table) {
            $table->bigIncrements('id');
            $table->string('name', 100);
            $table->string('email', 255);
            $table->text('bio');
            $table->dateTime('last_login');
            $table->nullable(['bio', 'last_login'], false);
            $table->timestamps();
            
            $table->uniqueIndex(['email'], 'email_index');
        });
    }

    public function down() {
        VigntMigrate::connection('local')->dropIfExists('users');
    }
}
