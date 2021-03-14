<?php

Class optinspin_Setting {
	
	function __construct() {
		add_action( 'activated_plugin', array($this,'optinspin_activation_redirect'), 50, 1 );
		add_action( 'admin_enqueue_scripts', array($this,'optinspin_admin_intro_page_style') );
		add_action('init', array($this,'optinpsin_save_optin_setting') );
		add_action( 'admin_menu', array($this,'optinspin_intro_menu'),99 );
	}

	function optinspin_activation_redirect( $plugin ) {
		if( $plugin == plugin_basename( __FILE__ ) && optinspin_get_active_version() != 'multiwheel' && optinspin_is_global_exist() == true ) {
			exit( wp_redirect( admin_url() . 'admin.php?page=optinspin-switch-v2' ) );
		}
	}


	function optinspin_admin_intro_page_style() {
		wp_enqueue_style( 'optinspin-admin-style-main', optin_PLUGIN_URL . 'assets/css/admin-style.css' );
		wp_enqueue_style( 'optinspin-admin-circle', optin_PLUGIN_URL . 'assets/css/circle.css' );
		wp_enqueue_script( 'optinspin-admin-script', optin_PLUGIN_URL . 'assets/js/admin-main-script.js' );
	}




	function optinpsin_save_optin_setting() {
		if( isset($_POST['optinspin_save_version']) ) {
			if( isset( $_POST['switch_two_zero'] )  ) {
				update_option('optinspin_switch_two_zero','yes');
				wp_redirect(admin_url() . 'edit.php?post_type=optin-wheels');
			} else {
				update_option('optinspin_switch_two_zero','');
			}
		}
	}

	function optinspin_intro_menu(){
		/*add_menu_page(
			__( 'OptinSpin Intro', 'textdomain' ),
			'OptinSpin Intro',
			'manage_options',
			'optinspin-intro',
			'optinspin_intro_html',
			'',
			99
		);*/
		add_submenu_page( 'optinspin-settings', 'Switch to 2.0', 'Switch to 2.0',
			'manage_options', 'optinspin-switch-v2','optinspsin_switch_v2_callback');
	}


	function optinspsin_switch_v2_callback() {

		$checked = '';
		if( !empty( get_option('optinspin_switch_two_zero') ) )
			$checked = 'checked';

		$html = '<div class="optinspin-into-wrapper">
					<div class="optinspin-banner"><img src="'. optin_PLUGIN_URL .'assets/img/optinspin-image-2.0.png' .'" /></div>                
					<div class="optinspin-features"><img src="'. optin_PLUGIN_URL .'assets/img/optinspin-features.jpg' .'" /></div>
					<div class="optinspin-fields">
						<form action="#" method="post">                    
							<div class="optinspin-confirm-btns">
								<div class="warning"><span class="dashicons dashicons-warning"></span><p>After switching to 2.0 you would have to create a new wheel, You will not be able to use your previous wheel anymore, as whole OptinSpin have been modified and optimized with new features and structure.</p></div>
								<input type="button"  id="optinspin_not_understand" value="I am OK with the old version" />
								<input type="button" id="optinspin_understand" value="I understand - Lets switch" />
							</div>
							<div class="optinspin-get-ready">
								<p>Getting Ready for <strong>2.0</strong></p>
							</div>
							<div class="optinspin-understand-btn">
								<input type="hidden" name="switch_two_zero" value="yes" />
								<input type="submit" value="Switch to 2.0" id="optinspin-btn-submit" name="optinspin_save_version">
							</div>
						</form>
					</div>
				</div>';
		echo $html;
	}

	// Check if user already uses Global Wheel
	function optinspin_is_global_exist() {
		
		if( function_exists( 'optinspin_crb_get_i18n_theme_option' ) ) {
			$global_sections = optinspin_crb_get_i18n_theme_option('crb_section');
			if( !empty( $global_sections ) ) {
				return true;
			}
		}
		return false;
	}

	// Getting Optinspin version
	function optinspin_get_active_version() {
		if( !empty( get_option('optinspin_switch_two_zero') ) ) {
			return 'multiwheel';
		}

		return 'singlewheel';
	}
}