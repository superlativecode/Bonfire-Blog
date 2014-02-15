<div class="admin-box">
    <?php if(!empty($post->post_id)): ?>
        <h3>Edit Post <small>This post has been viewed <?=number_format($post->views)?> times.</small></h3>
    <?php else: ?>
        <h3>New Post</h3>
    <?php endif; ?>
    <?php echo form_open_multipart(current_url(), 'class="form-horizontal has_images"'); ?>

        <div class="control-group <?php if (form_error('title')) echo 'error'; ?>">
            <label for="title">Title</label>
            <div class="controls">
                <input type="text" name="title" class="input-xxlarge" value="<?php echo isset($post->title) ? $post->title : set_value('title'); ?>" />
                <?php if (form_error('title')) echo '<span class="help-inline">'. form_error('title') .'</span>'; ?>
            </div>
        </div>
        
        <div class="control-group <?php if (form_error('release_date')) echo 'error'; ?>">
            <label for="release_date">Release Date</label>
            <div class="controls">
                <input type="text" name="release_date" class="datetimepicker input-large" value="<?php echo isset($post->release_date) ? $post->release_date : set_value('release_date'); ?>" />
                <?php if (form_error('release_date')) echo '<span class="help-inline">'. form_error('release_date') .'</span>'; ?>
            </div>
        </div>
        
        <?=form_label('Stage', 'stage')?>
        <?=form_dropdown('stage', array('draft' => 'Draft', 'review' => 'Ready for Review', 'published' => 'Published'), isset($post->stage) ? $post->stage : set_value('stage'))?> 

        <div class="control-group <?php if (form_error('slug')) echo 'error'; ?>">
            <label for="slug">Slug</label>
            <div class="controls">
                <div class="input-prepend">
                    <span class="add-on"><?php echo site_url() .'blog/' ?><?=(!empty($post->post_id) ? $post->post_id : '[:id]')?>/</span>
                    <input type="text" name="slug" class="input-xlarge" value="<?php echo isset($post->slug) ? $post->slug : set_value('slug'); ?>" />
                </div>
                <?php if (form_error('slug')) echo '<span class="help-inline">'. form_error('slug') .'</span>'; ?>
                <p class="help-block">The unique URL that this post can be viewed at.</p>
            </div>
        </div>
        
        <div class="control-group <?php if (form_error('tags')) echo 'error'; ?>">
            <label for="tags">Tags:</label>
            <div class="controls">
                <input type="text" name="tags" class="input-xxlarge" value="<?php echo isset($post->tags) ? $post->tags : set_value('tags'); ?>" />
                <?php if (form_error('slug')) echo '<span class="help-inline">'. form_error('tags') .'</span>'; ?>
                <p class="help-block">Comma Separated List of Tags.</p>
            </div>
        </div>
        
        <div class="control-group <?php if (form_error('body')) echo 'error'; ?>">
            <label for="excerpt">Excerpt</label>
            <div class="controls">
                <?php if (form_error('excerpt')) echo '<span class="help-inline">'. form_error('excerpt') .'</span>'; ?>
                <textarea name="excerpt" class="input-xxlarge" style="width: 725px;" rows="10"><?php echo isset($post->excerpt) ? $post->excerpt : set_value('excerpt') ?></textarea>
            </div>
        </div>

        <div class="control-group <?php if (form_error('body')) echo 'error'; ?>">
            <label for="body">Content</label>
            <div class="controls">
                <?php if (form_error('body')) echo '<span class="help-inline">'. form_error('body') .'</span>'; ?>
                <textarea name="body" class="markdown_editor input-xxlarge" rows="30"><?php echo isset($post->body) ? $post->body : set_value('body') ?></textarea>
            </div>
        </div>       

        <?php if(!empty($authors)): ?>
            <?=form_label('Author', 'user_id')?>
            <?=form_dropdown('user_id', $authors, isset($post->user_id) ? $post->user_id : set_value('user_id'))?>
        <?php endif; ?>
        <br>
        <?php $this->load->view('images/content/index', array('images' => isset($post->images) ? $post->images : false)); ?>
        
        <div class="form-actions">
            <input type="submit" name="submit" class="btn btn-primary" id="submit-all" value="Save Post" />
            <?php if(!empty($post->post_id) && $this->auth->has_permission('Blog.Content.Delete')) : ?>
    			or
    			<button type="submit" name="delete" class="btn btn-danger" id="delete-me" onclick="return confirm('<?php e(js_escape(lang('blog_delete_confirm'))); ?>'); ">
    				<span class="icon-trash icon-white"></span>&nbsp;<?php echo lang('blog_delete_record'); ?>
    			</button>
    			
    		<?php endif; ?>
            or <a href="<?php echo site_url(SITE_AREA .'/content/blog') ?>">Cancel</a>
        </div>

    <?php echo form_close(); ?>
    <br />
    <br />
    <?php $this->load->view('images/content/dropzone'); ?>    
</div>