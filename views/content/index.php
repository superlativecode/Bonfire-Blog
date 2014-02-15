<div class="admin-box">
    <h3>Blog Posts</h3>

    <?php echo form_open(); ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th class="column-check"><input class="check-all" type="checkbox" /></th>
                    <th>Title</th>
                    <th>Stage</th>
                    <th>Views</th>
                    <th style="width: 10em">Release Date</th>
                    <th style="width: 10em">Created Date</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <td colspan="3">
                        <strong>With selected:</strong><br>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th><?=form_label('Release Date', 'release_date')?></th>
                                    <th><?=form_label('Stage', 'stage')?></th>
                                    <th>&nbsp;</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><input type="text" name="release_date" class="datetimepicker input-large" value="<?=date('Y-m-d H:i:s')?>" /></td>
                                    <td><?=form_dropdown('stage', array('draft' => 'Draft', 'review' => 'Ready for Review', 'published' => 'Published'), isset($post->stage) ? $post->stage : set_value('stage'))?></td>
                                    <td><input type="submit" name="update" class="btn btn-primary" value="Update">&nbsp;&nbsp;<input type="submit" name="delete" class="btn btn-danger" value="Delete"></td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tfoot>
            <tbody>
            <?php if (isset($posts) && is_array($posts)) :?>
                <?php foreach ($posts as $post) : ?>
                <tr>
                    <td><input type="checkbox" name="checked[]" value="<?php echo $post->post_id ?>" /></td>
                    <td>
                        <a href="<?php echo site_url(SITE_AREA .'/content/blog/edit_post/'. $post->post_id) ?>">
                            <?php e($post->title); ?>
                        </a>
                    </td>
                    <td><?=$post->stage?></td>
                    <td><?=$post->views?></td>
                    <td>
                        <?php echo date('M j, Y g:ia', strtotime($post->release_date)); ?>
                    </td>
                    <td>
                        <?php echo date('M j, Y g:ia', strtotime($post->created_on)); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">
                        <br/>
                        <div class="alert alert-warning">
                            No Posts found.
                        </div>
                    </td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>

    <?php echo form_close(); ?>
</div>