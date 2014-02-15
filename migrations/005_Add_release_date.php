<?php

class Migration_Add_release_date extends Migration
{

    public $table = 'posts';
    
    public $fields = array(
        'release_date' => array(
			'type' => 'DATETIME',
			'null' => FALSE,
			'default' => '0000-00-00 00:00:00',
		)
    );

    public function up()
    {
        $this->dbforge->add_column($this->table, $this->fields);
        $this->dbforge->add_key('release_date');
    }

    //--------------------------------------------------------------------

    public function down()
    {
        foreach($this->fields as $field => $set)
            $this->dbforge->drop_column('posts', $field);
    }

    //--------------------------------------------------------------------

}