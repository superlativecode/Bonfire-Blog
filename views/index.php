<h3 class="page-title">Blog</h3>
<div class="visible-phone">
    <?=form_open($this->uri->uri_string(), array('id' => 'blog-search', 'role' => 'form', 'class' => 'form-inline','method' => 'get'))?>
        <label for="term">Search Posts</label>
        <input type="text" class="form-control" value="<?=($this->input->get('term') ? $this->input->get('term') : '')?>" name="term">
        <button type="submit" class="btn btn-default">Go</button>
    <?=form_close()?>
</div>
<div class="hidden-phone">
    <?=form_open($this->uri->uri_string(), array('id' => 'blog-search', 'role' => 'form', 'class' => 'form-inline text-right','method' => 'get'))?>
        <label for="term">Search Posts</label>
        <input type="text" class="form-control" value="<?=($this->input->get('term') ? $this->input->get('term') : '')?>" name="term">
        <button type="submit" class="btn btn-default">Go</button>
    <?=form_close()?>
</div>
<hr>
<?php if($posts): ?>   
    <?php foreach($posts as $i => $post): ?> 
    <?=($i != 0 ? '<hr />' : '')?>
    <div class="post row">
        <div class="col-xs-12 col-sm-6 col-md-2 col-lg-2">
        <?php if(isset($post->images[0])): ?>            
            <a href="<?=site_url()?>blog/<?=$post->post_id?>/<?=$post->slug?>" title="<?=e($post->title)?>"><img src="<?=$post->images[0]->thumb_url?>" alt="<?=$post->images[0]->title?>" class="img-circle center-block" width="120px"></a>
        <?php endif; ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-9 col-lg-10">
            <h4><a href="<?=site_url()?>blog/<?=$post->post_id?>/<?=$post->slug?>" title="<?=e($post->title)?>"><?php e($post->title) ?></a> - <small class="subtitle"><?=date('F jS, Y', strtotime($post->release_date))?></small></h4>
            <?php if(!empty($post->excerpt)): ?>
                <?php echo auto_typography(Parsedown::instance()->parse($post->excerpt)); ?>
            <?php else: ?>
                <?php echo ellipsize(Parsedown::instance()->parse($post->body), 500); ?>
            <?php endif; ?>
            <div class="hidden-phone pull-right">
                <button onclick="javascript:window.location.href='<?=site_url()?>blog/<?=$post->post_id?>/<?=$post->slug?>#comments'" title="<?=e($post->title)?>" class="btn btn-default pull-right">
                    <?=(!empty($post->comments) ? count($post->comments) : 0)?>&nbsp;&nbsp;<span class="glyphicon glyphicon-comment"></span>
                </button>
                <button onclick="javascript:window.location.href='<?=site_url()?>blog/<?=$post->post_id?>/<?=$post->slug?>'" title="<?=e($post->title)?>" class="btn btn-default pull-right">
                    Read More&nbsp;&nbsp;<span class="glyphicon glyphicon-eye-open"></span>
                </button>
            </div>
        </div>
        <div class="visible-phone col-xs-12 col-sm-6 col-md-9 col-lg-10 text-center">
            <button onclick="javascript:window.location.href='<?=site_url()?>blog/<?=$post->post_id?>/<?=$post->slug?>'" title="<?=e($post->title)?>" class="btn btn-default">
                Read More&nbsp;&nbsp;<span class="glyphicon glyphicon-eye-open"></span>
            </button>
            <button onclick="javascript:window.location.href='<?=site_url()?>blog/<?=$post->post_id?>/<?=$post->slug?>#comments'" title="<?=e($post->title)?>" class="btn btn-default">
                <?=(!empty($post->comments) ? count($post->comments) : 0)?>&nbsp;&nbsp;<span class="glyphicon glyphicon-comment"></span>
            </button>
        </div>
    </div>
    <?php endforeach; ?>
    <?=$this->pagination->create_links()?>
<?php else: ?>
    <div class="alert alert-info">
        No Posts were found.
    </div>
<?php endif; ?>
<?php if(isset($tag_cloud)): ?>
    <hr>
    <div id="tag_cloud" class="text-center center-block">
        <h4><span class="glyphicon glyphicon-cloud" style="top: 5px; position: relative;"></span> Tag Cloud</h4>
        <div class="tags">
            <?=$tag_cloud?>
        </div>
    </div>
<?php endif; ?>