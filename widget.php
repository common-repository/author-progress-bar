<?php
/*******************************************************************************
============================== Widget Settings =================================
********************************************************************************/
class Author_Progress_Bar_Settings
{
	public $optionName = 'author-progress-bar';
	public $settings;

	public function __construct()
	{
		$this->load();
		$this->addHooks();
	}

	public function addHooks()
	{
		if(!is_admin()) return;

		add_action('admin_menu', array($this, 'Author_Progress_Bar_Create_Menu'), 10);
		global $wp_version;
		if ( version_compare($wp_version, '2.7', '>=' ) ) {
			add_filter( 'plugin_action_links', array($this, 'add_filter_plugin_action_links'), 10, 2 );
		}
	}

	function add_filter_plugin_action_links( $links, $file )
	{
		if ( $file == AUTHOR_PROGRESS_BAR_PLUGIN_BASENAME )
		{
			$links[] = '<a href="'.$this->GetPluginOptionsURL().'">' . __('Settings') . '</a>';
		}
		return $links;
	}

	function GetPluginOptionsURL()
	{
		if (function_exists('admin_url'))
		{	// since WP 2.6.0
			$adminurl = trailingslashit(admin_url());
		}
		else
		{
			$adminurl = trailingslashit(get_settings('siteurl')).'wp-admin/';
		}
		return $adminurl.'options-general.php'.'?page=' . AUTHOR_PROGRESS_BAR_PLUGIN_NAME;
	}

	function Author_Progress_Bar_Create_Menu() {
		add_options_page('Author Progress Bar', 'Author Progress Bar', 10, AUTHOR_PROGRESS_BAR_PLUGIN_NAME, array($this, 'Author_Progress_Bar_Options_Page'));
	}

	function Author_Progress_Bar_Options_Page()
	{
		global $pb_PluginName;

		if(isset($_POST['updateSettings'])) {
			if(is_numeric($_POST['width']) || is_numeric($_POST['height'])) {
				echo "<div id='message' class='error' style='width: 500px;'><p><b>Specify units! Settings were <u>not</u> saved.</b></p></div>";
			} else {
				$settings['width'] = $_POST['width'];
				$settings['height'] = $_POST['height'];
				$settings['bg_color'] = $_POST['bg_color'];
				$settings['border_color'] = $_POST['border_color'];
				$settings['author_progress_bar_color'] = $_POST['author_progress_bar_color'];
				$settings['precision'] = $_POST['precision'];
				$this->settings = $settings;
				$this->save();

				echo "<div id='message' class='updated' style='width: 500px;'><p><b>Settings have been saved.</b></p></div>";
			}
		}

		extract($this->settings);

		require('frontend_style.php');
		?>
			<div class="wrap"><?php
					$configFile = plugin_dir_path(__FILE__) . '_config.php';
					if(is_readable($configFile)) {
						if(is_writable($configFile) && is_writable(plugin_dir_path(__FILE__))) {
							unlink($configFile);
						} else {
							?>
							<div id='message' class='error'><p><strong>The _config.php file is no longer needed since version 0.4. Since there are no write permissions on this file or the plugin directory, delete the file manually or assign the appropriate rights!</strong></p></div><br />
							<?php
						}
					}
				?><div id="icon-options-general" class="icon32"><br /></div>
				<h2>Settings &rsaquo; Author Progress Bar</h2>
				<form method="post" action="options-general.php?page=<?php echo AUTHOR_PROGRESS_BAR_PLUGIN_NAME;?>">
					<input type="hidden" name="updateSettings" value="1" />
					<table class="form-table">
						<tr>
							<th>Height</th>
							<td>
								<input type="text" value="<?php echo($width); ?>" maxlength="6" size="3" name="width" />
								x
								<input type="text" value="<?php echo($height); ?>" maxlength="6" size="3" name="height" />
							</td>
						</tr>
						<tr>
							<th>Background color</th>
							<td><input type="text" value="<?php echo($bg_color); ?>" class="regular-text" name="bg_color" style='width: 250px;' /></td>
						</tr>
						<tr>
							<th>Color of the progress bar</th>
							<td><input type="text" value="<?php echo($author_progress_bar_color); ?>" class="regular-text" name="author_progress_bar_color" style='width: 250px;' /></td>
						</tr>
						<tr>
							<th>Color of the frame</th>
							<td><input type="text" value="<?php echo($border_color); ?>" class="regular-text" name="border_color" style='width: 250px' /></td>
						</tr>
						<tr>
							<th>Number of decimal places</th>
							<td><input type="text" value="<?php echo($precision); ?>" class="regular-text" name="precision" style='width: 250px' /></td>
						</tr>
					</table>
					<p class="submit">
						<input type="submit" class="button-primary" value="Save" name="submit" />
					</p>
				</form>

				<div id="icon-themes" class="icon32"><br /></div>
				<h2>Progress Bar Design :</h2>
				<br />
				<div style="width: 400px;">
					<div id="author_progress_bar">
						<div style="width: 50%;"></div>
					</div>
				</div>
			</div>
		<?php
	}

