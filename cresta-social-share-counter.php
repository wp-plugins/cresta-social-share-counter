<?php
/**
 * Plugin Name: Cresta Social Share Counter
 * Plugin URI: http://crestaproject.com/downloads/cresta-social-share-counter/
 * Description: <strong>*** <a href="http://crestaproject.com/downloads/cresta-social-share-counter/" target="_blank">Get Cresta Social Share Counter PRO</a> ***</strong> Share your posts and pages quickly and easily with Cresta Social Share Count showing the share count.
 * Version: 1.6
 * Author: CrestaProject - Rizzo Andrea
 * Author URI: http://crestaproject.com
 * License: GPL2
 */

require_once('class/cresta-share-gp.php');


add_action('admin_menu', 'cresta_social_share_menu');
add_action('wp_enqueue_scripts', 'cresta_social_share_wp_enqueue_scripts');
add_filter('the_content', 'cresta_filter_in_content' ); 
add_shortcode('cresta-social-share', 'add_social_button_in_content' );
add_action('admin_enqueue_scripts', 'cresta_social_share_admin_enqueue_scripts');

function cresta_social_share_menu() {
	global $cresta_options_page;
	$cresta_options_page = add_menu_page( 'Cresta Social Share Counter Options', 'CSSC FREE', 'manage_options', 'cresta-social-share-counter.php', 'cresta_social_share_option', plugins_url( '/cresta-social-share-counter/images/cssc-icon.png' ), 81 );
	add_action( 'admin_init', 'register_social_button_setting' );
}

function cresta_social_setting_link($links) { 
  $settings_link = '<a href="admin.php?page=cresta-social-share-counter.php">Settings</a>'; 
  array_unshift($links, $settings_link); 
  return $links; 
}

function cresta_social_share_admin_enqueue_scripts( $hook ) {
	global $cresta_options_page;
	if ( $hook == $cresta_options_page ) {
		wp_enqueue_style( 'cresta-social-admin-style', plugins_url('css/cresta-admin-css.css',__FILE__));
	}
}

function cresta_social_share_wp_enqueue_scripts()
{
		wp_enqueue_style( 'cresta-social-font-awesome', plugins_url('css/font-awesome.min.css',__FILE__));
		wp_enqueue_style( 'cresta-social-wp-style', plugins_url('css/cresta-wp-css.css',__FILE__));
		wp_enqueue_style( 'cresta-social-googlefonts', '//fonts.googleapis.com/css?family=Noto+Sans:400,700');
		
		$show_count = get_option('cresta_social_shares_show_counter');
		$show_floatbutton = get_option('cresta_social_shares_show_floatbutton');
		$enable_animation = get_option('cresta_social_shares_enable_animation');
		
		if($show_floatbutton == 1 && $show_count == 1) { 
			wp_enqueue_script( 'cresta-social-counter-js', plugins_url('js/jquery.cresta-social-share-counter.js',__FILE__), array('jquery'), '1.0', true );
			$buttons = explode (',',get_option( 'selected_button' ));
			if(in_array('gplus',$buttons)) {
				$obj=new crestaShareSocialCount (get_permalink());
				wp_localize_script( 'cresta-social-counter-js', 'crestaShare', array( 'GPlusCount' => $obj->get_plusones() ) );
			}
			wp_localize_script( 'cresta-social-counter-js', 'crestaPermalink', array('thePermalink' => get_permalink() ) );
		}
		
		if($show_floatbutton == 1 && $enable_animation == 1) {
			wp_enqueue_script( 'cresta-social-effect-js', plugins_url('js/jquery.cresta-social-effect.js',__FILE__), array('jquery'), '1.0', true );
		}
		

}
 
$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'cresta_social_setting_link' );


add_action('wp_footer', 'add_social_button');

