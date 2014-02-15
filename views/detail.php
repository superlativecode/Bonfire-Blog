<?php if($post): ?>   
    <div class="post">
        <h3 class="page-title"><?php e($post->title) ?><small class="subtitle"> - <?=date('F jS, Y', strtotime($post->release_date))?></small></h3>
        <?php if(isset($post->images[0])): ?>
        <img src="<?=$post->images[0]->image_url?>" alt="<?=$post->images[0]->title?>" class="img-rounded img-responsive center-block" style="max-height: 200px;">
        <hr>
        <?php endif; ?>
        <?php echo auto_typography(Parsedown::instance()->parse($post->body)) ?>
        <?php if(!empty($post->author)): ?>
            <hr>
            <footer>
                <div class="subtitle"><strong>Tags: </strong><i><?=$post->tags?></i></div>
                <hr>
                <div class="author subtitle">
                    <cite><strong>Author: </strong><a href="<?=(!empty($post->author->author_url) ? $post->author->author_url : site_url())?>" title="<?=$post->author->display_name?>'s Website" target="_blank"><?=$post->author->display_name?></a></cite>
                    <?php if(!empty($post->author->author_bio)): ?>
                        <p class="bio">
                            <strong>Bio: </strong><?=$post->author->author_bio?>
                        </p>
                    <?php endif; ?>
                </div>
            </footer>
        <?php endif; ?>
        <hr>
        <?php $this->load->view('comments/comments'); ?>
        
    </div>
    
<?php else: ?>
    <div class="alert alert-info">
        Uh oh! We couldn't find that post.
    </div>
<?php endif; ?>