	public function load()
	{
		$this->settings = get_option($this->optionName);
		if(is_null($this->settings) || empty($this->settings)) {
			$this->loadDefaults();
		}
	}

	public function save()
	{
		update_option($this->optionName, $this->settings);
	}

	public function loadDefaults()
	{
		$settings['width'] = '100%';
		$settings['height'] = '12px';
		$settings['bg_color'] = '#f3f3f3';
		$settings['author_progress_bar_color'] = '#247102';
		$settings['border_color'] = '#000000';
		$settings['precision'] = 0;

		// legacy (<= 0.4): load all _config settings if the file is existing.
		$configFile = plugin_dir_path(__FILE__) . '_config.php';

		if(is_readable($configFile))
		{
			include($configFile);

			$settings['width'] = $width;
			$settings['height'] = $height;
			$settings['bg_color'] = $bg_color;
			$settings['border_color'] = $border_color;
			$settings['author_progress_bar_color'] = $author_progress_bar_color;

			if(is_writable($configFile) && is_writable(plugin_dir_path(__FILE__)))
			{
				unlink($configFile);
			}
		}

		$this->settings = $settings;
	}
}

/*******************************************************************************
====================== Progress Bar Management =================================
********************************************************************************/
	class Author_Progress_BarWidgetManager
	{
		public function __construct()
		{
			add_action('widgets_init',create_function('','register_widget("Author_Progress_BarWidget");'));
		}
	}

