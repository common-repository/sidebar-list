<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.princeahmed.com
 * @since             1.0.0
 * @package           Sidebar_List
 *
 * @wordpress-plugin
 * Plugin Name:       Sidebar List
 * Plugin URI:        http://www.princeahmed.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Prince
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sidebar-list
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}
class Sidebar_Contact_List_Widget extends WP_Widget {
	
	function __construct() {
		parent::__construct(
			'contact_list',
			esc_html( 'Contact List' ),
			array(
				'description' => 'Display your contact list with images'
			)
		);

		add_action( 'widgets_init', function () {
			register_widget( __CLASS__ );
		} );
		
		add_action('admin_enqueue_scripts',array($this,'sidebar_contactlist_admin_scripts'));
		//add_action('wp_enqueue_scripts',array($this,'sidebar_contactlist_frontend_scripts'));
		
	}
	
	function mhtml($label = '', $image = '', $icon = '', $text = '', $link = '',$display = false)
	{
		if($display){
			$img_btn = 'Change';
			$remove_img = 'style="display:inline-block;"';
			$image_display = 'style="display:block;"';
		}else{
			$img_btn = 'Add';
			$remove_img = 'style="display: none;"';
			$image_display = 'style="display: none;"';
		}
		return '<li class="sidebar-contact-list-body">
					<div class="sidebar-contact-list-tools">
						<a href="#" class="btn btn-primary edit">Edit</a>
						<a href="#" class="btn btn-danger delete">Delete</a>
						<span class="sidebar-contact-list-label">'.$label.'</span>
					</div>
					
					<span class="sidebar-contact-list-container">
					<table class="table">
					<tr>
						<td><label for="label">Label: </label></td>
						<td><input type="text" name="' .$this->get_field_name('label[]' ). '" value="'.$label.'" class="" id="label"></td>
					</tr>
					<tr class="sidebar-contact-list-img">
						<td><label for="">Image: </label></td>
						<td><input type="hidden" name="' .$this->get_field_name('image[]' ). '" value="'.$image.'" class="">
                            
						<a href="#" class="add-img btn btn-primary">'.$img_btn.'</a>
						<a href="#" class="remove-img btn btn-danger" '.$remove_img.'>Delete</a>
						<br>
						<br>
						<img src="'.$image.'" class="img-responsive" '.$image_display.'>
						<br>
<strong>keep blank if you don\'t want to use Image.</strong></span>
						</td>
					</tr>
					<tr>
						<td><label for="icon">Icon: </label></td>
						<td><input type="text" name="' .$this->get_field_name('icon[]' ). '" value="'.$icon.'" id="icon">
						<span class="sidebar-list-desc">Use Font-awesome fonts. Use only the icon name. Ex: <code>fa-facebook</code><br>
<strong>keep blank if you don\'t want to use Icon.</strong></span>
						</td>
					</tr>
					
					<tr>	
						<td><label for="texts">Texts: </label></td>
						<td><input type="text" name="' .$this->get_field_name('texts[]' ). '" value="'.$text.'" id="texts">
						<span class="sidebar-list-desc">Display texts.</span>
						</td>
					</tr>
					
					<tr>	
						<td><label for="link">Link: </label></td>
						<td><input type="text" name="' .$this->get_field_name('link[]' ). '" value="'.$link.'" id="link"></td>
					</tr>
					</table>
					</span>
				</li>';
}
	
