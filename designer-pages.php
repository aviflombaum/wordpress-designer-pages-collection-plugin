<?php
/*
Plugin Name: Designer Pages Widget
Plugin URI: http://www.designerpages.com
Description: Adds a configurable widget which can be used to display a designer pages listing on the blog.
Author: Nick Ohrn of Plugin-Developer.com
Version: 1.0.0
Author URI: http://plugin-developer.com
*/

if( !class_exists( 'Designer_Pages' ) ) {

	class Designer_Pages {
		
		// Associative array holding the settings for the plugin
		var $settings = null;
		// Associative array containing default settings for the plugin
		var $defaultSettings = array( 'header-font-color' => 'ffffff', 'header-background-color' => '333333', 'font-family' => 'Helvetica,Arial', 'background-color' => 'ffffff', 'font-color' => '000000', 'border-color' => '000000', 'products-limit' => '10', 'width' => '190', 'collection-url' => 'http://www.designerpages.com/collections/yanko-design/' );
		// Array containing the strings for keys which can be set
		var $keys = array( 'header-font-color', 'header-background-color', 'font-family', 'background-color', 'font-color', 'border-color', 'products-limit', 'width' );
		// Contains version string
		var $version = '1.0.0';
		// Array of available fonts
		var $availableFonts = array( 'Helvetica,Arial', 'Times New Roman,Times', 'Courier New,Courier', 'Palatino', 'Garamond', 'Bookman', 'Avant Garde', 'Verdana', 'Georgia', 'Comic Sans MS', 'Trebuchet MS', 'Arial Black', 'Impact' );

		/**
		 * PHP4 style constructor for compatibility.  All action and filter hooks are contained within.
		 *
		 * @return Designer_Pages
		 */
		function Designer_Pages( ) {
			register_deactivation_hook( __FILE__, array( &$this, 'deactivate' ) );
			add_action( 'init', array( &$this, 'enqueueNecessaryFiles' ) );
			add_action( 'init', array( &$this, 'registerWidget' ) );
			add_action( 'init', array( &$this, 'processSettings' ) );
			add_action( 'wp_ajax_designer_pages_live_preview', array( &$this, 'livePreview' ) );
			add_action( 'admin_menu', array( &$this, 'addAdministrativePage' ) );
		}

		// Generic Callbacks
		

		/**
		 * Adds the appropriate settings page to the administrative interface.
		 *
		 */
		function addAdministrativePage( ) {
			add_options_page( __( 'Designer Pages Widget' ), __( 'Designer Pages Widget' ), 'switch_themes', 'designer-pages', array( &$this, 'settingsPageOutput' ) );
		}
		
		/**
		 * Deactivation hook removes settings.
		 *
		 */
		function deactivate() {
			$this->deleteSettings();
		}

		/**
		 * Enqueues the custom JavaScript and 
		 *
		 */
		function enqueueNecessaryFiles( ) {
			if( defined( 'WP_ADMIN' ) && WP_ADMIN ) {
				$pluginFolderName = basename( dirname( __FILE__ ) );
				wp_enqueue_style( 'jquery-colorpicker', WP_PLUGIN_URL . '/' . $pluginFolderName . '/resources/colorpicker.css' );
				wp_enqueue_script( 'jquery-colorpicker', WP_PLUGIN_URL . '/' . $pluginFolderName . '/resources/colorpicker.js', array( 'jquery' ) );
				wp_enqueue_script( 'designer-pages', WP_PLUGIN_URL . '/' . $pluginFolderName . '/resources/designer-pages.js', array( 'jquery-colorpicker' ) );
			}
		}

		/**
		 * Prints a live preview of the current configuration on the admin panel.
		 *
		 */
		function livePreview( ) {
			$this->settings = $this->setSettingsArrayFromPost();
			echo $this->getOutputFromRemoteServices();
			exit();
		}

		/**
		 * Processes and saves the settings that the user selects.
		 *
		 */
		function processSettings( ) {
			if( defined( 'WP_ADMIN' ) && WP_ADMIN && isset( $_POST[ 'save-designer-pages-widget-settings' ] ) && check_admin_referer( 'save-designer-pages-widget-settings' ) ) {
				$settings = $this->setSettingsArrayFromPost();
				$this->saveSettings( $settings );
				wp_redirect( 'options-general.php?page=designer-pages&updated=1' );
			}
		}
		
		/**
		 * Set the settings array from a POST request.  Does validation.  
		 *
		 * @return array An array of new settings.
		 */
		function setSettingsArrayFromPost() {
			$settings = $this->getSettings( );
			if( preg_match( '/[0-9A-Fa-f]{6}/', $_POST[ 'header-font-color' ] ) ) {
				$settings[ 'header-font-color' ] = $_POST[ 'header-font-color' ];
			}
			if( preg_match( '/[0-9A-Fa-f]{6}/', $_POST[ 'header-background-color' ] ) ) {
				$settings[ 'header-background-color' ] = $_POST[ 'header-background-color' ];
			}
			if( preg_match( '/[0-9A-Fa-f]{6}/', $_POST[ 'font-color' ] ) ) {
				$settings[ 'font-color' ] = $_POST[ 'font-color' ];
			}
			if( preg_match( '/[0-9A-Fa-f]{6}/', $_POST[ 'background-color' ] ) ) {
				$settings[ 'background-color' ] = $_POST[ 'background-color' ];
			}
			if( preg_match( '/[0-9A-Fa-f]{6}/', $_POST[ 'border-color' ] ) ) {
				$settings[ 'border-color' ] = $_POST[ 'border-color' ];
			}
			if( is_numeric( $_POST[ 'width' ] ) ) {
				$settings[ 'width' ] = intval( $_POST[ 'width' ] );
			}
			if( is_numeric( $_POST[ 'products-limit' ] ) && $_POST[ 'products-limit' ] <= 10 && $_POST[ 'products-limit' ] >= 2 ) {
				$settings[ 'products-limit' ] = intval( $_POST[ 'products-limit' ] );
			}
			if( in_array( $_POST[ 'font-family' ], $this->availableFonts ) ) {
				$settings[ 'font-family' ] = $_POST[ 'font-family' ];
			}
			if( strpos( $_POST[ 'collection-url' ], 'http://www.designerpages.com/collections/' ) === 0) {
				$settings[ 'collection-url' ] = trailingslashit( $_POST[ 'collection-url' ] );
			}
			return $settings;
		}

		// Widget Stuff
		

		/**
		 * Registers the widget and the widget's control.
		 *
		 */
		function registerWidget( ) {
			register_sidebar_widget( __( 'Designer Pages' ), array( &$this, 'widgetOutput' ), 'designer-pages' );
			register_widget_control( 'designer-pages', array( &$this, 'widgetControlOutput' ) );
		}

		/**
		 * Includes the proper HTML for the widget control.
		 *
		 */
		function widgetControlOutput( ) {
			include ( 'views/widget-control-output.php' );
		}

		/**
		 * Produces the output for the widget.  The output is drawn from the API that was developed by 
		 * Avi Flombaum of Really Simple.
		 *
		 * @param array $args The arguments for the widget display, as determined by the sidebar.
		 */
		function widgetOutput( $args ) {
			if( $this->hasProperDependencies( ) ) {
				echo $this->getOutputFromRemoteServices( );
			}
		}

		// Settings Functions
		

		/**
		 * Removes the settings from the options database table.
		 *
		 */
		function deleteSettings( ) {
			delete_option( 'Designer Pages Widget Settings' );
		}

		/**
		 * Retrieves the settings for the Designer Pages widget.
		 *
		 * @return array An associative array with the settings for the Designer Pages widget.
		 */
		function getSettings( ) {
			if( null == $this->settings ) {
				$this->settings = get_option( 'Designer Pages Widget Settings', $this->defaultSettings );
				if( false == $this->settings ) {
					$this->settings = $this->defaultSettings;
				}
			}
			return $this->settings;
		}

		/**
		 * Sanitizes the settings array and then saves it to the database.
		 *
		 * @param array $settings The new settings for the plugin.
		 */
		function saveSettings( $settings ) {
			if( !is_array( $settings ) ) {
				return;
			}
			
			update_option( 'Designer Pages Widget Settings', $settings );
		}

		// Utility Functions
		

		/**
		 * Returns the DesignerPages.com URL that should be used to fetch the widget HTML.
		 *
		 */
		function getDesignerPagesUrl( ) {
			$settings = $this->getSettings( );
			$baseUrl = $settings[ 'collection-url' ] . 'widget?';
			foreach( $this->keys as $key ) {
				$settings[$key] = urlencode( $settings[$key] );
				$baseUrl .= "{$key}={$settings[$key]}&";
			}
			return $baseUrl;
		}

		/**
		 * Returns the output from the Designer Pages widget page.
		 *
		 */
		function getOutputFromRemoteServices( ) {
			$url = $this->getDesignerPagesUrl( );
			if( function_exists( 'curl_init' ) ) {
				$session = curl_init( $url );
				
				curl_setopt( $session, CURLOPT_HEADER, false );
				curl_setopt( $session, CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $session, CURLOPT_FOLLOWLOCATION, false );
				
				$response = curl_exec( $session );
				
				// Closes the initial session
				curl_close( $session );
			
			} else if( ini_get( 'allow_url_fopen' ) ) {
				$response = file_get_contents( $url );
			}
			
			$response = preg_replace( '@<style[^>]*?>.*?</style>@siu', '', $response );
			return $response;
		}

		/**
		 * Returns a boolean indicating whether or not the server has the correct dependencies for
		 * operation of the widget.
		 *
		 * @return bool True if the server supports remote fetching of files, false otherwise.
		 */
		function hasProperDependencies( ) {
			return function_exists( 'curl_init' ) || ini_get( 'allow_url_fopen' );
		}

		// Display Helpers
		

		/**
		 * Includes the appropriate HTML for the settings page.
		 *
		 */
		function settingsPageOutput( ) {
			include ( 'views/settings.php' );
		}
	}
}

if( class_exists( 'Designer_Pages' ) ) {
	$designerPages = new Designer_Pages( );
	
	/**
	 * Outputs the designer pages widget based on your saved settings.
	 *
	 */
	function designer_pages_widget() {
		global $designerPages;
		$designerPages->widgetOutput( array() );
	}
}