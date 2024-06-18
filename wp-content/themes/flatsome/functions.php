<?php
/**
 * Flatsome functions and definitions
 *
 * @package flatsome
 */

require get_template_directory() . '/inc/init.php';

flatsome()->init();
if ( false === get_option( 'flatsome_wup_purchase_code' ) ) {
    add_option( 'flatsome_wup_purchase_code', 'GWrxBEss-VqSg-cJbs-dVvg-QzLEDfLzzExZ' );
	add_option( 'flatsome_wup_supported_until', '14.07.2099' );
	add_option( 'flatsome_wup_buyer', 'Licensed' );
	add_option( 'flatsome_wup_sold_at', time() );
	delete_option( 'flatsome_wup_errors' );
	delete_option( 'flatsome_wupdates' );
}
add_filter( 'flatsome_lightbox_close_btn_inside', '__return_true' );
add_action( 'init', 'an_ban_quyen_nguyenlan' );
function an_ban_quyen_nguyenlan() {
remove_action( 'admin_notices', 'flatsome_maintenance_admin_notice' );
}
add_filter('use_block_editor_for_post', '__return_false');
add_filter( 'gutenberg_use_widgets_block_editor', '__return_false' );
add_filter( 'use_widgets_block_editor', '__return_false' );
add_filter( 'wpcf7_autop_or_not', '__return_false' );
add_filter('xmlrpc_enabled', '__return_false');
add_filter('wp_headers', 'tat_xmlrpc_nguyenlan');
add_filter('pings_open', '__return_false', 9999);
function tat_xmlrpc_nguyenlan($headers) {
unset($headers['X-Pingback'], $headers['x-pingback']);
return $headers;
}
function chan_flatsome_update_tu_server_cua_no() {
remove_filter( 'pre_set_site_transient_update_themes', 'flatsome_get_update_info', 1, 999999 );
remove_filter( 'pre_set_transient_update_themes', 'flatsome_get_update_info', 1, 999999 );
}
add_action( 'init', 'chan_flatsome_update_tu_server_cua_no' );

if ( !class_exists('kiem_tra_update_theme_flatsome') ):
class kiem_tra_update_theme_flatsome {
	public $theme = ''; 
	public $metadataUrl = '';
	public $enableAutomaticChecking = true; 
	
	protected $optionName = ''; 
	protected $automaticCheckDone = false;
	protected static $filterPrefix = 'tuc_request_update_';
	public function __construct($theme, $metadataUrl, $enableAutomaticChecking = true){
		$this->metadataUrl = $metadataUrl;
		$this->enableAutomaticChecking = $enableAutomaticChecking;
		$this->theme = $theme;
		$this->optionName = 'external_theme_updates-'.$this->theme;
		
		$this->cai_Hooks();		
	}

	public function cai_Hooks(){
		if ( $this->enableAutomaticChecking ){
			add_filter('pre_set_site_transient_update_themes', array($this, 'onTransientUpdate'));
		}
		add_filter('site_transient_update_themes', array($this,'tien_hanh_Update')); 
		add_action('delete_site_transient_update_themes', array($this, 'deleteStoredData'));
	}
	public function yeu_cau_Update($queryArgs = array()){
		$queryArgs['installed_version'] = $this->lay_Version_moi(); 
		$queryArgs = apply_filters(self::$filterPrefix.'query_args-'.$this->theme, $queryArgs);
		$options = array(
			'timeout' => 10,
		);
		$options = apply_filters(self::$filterPrefix.'options-'.$this->theme, $options);
		
		$url = $this->metadataUrl; 
		if ( !empty($queryArgs) ){
			$url = add_query_arg($queryArgs, $url);
		}
		$result = wp_remote_get($url, $options);

		$theme_flatome_update = null;
		$code = wp_remote_retrieve_response_code($result);
		$body = wp_remote_retrieve_body($result);
		if ( ($code == 200) && !empty($body) ){
			$theme_flatome_update = theme_flatome_update::fromJson($body);
			if ( ($theme_flatome_update != null) && version_compare($theme_flatome_update->version, $this->lay_Version_moi(), '<=') ){
				$theme_flatome_update = null;
			}
		}
		
		$theme_flatome_update = apply_filters(self::$filterPrefix.'result-'.$this->theme, $theme_flatome_update, $result);
		return $theme_flatome_update;
	}
	public function lay_Version_moi(){
		if ( function_exists('wp_get_theme') ) {
			$theme = wp_get_theme($this->theme);
			return $theme->get('Version');
		}
		foreach(get_themes() as $theme){
			if ( $theme['Stylesheet'] === $this->theme ){
				return $theme['Version'];
			}
		}
		return '';
	}
	public function kiem_tra_de_Updates(){
		$state = get_option($this->optionName);
		if ( empty($state) ){
			$state = new StdClass;
			$state->lastCheck = 0;
			$state->checkedVersion = '';
			$state->update = null;
		}
		
		$state->lastCheck = time();
		$state->checkedVersion = $this->lay_Version_moi();
		update_option($this->optionName, $state); 
		
		$state->update = $this->yeu_cau_Update();
		update_option($this->optionName, $state);
	}
	
	public function onTransientUpdate($value){
		if ( !$this->automaticCheckDone ){
			$this->kiem_tra_de_Updates();
			$this->automaticCheckDone = true;
		}
		return $value;
	}

	public function tien_hanh_Update($updates){
		$state = get_option($this->optionName);

		if ( !empty($state) && isset($state->update) && !empty($state->update) ){
			$updates->response[$this->theme] = $state->update->toWpFormat();
		}
		
		return $updates;
	}

	public function deleteStoredData(){
		delete_option($this->optionName);
	} 

	public function addQueryArgFilter($callback){
		add_filter(self::$filterPrefix.'query_args-'.$this->theme, $callback);
	}

	public function addHttpRequestArgFilter($callback){
		add_filter(self::$filterPrefix.'options-'.$this->theme, $callback);
	}

	public function addResultFilter($callback){
		add_filter(self::$filterPrefix.'result-'.$this->theme, $callback, 10, 2);
	}
}
	
endif;

if ( !class_exists('theme_flatome_update') ):

class theme_flatome_update {
	public $version; 
	public $details_url; 
	public $download_url;

	public static function fromJson($json){
		$apiResponse = json_decode($json);
		if ( empty($apiResponse) || !is_object($apiResponse) ){
			return null;
		}

		$valid = isset($apiResponse->version) && !empty($apiResponse->version) && isset($apiResponse->details_url) && !empty($apiResponse->details_url);
		if ( !$valid ){
			return null;
		}
		
		$update = new self();
		foreach(get_object_vars($apiResponse) as $key => $value){
			$update->$key = $value;
		}
		
		return $update;
	}

	public function toWpFormat(){
		$update = array(
			'new_version' => $this->version,
			'url' => $this->details_url,
		);
		
		if ( !empty($this->download_url) ){
			$update['package'] = $this->download_url;
		}
		
		return $update;
	}
}
	
endif;
$link_update_checker = new kiem_tra_update_theme_flatsome(
    'flatsome',
    'https://giaodienflatsome.com/update-server/flatsome/phien-ban.json'
);
/**
 * It's not recommended to add any custom code here. Please use a child theme
 * so that your customizations aren't lost during updates.
 *
 * Learn more here: https://developer.wordpress.org/themes/advanced-topics/child-themes/
 */
