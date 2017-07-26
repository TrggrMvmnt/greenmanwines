<?php
$show_all_counters = !isset($post_options['counters']);
$counters_tag = is_single() ? 'span' : 'a';
 
if ($show_all_counters || themerex_strpos($post_options['counters'], 'views')!==false) {
	?>
	<<?php echo balanceTags($counters_tag); ?> class="post_counters_item post_counters_views icon-eye" title="<?php echo esc_attr( sprintf(__('Views - %s', 'themerex'), $post_data['post_views']) ); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><?php echo balanceTags($post_data['post_views']); ?></<?php echo balanceTags($counters_tag); ?>>
	<?php
}

if ($show_all_counters || themerex_strpos($post_options['counters'], 'comments')!==false) {
	?>
	<a class="post_counters_item post_counters_comments icon-comment" title="<?php echo esc_attr( sprintf(__('Comments - %s', 'themerex'), $post_data['post_comments']) ); ?>" href="<?php echo esc_url($post_data['post_comments_link']); ?>"><span class="post_counters_number"><?php echo balanceTags($post_data['post_comments']); ?></span></a>
	<?php 
}
 
$rating = $post_data['post_reviews_'.(themerex_get_theme_option('reviews_first')=='author' ? 'author' : 'users')];
if ($rating > 0 && ($show_all_counters || themerex_strpos($post_options['counters'], 'rating')!==false)) { 
	?>
	<<?php echo balanceTags($counters_tag); ?> class="post_counters_item post_counters_rating icon-star" title="<?php echo esc_attr( sprintf(__('Rating - %s', 'themerex'), $rating) ); ?>" href="<?php echo esc_url($post_data['post_link']); ?>"><span class="post_counters_number"><?php echo balanceTags($rating); ?></span></<?php echo balanceTags($counters_tag); ?>>
	<?php
}

if ($show_all_counters || themerex_strpos($post_options['counters'], 'likes')!==false) {
	// Load core messages
	themerex_enqueue_messages();
	$likes = isset($_COOKIE['themerex_likes']) ? $_COOKIE['themerex_likes'] : '';
	$allow = themerex_strpos($likes, ','.($post_data['post_id']).',')===false;
	?>
	<a class="post_counters_item post_counters_likes icon-heart <?php echo balanceTags($allow ? 'enabled' : 'disabled'); ?>" title="<?php echo balanceTags($allow ? esc_attr__('Like', 'themerex') : esc_attr__('Dislike', 'themerex')); ?>" href="#"
		data-postid="<?php echo esc_attr($post_data['post_id']); ?>"
		data-likes="<?php echo esc_attr($post_data['post_likes']); ?>"
		data-title-like="<?php esc_html_e('Like', 'themerex'); ?>"
		data-title-dislike="<?php esc_html_e('Dislike', 'themerex'); ?>"><span class="post_counters_number"><?php echo balanceTags($post_data['post_likes']); ?></span></a>
	<?php
}

if (is_single() && themerex_strpos($post_options['counters'], 'markup')!==false) {
	?>
	<meta itemprop="interactionCount" content="User<?php echo esc_attr(themerex_strpos($post_options['counters'],'comments')!==false ? 'Comments' : 'PageVisits'); ?>:<?php echo esc_attr(themerex_strpos($post_options['counters'], 'comments')!==false ? $post_data['post_comments'] : $post_data['post_views']); ?>" />
	<?php
}
?>