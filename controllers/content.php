<?php

class Content extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('post_model');
        $this->load->model('post_images_model');
        $this->load->model('images/images_model');

        $this->lang->load('blog');
        
        Template::set_block('sub_nav', 'content/sub_nav');

        Template::set('toolbar_title', 'Manage Your Blog');
        
        Assets::add_js(
            array(
                'jquery-ui-1.8.13.min.js', 
                'jquery-ui-timepicker-addon.js',
            )
        );
        Assets::add_css(
            array(
                'flick/jquery-ui-1.8.13.custom.css', 
                'jquery-ui-timepicker.css', 
                'jquery-ui-timepicker.css'
            )
        );
        Assets::clear_cache();
        Assets::add_module_js('images', array('dropzone.min.js', 'images.js'));
        Assets::add_module_css('images', array('dropzone.css'));
        
        Assets::add_module_js('blog', array('bootstrap-markdown.js', 'blog.js'));
        
        Assets::add_module_css('blog', 'bootstrap-markdown.min.css');
       
    }

    //--------------------------------------------------------------------

    public function index()
    {
    
        // Deleting anything?
		if ($this->input->post('delete'))
		{
		    $this->auth->restrict('Blog.Content.Delete');
		    
			$checked = $this->input->post('checked');

			if (is_array($checked) && count($checked))
			{
				$result = FALSE;
				foreach ($checked as $pid)
				{
					$result = $this->post_model->delete($pid);
				}

				if ($result)
				{
					Template::set_message(count($checked) .' '. lang('blog_delete_success'), 'success');
				}
				else
				{
					Template::set_message(lang('post_delete_failure') . $this->post_model->error, 'error');
				}
			}
		}
		
		// Deleting anything?
		if ($this->input->post('update'))
		{
		    $this->auth->restrict('Blog.Content.Edit');
		    
			$checked = $this->input->post('checked');

			if (is_array($checked) && count($checked))
			{
				$result = FALSE;
				foreach ($checked as $pid)
				{
				    $post = $this->post_model->as_array()->find($pid);
				    unset($post['images']);
				    unset($post['author']);
				    unset($post['comments']);
				    $data = array(
                        'user_id' => $this->input->post('user_id') ? $this->input->post('user_id') : $post['user_id'],
                        'stage' => $this->input->post('stage') ? $this->input->post('stage') : $post['stage'],
                        'release_date' => $this->input->post('release_date') ? date('Y-m-d H:i:s', strtotime($this->input->post('release_date'))) : $post['release_date'],
                    );
					$result = $this->post_model->update($pid, array_merge($post, $data));
				}

				if ($result)
				{
					Template::set_message(count($checked) .' Post\'s we updated', 'success');
				}
				else
				{
					Template::set_message("Post's couldn't be updated. ". $this->post_model->error, 'error');
				}
			}
		}
		
        $posts = $this->post_model->where('deleted', 0)->order_by('created_on', 'DESC')->find_all();

        Template::set('posts', $posts);

        Template::render();
    }

    //--------------------------------------------------------------------

    public function create()
    {
        if ($this->input->post('submit'))
        {
            $data = array(
                'title' => $this->input->post('title'),
                'slug'  => $this->input->post('slug'),
                'body'  => $this->input->post('body'),
                'user_id' => $this->input->post('user_id'),
                'excerpt' => $this->input->post('excerpt'),
                'stage' => $this->input->post('stage'),
                'tags' => $this->input->post('tags'),
                'release_date' => date('Y-m-d H:i:s', strtotime($this->input->post('release_date'))),
            );

            if ($id = $this->post_model->insert($data))
            {
                if($this->input->post('new_image_id')){
                    foreach($this->input->post('new_image_id') as $image_id){
                        $rel = array();
            	        $rel['image_id'] = $image_id;
            	        $rel['post_id'] = $id;
            	        $this->post_images_model->insert($rel);
        	        }
    	        }
                Template::set_message('Your post was successfully saved.', 'success');
                redirect(SITE_AREA .'/content/blog');
            }
        }
        
        $users = $this->user_model->where_in('roles.role_id', array(1,2))->find_all();
        $authors = array();
        foreach($users as $u)
            $authors[$u->id] = $u->display_name;
        Template::set('authors', $authors);

        Template::set('toolbar_title', 'Create New Post');
        Template::set_view('content/post_form');
        Template::render();
    }
    public function edit_post($id=null)
    {
        if (isset($_POST['submit']))
        {
            $data = array(
                'title' => $this->input->post('title'),
                'slug'  => $this->input->post('slug'),
                'body'  => $this->input->post('body'),
                'user_id' => $this->input->post('user_id'),
                'excerpt' => $this->input->post('excerpt'),
                'stage' => $this->input->post('stage'),
                'tags' => $this->input->post('tags'),
                'release_date' => date('Y-m-d H:i:s', strtotime($this->input->post('release_date'))),
            );

            if ($this->post_model->update($id, $data))
            {
                if($this->input->post('new_image_id')){
                    foreach($this->input->post('new_image_id') as $image_id){
                        $rel = array();
            	        $rel['image_id'] = $image_id;
            	        $rel['post_id'] = $id;
            	        $this->post_images_model->insert($rel);
        	        }
    	        }
                Template::set_message('Your post was successfully saved.', 'success');
            }
        }
        // Deleting anything?
		if (isset($_POST['delete']))
		{
		    
		    $this->auth->restrict('Blog.Content.Delete');
		    
			if($this->post_model->delete($id)){
        		Template::set_message(lang('blog_delete_success'), 'success');
        		redirect(SITE_AREA . '/content/blog');
			}
			else
			{
				Template::set_message(lang('post_delete_failure') . $this->post_model->error, 'error');
			}
		}else if (isset($_POST['delete_images']))
		{
			$this->auth->restrict('Images.Content.Delete');
            
			if ($this->images_model->delete($this->input->post('delete_image')))
			{
			    $this->lang->load('images/images');
				// Log the activity
				log_activity($this->current_user->id, lang('images_act_delete_record') .': '. $id .' : '. $this->input->ip_address(), 'post');
                
				Template::set_message(lang('images_delete_success'), 'success');
			}
			else
			{
				Template::set_message(lang('post_delete_failure') . $this->post_model->error, 'error');
			}
		}

        Template::set('post', $this->post_model->find($id));
        
        $users = $this->user_model->where_in('roles.role_id', array(1,2))->find_all();
        $authors = array();
        foreach($users as $u)
            $authors[$u->id] = $u->display_name;
        Template::set('authors', $authors);

        Template::set('toolbar_title', 'Edit Post');
        Template::set_view('content/post_form');
        Template::render();
    }
}