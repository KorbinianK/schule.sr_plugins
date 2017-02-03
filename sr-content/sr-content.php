<?php
/**
 * @package sr_content
 * @version 1.3
 */
/*
  Plugin Name: Content Widget for schule.sr
  Description: Zeigt eine Seite als Element an
  Version: 1.0
  Author: 
  Author URI: 
  
 */

/**
 * sr_content_Widget Class
 */
class sr_content_Widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct('sr_content', '# SR Page Widget', array(
			'classname' => 'sr_content',
			'description' => 'Zeigt eine Seite als Element an'
		)
		);
	}

	/** @see WP_Widget::widget */
	public function widget($args, $instance) {
		remove_filter('get_the_excerpt', 'wp_trim_excerpt');
		add_filter('get_the_excerpt', 'wpsr_custom_wp_trim_excerpt'); 


		extract($args);
		$page_id = (int) $instance['page_id'];
		$title = apply_filters('widget_title', $instance['title']);
		
		
		$more = (int) $instance['more'];

		echo $before_widget;

		if(!$page_id){
			echo 'Page in widget::No Page id set.';
			echo $after_widget;
			return;
		}

		if ($title) {
			echo $before_title . $title . $after_title;
		}
			
		$auto_excerpt =  wpsr_custom_wp_trim_excerpt($page_id,7000);
		echo' <div class="sr-content">';
		echo "<h1 class=''>".get_the_title($page_id)."</h1>";
		echo' 	<div class="sr-img-block">';
		echo get_the_post_thumbnail( $page_id, 'large', array( 'class' => 'featured-front-img' ) );
		echo' 	<div class="sr-content-block">';
		echo' 	</div>';
		echo' 		<p class="sr-content-text">'.$auto_excerpt.'</p>';
		echo'	</div>';
		echo'</div>	';

		echo $after_widget;
	}


	/** @see WP_Widget::update */
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['page_id'] = (int) $new_instance['page_id'];
		$instance['more'] = (int) $new_instance['more'];
		return $instance;
	}

	/** @see WP_Widget::form */
	public function form($instance) {
		$title = '';
		$page_id = 0;
		$checked = '';

		if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		}

		if (isset($instance['page_id'])) {
			$page_id = (int) esc_attr($instance['page_id']);
		}

		if(isset($instance['more'])){
			if($instance['more'] == 1){
				$checked = 'checked="checked"';
			}
		}

		$pageIdArgs = array(
			'selected' => $page_id,
			'name' => $this->get_field_name('page_id'),
		);
?>

		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
		<p><?php wp_dropdown_pages($pageIdArgs); ?></p>
		<p><label for="<?php echo $this->get_field_id('more'); ?>"><input id="<?php echo $this->get_field_id('more'); ?>" name="<?php echo $this->get_field_name('more'); ?>" type="checkbox" value="1" <?php echo $checked; ?> /> <?php _e('Show more link'); ?></label></p>
<?php
	}

}


function register_sr_content() {
	 return register_widget("sr_content_Widget"); 
	 }
add_action('widgets_init', 'register_sr_content');
