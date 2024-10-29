<style type="text/css">
	#author_progress_bar {
		width: <?php echo($width); ?>;
		height: <?php echo($height); ?>;
		background-color: <?php echo($bg_color); ?>;	
		border: 1px solid <?php echo($border_color); ?>;
		margin: 0;
		padding: 0;
	}
	#author_progress_bar div {
		height: 100%;
		background-color: <?php echo($author_progress_bar_color); ?>;	
		<?php if($progress > 0) { ?>
		border-right: 1px solid <?php echo($border_color); ?>;
		<?php } ?>
		margin: 0;
		padding: 0;
	}

	.author_progress_bar_picture 
	{
		width: 150px;
		margin : 0;
	
	}
	
	
	.widgettitle_book {
		background: url("wp-content/plugins/author-progress-bar/images/booklines.png") repeat scroll 0 0 transparent;
		text-align: center;
		height: 40px;
		margin: 0 0 30px;

	}

	.widgettitle_ebook {
		background: url("wp-content/plugins/author-progress-bar/images/ebooklines.png") repeat scroll 0 0 transparent;
		text-align: center;
		height: 40px;
		margin: 0 0 30px;

	}

	.widgettitle_kindle {
		background: url("wp-content/plugins/author-progress-bar/images/kindlelines.png") no-repeat scroll 0 0 transparent;
		text-align: center;
		height: 40px;
		margin: 0 0 30px;

	}

	.widgettitle_audio_book {
		background: url("wp-content/plugins/author-progress-bar/images/audiobooklines.png") repeat scroll 0 0 transparent;
		text-align: center;
		height: 40px;
		margin: 0 0 30px;

	}

	.sidebar-title {
		padding:5px;
		color:#000000;
		font-size: 16px;
	}
	
	.widget_p {
		margin:0 0 1px;
		text-align: center;
	}
	
	.widget_nb_words_or_mins{
		margin:0 0 9px;
	}
	
	.widget_info {
		font-style:italic;
		font-size: 12px;
	}
</style>
