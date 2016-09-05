<?php
/*
 * Plugin Name:       Dashifen's Post Meta Widget
 * Description:       A small plugin that creates a post-meta widget the way that Dashifen likes it.
 * Author:            David Dashifen Kees
 * Author URI:        http://dashifen.com
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * License:           GPL-2.0+
 * Version:           1.0.0
 */

if (!defined('WPINC')) {
	die;
}

class dashifen_post_meta_widget extends WP_Widget {
	public function __construct() {
		$widget = array(
			"classname"   => "dashifen-post-meta-widget",
			"description" => "A widget showing show metadata about a post.",
		);

		parent::__construct("dashifen-post-meta-widget", "Post Meta Widget", $widget);
	}

	public function widget($args, $instance) {
		$widget = "";

		if (is_home() || is_single()) {
			ob_start(); ?>

			<ul class="post-meta">
				<li class="date">
					<i class="fa fa-fw fa-calendar" aria-hidden="true" title="Posted On"></i>
					<?= get_the_time(get_option("date_format")) ?>
				</li>

				<?php
				$ID = get_the_ID();
				$post = get_post($ID);
				$format  = '<a href="%s">%s</a>';
				$categories = wp_get_post_terms($post->ID, "category");
				$post_tags  = wp_get_post_terms($post->ID, "post_tag");

				if (!is_wp_error($categories) && sizeof($categories) > 0) {
					$category = array_shift($categories); ?>
					<li class="category">
						<i class="fa fa-fw fa-folder-open" aria-hidden="true" title="Categorized"></i>
						<?= sprintf($format, get_term_link($category, "category"), ucwords($category->name)) ?>
					</li>
				<?php }

				if (!is_wp_error($post_tags) && sizeof($post_tags) > 0) {
					foreach ($post_tags as $i => $tag) {
						$post_tags[$i] = sprintf($format, get_term_link($tag, "post_tag"), strtolower($tag->name));
					} ?>

					<li class="tags">
						<i class="fa fa-fw fa-tags" aria-hidden="true" title="Tagged"></i>
						<ul class="oxford"><li><?= join("</li><li>", $post_tags) ?></li></ul>
					</li>
				<?php }

				$words_per_minute = 225;
				$words_in_post = str_word_count(strip_tags($post->post_content));
				$minutes_per_post = ceil($words_in_post / $words_per_minute);

				if($minutes_per_post <= 1) {
					$time_to_read = "&le; 1 minute";
				} else {
					$time_to_read = sprintf("~ %d minutes", $minutes_per_post);
				} ?>

				<li class="time-to-read">
					<i class="fa fa-fw fa-clock-o" aria-hidden="true" title="Time to Read"></i>
					<?= $time_to_read ?>
				</li>
			</ul>


			<?php $display = ob_get_clean();
			$widget  = sprintf("%s %s %s %s %s %s", $args["before_widget"], $args["before_title"],
				$instance["post_meta_title"], $args["after_title"], $display, $args["after_widget"]);

		}
		
		
		echo $widget;
	}

	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance["post_meta_title"] = sanitize_text_field($new_instance["post_meta_title"]);
		return $instance;
	}

	public function form($instance) {
		$defaults = array(
			"post_meta_title" => "",
		);

		$instance = wp_parse_args((array) $instance, $defaults); ?>

		<p>
			<label for="<?php echo $this->get_field_id("post_meta_title"); ?>">Widget's Title:</label>
			<input id="<?php echo $this->get_field_id("post_meta_title"); ?>" name="<?php echo $this->get_field_name("post_meta_title"); ?>" value="<?php echo $instance["post_meta_title"]; ?>" type="text" class="widefat">
		</p>

	<?php }
}

function load_dashifen_post_meta_widget() {
	register_widget("dashifen_post_meta_widget");
}

add_action("widgets_init", "load_dashifen_post_meta_widget");