function register_social_button_setting()
{
	if ( get_option( 'selected_button' ) === false ) {
		add_option( 'selected_button', 'facebook,tweet,gplus,pinterest,linkedin' );
	}
	if ( get_option( 'selected_page' ) === false ) {
		add_option( 'selected_page', 'pages,posts' );
	}
	if (get_option( 'cresta_social_shares_float' ) === false ) {
		add_option( 'cresta_social_shares_float', 'left' );
	}
	if (get_option( 'cresta_social_shares_float_buttons' ) === false ) {
		add_option( 'cresta_social_shares_float_buttons', 'right' );
	}
	if (get_option( 'cresta_social_shares_style' ) === false ) {
		add_option( 'cresta_social_shares_style', 'first_style' );
	}
	if (get_option( 'cresta_social_shares_position_top' ) === false ) {
		add_option( 'cresta_social_shares_position_top', '20' );
	}
	if (get_option( 'cresta_social_shares_position_left' ) === false ) {
		add_option( 'cresta_social_shares_position_left', '20' );
	}
	if (get_option( 'cresta_social_shares_twitter_username' ) === false ) {
		add_option( 'cresta_social_shares_twitter_username', '' );
	}
	if (get_option( 'cresta_social_shares_show_counter' ) === false ) {
		add_option( 'cresta_social_shares_show_counter', '1' );
	}
	if (get_option( 'cresta_social_shares_show_total' ) === false ) {
		add_option( 'cresta_social_shares_show_total', '1' );
	}
	if (get_option( 'cresta_social_shares_disable_mobile' ) === false ) {
		add_option( 'cresta_social_shares_disable_mobile', '1' );
	}
	if (get_option( 'cresta_social_shares_enable_animation' ) === false ) {
		add_option( 'cresta_social_shares_enable_animation', '1' );
	}
	if (get_option( 'cresta_social_shares_before_content' ) === false ) {
		add_option( 'cresta_social_shares_before_content', '0' );
	}
	if (get_option( 'cresta_social_shares_after_content' ) === false ) {
		add_option( 'cresta_social_shares_after_content', '1' );
	}
	if (get_option( 'cresta_social_shares_show_floatbutton' ) === false ) {
		add_option( 'cresta_social_shares_show_floatbutton', '1' );
	}
	
}

