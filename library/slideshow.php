<?php 

function arras_add_slideshow() {
	global $post_blacklist;
	if (!is_home()) return false;
	
	$slideshow_cat = arras_get_option('slideshow_cat');
	
	if (arras_get_option('enable_slideshow') == false) return false;
	
	$query = arras_parse_query($slideshow_cat, arras_get_option('slideshow_count'), array_unique($post_blacklist), arras_get_option('slideshow_posttype'), arras_get_option('slideshow_tax'));
	
	$q = new WP_Query( apply_filters('arras_slideshow_query', $query) );
	if ($q->have_posts()) :
	?> 
	<!-- Featured Slideshow -->
	<div class="featured clearfix">
		<div id="controls">
			<a href="" class="prev"><?php _e('Prev', 'arras') ?></a>
			<a href="" class="next"><?php _e('Next', 'arras') ?></a>
		</div>
		<div id="featured-slideshow">
			<?php $count = 0; ?>
		
			<?php while ($q->have_posts()) : $q->the_post(); ?>
			<div class="featured-slideshow-inner" <?php if ($count != 0) echo 'style="display: none"'; ?>>
				<a class="featured-article" href="<?php the_permalink(); ?>" rel="bookmark">
				<?php echo arras_get_thumbnail('featured-slideshow-thumb'); ?>
				</a>
				<div class="featured-entry">
					<a class="entry-title" href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
					<div class="entry-summary"><?php the_excerpt() ?></div>
					<div class="progress"></div>
				</div>
			</div>
			<?php 
			arras_blacklist_duplicates(); // required for duplicate posts function to work.
			$count++; endwhile; ?>
		</div>
	</div>
	<?php endif;
}

add_action('arras_above_content', 'arras_add_slideshow');

function arras_add_slideshow_js() {
?>
<script type="text/javascript">
jQuery(document).ready(function($) {

<?php if (is_home() || is_front_page()) : ?>
$('#featured-slideshow').cycle({
	fx: 'fade',
	speed: 250,
	next: '#controls .next',
	prev: '#controls .prev',
	timeout: 6000,
	pause: 1,
	slideExpr: '.featured-slideshow-inner',
	height: '<?php $size = arras_get_image_size('featured-slideshow-thumb'); echo $size['h']; ?>px'
});
<?php endif ?>
	
});
</script>
<?php
}
add_action('arras_footer', 'arras_add_slideshow_js');

function arras_get_slideshow_thumb_size($layout = '') {
	if (!$layout) {
		$layout = arras_get_option('layout');
	}
	
	if ( strpos($layout, '1c') !== false ) {
		$size = array(940, 300);
	} else if ( strpos($layout, '3c') !== false ) {
		$size = array(490, 225);
	} else {
		$size = array(640, 250);
	}
	
	return apply_filters('arras_slideshow_thumb_size', $size);
}

/* End of file slideshow.php */
/* Location: ./library/slideshow.php */