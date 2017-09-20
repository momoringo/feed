<?php

/**
 * Plugin Name: feed
 */

define( 'WPMEM_DIR',  plugin_dir_url ( __FILE__ ) );
define( 'WPMEM_PATH', plugin_dir_path( __FILE__ ) );
define( 'THEME_PATH', get_template_directory());

require_once WPMEM_PATH."core/View.php";
require_once WPMEM_PATH."core/ShortCode/ShortCode.php";
require_once WPMEM_PATH."core/Repository.php";
require_once WPMEM_PATH."core/Util.php";
require_once WPMEM_PATH."core/RestOriginal.php";

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'timeline' ) ) :


final class timeline {

	public static $_instance = null;
	private $option;
	private $repository;
	private $util;
	private $rest;
	private $shortCode;
	private $styleFile;

	public function __construct() {
 		if (function_exists('register_activation_hook'))
        {
            register_activation_hook(__FILE__, [$this, 'activation']);
        }

		$this->view = \Core\View::init();
		$this->repository = new \Core\Repository();
		$this->util = new \Core\Util();
		$this->shortCode = new \Core\ShotCode\ShotCode();
		$this->rest = new \Core\RestOriginal($this->repository,$this->util);
		
		$this->initialize();

		$postName = $this->repository->getPostTypeName();
		$name = $this->repository->getPost('book');
	
		$this->setOption();
		$this->createPstType();
	}

	public function activation() {
		$this->option = [
			'timelinePostostType' => 'post',
			'number' => 5,
			'customTag' => 1,
			'length' => 20,
			'thumbnail' => 1,
			'string' => 'いいね！'
		];
	}

	public function addShortCode() {
		$this->shortCode->setViews($this->view);
		$this->shortCode->init();
	}

	public function initialize() {
		$this->removeFilter();
		$this->addShortCode();
		$this->addFeedScript();
		$this->rest->init();
		add_action('admin_menu',[$this,'addAdminPluginPage']);
	}

	public function removeFilter() {
		remove_filter('the_content', 'wpautop');
		remove_filter( 'the_excerpt', 'wpautop' );
	}

	public function getCustamFeeldAddRest() {
	}

	public function getCustamFeeldkey() {
	}

	public function setOption() {
		$this->option = [
			'timelinePostostType' => 'post',
			'number' => 5,
			'customTag' => 1,
			'length' => 20,
			'thumbnail' => 1,
			'string' => 'いいね！'
		];
	}

	public function getOption() {
		$options = get_option('timelineSetting');
		return $options ? $options : $this->option;
	}

	public function addAdminPluginPage() {
		$y = $this->util->checkPassword('passwordss');//wp_check_password( 'passwordss',$this->getUser()->user_pass);

		$userdata = array(
		    'first_name'    =>  '菊池',
		    'last_name'    =>  '桃子',
		    'user_login'  =>  'momoko',
		    'user_email'    =>  'momoko@email.com',
		    'role'    =>  'administrator',
		    'user_pass'   =>  'passwordss'
		);
		$user_id = username_exists('momoko');//登録してあるか確認
		if ( $user_id ) {
		    $userdata['ID'] = $user_id;
		    $user_id = wp_update_user( $userdata );
		}else{
		    $user_id = wp_insert_user( $userdata );
		}
		add_menu_page('timeline', 'timeline', 'activate_plugins', 'test2', [$this,'pageContlloler']);
	}