	function widget( $args, $instance ) {
		$images = $instance[ 'images' ];
		$icons = !empty( $instance[ 'icons' ] ) ? $instance[ 'icons' ] : '';
		$texts = $instance[ 'texts' ];
		$links = $instance[ 'links' ];
		
		$color = $instance[ 'color' ];
		$iconColor = $instance[ 'icon-color' ];
		$fontSize = $instance[ 'font-size' ];
		$iconSize = $instance[ 'icon-size' ];
		$imgWidth = $instance[ 'img-width' ];
		$imgHeight = $instance[ 'img-width' ];

		
		echo "<style>
		.list-unstyled{
		list-style: none;
		}
		.sidebar-list-img{
		width: {$imgWidth}px;
		height: {$imgHeight}px;
		margin-right: 15px;
		}
		.sidebar-list-icon{
		font-size: {$iconSize}px;
		color: $iconColor;
		margin-right: 15px;
		}
		.sidebar-list-texts{
		font-size: {$fontSize}px;
		color: $color;
		font-weight: bold;
		
		}
		</style>";
		
		
		
		echo $args[ 'before_widget' ];
		echo $args[ 'before_title' ];
		echo $instance[ 'title' ];
		echo $args[ 'after_title' ];
			echo '<ul class="list-unstyled">';
			$a = 0;
			while ( $a < count( $texts) || $a < count( $images ) ) {
				if(!$a == 0){
				?>
				
				<li class="">
					<a href="<?php echo $links[$a] ?>">
						<?php if(!empty($images[$a])) : ?>
						<img src="<?php echo $images[$a] ?>" class="sidebar-list-img">
						<?php endif;  if(!empty($icons[$a])) : ?>
						<i class="<?php echo 'fa '.$icons[$a] ?> sidebar-list-icon"></i>
						<?php endif;  if(!empty($texts[$a])) : ?>
						<span class="sidebar-list-texts">
							<?php echo $texts[$a] ?>
						</span>
						<?php endif; ?>
					</a>
				</li>

				<?php
				}
				$a++;
			}
			echo '</ul>';
		echo $args[ 'after_widget' ];
	}
	function form( $instance ) {
		$title = !empty( $instance[ 'title' ] ) ? $instance[ 'title' ] : 'Contact List';
		$labels = !empty( $instance[ 'labels' ] ) ? $instance[ 'labels' ] : '';
		$images = !empty( $instance[ 'images' ] ) ? $instance[ 'images' ] : '';
		$icons = !empty( $instance[ 'icons' ] ) ? $instance[ 'icons' ] : '';
		$texts = !empty( $instance[ 'texts' ] ) ? $instance[ 'texts' ] : '';
		$links = !empty( $instance[ 'links' ] ) ? $instance[ 'links' ] : '';
		$color = !empty( $instance[ 'color' ] ) ? $instance[ 'color' ] : '#000000';
		$iconColor = !empty( $instance[ 'icon-color' ] ) ? $instance[ 'icon-color' ] : '#001';
		$fontSize = !empty( $instance[ 'font-size' ] ) ? $instance[ 'font-size' ] : '14';
		$iconSize = !empty( $instance[ 'icon-size' ] ) ? $instance[ 'icon-size' ] : '38';
		$imgWidth = !empty( $instance[ 'img-width' ]  ) ? $instance[ 'img-width' ] : '60';
		$imgHeight = !empty( $instance[ 'img-height' ]  ) ? $instance[ 'img-height' ] : '60';
		
		?>
		<p>
			<label for="<?php echo $this->get_field_id('title') ?>">Title</label>
			<input class="widefat title" name="<?php echo $this->get_field_name('title') ?>" type="text" value="<?php echo $title ?>" id="title">
		</p>

		<ul class="sidebar-contact-list">
			<div id="sample" style="display: none;">
				<?php echo $this->mhtml(); ?>
			</div>
			<?php

			if ( count( $texts ) > 0 or count( $images ) > 0 ) {
				$a = 1;
				
				while ( $a < count( $texts ) or $a < count( $images ) ) {
					echo $this->mhtml(
							  $labels[$a],
							  $images[$a],
							  $icons[$a],
							  $texts[$a],
							  $links[$a],
							  true
							 );
					$a++;
				}
			}else{
				echo $this->mhtml();
			} ?>
		</ul>
		<a href="#" class="add-list btn btn-success"> Add New List</a>
<hr>
	<div class="output-settings">
	<h3>Output Settings</h3>
	<hr>
	<table class="table">
		<tr>
			<td><label>Font Color:</label></td>
			<td><input type="text" name="<?php echo $this->get_field_name('color') ?>" value="<?php echo $color ?>" id="output-color"></td>
		</tr>
		<tr>
			<td><label>Font Size</label></td>
			<td><input type="text" name="<?php echo $this->get_field_name('font-size') ?>" value="<?php echo $fontSize ?>" id="output-font-size">px</td>
		</tr>
		<tr>
			<td><label>Icon Color:</label></td>
			<td><input type="text" name="<?php echo $this->get_field_name('icon-color') ?>" value="<?php echo $iconColor ?>" id="output-color"></td>
		</tr>
		<tr>
			<td><label>Icon Size</label></td>
			<td><input type="text" name="<?php echo $this->get_field_name('icon-size') ?>" value="<?php echo $iconSize ?>" id="output-font-size">px<br>
 (<strong>If used Icon</strong>)</td>
		</tr>
		<tr>
			<td><label>Image Size</label></td>
			<td>
			<div class="img-size">
			<input type="text" name="<?php echo $this->get_field_name('img-width') ?>" value="<?php echo $imgWidth ?>">x
			<input type="text" name="<?php echo $this->get_field_name('img-height') ?>" value="<?php echo $imgHeight ?>">px <br>
(<strong>If used Image</strong>)
			</div>
			</td>
		</tr>
		
	</table>
	</div>
		<?php
	}
	function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance[ 'title' ] = esc_html($new_instance[ 'title' ]);
		$instance[ 'labels' ] = array_map('esc_html',$new_instance[ 'label' ]);
		$instance[ 'images' ] = array_map('esc_url',$new_instance[ 'image' ]);
		$instance[ 'icons' ] = array_map('esc_html',$new_instance[ 'icon' ]);
		$instance[ 'texts' ] = array_map('esc_html',$new_instance[ 'texts' ]);
		$instance[ 'links' ] = array_map('esc_url',$new_instance[ 'link' ]);
		$instance[ 'color' ] = esc_html($new_instance[ 'color' ]);
		$instance[ 'icon-color' ] = esc_html($new_instance[ 'icon-color' ]);
		$instance[ 'font-size' ] = esc_html($new_instance[ 'font-size' ]);
		$instance[ 'icon-size' ] = esc_html($new_instance[ 'icon-size' ]);
		$instance[ 'img-width' ] = esc_html($new_instance[ 'img-width' ]);
		$instance[ 'img-height' ] = esc_html($new_instance[ 'img-height' ]);


		return $instance;
	}
	function sidebar_contactlist_admin_scripts(){
	wp_register_style('sidebar-contact-list',plugin_dir_url( __FILE__ ).'assests/sidebar-contact-list.css',array(),'1.0');
	wp_enqueue_style("sidebar-contact-list");
		wp_enqueue_style('wp-color-picker');
	
	wp_register_script('sidebar-contact-list',plugin_dir_url( __FILE__ ).'assests/sidebar-contact-list.js',array('jquery','wp-color-picker'),'1.0', true);
	wp_enqueue_script("sidebar-contact-list");
}

}
new Sidebar_Contact_List_Widget();