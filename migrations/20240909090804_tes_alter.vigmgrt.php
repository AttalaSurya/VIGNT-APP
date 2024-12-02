<?php

class Vignt20240909090804TesAlter extends BaseMigration {
    public function up() {
        VigntMigrate::connection('local')->alter('users', function (Table $table) {
            $table->addColumn('age', 'INT');
            $table->modifyColumn('bio', 'VARCHAR(255)');
            $table->dropColumn('last_login');
            $table->index(['age'], 'age_index');
            $table->nullable(['bio'], true);
        });
    }

    public function down() {
        VigntMigrate::connection('local')->alter('users', function (Table $table) {
            $table->addColumn('last_login', 'DATETIME');
            $table->modifyColumn('bio', 'TEXT');
            $table->dropColumn('age');
            $table->nullable(['bio', 'last_login'], false);
        });
    }
}
