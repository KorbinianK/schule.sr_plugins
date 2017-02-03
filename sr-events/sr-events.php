<?php
/**
 * @package sr_event
 * @version 1.3
 */
/*
  Plugin Name: Event Widget for schule.sr
  Description: Zeigt einen Termin als Element an
  Version: 1.0
  Author: 
  Author URI: 
  
 */

/**
 * sr_event_Widget Class
 */
class sr_event_Widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct('sr_event', '# SR Termin', array(
			'classname' => 'sr_event',
			'description' => 'Zeigt Termin an'
		)
		);
	}

	/** @see WP_Widget::widget */
	public function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$year = $instance['date_y'];
		if($instance['date_d'] >10){
			$day = '0'.$instance['date_d'];
		}else{
			$day = $instance['date_d'];
		}
		$day = $instance['date_d'];
		

		switch ($instance['date_m']) {
			case '1':
				$month = 'jan';
				break;
			case '2':
				$month = 'feb';
				break;
			case '3':
				$month = 'mär';
				break;
			case '4':
				$month = 'apr';
				break;
			case '5':
				$month = 'mai';
				break;
			case '6':
				$month = 'jun';
				break;
			case '7':
				$month = 'jul';
				break;
			case '8':
				$month = 'aug';
				break;
			case '9':
				$month = 'sep';
				break;
			case '10':
				$month = 'okt';
				break;
			case '11':
				$month = 'nov';
				break;
			case '12':
				$month = 'dez';
				break;
			
			default:
				$month = 'error';
				break;
		}

		echo $before_widget;

		
		echo' <div class="sr-event">';
		echo' 	<div class="sr-event-date">';
		echo '		<span class="sr-event-month">'.$month.'</span>
					<span class="sr-event-day">'.$day.'</span>';
		echo' 	</div>';

		echo '<div class="sr-event-text">';
		echo "  <h2 class='sr-event-headline'>".$instance['name']."</h2>";
		echo "  <p class='sr-event-desc'>".$instance['desc']."</p>";
		echo'</div>';
		echo'</div>';

		echo $after_widget;
	}


	/** @see WP_Widget::update */
	public function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['name']);
		$instance['name'] = strip_tags($new_instance['name']);
		$instance['desc'] = strip_tags($new_instance['desc']);
		$instance['date_m'] = strip_tags($new_instance['date_m']);
		$instance['date_d'] = strip_tags($new_instance['date_d']);
		$instance['date_y'] = strip_tags($new_instance['date_y']);
		// $instance['more'] = (int) $new_instance['more'];
		return $instance;
	}

	/** @see WP_Widget::form */
	public function form($instance) {
		$name = '';
		if (isset($instance['title'])) {
			$title = esc_attr($instance['title']);
		}
		if (isset($instance['name'])) {
			$name = esc_attr($instance['name']);
		}
		if (isset($instance['desc'])) {
			$desc = esc_attr($instance['desc']);
		}

		if (isset($instance['date_m'])) {
			$date_m = esc_attr($instance['date_m']);
		}
		if (isset($instance['date_d'])) {
			$date_d = esc_attr($instance['date_d']);
		}
		if(isset($instance['date_y'])){
			$date_y = esc_attr($instance['date_y']);
		}else{
			$date_y = date('Y');
		}

?>
		<p>
		
		<input type="hidden"class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />

		<p><label for="<?php echo $this->get_field_id('name'); ?>"><?php _e('Name:'); ?> 
		<input class="widefat" id="<?php echo $this->get_field_id('name'); ?>" name="<?php echo $this->get_field_name('name'); ?>" type="text" value="<?php echo $name; ?>" />
		</label></p>
		<p><label for="<?php echo $this->get_field_id('desc'); ?>"><?php _e('Beschreibung:'); ?> 
		<input class="widefat" id="<?php echo $this->get_field_id('desc'); ?>" name="<?php echo $this->get_field_name('desc'); ?>" type="text" value="<?php echo $desc; ?>" />
		</label></p>


		<!--<p><label for="<?php echo $this->get_field_id('date'); ?>"><?php _e('Datum:'); ?>
		<input class="widefat" id="<?php echo $this->get_field_id('date'); ?> datepicker"  name="<?php echo $this->get_field_name('date'); ?>" type="text" value="" />
		</label></p>-->
		<div class="wrap">
			<input type="text" class="datepicker" name="datepicker" value=""/>
		</div>

		<p><label for="<?php echo $this->get_field_id('date_m'); ?>"><?php _e('Monat:'); ?>
		<input class="widefat" id="<?php echo $this->get_field_id('date_m'); ?>" name="<?php echo $this->get_field_name('date_m'); ?>" type="number" min="1" max="12" placeholder="4" value="<?php echo $date_m; ?>" />
		</label></p>
		<p><label for="<?php echo $this->get_field_id('date_d'); ?>"><?php _e('Tag:'); ?> 
		<input class="widefat" id="<?php echo $this->get_field_id('date_d'); ?>" name="<?php echo $this->get_field_name('date_d'); ?>" type="number" min="1" max="31"  placeholder="30" value="<?php echo $date_d; ?>" />
		</label></p>
		<p><label for="<?php echo $this->get_field_id('date_y'); ?>"><?php _e('Jahr:'); ?> 
		<input class="widefat" id="<?php echo $this->get_field_id('date_y'); ?>" name="<?php echo $this->get_field_name('date_y'); ?>" type="number" min="<?php echo date('Y'); ?>" placeholder="2016" value="<?php echo $date_y; ?>" />
		</label></p>
		
<?php
	}

}


function register_sr_event() {
	 return register_widget("sr_event_Widget"); 
	 }
