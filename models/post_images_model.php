<?php

class Post_images_model extends MY_Model
{

    protected $table_name   = 'post_images';
    protected $key          = 'id';
    protected $set_created  = false;
    protected $set_modified = false;
    protected $soft_deletes = false;
    protected $date_format  = 'datetime';

    //---------------------------------------------------------------
    
    protected $validation_rules = array();       
    
    private function join_by_post_id($post_id){
        $this->db->join('bf_images', 'bf_post_images.image_id = bf_images.id', 'right');
        $this->db->where("bf_post_images.post_id = '$post_id'");
    }
    
    public function find_all($post_id)
    {
        $this->join_by_post_id($post_id);
        $results = parent::find_all();
        $data = array();
        if(!$results) return array();
        foreach($results as $row){
            $data[] = $row->image_id;
        }
        return $data;
    }
    
    public function set_main($post_id, $image_id)
    {
        $ids = $this->find_all($post_id);
        if(!empty($ids)){
            $this->db->where_in('id', $ids);
            $this->db->update('bf_images', array(
                'is_main' => 0
            ));
        }
        
        $this->db->where_in('id', $image_id);
        $this->db->update('bf_images', array(
            'is_main' => 1
        ));
        return true;
    }
}