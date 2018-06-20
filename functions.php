<?php

if ( ! class_exists( 'Timber' ) ) {
	add_action( 'admin_notices', function() {
		echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php') ) . '</a></p></div>';
	});

	add_filter('template_include', function($template) {
		return get_stylesheet_directory() . '/static/no-timber.html';
	});

	return;
}

Timber::$dirname = array('templates', 'views');

function remove_menus()
{
		global $menu;
		global $current_user;
		get_currentuserinfo();
				$restricted = array(
					// If you want to hide anything from the admin feel free to do so here
						// __('Posts'),
						// __('Media'),
						// __('Links'),
						// __('Comments'),
						// __('Appearance'),
						// __('Plugins'),
						// __('Users'),
						//
						// __('Tools'),
						// __('Settings')
				);
				end ($menu);
				while (prev($menu)){
						$value = explode(' ',$menu[key($menu)][0]);
						if(in_array($value[0] != NULL?$value[0]:"" , $restricted)){unset($menu[key($menu)]);}
				}// end while

}
add_action('admin_menu', 'remove_menus');

class StarterSite extends TimberSite {

	function __construct() {
		add_theme_support( 'post-formats' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'menus' );
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption' ) );
		add_filter( 'timber_context', array( $this, 'add_to_context' ) );
		add_filter( 'get_twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		parent::__construct();
	}

	function register_post_types() {
		//this is where you can register custom post types

    // Example custom post types Video, Events, Galleries
    $custom_post_types = array('issue');

    // iterate through each post type
    foreach ($custom_post_types as $f) {
        $uc_f = ucwords(strtolower(preg_replace('/-/',' ',$f)));

        $labels = array(
            "name"                => _x( $uc_f . ""                            , "Post Type General Name" , $f ),
            "singular_name"       => _x( $uc_f . ""                             , "Post Type Singular Name", $f ),
            "menu_name"           => __( $uc_f . "s"                                    , $f ),
            "parent_item_colon"   => __( "Feed Data:"                                , $f ),
            "all_items"           => __( "All "     . $uc_f . "s"               , $f ),
            "view_item"           => __( "View "    . $uc_f . "s"               , $f ),
            "add_new_item"        => __( "Add New " . $uc_f . " "                , $f ),
            "add_new"             => __( "New "     . $uc_f . " "                , $f ),
            "edit_item"           => __( "Edit "    . $uc_f . " "                , $f ),
            "update_item"         => __( "Update "  . $uc_f . " "                , $f ),
            "search_items"        => __( "Search "  . $uc_f . " "               , $f ),
            "not_found"           => __( "No "      . $uc_f . "s  found"         , $f ),
            "not_found_in_trash"  => __( "No "      . $uc_f . "s  found in Trash", $f ),
        );


        // Set up the taxonomy associations and menu icons
        // $taxonomies = array('category', 'tags');
        // $menu_icon  =  'dashicons-welcome-write-blog';
        $taxonomies = array();

        if ($f === 'case-studies') {
          $menu_icon = 'dashicons-welcome-view-site';
          $taxonomies = array('');
        }

        $args = array(
            "label"               => __( $f , $f  ),
            "description"         => __( $uc_f . " Posts", $f  ),
            "labels"              => $labels,
            "hierarchical"        => false,
            "public"              => true,
            "show_ui"             => true,
            "show_in_menu"        => true,
            "taxonomies"          => $taxonomies,
            "show_in_nav_menus"   => true,
            "show_in_admin_bar"   => true,
            "menu_position"       => 5,
            "menu_icon"           => $menu_icon,
            "can_export"          => true,
            "show_in_rest"        => true,
            "has_archive"         => false,
            "exclude_from_search" => false,
            "publicly_queryable"  => true,
            'query_var'           => true,
            "capability_type"     => "post",
        		'rest_controller_class' => 'WP_REST_Posts_Controller',
            "rewrite"             => array('slug' => $f),
            "supports"            => array("author", "thumbnail", "title", "editor", "custom-field")
        );

        register_post_type( $f , $args );

        unset($labels, $args);
    }
	}

	function register_taxonomies() {
		//this is where you can register custom taxonomies
	}

	function add_to_context( $context ) {
		$context['foo'] = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::get_context();';
		$context['menu'] = new TimberMenu();
		$context['site'] = $this;
		return $context;
	}

	function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	function add_to_twig( $twig ) {
		/* this is where you can add your own functions to twig */
		$twig->addExtension( new Twig_Extension_StringLoader() );
		$twig->addFilter('myfoo', new Twig_SimpleFilter('myfoo', array($this, 'myfoo')));
		return $twig;
	}

}

new StarterSite();
