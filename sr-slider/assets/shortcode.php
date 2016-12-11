<?php if ( ! defined( 'ABSPATH' ) ) exit;



/**
 * Image Widget Shortcode
 */

add_shortcode( 'sr_slider', 'sr_slider_shortcode' );

if( !function_exists('sr_slider_shortcode') ):
	function sr_slider_shortcode($atts){
		extract(shortcode_atts(array(
			
			'url' 	  		=> '',
            'first_line'    => '',
            'second_line'    => '',
            'img_src'    => '',
			'size' 	  		=> 'large',
			'align'   		=> 'left',
			'linking'   	=> 'no',
			'window'   		=> 'same',
			'theme'   		=> 'hover',
			'width'   		=> '',
			'height'  		=> '',
		), $atts));

		
			$link_href = '';

		if( $img_src == '' ){
			$img_src = 'http://placehold.it/300?text=Placeholder';
		}else {
			$img_src = wp_get_attachment_image_src( $img_src, $size);
			$img_src = $img_src[0];
		}

		

		if( $window === 'new' ){
			$window = ' target="_blank"';
		}else{
			$window = '';
		}

		ob_start();
		?>

            
            <div class="slider-container"> 
            <a href="<?php echo $url;?>" class="sr-link">
				<div class="slider-image" style="background-image:url('<?php echo $img_src; ?>')"> 
               
                   <div class="flex-items-xs-center container sr-lines-container">
                   
                        <span class="sr-firstline"><?php echo $first_line; ?></span>
                        <span class="sr-secondline"><?php echo $second_line; ?></span>
                   
                   </div>
                </div>
                </a>
               </div>
           	

		<?php
		return ob_get_clean();
	}
endif;