/*******************************************************************************
======================== Progress Bar Widget ===================================
********************************************************************************/
	class Author_Progress_BarWidget extends WP_Widget
	{
		public $id_base = 'author_progress_bar';
		public $name = 'Author Progress Bar';
		public $description = 'Author Progress Bar displays the progress of your book in a sidebar widget. Allowing for greater audience engagement & to motivate you the writer.';

		public $progressTitle = array('No words', 'One word', ' words');
		public $progressLabel = 'word';
		public $widgetTitle = 'I am writing';

		public $progressTitleAudio = array('No minute', 'One Minute', ' Minutes');
		public $progressLabelAudio = 'Minute';
		public $widgetTitleAudio = 'I am working on';


		function singularPlural($amount, $list)
		{
			if($amount > 1)
			{
				return $amount . ' ' . $list[2];
			}
			elseif($amount == 1)
			{
				return $list[1];
			}

			return $list[0];
		}

		function __construct()
		{
			parent::WP_Widget($this->id_base, $this->name, array('description' => $this->description));
		}

		function widget($args,$instance)
		{
			global $author_progress_bar_settings;
			extract($args);

			$title 		= apply_filters('widget_title', $instance['title'] );
			$author 	= apply_filters('widget_author',$instance['author']);
			$book 		= apply_filters('widget_book',$instance['book']);
			$progress 	= apply_filters('widget_progress',$instance['progress']);
			$max 		= apply_filters('widget_max',$instance['max']);
			$progress_bar_type = apply_filters('widget_progress_bar_type',$instance['progress_bar_type']);
			$cover 		= apply_filters('widget_cover',$instance['cover']);
			$info           = apply_filters('widget_info',$instance['info']);

			if(strcmp($progress_bar_type,"book")==0){
				$before_title = '<h4 class="widgettitle_book"><span class="sidebar-title">';
				$after_title = '</span></h4>';
			}

			elseif(strcmp($progress_bar_type,"ebook")==0){
				$before_title = '<h4 class="widgettitle_ebook"><span class="sidebar-title">';
				$after_title = '</span></h4>';
			}

			elseif(strcmp($progress_bar_type,"kindle")==0)
			{
				$before_title = '<h4 class="widgettitle_kindle"><span class="sidebar-title">';
				$after_title = '</span></h4>';
			}

			elseif(strcmp($progress_bar_type,"audio")==0)
			{
				$before_title = '<h4 class="widgettitle_audio_book"><span class="sidebar-title">';
				$after_title = '</span></h4>';
			}

			echo $before_widget;

			if ($title){
				echo $before_title . $title . $after_title;
			}

			extract($author_progress_bar_settings->settings);

			include('frontend_style.php');

			if($cover) { ?>
				    <p align="center"><img class="author_progress_bar_picture" src="<?php echo($cover); ?>" /></p>
			    <?php } ?>
			<p style="text-align: center;" class="widget_p">
			    <b><?php if($book){ echo $book; } if($author){ ?><br />(<?php echo $author; ?>)<?php } ?></b>
			</p>
			<?php if($max) { ?>
			<p>
			    <div id="author_progress_bar" style="margin: 0 auto;">
				    <div style="width:<?php echo $this->currentProgress($progress, $max); ?>%"></div>
			    </div>
			</p>

			<p style="text-align: center;" class="widget_p">
			<?php echo $this->currentProgress($progress, $max, $precision); ?>%
			</p>

			<p style="text-align: center;" class="widget_nb_words_or_mins">
			    <?php echo $this->currentPage($progress, $max); ?> of <?php if(strcmp($progress_bar_type,"audio")==0){ echo $this->singularPlural($max, $this->progressTitleAudio);} else {echo $this->singularPlural($max, $this->progressTitle);} ?>
			</p>


			<?php }
			if($info) {?>
			<p style="text-align: center;" class="widget_info">
			    <?php echo $info;?>
			</p>
			<?php } ?>

			<p style="text-align: center;"><a href="http://featuredfiction.com">Sponsored by Featured Fiction</a>
			</p>

			<?php echo $after_widget;
		}

		function currentPage($progress, $max, $precision=0)
		{
		    return $progress;
		}

		function currentProgress($progress, $max, $precision=0)
		{
		    if($progress > 0) {
		        return round(($progress/$max)*100, $precision);
		    }
		    return 0;
		}

		function update( $new_instance, $old_instance )
		{
			$instance = $old_instance;
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['author']= strip_tags($new_instance['author']);
			$instance['book'] = strip_tags($new_instance['book']);
			$instance['progress'] = strip_tags($new_instance['progress']);
			$instance['max'] = strip_tags($new_instance['max']);
			$instance['progress_bar_type'] = strip_tags($new_instance['progress_bar_type']);
			$instance['cover'] = strip_tags($new_instance['cover']);
			$instance['info'] = $new_instance['info'];
			return $instance;
		}

		function form( $instance )
		{
			if ( $instance )
			{
				$title 				= esc_attr($instance['title']);
				$author 			= esc_attr($instance['author']);
				$book 				= esc_attr($instance['book']);
				$progress 			= esc_attr($instance['progress']);
				$max 				= esc_attr($instance['max']);
				$progress_bar_type 		= esc_attr($instance['progress_bar_type']);
				$cover 				= esc_attr($instance['cover']);
				$info               		= esc_attr($instance['info']);
			}
			else
			{
				$title 				= __( $this->widgetTitle, 'title' );
				$author 			= __('','author');
				$book 				= __('','book');
				$progress 			= __('','progress');
				$max 				= __('','max');
				$progress_bar_type 		= __('','progress_bar_type');
				$cover 				= __('','cover');
				$info               		= __('','info');
			}

			// load the script to use the media upload dialog.
			wp_enqueue_script( 'author_progress_barMediaUpload' );
			?>
				<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
				</p>

				<p>
				    <label for="<?php echo $this->get_field_id('author'); ?>"><?php _e('Author:'); ?></label>
				    <input class="widefat" id="<?php echo $this->get_field_id('author'); ?>" name="<?php echo $this->get_field_name('author'); ?>" type="text" value="<?php echo $author; ?>" />
				</p>
				<p>
				    <label for="<?php echo $this->get_field_id('book'); ?>"><?php _e('Book:'); ?></label>
				    <input class="widefat" id="<?php echo $this->get_field_id('book'); ?>" name="<?php echo $this->get_field_name('book'); ?>" type="text" value="<?php echo $book; ?>" />
				</p>
				<p>
				    <label for="<?php echo $this->get_field_id('progress'); ?>"><?php if(strcmp($progress_bar_type,"audio")==0){echo $this->progressLabelAudio;}else{echo $this->progressLabel;} ?>:</label>
				    <input class="widefat" id="<?php echo $this->get_field_id('progress'); ?>" name="<?php echo $this->get_field_name('progress'); ?>" type="text" value="<?php echo $progress; ?>" /> of
				    <input class="widefat" id="<?php echo $this->get_field_id('max'); ?>" name="<?php echo $this->get_field_name('max'); ?>" type="text" value="<?php echo $max; ?>" />
				</p>
				<p>
				    <td>Type of progress bar:</td>
				    <td>
				       <select name="<?php echo $this->get_field_name('progress_bar_type'); ?>">
						<option value="audio" <?php selected( $progress_bar_type, 'audio', true);?>>audio</option>
					    <option value="book" <?php selected( $progress_bar_type, 'book', true);?>>book</option>
					    <option value="ebook" <?php selected( $progress_bar_type, 'ebook', true);?>>ebook</option>
					    <option value="kindle" <?php selected( $progress_bar_type, 'kindle', true);?>>kindle</option>
				       </select>
				    </td>
				</p>

				<p>
				    <label for="<?php echo $this->get_field_id('cover'); ?>"><?php _e('Link to the cover:'); ?>
					    <?php $this->addMediaButton($this->get_field_id('cover')); ?>
				    </label>
				    <textarea name="<?php echo $this->get_field_name('cover'); ?>" id="<?php echo $this->get_field_id('cover');?>" class="widefat" rows="3" cols="15"><?php echo $cover; ?></textarea>
				</p>
				<p>
				    <label for="<?php echo $this->get_field_id('info'); ?>"><?php _e('For more information:'); ?></label>
				    <textarea name="<?php echo $this->get_field_name('info'); ?>" id="<?php echo $this->get_field_id('info'); ?>" class="widefat" rows="3" cols="15"><?php echo $info; ?></textarea>
				</p>
			<?php
		}

		function addMediaButton($dataEditor)
		{

			// we are using the new media library introduced with WP 3.5
			// which needs at least one tinyMCE editor on the page.
			if(function_exists( 'wp_enqueue_media' ))
			{
				echo '<a onClick="media_upload(\'' . $dataEditor . '\')" class="button-secondary" title="Add Media" id="author_progress_bar_add_media">Add Media</a>';
				echo '<!-- add a dummy editor to have at least one tinyMCE editor on the page.';
				wp_editor('','content');
				echo '-->';
			}
			else
			{
				echo '<a onClick="media_upload(\'' . $dataEditor . '\');" class="add_media" id="author_progress_barWidget">Upload/Insert <img src="images/media-button.png?ver=20111005" width="15" height="15" /></a>';
			}
		}
	}

	// Initialize the widget manager.
	new Author_Progress_BarWidgetManager();
?>
