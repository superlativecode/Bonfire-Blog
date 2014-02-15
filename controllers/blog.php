<?php

class Blog extends Front_Controller
{

    public function __construct()
    {
        parent::__construct();
        
        $this->load->library('users/auth');
		$this->set_current_user();
		
		$this->load->helper('text');
        $this->load->helper('typography');
        $this->load->helper('form');
        $this->load->helper('sef');
        $this->load->model('comments/comments_model');
		
        $this->load->model('post_model');
        Assets::clear_cache();
        Assets::add_module_js('comments', array('bootstrap-markdown.js', 'comments.js'));
        Assets::add_module_css('comments', 'bootstrap-markdown.min.css');
    }

    //--------------------------------------------------------------------

    public function index()
    {
        $stages = array('published');
        if($this->is_editor()){
		    $stages[] = 'draft';
		    $stages[] = 'review';
		    $stages[] = '';
        }
        
        $id = $this->uri->segment(2);
        if(!empty($id)){
            if($post = $this->post_model->where('deleted', 0)->where_in('stage', $stages)->find($id)){
                $this->detail($post);
                return;       
            }
        }
        
        
        Template::set('page_title', "Blog");
        
        $limit = $this->settings_lib->item('site.list_limit');
        $offset = $this->input->get('per_page');
		
		$total = $this->post_model->where('deleted', 0)->where_in('stage', $stages)->count_all();
		
		$this->pager['base_url'] 			= current_url() .'?';
		$this->pager['total_rows'] 			= $total;
		$this->pager['per_page'] 			= $limit;
		$this->pager['page_query_string']	= TRUE;
		
		$this->load->library('pagination');

		$this->pagination->initialize($this->pager);

        $this->post_model->order_by('release_date', 'DESC')
                                  ->where('deleted', 0)
                                  ->where_in('stage', $stages);
        if($tag = $this->input->get('tag'))
            $this->post_model->where('tags LIKE', "%{$tag}%");
        if($term = $this->input->get('term')){
            $this->post_model->where("(
                tags LIKE '%{$term}%'
                OR
                title LIKE '%{$term}%'
            )");
        }
        if(!$this->is_editor()){
            $this->post_model->where('release_date < NOW()');
        }
        $posts = $this->post_model->find_all();
        if(!($tag || $term) && $posts)
            $this->generate_tag_cloud($posts);                          
        if($posts)                         
            $posts = array_slice($posts, $offset, $limit);                          
        
        Template::set('posts', $posts);

        Template::render();
    }
    
    private function generate_tag_cloud($posts){
        $this->load->library('taggly');                          
        $tags_array = array();   
        foreach($posts as $post){
            if(empty($post->tags)) continue;
            $tags = explode(',', $post->tags);
            foreach($tags as $t){
                $t = trim(trim($t, ','));
                if(empty($t)) continue;
                $count = 1;
                if(isset($tags_array[$t])){
                    $count = $tags_array[$t][0] + 1;
                }
                $tags_array[strtolower($t)] = array($count, $t, base_url() . 'blog?tag=' . urlencode($t));
            }
        } 
        Template::set('tag_cloud', $this->taggly->cloud($tags_array, array('min_font' => 20)));
    }
    
    private function detail($post){
        if (isset($_POST['create_comment']))
		{
		    $this->auth->restrict('Comments.Content.Create');
			if ($insert_id = $this->save_comments())
			{
				// Log the activity
				log_activity($this->current_user->id, 'Comments Created : '. $insert_id .' : '. $this->input->ip_address(), 'comments');

				Template::set_message("Successfully created comment.", 'success');
				redirect($this->uri->uri_string());
			}
			else
			{
				Template::set_message("Failed to create comment " . $this->comments_model->error, 'error');
			}
		}
        
        $new_views = $post->views + 1;
        $this->post_model->skip_validation = true;
        if(!$this->is_editor())
            $this->post_model->update($post->post_id, array('views' => ++$post->views));
        Template::set('comments', $this->comments_model->get_nested($post->post_id));
        Template::set('post', $post);
        Template::set('page_title', substr($post->title, 0, 30));
        Template::set_view('detail');
        Template::render();
    }
	
	//--------------------------------------------------------------------
	// !PRIVATE METHODS
	//--------------------------------------------------------------------

	/**
	 * Summary
	 *
	 * @param String $type Either "insert" or "update"
	 * @param Int	 $id	The ID of the record to update, ignored on inserts
	 *
	 * @return Mixed    An INT id for successful inserts, TRUE for successful updates, else FALSE
	 */
	private function save_comments($type='insert', $id=0)
	{
		if ($type == 'update')
		{
			$_POST['id'] = $id;
		}

		// make sure we only pass in the fields we want
		
		$data = array();
		$data['text']  = $this->input->post('comments_text');
		$data['user_id']        = $this->current_user->id;
		$data['post_id']        = $this->input->post('post_id');
		if(!empty($_POST['comment_id'])){
    		$data['parent_id']    = $this->input->post('comment_id');
		}
		$data['approved']       = 0;

		if ($type == 'insert')
		{
			$id = $this->comments_model->insert($data);

			if (is_numeric($id))
			{
				$return = $id;
			}
			else
			{
				$return = FALSE;
			}
		}
		elseif ($type == 'update')
		{
			$return = $this->comments_model->update($id, $data);
		}
		return $return;
	}
    //--------------------------------------------------------------------

}