/* Cresta Social Share Counter In Content Position */
function cresta_filter_in_content( $content ) { 
	
	$before_content = get_option('cresta_social_shares_before_content');
	$after_content = get_option('cresta_social_shares_after_content');
	$show_on = explode (',',get_option( 'selected_page' ));

	if( is_page() && !in_array( 'pages', $show_on ) ) {
				return $content;
	}
	if( is_singular('post') && !in_array( 'posts', $show_on ) ) {
				return $content;
	}
	if( is_attachment() && !in_array( 'media', $show_on ) ) {
				return $content;
	}
	if( is_search() || is_404() || is_home() || is_archive() || is_front_page() ) {
				return $content;
	}
	$args = array(
		'public'   => true,
		'_builtin' => false
	);
	$post_types = get_post_types( $args, 'names', 'and' ); 
	foreach ( $post_types  as $post_type ) { 
		if ( is_singular( $post_type ) && !in_array( $post_type, $show_on )  ) {
			return $content;
		}
	}
	
	$addd_social_button_in_content = do_shortcode( add_social_button_in_content() );
		
	if($before_content == 1) {
		$content = $addd_social_button_in_content.$content;
	}
	if($after_content == 1) {
		$content .= $addd_social_button_in_content;
	}
		
    return $content;
}
function add_social_button_in_content() {
	$buttons = explode (',',get_option( 'selected_button' ));
	$button_style = get_option('cresta_social_shares_style');
	$cresta_twitter_username = get_option('cresta_social_shares_twitter_username');
	$buttons_position = get_option('cresta_social_shares_float_buttons');
	
	if ( '' != get_the_post_thumbnail() ) {
		$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		$pinImage = $pinterestimage[0];
	} else {
		$pinImage = plugins_url( '/images/no-image-found.png' , __FILE__ );
	}
	
	$allButtonsSelected = '';
	$theTwitterUsername = '';
	
	if ($cresta_twitter_username) {
		$theTwitterUsername = '&via=' .$cresta_twitter_username;
	}
	
	if(in_array('facebook',$buttons)) {
		$allButtonsSelected .= '<div class="sbutton facebook-cresta-share" id="facebook-cresta-c"><a rel="nofollow" href="http://www.facebook.com/sharer.php?u='. urlencode(get_permalink()) .'&t='. htmlspecialchars(urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') .'" title="Share to Facebook" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450\');return false;"><i class="fa fa-facebook"></i></a></div>';
	}

	if(in_array('tweet',$buttons)) {
		$allButtonsSelected .= '<div class="sbutton twitter-cresta-share" id="twitter-cresta-c"><a rel="nofollow" href="http://twitter.com/share?text='. htmlspecialchars(urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') .'&url='. urlencode(get_permalink()) .''. $theTwitterUsername .'" title="Share to Twitter" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450\');return false;"><i class="fa fa-twitter"></i></a></div>';
	}

	if(in_array('gplus',$buttons)) {
		$allButtonsSelected .= '<div class="sbutton googleplus-cresta-share" id="googleplus-cresta-c"><a rel="nofollow" href="https://plus.google.com/share?url='. urlencode(get_permalink()) .'" title="Share to Google Plus" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450\');return false;"><i class="fa fa-google-plus"></i></a></div>';
	}

	if(in_array('linkedin',$buttons)) {
		$allButtonsSelected .= '<div class="sbutton linkedin-cresta-share" id="linkedin-cresta-c"><a rel="nofollow" href="http://www.linkedin.com/shareArticle?mini=true&url='. urlencode(get_permalink()) .'&title='. htmlspecialchars(urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') .'&source='. esc_url( home_url( '/' )) .'" title="Share to LinkedIn" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450\');return false;"><i class="fa fa-linkedin"></i></a></div>';
	}

	if(in_array('pinterest',$buttons)) {
		$allButtonsSelected .= '<div class="sbutton pinterest-cresta-share" id="pinterest-cresta-c"><a rel="nofollow" href="http://pinterest.com/pin/create/bookmarklet/?url='.urlencode(get_permalink()) .'&media='. $pinImage .'&description='. htmlspecialchars(urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8').'" title="Share to Pinterest" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450\');return false;"><i class="fa fa-pinterest"></i></a></div>';
	}
	
	return '<!--www.crestaproject.com Social Button in Content Start--><style>#crestashareiconincontent {float: '. $buttons_position .';}</style><div id="crestashareiconincontent" class="cresta-share-icon '. $button_style .'">'. $allButtonsSelected .'<div style="clear: both;"></div></div><div style="clear: both;"></div><!--www.crestaproject.com Social Button in Content End-->';

}

/* Cresta Social Share Counter Float Position */
function add_social_button() {
	$show_floatbutton = get_option('cresta_social_shares_show_floatbutton');
	
	if ( $show_floatbutton ==1 ) {
	
	$buttons = explode (',',get_option( 'selected_button' ));
	$show_on = explode (',',get_option( 'selected_page' ));
	$show_count = get_option('cresta_social_shares_show_counter');
	$show_total = get_option ('cresta_social_shares_show_total');
	$enable_animation = get_option('cresta_social_shares_enable_animation');
	$float = get_option('cresta_social_shares_float');
	$button_style = get_option('cresta_social_shares_style');
	$position_top =  get_option('cresta_social_shares_position_top');
	$position_left =  get_option('cresta_social_shares_position_left');
	$disable = get_option('cresta_social_shares_disable_mobile');
	$cresta_twitter_username = get_option('cresta_social_shares_twitter_username');

	if($disable == 1) {
	echo '<style>
	@media (max-width : 640px) {
		#crestashareicon {
			display:none !important;
		}
	}
	</style>';
	}
	
	if($disable == 1 && wp_is_mobile()) {
	return;
	} else {

	if( is_page() && !in_array( 'pages', $show_on ) ) {
				return;
	}
	if( is_singular('post') && !in_array( 'posts', $show_on ) ) {
				return;
	}
	if( is_attachment() && !in_array( 'media', $show_on ) ) {
				return;
	}
	if( is_search() || is_404() || is_home() || is_archive() || is_front_page() ) {
				return;
	}
	$args = array(
		'public'   => true,
		'_builtin' => false
	);
	$post_types = get_post_types( $args, 'names', 'and' ); 
	foreach ( $post_types as $post_type ) { 
		if ( is_singular( $post_type ) && !in_array( $post_type, $show_on )  ) {
			return;
		}
	}
	

if ( '' != get_the_post_thumbnail() ) {
		$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
		$pinImage = $pinterestimage[0];
} else {
		$pinImage = plugins_url( '/images/no-image-found.png' , __FILE__ );
}

    echo "<!--www.crestaproject.com Social Button Floating Start-->

<style>

#crestashareicon {position:fixed; top:".$position_top."%; ".$float.":".$position_left."px; float:left;z-index:10;}

#crestashareicon .sbutton {clear:both;";if($enable_animation == 1) { echo 'display:none;'; }  echo "}
";
if($float == "right") {
	echo "#crestashareicon .sbutton {float:right;}";
	if ($button_style == "first_style") {
		echo ".cresta-share-icon.first_style .cresta-the-count {left: -11px;}";
	}
	if ($button_style == "second_style") {
		echo ".cresta-share-icon.second_style .cresta-the-count {left: -11px;}";
	}
	if ($button_style == "third_style") {
		echo ".cresta-share-icon.third_style .cresta-the-count {float: left;}";
	}
	if ($button_style == "fourth_style") {
		echo ".cresta-share-icon.fourth_style .cresta-the-count {left: -11px;}";
	}
} else {
	echo "#crestashareicon .sbutton { float:left;}";
}
echo "
</style>";

echo '<div id="crestashareicon" class="cresta-share-icon '. $button_style .' '; if($show_count == 1) { echo 'show-count-active'; } echo'">';


if(in_array('facebook',$buttons)) {
	echo '<div class="sbutton facebook-cresta-share" id="facebook-cresta"><a rel="nofollow" href="http://www.facebook.com/sharer.php?u='. urlencode(get_permalink()) .'&t='. htmlspecialchars(urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') .'" title="Share to Facebook" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450\');return false;"><i class="fa fa-facebook"></i></a>'; if($show_count == 1) { echo '<span class="cresta-the-count" id="facebook-count"><i class="fa fa-spinner fa-spin"></i></span>'; } echo '</div>';
}

if(in_array('tweet',$buttons)) {
	echo '<div class="sbutton twitter-cresta-share" id="twitter-cresta"><a rel="nofollow" href="http://twitter.com/share?text='. htmlspecialchars(urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') .'&url='. urlencode(get_permalink()) .''; if($cresta_twitter_username) { echo '&via=' . $cresta_twitter_username . ''; } echo '" title="Share to Twitter" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450\');return false;"><i class="fa fa-twitter"></i></a>'; if($show_count == 1) { echo '<span class="cresta-the-count" id="twitter-count"><i class="fa fa-spinner fa-spin"></i></span>'; } echo '</div>';
}

if(in_array('gplus',$buttons)) {
	echo '<div class="sbutton googleplus-cresta-share" id="googleplus-cresta"><a rel="nofollow" href="https://plus.google.com/share?url='. urlencode(get_permalink()) .'" title="Share to Google Plus" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450\');return false;"><i class="fa fa-google-plus"></i></a>'; if($show_count == 1) { echo '<span class="cresta-the-count" id="googleplus-count"><i class="fa fa-spinner fa-spin"></i></span>'; } echo '</div>';
}

if(in_array('linkedin',$buttons)) {
	echo '<div class="sbutton linkedin-cresta-share" id="linkedin-cresta"><a rel="nofollow" href="http://www.linkedin.com/shareArticle?mini=true&url='. urlencode(get_permalink()) .'&title='. htmlspecialchars(urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') .'&source='. esc_url( home_url( '/' )) .'" title="Share to LinkedIn" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450\');return false;"><i class="fa fa-linkedin"></i></a>'; if($show_count == 1) { echo '<span class="cresta-the-count" id="linkedin-count"><i class="fa fa-spinner fa-spin"></i></span>'; } echo '</div>';
}

if(in_array('pinterest',$buttons)) {
	echo '<div class="sbutton pinterest-cresta-share" id="pinterest-cresta"><a rel="nofollow" href="http://pinterest.com/pin/create/bookmarklet/?url='.urlencode(get_permalink()) .'&media='. $pinImage .'&description='. htmlspecialchars(urlencode(html_entity_decode(get_the_title(), ENT_COMPAT, 'UTF-8')), ENT_COMPAT, 'UTF-8') .'" title="Share to Pinterest" onclick="window.open(this.href,\'targetWindow\',\'toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes,width=700,height=450\');return false;"><i class="fa fa-pinterest"></i></a>'; if($show_count == 1) { echo '<span class="cresta-the-count" id="pinterest-count"><i class="fa fa-spinner fa-spin"></i></span>'; } echo '</div>';
}

if($show_count == 1) {
	echo '<div class="sbutton" id="total-shares">'; if($show_total == 1) { echo '<span class="cresta-the-total-count" id="total-count"><i class="fa fa-spinner fa-spin"></i></span><span class="cresta-the-total-text">Shares</span>'; } echo '</div>';
}

echo '<div style="clear: both;"></div></div>

<!--www.crestaproject.com Social Button Floating End-->
';
	} //if disable = 1 && wp_is_mobile
} //if show floating buttons is ON
}



function cresta_social_share_option() {
	
$message ="";
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}

if(isset($_REQUEST['social_buttons_hidden']))
{
	
	if(isset($_REQUEST['socialbuttons'])) {
	
		if($_REQUEST['socialbuttons'] != '') {
			$buttons  = implode(',',$_REQUEST['socialbuttons']);
			update_option('selected_button',$buttons);
			
		} if($_REQUEST['socialpages'] != '') {
			$show_on  = implode(',',$_REQUEST['socialpages']);
			update_option('selected_page',$show_on);
		}
		
		update_option('cresta_social_shares_float',$_REQUEST['floating']);
		update_option('cresta_social_shares_float_buttons', $_REQUEST['floatingbuttons']);
		update_option('cresta_social_shares_style',$_REQUEST['stylebutton']);
		update_option('cresta_social_shares_position_top',$_REQUEST['position-top']);
		update_option('cresta_social_shares_position_left',$_REQUEST['position-left']);
		update_option('cresta_social_shares_twitter_username', $_REQUEST['cresta-twitter-username']);
		
		if(isset($_REQUEST['showthecounter'])) {
			update_option('cresta_social_shares_show_counter','1');
		} else {
			update_option('cresta_social_shares_show_counter','0');
		}
		
		if(isset($_REQUEST['showthetotal'])) {
			update_option('cresta_social_shares_show_total','1');
		} else {
			update_option('cresta_social_shares_show_total','0');
		}
		
		if(isset($_REQUEST['disableonmobile'])) {
			update_option('cresta_social_shares_disable_mobile','1');
		} else {
			update_option('cresta_social_shares_disable_mobile','0');
		}
		
		if(isset($_REQUEST['enabletheanimation'])) {
			update_option('cresta_social_shares_enable_animation','1');
		} else {
			update_option('cresta_social_shares_enable_animation','0');
		}
		
		if(isset($_REQUEST['addaftercontent'])) {
			update_option('cresta_social_shares_after_content','1');
		} else {
			update_option('cresta_social_shares_after_content','0');
		}
		
		if(isset($_REQUEST['addbeforecontent'])) {
			update_option('cresta_social_shares_before_content','1');
		} else {
			update_option('cresta_social_shares_before_content','0');
		}
		
		if(isset($_REQUEST['showfloatbutton'])) {
			update_option('cresta_social_shares_show_floatbutton','1');
		} else {
			update_option('cresta_social_shares_show_floatbutton','0');
		}
		
		$message = '<div id="message" class="updated"><p><strong>Settings Saved...</strong></p></div>';
	}
}
	
	?>
	
<div class="wrap">
<div id="icon-options-general" class="icon32"></div>
<h2>Cresta Social Share Counter FREE</h2>

<?php echo $message; 
$buttons = explode (',',get_option( 'selected_button' ));
$show_on = explode (',',get_option( 'selected_page' ));
?>

<script type="text/javascript">
jQuery(document).ready(function(){
		
		if ( jQuery('input.crestashowsocialcounter').hasClass('active') ) {
			jQuery('.crestachoosetoshow').show();
		} else {
			jQuery('.crestachoosetoshow').hide();
		}
		
		if ( jQuery('input.crestatwitterenable').hasClass('active') ) {
			jQuery('.crestashowtwittername').show();
		} else {
			jQuery('.crestashowtwittername').hide();
		}
	
	jQuery('input.crestashowsocialcounter').on('click', function(){
		if ( jQuery(this).is(':checked') ) {
			jQuery('.crestachoosetoshow').fadeIn();
		} else {
			jQuery('.crestachoosetoshow').fadeOut();
		}
	});
	
	jQuery('input.crestatwitterenable').on('click', function(){
		if ( jQuery(this).is(':checked') ) {
			jQuery('.crestashowtwittername').fadeIn();
		} else {
			jQuery('.crestashowtwittername').fadeOut();
		}
	});
	
});
</script>

<div id="poststuff">

            <div id="post-body" class="metabox-holder columns-2">

                <!-- main content -->
                <div id="post-body-content">

                    <div class="meta-box-sortables ui-sortable">


                        <div class="postbox">

                            <h3><span><div class="dashicons dashicons-admin-generic"></div> Cresta Social Share Counter Settings</span></h3>
                            <div class="inside">
 <form name="social_buttons" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">  
        <input type="hidden" name="social_buttons_hidden" value="Y">
		
		
		<ul class="list-group">
		<li class="list-group-item list-group-item-info"><strong>Select buttons to display on website:</strong></li>
		<li class="list-group-item">
			<label><input type="checkbox" <?php if(in_array('facebook',$buttons)) { echo 'checked="checked"'; }?> name="socialbuttons[]" value="facebook"/>FaceBook</label>
		</li>
		<li class="list-group-item">
			<label><input type="checkbox" <?php if(in_array('tweet',$buttons)) { echo 'checked="checked"'; }?> name="socialbuttons[]" value="tweet" class="crestatwitterenable <?php if(in_array('tweet',$buttons)) { echo 'active'; }?>"/>Twitter</label>
		</li>
		<li class="list-group-item crestashowtwittername">
			<label>Twitter username (optional): @<input type="text" name="cresta-twitter-username" value="<?php echo get_option('cresta_social_shares_twitter_username');?>"/></label>
		</li>
		<li class="list-group-item">
			<label><input type="checkbox" <?php if(in_array('gplus',$buttons)) { echo 'checked="checked"'; }?> name="socialbuttons[]" value="gplus"/>Google Plus</label>
		</li>
		<li class="list-group-item">
			<label><input type="checkbox" <?php if(in_array('linkedin',$buttons)) { echo 'checked="checked"'; }?> name="socialbuttons[]" value="linkedin"/>Linkedin</label>
		</li>
		<li class="list-group-item">
			<label><input type="checkbox" <?php if(in_array('pinterest',$buttons)) { echo 'checked="checked"'; }?> name="socialbuttons[]" value="pinterest"/>Pinterest</label>
		</li>		
		</ul>
				<ul class="list-group">
		<li class="list-group-item list-group-item-info"><strong>Choose buttons style :</strong></li>
		<li class="list-group-item">
			<label>
				<input type="radio" name="stylebutton" <?php if(get_option('cresta_social_shares_style') == "first_style") { echo 'checked="checked"'; }?> value="first_style" >
				<img src="<?php echo plugins_url( '/images/cresta-social-share-counter-style-1.png' , __FILE__ ); ?>">
			</label>
			<label>
				<input type="radio" name="stylebutton" <?php if(get_option('cresta_social_shares_style') == "second_style") { echo 'checked="checked"'; }?> value="second_style" >
				<img src="<?php echo plugins_url( '/images/cresta-social-share-counter-style-2.png' , __FILE__ ); ?>">
			</label>
			<label>
				<input type="radio" name="stylebutton" <?php if(get_option('cresta_social_shares_style') == "third_style") { echo 'checked="checked"'; }?> value="third_style" >
				<img src="<?php echo plugins_url( '/images/cresta-social-share-counter-style-3.png' , __FILE__ ); ?>">
			</label>
			<label>
				<input type="radio" name="stylebutton" <?php if(get_option('cresta_social_shares_style') == "fourth_style") { echo 'checked="checked"'; }?> value="fourth_style" >
				<img src="<?php echo plugins_url( '/images/cresta-social-share-counter-style-4.png' , __FILE__ ); ?>">
			</label>
			<label>
				<input type="radio" name="stylebutton" <?php if(get_option('cresta_social_shares_style') == "fifth_style") { echo 'checked="checked"'; }?> value="fifth_style" >
				<img src="<?php echo plugins_url( '/images/cresta-social-share-counter-style-5.png' , __FILE__ ); ?>">
			</label>
			<label>
				<input type="radio" name="stylebutton" <?php if(get_option('cresta_social_shares_style') == "eleventh_style") { echo 'checked="checked"'; }?> value="eleventh_style" >
				<img src="<?php echo plugins_url( '/images/cresta-social-share-counter-style-11.png' , __FILE__ ); ?>">
			</label>
			<label>
				<input type="radio" name="stylebutton" <?php if(get_option('cresta_social_shares_style') == "twelfth_style") { echo 'checked="checked"'; }?> value="twelfth_style" >
				<img src="<?php echo plugins_url( '/images/cresta-social-share-counter-style-12.png' , __FILE__ ); ?>">
			</label>
		</li>
		</ul>
		<ul class="list-group">
		<li class="list-group-item list-group-item-info"><strong>Display Setting :</strong></li>
		<li class="list-group-item">
			<label><input type="checkbox" id="chkanim" name="enabletheanimation"  <?php if(get_option('cresta_social_shares_enable_animation') == "1") { echo 'checked="checked"'; }?> />Enable fade animation</label>
		</li>
		<li class="list-group-item">
			<label><input type="checkbox" id="chksocialcounter" name="showthecounter" class="crestashowsocialcounter <?php if(get_option('cresta_social_shares_show_counter') == "1") { echo 'active'; }?>"  <?php if(get_option('cresta_social_shares_show_counter') == "1") { echo 'checked="checked"'; }?> />Show Social Counter</label>
		</li>
		<li class="list-group-item crestachoosetoshow">
			<label><input type="checkbox" id="chksocialtotal" name="showthetotal"  <?php if(get_option('cresta_social_shares_show_total') == "1") { echo 'checked="checked"'; }?> />Show Total Shares</label>
		</li>
		</ul>
		<ul class="list-group">
		<li class="list-group-item list-group-item-info"><strong>Float Position :</strong></li>
		<li class="list-group-item">
			<label><input type="checkbox" id="chkflotbtn" name="showfloatbutton" class="crestashowfloatbutton" <?php if(get_option('cresta_social_shares_show_floatbutton') == "1") { echo 'checked="checked"'; }?> />Show Floating Buttons</label>
		</li>
		<li class="list-group-item">
		Float Buttons Position : <label><input type="radio" name="floating" <?php if(get_option('cresta_social_shares_float') == "left") { echo 'checked="checked"'; }?> value="left" >Left</label> || <label><input type="radio" name="floating" value="right" <?php if(get_option('cresta_social_shares_float') == "right") { echo 'checked="checked"'; }?>>Right</label>
		</li>
		<li class="list-group-item">
		Position From Top : <input type="text" name="position-top" value="<?php echo get_option('cresta_social_shares_position_top');?>" style="width:40px;"/>%</li>
		<li class="list-group-item">
		Position From Left or Right : <input type="text" name="position-left" value="<?php echo get_option('cresta_social_shares_position_left');?>" style="width:40px;"/>px </li>
		<li class="list-group-item">
			<label><input type="checkbox" id="chkmobile" name="disableonmobile"  <?php if(get_option('cresta_social_shares_disable_mobile') == "1") { echo 'checked="checked"'; }?> />Disable Buttons On Mobile</label>
		</li>
		</ul>
		<ul class="list-group">
		<li class="list-group-item list-group-item-info"><strong>Post and Page Position :</strong></li>
		<li class="list-group-item">
			<label><input type="checkbox" id="chkbeforecontent" name="addbeforecontent"  <?php if(get_option('cresta_social_shares_before_content') == "1") { echo 'checked="checked"'; }?> />Add Social Buttons before post/page content</label>
		</li>
		<li class="list-group-item">
			<label><input type="checkbox" id="chkaftercontent" name="addaftercontent"  <?php if(get_option('cresta_social_shares_after_content') == "1") { echo 'checked="checked"'; }?> />Add Social Buttons after post/page content</label>
		</li>
		<li class="list-group-item">
		Buttons Position (in content): <label><input type="radio" name="floatingbuttons" <?php if(get_option('cresta_social_shares_float_buttons') == "left") { echo 'checked="checked"'; }?> value="left" >Left</label> || <label><input type="radio" name="floatingbuttons" value="right" <?php if(get_option('cresta_social_shares_float_buttons') == "right") { echo 'checked="checked"'; }?>>Right</label>
		</li>
		<li class="list-group-item">
			<p>You can place the shortcode <code>[cresta-social-share]</code> wherever you want to display the social buttons.</p>
		</li>
		</ul>
		<ul class="list-group">
		<li class="list-group-item list-group-item-info"><strong>Show on :</strong></li>
		<li class="list-group-item">
			<label><input type="checkbox" <?php if(in_array('pages',$show_on)) { echo 'checked="checked"'; }?> name="socialpages[]" value="pages"/>Pages</label>
		</li>
		<li class="list-group-item">
			<label><input type="checkbox" <?php if(in_array('posts',$show_on)) { echo 'checked="checked"'; }?> name="socialpages[]" value="posts"/>Posts</label>
		</li>
		<li class="list-group-item">
			<label><input type="checkbox" <?php if(in_array('media',$show_on)) { echo 'checked="checked"'; }?> name="socialpages[]" value="media"/>Media</label>
		</li>
		<?php
			$args = array(
				'public'   => true,
				'_builtin' => false
			);
			$post_types = get_post_types( $args, 'names', 'and' ); 
			foreach ( $post_types  as $post_type ) { 
				$post_type_name = get_post_type_object( $post_type );
				echo '
					<li class="list-group-item">
						<label><input type="checkbox"';  if(in_array( $post_type ,$show_on)) { echo 'checked="checked"'; } echo' name="socialpages[]" value="' . $post_type . '"/>' . $post_type_name->labels->singular_name . ' <i>(Custom Post Type)</i></label>
					</li>
				';
			}
		?>
		</ul>
		
		<input type="submit" name="savesetting" class="btn btn-primary" value="Save Setting"/>
</form>
</div> <!-- .inside -->

                        </div> <!-- .postbox -->

                    </div> <!-- .meta-box-sortables .ui-sortable -->

                </div> <!-- post-body-content -->
  <!-- sidebar -->
                <div id="postbox-container-1" class="postbox-container">

                    <div class="meta-box-sortables">

                        <div class="postbox">
                            <h3><span><div class="dashicons dashicons-star-filled"></div> Rate it!</span></h3>
                            <div class="inside">
								Don't forget to rate <strong>Cresta Social Share Counter</strong> on WordPress Pugins Directory.<br/>
								I really appreciate it ;)
                                <br/>
								<img src="<?php echo plugins_url( '/images/5-stars.png' , __FILE__ ); ?>">
								<br/>
								<a class="crestaButton" href="https://wordpress.org/plugins/cresta-social-share-counter/"title="Rate Cresta Social Share Counter on WordPress Plugins Directory" class="btn btn-primary" target="_blank">Rate Cresta Social Share Counter</a>
                            </div> <!-- .inside -->
                        </div> <!-- .postbox -->

                        <div class="postbox">
                            
                            <h3><span><div class="dashicons dashicons-megaphone"></div> Need more? Get the PRO version</span></h3>
                            <div class="inside">
                                <a href="http://crestaproject.com/downloads/cresta-social-share-counter/" target="_blank" alt="Get Cresta Social Share Counter PRO"><img src="<?php echo plugins_url( '/images/banner-cresta-social-share-counter-pro.png' , __FILE__ ); ?>"></a><br/>
								Get <strong>Cresta Social Share Counter PRO</strong> for only <strong>4,99€</strong>.<br/>
								<ul>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> Share Email Button</li>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> More than 30 Effects</li>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> 13 Exclusive Button Styles</li>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> Social Counter Before / After Content</li>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> Change Colors</li>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> Tooltip on the Buttons</li>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> More settings</li>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> 20% discount code for all Cresta Project Themes</li>
									<li><div class="dashicons dashicons-yes crestaGreen"></div> and Much More...</li>
								</ul>
								<a class="crestaButton" href="http://crestaproject.com/downloads/cresta-social-share-counter/" target="_blank" title="More details">See More Details</a>
                            </div> <!-- .inside -->
                         </div> <!-- .postbox -->

                    </div> <!-- .meta-box-sortables -->

                </div> <!-- #postbox-container-1 .postbox-container -->

            </div> <!-- #post-body .metabox-holder .columns-2 -->

            <br class="clear">
        </div> <!-- #poststuff -->



</div>
<?php 
}?>