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


defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'timeline' ) ) :


final class timeline {

	public static $_instance = null;
	public $tes = 67;
	public $tmpl;
	public $sesstion;
	private $option;
	private $repository;
	private $util;
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
		$this->styleFile = THEME_PATH.'/feed.css';
		$this->initialize();


		$this->likeController();
		

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
			'thumbnail' => 1
		];
	}

	public function addShortCode() {
		$this->shortCode->setViews($this->view);
		$this->shortCode->init();
	}

	//custamfeeldの調整

	public function initialize() {
		$this->removeFilter();
		$this->addShortCode();
		$this->addFeedScript();
		add_action('admin_menu',[$this,'addAdminPluginPage']);
		add_action( 'rest_api_init', [$this,'addEndpoitsRest']);
	}

	public function removeFilter() {
		remove_filter('the_content', 'wpautop');
		remove_filter( 'the_excerpt', 'wpautop' );
	}

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

	    register_rest_field( $post_type,
	        'likeCount',
	        array(
	            'get_callback'    => [$this,'get_like'],
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

		$image_url = wp_get_attachment_image_src(get_post_thumbnail_id($object['id']), 'thumbnail');
	    return $image_url[0];
	}

	public function get_like( $object, $field_name, $request ) {
		$likeCount =  (int)get_post_meta( $object['id'], '_like' )[0];
		if(empty($likeCount)) {
			update_post_meta($object['id'],'_like',0,true);
			$likeCount = 0;
		}
	    return $likeCount;
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


	public function likeController() {

		add_action('wp_ajax_like', 'like');
		add_action('wp_ajax_nopriv_like', 'like');
		function like() {

			$meta_values = get_post_meta($_POST['post_id'],'_like');
			$count = (int)$meta_values[0];

			$likeCount = [
				'count' => ++$count,
				'post_id' => $_POST['post_id']
			];

			update_post_meta($_POST['post_id'],'_like',$likeCount['count']);

			wp_send_json($likeCount);
		}
	}


	public function add_my_scripts() {
		global $post;
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