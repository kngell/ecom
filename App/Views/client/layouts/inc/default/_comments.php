<section class="justify-content-center" id="comments_section">
    <div class="container py-4 w-75" id="comments-section-wrapper">
        <hr>
        <div class="comment_header">
            <span class="total"><?=$totalComments?>&nbsp;comments</span>
            <?=$commentsSortForm ?? ''?>
        </div>
        <?=$commentsform?>
        <div class="comments_wrapper">
            <?=$allComments?>
        </div>
        <?php if (count($comments) < $totalComments) :?>
        <a href="#" class="sho_more_comments">Show More</a>
        <?php endif; ?>
    </div>
</section>