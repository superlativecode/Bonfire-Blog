<?php

class Migration_Add_user extends Migration
{

    public $table = 'posts';
    
    public $fields = array(
        'user_id' => array(
			'type' => 'INT',
			'constraint' => 11,
			'default' => 0,
		)
    );

    public function up()
    {
        $this->dbforge->add_column($this->table, $this->fields);
    }

    //--------------------------------------------------------------------

    public function down()
    {
        foreach($this->fields as $field => $set)
            $this->dbforge->drop_column('posts', $field);
    }

    //--------------------------------------------------------------------

}