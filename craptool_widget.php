<?php
/*
Plugin Name: CrapTool.com Software Vote Widget
Plugin URI: http://www.craptool.com/
Description: With this Widget you can show the actual Worst Software Rating from CrapTool.com in your sidebar.
Author: Michael Plas
Version: 1.0
Author URI: http://www.michaelplas.de
License: GPL 2.0, @see http://www.gnu.org/licenses/gpl-2.0.html
*/


class Craptool_Widget extends WP_Widget {
	function Craptool_Widget() {
		$widget_ops = array('classname' => 'widget_craptool', 'description' => 'With this Widget you can show the actual Worst Software Rating from CrapTool.com in your sidebar.' );
		$this->WP_Widget('craptool', 'CrapTool.com Stats', $widget_ops);
	}
 
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
 
		echo $before_widget;
		$title = empty($instance['title']) ? '&nbsp;' : apply_filters('widget_title', $instance['title']);
	
		if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };
		//$homepage = file_get_contents('http://www.craptool.com/index.php?page=wordpress');
		
		$fp = fsockopen("www.craptool.com", 80, $errno, $errstr, 3);
$homepage="";

 

		
		if (!$fp)
			{ echo"<ul> Dein Server unterstützt dieses Plugin leider nicht oder es konnte keine Verbindung aufgebaut werden $errstr ($errno)</ul>";
			//<iframe src="http://www.craptool.com/index.php?page=wordpress" style="border:0px #FFFFFF none;" name="craptool.com" scrolling="no" frameborder="0"  marginheight="0px" marginwidth="0px"></iframe>
			}
			else{
			
			   $out = "GET /index.php?page=wordpress HTTP/1.1\r\n";
    $out .= "Host: www.craptool.com\r\n";
    $out .= "Connection: Close\r\n\r\n";
    fwrite($fp, $out);
    while (!feof($fp)) {
     $homepage .= fgets($fp, 128);
	
    }
		preg_match("/<ul.*?>(.*)<\/ul>/s",$homepage  ,$homepagefilted);
		    echo $homepagefilted[0];

    fclose($fp);
			

		
			
			}
		echo $after_widget;
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);

 
		return $instance;
	}
 
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => 'CrapTool.com Stats' ) );
		$title = strip_tags($instance['title']);
	
?>
			<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>

<?php
	}
}
add_action( 'widgets_init', create_function('', 'return register_widget("Craptool_Widget");') );