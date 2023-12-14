<?php
/**
 * Timber Juniper Theme
 * https://github.com/osomstudio/JuniperTheme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */


/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';

if ( file_exists( $composer_autoload ) ) {
	require_once $composer_autoload;
	$timber = new Timber\Timber();
}

require_once 'inc/include.php';

/**
 * @param callable $endpoints AUTHOR-API-EXPOSING deaktivieren
 */
add_filter( 'rest_endpoints', function ( $endpoints ) {
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
        unset( $endpoints['/wp/v2/users'] );
    }
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }

    return $endpoints;
} );

/**
 * @param string $scssFile the File we want to watch
 * @param bool $is_import the watched file is a file that gets importet in scss via @import
 * @param string $fileToCombile the file that needs to be recombiled when the $scssFile file changes
 */

function check_for_recompile( string $scssFile, bool $is_import = false, string $fileToCombile=''){
    $css_file = __DIR__ . '/src/css/theme.min.css';
    $map_file = 'style.map';

    if(!file_exists($scssFile)){

        // show message for Administrators
        if(function_exists('current_user_can')){
            if(current_user_can('Administrator')){
                $style="position:fixed; top: 0; left: 0; right: 0; background: red; color: white; text-align: center; padding: 0.5rem;";
                echo '<div style="'.$style.'">'.$scssFile . ' - file not found in scss compiler'.'</div>';
            }
        }
        return;
    };

    
    if( filemtime($scssFile) > filemtime($css_file) || filesize($css_file) == 0) {

        try {
            $wp_root_path = str_replace('/wp-content/themes', '', get_theme_root());
            $compiler = new ScssPhp\ScssPhp\Compiler();
            $compiler->setImportPaths(__DIR__ . '/scss');
            $compiler->setOutputStyle(ScssPhp\ScssPhp\OutputStyle::COMPRESSED);
            $compiler->setSourceMap(ScssPhp\ScssPhp\Compiler::SOURCE_MAP_FILE);
            $compiler->setSourceMapOptions([
                'sourceMapURL' =>  get_stylesheet_directory_uri() . '/style.map',
                'sourceMapBasepath' => get_stylesheet_directory_uri(),//$wp_root_path
            ]);

            if(true === $is_import){
                $scss_raw_string = file_get_contents($fileToCombile);
            }else{
                $scss_raw_string = file_get_contents($scssFile);
            }

            $result =  $compiler->compileString($scss_raw_string);

            if(!!$result){
                file_put_contents($map_file, $result->getSourceMap());
                file_put_contents($css_file, $result->getCss());
            }

        } catch(\Exception $e){
            // show message for Administrators
            if(function_exists('current_user_can')){
                if(current_user_can('administrator')){
                    $style="position:fixed; top: 0; left: 0; right: 0; background: red; color: white; text-align: center; padding: 0.5rem; z-index: 999999999;";
                    echo '<div style="'.$style.'">scssphp: Unable to compile content: '.$e->getMessage().'</div>';
                }
            }
        }
    }
}

function juniper_theme_enqueue() {
	$refresh_cache_time = time();
	// wp_enqueue_script( 'app-js', get_template_directory_uri() . '/src/js/_app.js', array(), $refresh_cache_time, true );
    wp_enqueue_script( 'nav-js', get_template_directory_uri() . '/src/js/nav.js', array(), $refresh_cache_time, true );
    wp_enqueue_script( 'project-js', get_template_directory_uri() . '/src/js/project.js', array(), $refresh_cache_time, true );
    wp_enqueue_style( 'tailwind-css', get_template_directory_uri() . '/src/css/_tailwindStyles.css', array(), $refresh_cache_time );

    check_for_recompile( __DIR__ . '/src/scss/_project.scss', true, __DIR__ . '/src/scss/_project.scss');
	wp_enqueue_style( 'theme-css', get_template_directory_uri() . '/src/css/theme.min.css', array(), $refresh_cache_time );


    wp_enqueue_style( 'font-css', get_template_directory_uri() . '/fonts.css', array(), $refresh_cache_time );

}

add_action( 'wp_enqueue_scripts', 'juniper_theme_enqueue' );


/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( ! class_exists( 'Timber' ) ) {

	add_action(
		'admin_notices',
		function () {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		}
	);

	add_filter(
		'template_include',
		function ( $template ) {
			return get_stylesheet_directory() . '/static/no-timber.html';
		}
	);
	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


//StarterSite class
require_once 'class-startersite.php';
$site = new StarterSite();

add_theme_support( 'custom-logo' );

function juniper_customizer_setting($wp_customize) {
    $wp_customize->add_setting('footer_logo');
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'footer_logo', array(
        'label' => 'Upload Footer Logo',
        'section' => 'title_tagline', //this is the section where the custom-logo from WordPress is
        'settings' => 'footer_logo',
        'priority' => 8 // show it just below the custom-logo
    )));

    $wp_customize->add_setting( 'juniper_footer_textarea', array(
        'capability' => 'edit_theme_options',
        'default' => 'Lorem Ipsum Dolor Sit amet',
        'sanitize_callback' => 'sanitize_textarea_field',
    ) );
      
    $wp_customize->add_control( 'juniper_footer_textarea', array(
        'type' => 'textarea',
        'section' => 'title_tagline', // // Add a default or your own section
        'label' => __( 'Footer Quote' ),
        'description' => __( 'Enter footer quote.' ),
    ) );
}

