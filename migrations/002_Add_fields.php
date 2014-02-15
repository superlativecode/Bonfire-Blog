<?php

class Migration_Add_fields extends Migration
{

    public $table = 'posts';
    
    public $fields = array(
        'excerpt' => array(
			'type' => 'TEXT',
			'null' => TRUE,
		),
		'stage' => array(
			'type' => 'VARCHAR',
			'constraint' => 36,
			'null' => FALSE,
			'default' => '',
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