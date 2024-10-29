<?php
/*
	Plugin Name: Author Progress Bar
	Plugin url: http://wordpress.org/extend/plugins/author-progress-bar/
	Description: Allows authors to show progress of their current writing projects within a widget in their sidebar.
	Visitors to their blog based author platform can see progress of writing which builds anticipation prior to publication.
	Keeping fans informed of progress also helps to build trust and can help to motivate the author to meet daily word targets.
	Version: 1.0.0
	Copyright: John Taylor, Featured Fiction - October 2013
	Last Updated: 26-10-2013
*/

define( 'AUTHOR_PROGRESS_BAR_VERSION', '1.0.0' );
if ( ! defined( 'AUTHOR_PROGRESS_BAR_PLUGIN_URL' ) )	define('AUTHOR_PROGRESS_BAR_PLUGIN_URL', plugin_dir_url(__FILE__));

if ( ! defined( 'AUTHOR_PROGRESS_BAR_PLUGIN_BASENAME' ) )  define('AUTHOR_PROGRESS_BAR_PLUGIN_BASENAME', plugin_basename(__FILE__));

if ( ! defined( 'AUTHOR_PROGRESS_BAR_PLUGIN_NAME' ) )	define('AUTHOR_PROGRESS_BAR_PLUGIN_NAME', basename(__FILE__));

if ( ! defined( 'AUTHOR_PROGRESS_BAR_MEDIA_UPLOAD' ) )	define('AUTHOR_PROGRESS_BAR_MEDIA_UPLOAD', 'author_progress_barMediaUpload');

include_once('widget.php');
$author_progress_bar_settings = new Author_Progress_Bar_Settings();


if(is_admin())
{
	require('backend.php');

	function author_progress_bar_get_wp_version()
	{
		global $wp_version;
		return $wp_version;
	}

	function author_progress_bar_admin_scripts()
	{
		//double check for Wordpress version and function exists
		if(function_exists('wp_enqueue_media')&&version_compare(author_progress_bar_get_wp_version(),'3.5','>='))
		{
			//call for new media manager
			wp_enqueue_script('editor');
			wp_register_script(AUTHOR_PROGRESS_BAR_MEDIA_UPLOAD, plugins_url('media-upload-3_5.js', __FILE__));
		}
		else{ //old Wordpress < 3.5
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_enqueue_style('thickbox');
		wp_enqueue_script('jquery');
		wp_register_script(AUTHOR_PROGRESS_BAR_MEDIA_UPLOAD, plugins_url('media-upload.js', __FILE__));
		}

	}

	//register the backend stylesheet.
	function author_progress_bar_admin_enqueue_styles()
	{
		wp_register_style('dashboard_author_progress_bar_style', plugins_url('backend_style.css', __FILE__) );
		wp_enqueue_style('dashboard_author_progress_bar_style');
	}

	 //Adds actions to wordpress.
	add_action('admin_enqueue_scripts', 'author_progress_bar_admin_scripts');
	add_action('admin_enqueue_styles', 'author_progress_bar_admin_enqueue_styles');
}
?>
