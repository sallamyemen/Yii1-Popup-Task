<?php

class m241223_082257_create_popup_table extends CDbMigration
{
	public function up()
	{
        $this->createTable('popup', [
            'id' => 'pk',
            'title' => 'string NOT NULL',
            'content' => 'text NOT NULL',
            'enabled' => 'boolean NOT NULL DEFAULT 1',
            'views' => 'integer NOT NULL DEFAULT 0',
        ]);
	}

	public function down()
	{
		//echo "m241223_082257_create_popup_table does not support migration down.\n";
        $this->dropTable('popup');
		//return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}