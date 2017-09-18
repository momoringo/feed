<?php


namespace Core\ShotCode;


class ShotCode
{
	
	protected $View;
	protected $tmplName;
	protected $setting;

	public function __construct() {
		$this->Setting();
	}
	public function Setting() {
		$this->setting = [
			'basicFeed' => 'code.html'
		];
	}
	public function setViews($twig) {
		$this->View = $twig;
	}

	public function init() {
		add_shortcode('basicFeed', [$this,'addFeedShortCode']);
	}

	public function addFeedShortCode() {
		$options = get_option('timelineSetting');
		$template = $this->getViewTemplate($this->setting['basicFeed']);//loadTemplate($this->setting['basicFeed']);
		if(is_page()) {
			echo $template->render(array(
			    'option' => $options,
			    'resturl' => rest_url()
			));
		}
	}

	public function CreateShortCode() {
		//add_shortcode('basicFeed', [$this,'addFeedShortCode']);
	}
	public function getViewTemplate($template) {
		return $this->View->loadTemplate($template);
	}
}
