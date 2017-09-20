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
require_once WPMEM_PATH."core/SessionService.php";
require_once dirname(__FILE__).DIRECTORY_SEPARATOR."util/class.test.php";
require_once dirname(__FILE__).DIRECTORY_SEPARATOR."shortCode/code.php";
require_once dirname(__FILE__).DIRECTORY_SEPARATOR."core/repository.php";
require_once dirname(__FILE__).DIRECTORY_SEPARATOR."core/util.php";



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

		$found_posts = get_posts( 'numberposts=1' );

	
		$this->setOption();


		$this->createPstType();

		$this -> init_hooks();
	}




	public function setOption() {
		$this->option = [
			'timelinePostostType' => 'post',
			'number' => 5,
			'customTag' => 1
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
			add_options_page(
		          "taitai",//ダッシュボードのメニューに表示するテキスト
		          "taitai",//ページのタイトル
		          'edit_themes',
		          'ht',//ページスラッグ
		          [ $this, 'create_admin_page' ]
		      );
	}



	public function pageContlloler() {

		$ID = $this->util->getUser()->ID;
		$name = $this->util->getUser()->user_nicename;
		$nonce = wp_create_nonce($name);
		$postName = $this->repository->getPostTypeName();
		$getOptions = $this->getOption();

		if( $this->util->is_post() && wp_verify_nonce($_POST['timeline_nonce'],$name) ) {

			$getOptions['timelinePostostType'] = isset($_POST['timelinePostostType']) ? $_POST['timelinePostostType'] : $getOptions['timelinePostostType'];
			$getOptions['number'] = isset($_POST['number']) ? (int)$_POST['number'] : (int)$getOptions['number'];
			$getOptions['customTag'] = isset($_POST['customTag']) ? $_POST['customTag'] : $getOptions['customTag'];
	
			$this->option = $getOptions;

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
		    'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
		  );
		 
		  register_post_type( 'book', $args );
		}
		add_action( 'init', 'codex_custom_init' );

	}


public function create_admin_page(){
      // Set class property
    //wp_nonce_field( 'name_of_my_action', 'name_of_nonce_field' );
	$article = get_posts();
	$user = wp_get_current_user();
		
	$nonece = wp_create_nonce('test');

    if( $this->util->is_post() && isset($_POST['t']) ) 
    {
    	if( wp_verify_nonce($_POST['test'],'test') ) 
    	{
	    	update_option( 'my_text', $_POST['t'] );
	    	update_option( 'my_text2', ["my" => 8,"my2" => 55]); 
    	}
    }




      $op = get_option('my_text2');
      $op2 = get_option('my_text');

    //wp_list_authors();

		$ud = get_users();

$key = wp_generate_password( 20, false );

     
		wp_enqueue_media();

$diff = wp_text_diff('tevt','tevt');
	
	$d = get_default_post_to_edit( 'post', true );

	


	if($this->util->is_post()) {
var_dump(wp_verify_nonce($_POST['nono'],'uit'));
	}
	wp_editor('iji',87978);

if ( !empty($_POST) && check_admin_referer( 'name_of_my_action', 'name_of_nonce_field' ) ) {
   //データ更新の処理（update_optionなど）
}


      $template = $this->tmpl->loadTemplate('tei.html');
		echo $template->render(array(
		    'op' => $op,
		    'p'=> $this->util->getURL(),
		    'fla'=>is_admin(),
		    'nonce' => $nonece,
		    'posts' => $article,
		    'op2' => $op2,
		    'nono' => $this->util->getNonce('uit')
		));
  }


	public function activation() {

		global $wpdb;





		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . "sample_table_zuke"; 

		$sql = "CREATE TABLE $table_name (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  name tinytext NOT NULL,
		  text text NOT NULL,
		  url varchar(55) DEFAULT '' NOT NULL,
		  UNIQUE KEY id (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

	}
	private function init_hooks() {
		require_once dirname(__FILE__).DIRECTORY_SEPARATOR."class.init.php";
		require_once dirname(__FILE__).DIRECTORY_SEPARATOR."/tmpl/list-table-zuke.php";




		add_action('wp_print_scripts', 'add_my_scripts');

		function add_my_scripts() {
			global $post;
			if (!has_shortcode( $post->post_content, 'basic')) 
				return;
			wp_enqueue_script('Riot', plugins_url('timeline').'/public/riot/min/main.bundle.js', '', '3.0', true);
		}



	}



	public function func($callback)
	{
		$args = func_get_args();
	    echo "callback function result :" . call_user_func($callback) . PHP_EOL;
	}

	public function oxy_hello_world() {
			 //add_menu_page('zuke', 'zuke', 'activate_plugins', 'test', 'dr');
	         //add_menu_page( '基本設定', '基本設定', 8, 'test', [$this,'create_custom_menu_page2']);
	         //add_menu_page('My Custom Page', 'My Custom Page', 'manage_options', 'edit.php?post_type=topic2');
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