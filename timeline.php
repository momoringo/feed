<?php
/**
 * The bbPress Plugin
 *
 * bbPress is forum software with a twist from the creators of WordPress.
 *
 * $Id: bbpress.php 6254 2017-01-17 09:05:29Z johnjamesjacoby $
 *
 * @package bbPress
 * @subpackage Main
 */

/**
 * Plugin Name: timeline
 */


define( 'WPMEM_DIR',  plugin_dir_url ( __FILE__ ) );
define( 'WPMEM_PATH', plugin_dir_path( __FILE__ ) );

require_once WPMEM_PATH.'tmplInit.php';
require_once WPMEM_PATH."shortCode/code.php";
require_once WPMEM_PATH."core/repository.php";
require_once WPMEM_PATH."core/util.php";
require_once WPMEM_PATH."core/view.php";




		$path = explode('/',str_replace('\\', '/', '/test/kk/jhf\jhg'));


// Exit if accessed directly



defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'timeline' ) ) :
/**
 * Main bbPress Class
 *
 * "How doth the little busy bee, improve each shining hour..."
 *
 * @since 2.0.0 bbPress (r2464)
 */


final class timeline {

	public static $_instance = null;
	public $tes = 67;
	public $tmpl;
	public $sesstion;
	private $option;
	private $repository;
	private $util;

	public function __construct() {

 		if (function_exists('register_activation_hook'))
        {
            register_activation_hook(__FILE__, array($this, 'activation'));
        }

		$this->tmpl = TwigInit::init();
		$this->repository = new Repository();
		$this->util = new Util();

		$postName = $this->repository->getPostTypeName();
		$name = $this->repository->getPost('book');
	
		add_action('admin_menu',[$this,'addAdminPluginPage']);

		$u = new \shotCode\Code($this->tmpl);

			
		$u->init();


		    remove_filter('the_content', 'wpautop');
			remove_filter( 'the_excerpt', 'wpautop' );

		$found_posts = get_posts( 'numberposts=1' );

	
		$this->setOption();


		$this->createPstType();

		$this -> addFeedScript();


		




		add_action( 'rest_api_init', [$this,'addEndpoitsRest'] );

	}




	//custamfeeldの調整


	public function getCustamFeeldAddRest() {

	}

	public function getCustamFeeldkey() {

	}

	public function addEndpoitsRest() {

		$post_type = $this->repository->getOnlyTypeName();
		$option = $this->getOption();

		
	    register_rest_field( $post_type,
	        'originalExcerpt',
	        array(
	            'get_callback'    => [$this,'slug_get_starship'],
	            'update_callback' => null,
	            'schema'          => null,
	        )
	    );

	    if($option['thumbnail']) {
		    register_rest_field( $post_type,
		        'originalThumbnail',
		        array(
		            'get_callback'    => [$this,'slug_get_thumb'],
		            'update_callback' => null,
		            'schema'          => null,
		        )
		    );	    	
	    }

		register_rest_field(
	        $post_type,        // post type
	        'post_meta',   // rest-apiに追加するキー
	        array(
	            'get_callback'  => function(  $object, $field_name, $request  ) {
	                // 出力したいカスタムフィールドのキーをここで定義

	                $metas = $meta = $this->repository->getMetaData($object[ 'id' ]);
	                return $metas;
	            },
	            'update_callback' => null,
	            'schema'          => null,
	        )
    	);

	}

	public function slug_get_starship( $object, $field_name, $request ) {
		$option = $this->getOption();
		$result = mb_strimwidth( $object["content"]['raw'], 0, $option["length"], "...", "UTF-8" );
	    return $result;
	}

	public function slug_get_thumb( $object, $field_name, $request ) {
		$image_url = wp_get_attachment_image_src(get_post_thumbnail_id($object->ID), 'thumbnail');
	    return $image_url[0];
	}


	public function createEndpoitsRest() {

		add_action( 'rest_api_init', function () {
			register_rest_route( 'custom/v0', '/show', array(
				'methods' => 'GET',
				'callback' => [$this,'show_item']
			) );
		} );

	}


public function show_item(){
  //何かしらの処理
  //$data = ['apple'=>'りんご', 'peach'=>'もも', 'pear'=>'なし'];
  $Data = $this->repository->getPost('page');

  $response = new WP_REST_Response($Data);
  $response->set_status(200);
  $domain = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"];
  $response->header( 'Location', $domain );
  return $response;
}


	public function setOption() {
		$this->option = [
			'timelinePostostType' => 'post',
			'number' => 5,
			'customTag' => 1,
			'length' => 20,
			'thumbnail' => 1
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


			$this->option = $getOptions;

			$postObject = get_post_type_object( $getOptions['timelinePostostType'] );
			$rest = ['show'=>$postObject->show_in_rest,'base'=>$postObject->rest_base];

			$getOptions = $this->option = array_map([$this,"myhtmlspecialchars"], $this->option);
			$flag = update_option( 'timelineSetting', $getOptions );
		}

	
		$Data = $this->repository->getAllPosts();

		$template = $this->tmpl->loadTemplate('timelin_admin.html');
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


	public function activation() {
	}

	private function addFeedScript() {


		add_action('wp_print_scripts', 'add_my_scripts');
		add_action( 'admin_enqueue_scripts', 'wpdocs_enqueue_custom_admin_style' );

		function add_my_scripts() {
			global $post;
			if (!has_shortcode( $post->post_content, 'basic')) 
				return;

		    $args = array(
		        'root' => esc_url_raw( rest_url() )
		    );
			wp_enqueue_style( 'Riot', plugins_url('timeline') . '/public/css/style.css', "", '20160608' );
			wp_enqueue_script('Riot', plugins_url('timeline').'/public/riot/min/main.bundle.js', '', '3.0', true);
			wp_localize_script( 'Riot', 'WP_API_Settings', $args );
		}



		function wpdocs_enqueue_custom_admin_style($hook_suffix) {
	        wp_register_style( 'custom_wp_admin_css', plugins_url('timeline')  . '/public/css/admin-style.css', false, '1.0.0' );
	        wp_enqueue_style( 'custom_wp_admin_css' );
		}


	}



	public function func($callback)
	{
		$args = func_get_args();
	    echo "callback function result :" . call_user_func($callback) . PHP_EOL;
	}




	public function setUser() {
		$userdata = array(
		    'first_name'    =>  '菊池',
		    'last_name'    =>  '桃子',
		    'user_login'  =>  'momoko',
		    'user_email'    =>  'momoko@email.com',
		    'role'    =>  'editor',
		    'user_pass'   =>  'password'
		);
		$user_id = username_exists( 'momoko');//登録してあるか確認
		if ( $user_id ) {
		    $userdata['ID'] = $user_id;
		    $user_id = wp_update_user( $userdata );
		}else{
		    $user_id = wp_insert_user( $userdata );
		}
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