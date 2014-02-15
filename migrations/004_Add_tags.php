<?php

class Migration_Add_tags extends Migration
{

    public $table = 'posts';
    
    public $fields = array(
        'tags' => array(
			'type' => 'VARCHAR',
			'constraint' => 255,
			'null' => FALSE,
			'default' => '',
		),
		'views' => array(
			'type' => 'INT',
			'constraint' => 11,
			'null' => FALSE,
			'default' => 0,
		),
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