add_action('customize_register', 'juniper_customizer_setting');

function wps_juniper_register_nav_menu(){
    register_nav_menus( array(
        'primary_menu' => __( 'Primary Menu', 'wps_juniper' ),
        'secondary_menu' => __( 'Secondary Menu', 'wps_juniper' ),
        'footer_menu'  => __( 'Footer Menu', 'wps_juniper' ),
    ) );
}
add_action( 'after_setup_theme', 'wps_juniper_register_nav_menu', 0 );

add_filter( 'timber/context', 'wps_add_to_context' );
function wps_add_to_context( $context ) {
    $custom_logo_id                 = get_theme_mod( 'custom_logo' );
    $logo                           = wp_get_attachment_image_url( $custom_logo_id , 'full' );
    $context['logo']                = $logo;
    $footer_logo                    = get_theme_mod( 'footer_logo' );
    $context['footer_logo']         = $footer_logo;
    $footer_quote                   = get_theme_mod( 'juniper_footer_textarea' );
    $context['footer_quote']        = $footer_quote;
    $upload_dir                     = wp_upload_dir();
    $context['uploads']             = $upload_dir;
    $context['theme_dir']           = get_stylesheet_directory_uri();
    $context['primary_menu']        = new \Timber\Menu( 'primary-menu' );
    $context['secondary_menu']      = new \Timber\Menu( 'secondary-menu' );
    $context['footer_menu']         = new \Timber\Menu( 'footer-menu' );
    $context['title']               = get_the_title();
    $context['jumbotron_bg_image']  = get_stylesheet_directory_uri() . '/assets/img/default_bg_image.png';
    $context['home_page_url']       = home_url();
    $context['page_title']          = get_the_title();
    $home_page_url                  = home_url();
    $context['home_page_url']       = $home_page_url;
    $context['parent_page_title']   = '';
    $context['parent_page_url']     = '';
    
    if( is_single() ) {
        $post_type = get_post_type();
        $page = get_field($post_type . '_archive_page', 'option');
        if($page) {
            $context['parent_page_title'] = $page->post_title;
            $context['parent_page_url']   = get_permalink($page);
        }

        if($post_type === "post") {
            $post = get_post();
            if($post_thumbnail = get_the_post_thumbnail_url( $post, 'full' )) {
                $context['jumbotron_bg_image'] = $post_thumbnail;  
            }
        }
    }

    return $context;
}

add_action( 'wp_enqueue_scripts', 'wpse_enqueues' );
function wpse_enqueues() {
    // Only enqueue on specified single CPTs
    if( is_singular() ) {
        $refresh_cache_time = time();
        wp_enqueue_style( 'wps-jumbotron-css', get_stylesheet_directory_uri() . '/blocks/jumbotron/style.css', array(), $refresh_cache_time );
    }
}

// custom wps functionality from classes

require_once __DIR__.'/classes/MailPoetGF.php';
use wps\MailPoetGF;

// define in init so plugin functions are available in this class
add_action('init', array(MailPoetGF::get_instance(), 'init'));


add_filter( 'render_block', 'wps_juniper_add_class_to_list_block', 10, 2 );
function wps_juniper_add_class_to_list_block( $block_content, $block ) {
    if ( 'core/group' === $block['blockName'] ) {
        $block_content = new WP_HTML_Tag_Processor( $block_content );
        $block_content->next_tag( 'div' );
        $block_content->add_class( 'container' );
        $block_content->add_class( 'wps-content' );
        $block_content->get_updated_html();
    }

    return $block_content;
}

function wps_juniper_acf_init() {
    
    acf_update_setting('google_api_key', 'AIzaSyA2nwpgRNcXh27RBL41e47d6pFcJda9qiY');
}

add_action('acf/init', 'wps_juniper_acf_init');

/**
 * Change the excerpt more string
 */
function wps_juniper_excerpt_more( $more ) {
    return ' [...]';
}
add_filter( 'excerpt_more', 'wps_juniper_excerpt_more' );




add_filter('locale', 'change_gravity_forms_language');
function change_gravity_forms_language($locale) {

    if (class_exists('RGForms')) {
        $gravity_forms_language = 'de_DE';
        return $gravity_forms_language;
    }

    return $locale;
}


/**
* Filters the next, previous and submit buttons.
* Replaces the form's <input> buttons with <button> while maintaining attributes from original <input>.
*
* @param string $button Contains the <input> tag to be filtered.
* @param object $form Contains all the properties of the current form.
*
* @return string The filtered button.
*/
add_filter( 'gform_next_button', 'input_to_button', 10, 2 );
add_filter( 'gform_previous_button', 'input_to_button', 10, 2 );
add_filter( 'gform_submit_button', 'input_to_button', 10, 2 );
function input_to_button( $button, $form ) {
     
    $dom = new DOMDocument();
    $dom->loadHTML( '<?xml encoding="utf-8" ?>' . $button );
    $input = $dom->getElementsByTagName( 'input' )->item(0);
    $new_button = $dom->createElement( 'button' );
    $new_button->appendChild( $dom->createTextNode( $input->getAttribute( 'value' ) ) );
    $input->removeAttribute( 'value' );
    foreach( $input->attributes as $attribute ) {
        $new_button->setAttribute( $attribute->name, $attribute->value );
    }
    $new_button->setAttribute('class', 'btn btn-primary cursor-pointer');
    $input->parentNode->replaceChild( $new_button, $input );
 
    return $dom->saveHtml( $new_button );
}