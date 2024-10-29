<?php
	class Author_Progress_Bar_Backend
	{
		public function readWidgetConfiguration()
		{
			$progress_bar_type = array('author_progress_bar', 'author_progress_bar-audio', 'author_progress_bar-ebook', 'author_progress_bar-kindle');
			$activeWidgets = NULL;
			
			foreach($progress_bar_type as $pbar_type)
			{
				$value = get_option('widget_' . $pbar_type);
												
				if(is_null($value) || empty($value)) continue;
	
				foreach($value as $number => $widget_key)
				{
					if($number == '_multiwidget') continue;
					
					if(isset($_POST['updateProgressBar'])) {
						$widget_key['progress'] = $_POST[$pbar_type.'-'.$number.'_progress'];
						$value[$number]['progress'] = $widget_key['progress'];
					}
					
					if($widget_key['book']) {
						$activeWidgets[$pbar_type . '-' . $number] = $widget_key;
					}
				}
				
				if(isset($_POST['updateProgressBar'])) 
				{
					update_option('widget_' . $pbar_type, $value);
				}
			}
			
			return $activeWidgets;
		}
		
		public function dashboard_widget_function() 
		{
			$activeWidgets = $this->readWidgetConfiguration();
					
			?>
			<div class="table table_content">
			<p class="sub">Current progress</p>
			<form action="" method="POST">
			<input type="hidden" name="updateProgressBar" value="1" />
			<table>
			
			<?php
			if(is_null($activeWidgets) || empty($activeWidgets)) {
				echo '<tr><td>No books recorded. Please add new books in your Progress Bar widget.</td></tr>';
			} else {
				foreach($activeWidgets as $id => $widget)
				{
					?><tr>
					<td align="right"><?=$widget['book'];?>:</td>
					<td><input type="text" value="<?=$widget['progress']; ?>" name="<?=$id; ?>_progress" size="2" /> of <?=$widget['max'];?></td>
					</tr>
					<?php
				}
			
				?>
				<tr><td></td>
				<td><input type="submit" value="Update" class="button" /></td>
				
				<?php
				}
			?>
			</tr>
			</table>
			</form>
			</div>
			
			<p class="manage">
				<a href="<?=get_admin_url();?>widgets.php">Manage your books</a>
			</p><?php
		}
		
		public function add_dashboard_widgets() 
		{
			wp_add_dashboard_widget('dashboard_author_progress_bar', 'Author Progress Bar', array($this, 'dashboard_widget_function'));	
		}
		
		public function add_my_stylesheet()
		{
			// backend_style.css relative to the current file
	        wp_register_style( 'dashboard_author_progress_bar_style', plugins_url('backend_style.css', __FILE__) );
	        wp_enqueue_style( 'dashboard_author_progress_bar_style' );
		}
		
		public function __construct() 
		{
		     //Register with hook 'wp_enqueue_scripts', which can be used for front end CSS and JavaScript
			add_action('admin_enqueue_scripts', array($this, 'add_my_stylesheet' ) );
			add_action('wp_dashboard_setup', array($this, 'add_dashboard_widgets') );
		}

	}
	// Initialize the plugin dashboard (backend)
	new Author_Progress_Bar_Backend();
?>