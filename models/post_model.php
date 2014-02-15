<?php

class Post_model extends MY_Model
{

    protected $table_name   = 'posts';
    protected $key          = 'post_id';
    protected $set_created  = true;
    protected $set_modified = true;
    protected $soft_deletes = true;
    protected $date_format  = 'datetime';

    //---------------------------------------------------------------

    protected $validation_rules = array(
        array(
            'field' => 'title',
            'label' => 'Title',
            'rules' => 'trim|strip_tags|xss_clean|required'
        ),
        array(
            'field' => 'slug',
            'label' => 'Slug',
            'rules' => 'trim|strip_tags|xss_clean|required'
        ),
        array(
            'field' => 'tags',
            'label' => 'Tags',
            'rules' => 'trim'
        ),
        array(
            'field' => 'body',
            'label' => 'Body',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'excerpt',
            'label' => 'Excerpt',
            'rules' => 'trim|strip_tags|xss_clean'
        ),
        array(
            'field' => 'stage',
            'label' => 'Stage',
            'rules' => 'trim|strip_tags|xss_clean'
        ),
        array(
            'field' => 'release_date',
            'label' => 'Release Date',
            'rules' => 'trim|strip_tags|xss_clean|required'
        )
    );
    
    public $skip_validation = false;
    
    public function find_all(){
        $results = parent::find_all();
        if($results){
            $this->load->model('user_model');
            $this->load->model('post_images_model');
            $this->load->model('images/images_model');
            $this->load->model('comments/comments_model');
            foreach($results as &$item){
                if(is_array($item)){
                    $item['images'] = $this->images_model->find_all($this->post_images_model->find_all($item['post_id']));
                    $item['comments'] = $this->comments_model->where('post_id', $item['post_id'])->find_all();
                    if(!empty($item['user_id']))
                        $item['author'] = $this->user_model->find_user_and_meta($item['user_id']);                    
                }else{
                    $item->images = $this->images_model->find_all($this->post_images_model->find_all($item->post_id));
                    $item->comments = $this->comments_model->where('post_id', $item->post_id)->find_all();
                    if(!empty($item->user_id))
                        $item->author = $this->user_model->find_user_and_meta($item->user_id);
                }
                
            }
        }
        return $results;
    }

    public function find($id=null){
        $result = parent::find($id);

        if($result){
            $this->load->model('user_model');
            $this->load->model('post_images_model');
            $this->load->model('images/images_model');
            $this->load->model('comments/comments_model');
            if(is_array($result)){
                $result['images'] = $this->images_model->find_all($this->post_images_model->find_all($result['post_id']));
                $result['comments'] = $this->comments_model->where('post_id', $result['post_id'])->find_all();
                if(!empty($result['user_id']))
                    $result['author'] = $this->user_model->find_user_and_meta($result['user_id']);                    
            }else{
                $result->images = $this->images_model->find_all($this->post_images_model->find_all($result->post_id));
                $result->comments = $this->comments_model->where('post_id', $result->post_id)->find_all();
                if(!empty($result->user_id))
                    $result->author = $this->user_model->find_user_and_meta($result->user_id);
            }
        }
        return $result;
    }
}