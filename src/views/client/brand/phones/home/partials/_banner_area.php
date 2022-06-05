<section id="banner-area">
    <div class="owl-carousel owl-theme">
    <?php if (isset($slider['image']) && is_array($slider['image'])):
    foreach ($slider['image'] as $image) :?>
        <div class="item">
            <img src="<?=$image?>" alt="<?=$slider['title']?>">
        </div>
        <?php endforeach; endif; ?>
    </div>
</section>