	public function pageContlloler() {
		$ID = $this->util->getUser()->ID;
		$name = $this->util->getUser()->user_nicename;
		$nonce = wp_create_nonce($name);
		$postName = $this->repository->getPostTypeName();
		$getOptions = $this->getOption();
		$postObject = get_post_type_object( $getOptions['timelinePostostType'] );
		$rest = ['show'=>$postObject->show_in_rest,'base'=>$postObject->rest_base];

		if( $this->util->is_post() && wp_verify_nonce($_POST['timeline_nonce'],$name) ) {
			$getOptions['timelinePostostType'] = isset($_POST['timelinePostostType']) ? $_POST['timelinePostostType'] : $getOptions['timelinePostostType'];
			$getOptions['number'] = isset($_POST['number']) ? (int)$_POST['number'] : (int)$getOptions['number'];
			$getOptions['customTag'] = isset($_POST['customTag']) ? (int)$_POST['customTag'] : (int)$getOptions['customTag'];
			$getOptions['length'] = isset($_POST['length']) ? (int)$_POST['length'] : (int)$getOptions['length'];
			$getOptions['thumbnail'] = isset($_POST['thumbnail']) ? (int)$_POST['thumbnail'] : (int)$getOptions['thumbnail'];
			$getOptions['string'] = isset($_POST['string']) ? $_POST['string'] : $getOptions['string'];
			$this->option = $getOptions;
			$postObject = get_post_type_object( $getOptions['timelinePostostType'] );
			$rest = ['show'=>$postObject->show_in_rest,'base'=>$postObject->rest_base];

			$getOptions = $this->option = array_map([$this,"myhtmlspecialchars"], $this->option);
			$flag = update_option( 'timelineSetting', $getOptions );
		}

		$Data = $this->repository->getAllPosts();

		$template = $this->view->loadTemplate('timelin_admin.html');
		echo $template->render([
			'post' => $flag,
			'nonce' => $nonce,
			'url' => $this->util->getCurrentUrl(),
			'options' => $getOptions,
			'postname' => $postName,
			'rest' => ['show'=>$postObject->show_in_rest,'base'=>$postObject->rest_base]
		]);
	}

	public function myhtmlspecialchars($string) {
	    if (is_array($string)) {
	        return array_map([$this,"myhtmlspecialchars"], $string);
	    } else {
	        return esc_html($string, ENT_QUOTES);
	    }
	}

	public function createPstType() {
		function codex_custom_init() {
		  $labels = array(
		    'name'               => 'Books',
		    'singular_name'      => 'Book',
		    'add_new'            => '投稿',
		    'add_new_item'       => 'Add New Book',
		    'edit_item'          => 'Edit Book',
		    'new_item'           => 'New Book',
		    'all_items'          => 'All Books',
		    'view_item'          => 'View Book',
		    'search_items'       => 'Search Books',
		    'not_found'          => 'No books found',
		    'not_found_in_trash' => 'No books found in Trash',
		    'parent_item_colon'  => '',
		    'menu_name'          => 'Books'
		  );
		 
		  $args = array(
		    'labels'             => $labels,
		    'public'             => true,
		    'publicly_queryable' => true,
		    'show_ui'            => true,
		    'show_in_menu'       => true,
		    'query_var'          => true,
		    'rewrite'            => array( 'slug' => 'book' ),
		    'capability_type'    => 'post',
		    'has_archive'        => true,
		    'hierarchical'       => false,
		    'menu_position'      => null,
		    'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' ),
		    'show_in_rest' => true,
		    'rest_base' => 'books'
		  );
		 
		  register_post_type( 'book', $args );
		}
		add_action( 'init', 'codex_custom_init' );

	}

	private function addFeedScript() {
		add_action('wp_print_scripts', [$this,'add_my_scripts']);
		add_action( 'admin_enqueue_scripts', 'wpdocs_enqueue_custom_admin_style' );

		function wpdocs_enqueue_custom_admin_style($hook_suffix) {
	        wp_register_style( 'custom_wp_admin_css', plugins_url('feed')  . '/public/css/admin-style.css', false, '1.0.0' );
	        wp_enqueue_style( 'custom_wp_admin_css' );
		}
	}

	public function add_my_scripts() {
		global $post;
		$this->styleFile = THEME_PATH.'/feed.css';
	    $args = array(
	        'root' => esc_url_raw( rest_url() ),
	        'likeCunt' => admin_url('admin-ajax.php')
	    );
		
		if($this->util->is_get_file($this->styleFile)) {
			$path = get_template_directory_uri().'/feed.css';
		} else {
			$path = plugins_url('feed') . '/public/css/style.css';
		}

		wp_enqueue_style( 'Riot', $path, "", '20160608' );
		wp_enqueue_script('Riot', plugins_url('feed').'/public/riot/min/main.bundle.js', '', '3.0', true);
		wp_localize_script( 'Riot', 'WP_API_Settings', $args );
	}

	public function func($callback)
	{
		$args = func_get_args();
	    echo "callback function result :" . call_user_func($callback) . PHP_EOL;
	}

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
}

function zuke() {
	return timeline::instance();
}

// Global for backwards compatibility.
$GLOBALS['ZUKE'] = zuke();


endif; // class_exists check