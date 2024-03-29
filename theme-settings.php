<?php
// +------------------------------------------------------------------------+
// | PHP Melody ( www.phpsugar.com )
// +------------------------------------------------------------------------+
// | PHP Melody IS NOT FREE SOFTWARE
// | If you have downloaded this software from a website other
// | than www.phpsugar.com or if you have received
// | this software from someone who is not a representative of
// | PHPSUGAR, you are involved in an illegal activity.
// | ---
// | In such case, please contact: support@phpsugar.com.
// +------------------------------------------------------------------------+
// | Developed by: PHPSUGAR (www.phpsugar.com) / support@phpsugar.com
// | Copyright: (c) 2004-2013 PHPSUGAR. All rights reserved.
// +------------------------------------------------------------------------+

$showm = '8';
/*
$load_uniform = 0;
$load_ibutton = 0;
$load_tinymce = 0;
$load_colorpicker = 0;
$load_prettypop = 0;
*/
$load_swfupload = 1;
$load_swfupload_upload_image_handlers = 1;
$load_colorpicker = 1;
$load_scrolltofixed = 1;
$load_settings_theme_resources = 1;
$load_fileinput_upload = 1;

$_page_title = 'Layout Settings';
include('header.php');
include_once('syndicate-news.php');

//$config	= get_config();

$inputs = array();
$info_msg = '';
$video_sources = a_fetch_video_sources();

if ($_POST['submit'] == "Save" && ( ! csrfguard_check_referer('_admin_settings')))
{
	$info_msg = 'Invalid token or session expired. Please load this page from the menu and try again.'; 
}
else if ($_POST['submit'] == "Save")
{
	$req_fields = array("browse_page" => "Videos per browsing page",
						"top_page_limit" => "Popular videos page (limit)",
						"new_page_limit" => "New videos page (limit)",
						"comments_page" => "Comments per page",
						"thumb_video_w" => "Video thumbnail width",
						"thumb_video_h" => "Video thumbnail height",
						"thumb_article_w" => "Article thumbnail width",
						"thumb_article_h" => "Article thumbnail height",
						"thumb_avatar_w" => "User avatar width",
						"thumb_avatar_h" => "User avatar heigh"
					);
	$num_fields = array('new_videos', 'article_widget_limit', 'chart_days', 'top_videos', 'playingnow_limit', 'watch_related_limit', 'watch_toprated_limit', 'fav_limit', 'browse_page', 'comments_page', 'thumb_video_w', 'thumb_video_h', 'thumb_article_w', 'thumb_article_h', 'thumb_avatar_w', 'thumb_avatar_h', 'chart_days', 'show_stats', 'show_tags', 'tag_cloud_limit', 'search_suggest', 'show_addthis_widget', 'browse_articles', 'rtl_support');
	foreach($_POST as $k => $v)
	{
		if($_POST[$k] == '' && in_array($k, $req_fields))
		{
			$info_msg .= "'".$req_fields[$k] . "' field cannot be left blank!";
		}
		if(in_array($k, $num_fields))
		{
			$v = (int) $v;
			$v = abs($v);
			$inputs[$k] = $v;
		}
		else if ( ! is_array($v))
			$inputs[$k] = $v;
	}
	
	//  Template has changed? Clear the Smarty Cache & Compile directories
		if ($inputs['template_f'] != $config['template_f'])
		{
				@include_once(ABSPATH . 'Smarty/SmartyBC.class.php');
				$smarty = new Smarty;
				// clear out all cache files
				$smarty->clearAllCache();

				$smarty_compile_dir = ABSPATH . 'Smarty/templates_c/';
				$smarty_cache_dir = ABSPATH . 'Smarty/cache/';

				//  empty compile directory
				$dir = @opendir($smarty_compile_dir);
				if ($dir)
				{
						while (false !== ($file = readdir($dir)))
						{
								if(strlen($file) > 2)
								{
										$tmp_parts = explode('.', $file);
										$ext = array_pop($tmp_parts);
										$ext = strtolower($ext);
										
										if ($ext == 'php' && strpos($file, 'tpl') !== false)
										{
												@unlink($smarty_compile_dir .'/'. $file);
										}
								}
						}
						closedir($dir);
				}
				
				//  empty cache directory
				$dir = @opendir($smarty_cache_dir);
				if ($dir)
				{
						while (false !== ($file = readdir($dir)))
						{
								if(strlen($file) > 2)
								{
										$tmp_parts = explode('.', $file);
										$ext = array_pop($tmp_parts);
										$ext = strtolower($ext);
										
										if ($ext == 'php' && strpos($file, 'tpl') !== false)
										{
												@unlink($smarty_cache_dir .'/'. $file);
										}
								}
						}
						closedir($dir);
				}
		}

	// Save config	
	if($info_msg == '')
	{
		foreach ($inputs as $config_name => $config_value)
		{
			if ($config_name != 'submit' && $config_name != 'allow_user_uploadvideo_unit')
			{	
				update_config($config_name, $config_value, true);
			}
		}
	}
}

$selected_tab_view = '';
$page_tab_views = array('t0', 't1', 't2', 't3', 'general', 'customize', 'store');

if ($_POST['settings_selected_tab'] != '' || $_GET['view'] != '')
{
	$selected_tab_view = ($_POST['settings_selected_tab'] != '') ? $_POST['settings_selected_tab'] : $_GET['view'];
	if ( ! in_array($selected_tab_view, $page_tab_views)) 
	{
		$selected_tab_view = '';
	}
}

?>
<!-- Main content -->
<div class="content-wrapper">
<div class="page-header-wrapper page-header-edit"> 
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
		<div class="d-flex justify-content-between w-100">
			<div class="page-title d-flex">
				<h4><a href="<?php echo _URL; ?>" class="open-in-new" target="_blank" data-popup="tooltip" data-placement="right" data-original-title="Switch to Front-End"><span class="font-weight-semibold"><?php echo $_page_title; ?></span> <i class="mi-open-in-new"></i></a></h4>
			</div>
			<div class="header-elements d-flex-inline align-self-center ml-auto">
				<div class="">
					<button type="submit" name="submit" value="Save" class="btn btn-sm btn-outline alpha-success text-success-400 border-success-400 border-2" onclick="document.forms[0].submit({return validateFormOnSubmit(this, 'Please fill in the required fields (highlighted)')});" form="sitesettings"><i class="mi-check"></i> Save changes</button>
				</div>
			</div>
		</div>
		</div>

		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline d-none">
			<div class="d-flex">
			<div class="d-horizontal-scroll">
				<div class="breadcrumb">
					<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
					<a href="settings.php" class="breadcrumb-item">Settings</a>
					<span class="breadcrumb-item active">Layout Settings</span>
				</div>
			</div>
			</div>

			<div class="header-elements d-none d-md-block"><!--d-none-->
				<div class="breadcrumb justify-content-center">
				</div>
			</div>
		</div>
	</div><!-- /page header -->
</div><!--.page-header-wrapper-->	

	<!-- Content area -->
	<div class="content content-full-width">

	<div class="d-horizontal-scroll">
		<nav id="import-nav" class="tabbable d-md-none d-lg-none d-sm-block" role="navigation">
			<ul class="nav nav-pills nav-pills-bottom bg-white rounded justify-content">
				<li class="nav-item <?php echo ($selected_tab_view == 't0' || $selected_tab_view == '' || $selected_tab_view == 't1' || $selected_tab_view == 'general') ? 'active' : '';?>"><a href="#t0" data-toggle="tab" class="nav-link active">General Settings</a></li>
				<li class="nav-item <?php echo ($selected_tab_view == 't2' || $selected_tab_view == 'customize') ? 'active' : '';?>"><a href="#t2" data-toggle="tab" class="nav-link<?php echo ($selected_tab_view == 't2' || $selected_tab_view == 'customize') ? ' active' : '';?>">Customize Theme</a></li>
				<li class="nav-item <?php echo ($selected_tab_view == 't3' || $selected_tab_view == 'store') ? 'active' : '';?>"><a data-toggle="tab" href="#t3" class="nav-link<?php echo ($selected_tab_view == 't3' || $selected_tab_view == 'store') ? ' active' : '';?>">Theme Store</a></li>
			</ul>
		</nav>
	</div>
<?php if ('' != $info_msg) : ?>
	<?php echo pm_alert_error($info_msg); ?>
<?php elseif ($_POST['submit'] == "Save" && $info_msg == '') : ?>
	<?php echo pm_alert_success('The new settings have been saved and applied.'); ?>
<?php endif; ?>
<div id="settings-jump"></div>
	<div class="card card-blanche">

<form name="sitesettings" id="sitesettings" method="post" action="theme-settings.php">
<?php echo csrfguard_form('_admin_settings'); ?>



	<div class="tab-content">
	<div class="tab-pane fade<?php echo ($selected_tab_view == 't0' || $selected_tab_view == 't1' || $selected_tab_view == ''  || $selected_tab_view == 'general') ? ' show active' : '';?>" id="t0">
	

	<div class="card-body">
		<!-- <h5 class="sub-head-settings">General Layout Settings</h5> -->
	</div>

	<table cellpadding="0" cellspacing="0" width="100%" class="table table-striped table-columned pm-tables pm-tables-settings">
			<tr class="border-top-1 border-bottom-0 bg-transparent">
				<td colspan="2">
					<h5 class="pt-2 pb-2 mb-0 text-dark font-weight-semibold">Header</h5>
				</td>
			</tr>
			<tr>
				<td class="w-30">Site theme</td>
				<td>
				<select name="template_f" class="custom-select form-control w-auto">
				<?php echo dropdown_templates($config['template_f']); ?>
				</select> 
				<?php if (strtolower($config['template_f']) == 'default' || strtolower($config['template_f'] == 'apollo') || strtolower($config['template_f']) == 'echo') : ?>
					<a href="customize.php" class="btn btn-sm btn-primary" target="_blank"><i class="mi-format-paint"></i> Customize</a>
				<?php endif; ?>
				</td>
			</tr>
			<tr>
				<td class="w-30" valign="top">Site title</td>
				<td>
				<input name="homepage_title" type="text" class="form-control" size="45" value="<?php echo htmlspecialchars(stripslashes($config['homepage_title'])); ?>" />
				</td>
			</tr>
			<tr>
				<td class="w-30" valign="top">Logo image</td>
				<td>
					<div id="settings-logo-container">
						<?php if ($config['custom_logo_url'] != ''): ?>
							<img src="<?php echo $config['custom_logo_url'];?>" border="0" class="mb-3" style="max-width:200px" />
						<?php endif; ?>
					</div>
					<div class="input-group">
						<button class="btn btn-sm btn-warning mr-2 <?php if ($config['custom_logo_url'] == '') echo 'hide';?>" id="btn-remove-logo"><i class="icon-bin mr-2"></i> Remove logo</button>
						<span class="fileinput-button">
							<input type="file" name="file" id="upload-logo-btn" class="file-input form-control form-control-sm alpha-grey" data-show-caption="false" data-show-upload="false" data-browse-icon="<i class='icon-upload4 mr-2'></i>" data-browse-label="Upload" data-browse-class="btn btn-sm btn-success" data-remove-class="btn btn-sm btn-success" data-show-remove="false" data-show-preview="false" />
						</span>
						<span class="input-group-text bg-transparent border-0">
							<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="We recommend using a transparent PNG image with a suggested width of <strong>233 pixels</strong> and maximum height of <strong>80 pixels</strong>. Large images will be automatically resized to fit within the header."><i class="mi-info-outline"></i></a>
						</span>
					</div>
				</td>
			</tr>
			<?php if (strtolower($config['template_f']) == 'default' || strtolower($config['template_f'] == 'apollo') || strtolower($config['template_f']) == 'echo') : ?>
				<tr>
					<td class="w-30" valign="top">Right-To-Left layout</td>
					<td>
						<label class="m-0 mr-2"><input name="rtl_support" type="radio" value="1" <?php echo ($config['rtl_support']==1) ? 'checked="checked"' : "";?> /> Enabled</label>
						<label class="m-0 mr-2"><input name="rtl_support" type="radio" value="0" <?php echo ($config['rtl_support']==0) ? 'checked="checked"' : "";?> /> Disabled</label>
						<span class="d-inline-block ml-2">
							<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="Useful for right-to-left layout orientation. <strong>Note</strong>: Not all themese support this feature."><i class="mi-info-outline"></i></a>
						</span>
					</td>
				</tr>
			<?php endif; ?>
			<tr>
				<td>Meta keywords</td>
				<td>
					<input name="homepage_keywords" type="text" size="45" class="form-control" value="<?php echo htmlspecialchars(stripslashes($config['homepage_keywords'])); ?>" />
				</td>
			</tr>
			<tr>
				<td valign="top">Meta description</td>
				<td>
					<textarea name="homepage_description" rows="2" cols="55" class="form-control"><?php echo stripslashes($config['homepage_description']); ?></textarea>
				</td>
			</tr>
		<tr>
		<td>Live search recommendations</td>
		<td>
			<label class="m-0 mr-2"><input name="search_suggest" type="radio" value="1" <?php echo ($config['search_suggest']==1) ? 'checked="checked"' : "";?> /> Enabled</label>
			<label class="m-0 mr-2"><input name="search_suggest" type="radio" value="0" <?php echo ($config['search_suggest']==0) ? 'checked="checked"' : "";?> /> Disabled</label>
			<span class="d-inline-block ml-2">
				<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="If <em>enabled</em>, users will see a search suggestions list as they type the search query."><i class="mi-info-outline"></i></a>
			</span>
			</td>
		</tr>
	</table>
		
	<table cellpadding="0" cellspacing="0" width="100%" class="table table-striped table-columned pm-tables pm-tables-settings">
		<tr class="border-top-1 border-bottom-0 bg-transparent">
			<td colspan="2">
				<h5 class="pt-2 pb-2 mb-0 text-dark font-weight-semibold">Homepage</h5>
			</td>
		</tr>
		<tr>
			<td class="w-30">Featured videos limit</td>
			<td>
			<div class="input-group input-group-sm">
				<input name="homepage_featured_limit" type="text" size="8" class="form-control col-md-2 w-auto" value="<?php echo $config['homepage_featured_limit']; ?>" />
				<span class="input-group-append"><span class="input-group-text border-left-0 bg-transparent">videos</span></span>
			</div>
			</td>
		</tr>
		<tr>
			<td>Popular videos widget: sort by</td>
			<td>
				<select name="top_videos_sort" class="custom-select form-control w-auto">
				<option value="views" <?php if($config['top_videos_sort'] == "views") echo ' selected="selected" ';?>>Most viewed</option>
				<option value="chart" <?php if($config['top_videos_sort'] == "chart") echo ' selected="selected" ';?>>Most viewed (last <?php echo $config['chart_days'];?> days)</option>
				<option value="rating"<?php if($config['top_videos_sort'] == "rating") echo ' selected="selected" ';?>>Most liked</option>
				</select>
			</td>
		</tr>
		<tr>
				<td>Popular videos widget: limit</td>
				<td>
					<div class="input-group input-group-sm">
						<input name="top_videos" type="text" size="8" class="form-control col-md-2 w-auto" value="<?php echo $config['top_videos']; ?>" />
						<span class="input-group-append"><span class="input-group-text border-left-0 bg-transparent">videos</span></span>
						<span class="input-group-text bg-transparent border-0">
							<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="Set how many videos you want to list in the <em>Popular Videos</em> widget from your homepage."><i class="mi-info-outline"></i></a>
						</span>
					</div>
				</td>
				</tr>
				<tr>
				<td>'Being watched' limit</td>
				<td>
					<div class="input-group input-group-sm">
						<input name="playingnow_limit" type="text" size="8" class="form-control col-md-2 w-auto" value="<?php echo $config['playingnow_limit']; ?>" />
						<span class="input-group-append"><span class="input-group-text border-left-0 bg-transparent">videos</span></span>
						<span class="input-group-text bg-transparent border-0">
							<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="Set how many videos you want to list in the <em>'Being watched now'</em> widget from your homepage (under the homepage 'Featured' video)."><i class="mi-info-outline"></i></a>
						</span>
					</div>
				</td>
				</tr>
				<tr>
				<td>New videos limit</td>
				<td>
					<div class="input-group input-group-sm">
						<input name="new_videos" type="text" size="8" class="form-control col-md-2 w-auto" value="<?php echo $config['new_videos']; ?>" />
						<span class="input-group-append"><span class="input-group-text border-left-0 bg-transparent">videos</span></span>
						<span class="input-group-text bg-transparent border-0">
							<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="Set how many videos you want to list in the <em>New Videos</em> widget from your homepage."><i class="mi-info-outline"></i></a>
						</span>
					</div>
				</td>
				</tr>
				<tr>
				<td>Articles widget limit</td>
				<td>
					<div class="input-group input-group-sm">
						<input name="article_widget_limit" type="text" size="8" class="form-control col-md-2 w-auto" value="<?php echo $config['article_widget_limit']; ?>" />
						<span class="input-group-append"><span class="input-group-text border-left-0 bg-transparent">articles</span></span>
						<span class="input-group-text bg-transparent border-0">
							<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="Set how many articles you want to show in the <em>Latest Articles</em> widget from your homepage."><i class="mi-info-outline"></i></a>
						</span>
					</div>
				</td>
				</tr>
		<tr>
		<td>Show statistics</td>
			<td>
				<label class="m-0 mr-2"><input name="show_stats" type="radio" value="1" <?php echo ($config['show_stats']==1) ? 'checked="checked"' : "";?> /> Yes</label>
				<label class="m-0 mr-2"><input name="show_stats" type="radio" value="0" <?php echo ($config['show_stats']==0) ? 'checked="checked"' : "";?> /> No</label>
				<span class="d-inline-block ml-2">
					<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="If enabled, a widget containing details such as <em>member count</em>, <em>video count</em>, etc. will appear on your homepage."><i class="mi-info-outline"></i></a>
				</span>
			</td>
		</tr>
		<tr>
			<td>Show tag cloud</td>
			<td>
				<label class="m-0 mr-2"><input name="show_tags" type="radio" value="1" <?php echo ($config['show_tags']==1) ? 'checked="checked"' : "";?> /> Yes</label>
				<label class="m-0 mr-2"><input name="show_tags" type="radio" value="0" <?php echo ($config['show_tags']==0) ? 'checked="checked"' : "";?> /> No</label>
				<span class="d-inline-block ml-2">
					<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="If enabled, a widget listing the most common tags will appear on your homepage. This helps visitors find popular content on your site."><i class="mi-info-outline"></i></a>
				</span>
			</td>
			</tr>
		 <tr>
			<td>Tag cloud limit</td>
			<td>
				<div class="input-group input-group-sm">
					<input name="tag_cloud_limit" type="text" size="8" class="form-control col-md-2 w-auto" value="<?php echo $config['tag_cloud_limit']; ?>" />
					<span class="input-group-append"><span class="input-group-text border-left-0 bg-transparent">videos</span></span>
				</div>
			</td>
			</tr>
		<tr>
			<td>Order tag cloud</td>
			<td>
				<label class="m-0 mr-2"><input name="shuffle_tags" type="radio" value="0" <?php echo ($config['shuffle_tags']==0) ? 'checked="checked"' : "";?> /> Descending</label> 
				<label class="m-0 mr-2"><input name="shuffle_tags" type="radio" value="1" <?php echo ($config['shuffle_tags']==1) ? 'checked="checked"' : "";?> /> Shuffle</label>
			</td>
		</tr>
	</table>

	<table cellpadding="0" cellspacing="0" width="100%" class="table table-striped table-columned pm-tables pm-tables-settings">
		<tr class="border-top-1 border-bottom-0 bg-transparent">
			<td colspan="2">
				<h5 class="pt-2 pb-2 mb-0 text-dark font-weight-semibold">Video &amp; Content Pages</h5>
			</td>
		</tr>
		<tr>
			<td class="w-30">"Related" videos limit</td>
			<td>
				<div class="input-group input-group-sm">
					<input name="watch_related_limit" type="text" size="8" class="form-control col-md-2 w-auto" value="<?php echo $config['watch_related_limit']; ?>" />
					<span class="input-group-append"><span class="input-group-text border-left-0 bg-transparent">videos</span></span>
					<span class="input-group-text bg-transparent border-0">
						<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="This value must be greater than 0 (zero)."><i class="mi-info-outline"></i></a>
					</span>
				</div>
			</td>
		</tr>
		<tr>
			<td class="w-30">"Popular" videos limit</td>
			<td>
				<div class="input-group input-group-sm">
					<input name="watch_toprated_limit" type="text" size="8" class="form-control col-md-2 w-auto" value="<?php echo $config['watch_toprated_limit']; ?>" />
					<span class="input-group-append"><span class="input-group-text border-left-0 bg-transparent">videos</span></span>
					<span class="input-group-text bg-transparent border-0">
						<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="This value must be greater than 0 (zero)."><i class="mi-info-outline"></i></a>
					</span>
				</div>
			</td>
		</tr>
		<tr>
			<td class="w-30">Show a floating social sharing widget (share buttons)</td>
			<td>
				<label class="m-0 mr-2"><input name="show_addthis_widget" type="radio" value="1" <?php echo ($config['show_addthis_widget']==1) ? 'checked="checked"' : "";?> /> Yes</label>  
				<label class="m-0 mr-2"><input name="show_addthis_widget" type="radio" value="0" <?php echo ($config['show_addthis_widget']==0) ? 'checked="checked"' : "";?> /> No</label>
				<span class="d-inline-block ml-2">
					<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="When enabled, a floating widget of sharing buttons (Facebook, Twitter, Google, etc.) appears next to your content pages (video and article pages)."><i class="mi-info-outline"></i></a>
				</span>
			</td>
		</tr>
	</table>
		
	<table cellpadding="0" cellspacing="0" width="100%" class="table table-striped table-columned pm-tables pm-tables-settings">
		<tr class="border-top-1 border-bottom-0 bg-transparent">
			<td colspan="2">
				<h5 class="pt-2 pb-2 mb-0 text-dark font-weight-semibold">Listings</h5>
			</td>
		</tr>
		<tr>
		<td>Articles per browsing page</td>
		<td>
			<div class="input-group input-group-sm">
				<input name="browse_articles" type="text" size="8" class="form-control col-md-2 w-auto" value="<?php echo $config['browse_articles']; ?>" />
				<span class="input-group-append"><span class="input-group-text border-left-0 bg-transparent">articles</span></span>
				<span class="input-group-text bg-transparent border-0">
					<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="This value must be greater than 0 (zero)."><i class="mi-info-outline"></i></a>
				</span>
			</div>
		</td>
		</tr>
		<tr>
			<td class="w-30">Videos per browsing page</td>
			<td>
				<div class="input-group input-group-sm">
				<input name="browse_page" type="text" size="8" class="form-control col-md-2 w-auto" value="<?php echo $config['browse_page']; ?>" />
				<span class="input-group-append"><span class="input-group-text border-left-0 bg-transparent">videos</span></span>
				<span class="input-group-text bg-transparent border-0">
					<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="Limit how many videos to show on each category or search results page."><i class="mi-info-outline"></i></a>
				</span>
				</div>
			</td>
		</tr>
		<tr>
			<td>"<a href="<?php echo _URL; ?>/newvideos.php">New videos</a>" page</td>
			<td>
				<div class="input-group input-group-sm">
					<input name="new_page_limit" type="text" size="8" class="form-control col-md-2 w-auto" value="<?php echo $config['new_page_limit']; ?>" />
					<span class="input-group-append"><span class="input-group-text border-left-0 bg-transparent">videos</span></span>
					<span class="input-group-text bg-transparent border-0">
						<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="Limit how many videos to list on the 'New Videos' page."><i class="mi-info-outline"></i></a>
					</span>
				</div>
			</td>
		</tr>
		<tr>
			<td>"<a href="<?php echo _URL; ?>/topvideos.php?do=recent">Popular videos</a>" page</td>
			<td>
				<div class="input-group input-group-sm">
				<input name="top_page_limit" type="text" size="8" class="form-control col-md-2 w-auto" value="<?php echo $config['top_page_limit']; ?>" />
				<span class="input-group-append"><span class="input-group-text border-left-0 bg-transparent">videos</span></span>
				<span class="input-group-text bg-transparent border-0">
					<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="Limit how many videos to list on the 'Popular Videos' page."><i class="mi-info-outline"></i></a>
				</span>
				</div>
			</td>
	 	</tr>
		<tr>
			<td>Refresh "<a href="<?php echo _URL; ?>/topvideos.php?do=recent">Popular videos</a>" page</a> every</td>
			<td>
				<div class="input-group input-group-sm">
					<input name="chart_days" type="text" size="8" class="form-control col-md-2 w-auto" value="<?php echo $config['chart_days']; ?>" />
					<span class="input-group-append"><span class="input-group-text border-left-0 bg-transparent">days</span></span>
					<span class="input-group-text bg-transparent border-0">
						<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="Insert <strong>0 (zero)</strong> to prevent the list from being refreshed. This will result in having an 'All time' popular videos chart/list."><i class="mi-info-outline"></i></a>
					</span>
				</div>
			</td>
		</tr>
		<tr>
			<td>Comments per page</td>
			<td>
				<div class="input-group input-group-sm">
					<input name="comments_page" type="text" size="8" class="form-control col-md-2 w-auto" value="<?php echo $config['comments_page']; ?>" />
					<span class="input-group-append"><span class="input-group-text border-left-0 bg-transparent">comments</span></span>
					<span class="input-group-text bg-transparent border-0">
						<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="Limit the number of comments for each article/video."><i class="mi-info-outline"></i></a>
					</span>
				</div>
			</td>
		</tr>
	</table>

	<h5 class="sub-head-settings"></h5>
	<table cellpadding="0" cellspacing="0" width="100%" class="table table-striped table-columned pm-tables pm-tables-settings">
		<tr class="border-top-1 border-bottom-0 bg-transparent">
			<td colspan="2">
				<h5 class="pt-2 pb-2 mb-0 text-dark font-weight-semibold">Thumbnails &amp; Avatars</h5>
			</td>
		</tr>
		<tr>
			<td class="w-30">Video Thumbnails</td>
			<td>
				<div class="input-group">
					<input type="text" name="thumb_video_w" size="4" maxlength="4" class="form-control col-md-2 rounded" value="<?php echo $config['thumb_video_w'];?>" /> <span class="input-group-text bg-transparent border-0">x</span> 
					<input type="text" name="thumb_video_h" size="4" maxlength="4" class="form-control col-md-2 rounded" value="<?php echo $config['thumb_video_h'];?>" /> <span class="input-group-text bg-transparent border-0">px</span>
					<span class="input-group-text bg-transparent border-0">
						<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="Assign the maximum width and height for video thumbnails. Uploaded thumbnails will be resized to fit these specifications. <br><strong>Format</strong>: WIDTH x HEIGHT (in pixels)"><i class="mi-info-outline"></i></a>
					</span>
				</div>
			</td>
		</tr>
		<tr>
		<td>Article Thumbnails</td>
			<td>
				<div class="input-group">
					<input type="text" name="thumb_article_w" size="4" maxlength="4" class="form-control col-md-2 rounded" value="<?php echo $config['thumb_article_w'];?>" /> <span class="input-group-text bg-transparent border-0">x</span> 
					<input type="text" name="thumb_article_h" size="4" maxlength="4" class="form-control col-md-2 rounded" value="<?php echo $config['thumb_article_h'];?>" /> <span class="input-group-text bg-transparent border-0">px</span>
					<span class="input-group-text bg-transparent border-0">
						<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="Assign the maximum width and height for article thumbnails. Uploaded thumbnails will be resized to fit these specifications. <br><strong>Format</strong>: WIDTH x HEIGHT (in pixels)"><i class="mi-info-outline"></i></a>
					</span>
				</div>
				<?php if ( $config['mod_article'] != 1 ) : ?>
				<div class="d-block text-warning font-size-sm">The 'Article Module' is disabled. Visit the '<strong>Settings</strong>' page to enable it.</div>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td>User Avatar</td>
			<td>
				<div class="input-group">
					<input type="text" name="thumb_avatar_w" size="4" maxlength="4" class="form-control col-md-2 rounded" value="<?php echo $config['thumb_avatar_w'];?>" /> <span class="input-group-text bg-transparent border-0">x</span> 
					<input type="text" name="thumb_avatar_h" size="4" maxlength="4" class="form-control col-md-2 rounded" value="<?php echo $config['thumb_avatar_h'];?>" /> <span class="input-group-text bg-transparent border-0">px</span>
					<span class="input-group-text bg-transparent border-0">
						<a href="#" class="text-grey-300 alpha-grey" data-popup="popover" data-html="true" data-placement="right" data-trigger="hover" data-content="Assign the maximum width and height for article thumbnails. Uploaded thumbnails will be resized to fit these specifications. <br><strong>Format</strong>: WIDTH x HEIGHT (in pixels)"><i class="mi-info-outline"></i></a>
					</span>
				</div>
			</td>
		</tr>
	</table>
	</div>
		
	<div class="tab-pane fade<?php echo ($selected_tab_view == 't2' || $selected_tab_view == 'customize') ? ' in active' : '';?>" id="t2">    
		<div class="card-body">
			<!-- <h5 class="sub-head-settings">Customize Theme</h5> -->

			<?php if ($config['template_f'] != 'default' && $config['template_f'] != 'apollo') : ?>
				<div class="alert alert-warning">Sorry, the <strong><?php echo ucfirst($config['template_f']); ?></strong> theme doesn't appear to support live customizations.</div>
			<?php else : ?>
				<div class="alert alert-success">The <strong><?php echo ucfirst($config['template_f']); ?></strong> theme supports customizations.</div>
				<a href="customize.php" class="btn btn-sm btn-primary ml-3" target="_blank"><i class="mi-open-in-new"></i> Launch the customizer</a>
			<?php endif; ?>

		</div>
	</div>

	<div class="tab-pane fade<?php echo ($selected_tab_view == 't3' || $selected_tab_view == 'store') ? ' in active' : '';?>" id="t3">
	<div class="card-body">
		
		<!-- <h5 class="sub-head-settings">Theme Store</h5> -->
		<div class="alert alert-info alert-styled-left">Make it your own by using a premium theme from the PHPSUGAR's theme collection. All these are compatible with PHP Melody v<?php echo _PM_VERSION; ?>.</div>
		
		<div class="pm-themes">
		<?php
		$data_serialized = cache_this('get_theme_store_data', 'get_theme_store_data');
		$data = unserialize($data_serialized);

		if (is_array($data) && pm_count($data) > 0) : 

			if ($data['items_count'] > 0) : ?>

			<ul class="row pm-themes-list">
			<?php 
				foreach ($data['items'] as $k => $theme) : 
					
					$theme_mark_new = false;
					
					if (array_key_exists('pubDate', $theme) && $theme['pubDate'] != '')
					{
						$theme['release_date_timestamp'] = strtotime($theme['pubDate']);
						if ((time() - $theme['release_date_timestamp']) <= 2678400) // a month
						{
							$theme_mark_new = true;
						}
					}
					else
					{
						$theme['release_date_timestamp'] = 0;
					}
				
				?>
				<li class="theme-item">
					<h3><?php echo $theme['title'];?></h3>
									<?php if ($theme_mark_new) : ?>
						<div class="theme-label">NEW</div>
					<?php endif; ?>
									<a href="<?php echo 'http://blankrefer.com/?'.$theme['preview_url'];?>" class="theme-preview" target="_blank" title="Preview <?php echo str_replace('"', '', $theme['title']);?> Theme">
					<img src="<?php echo make_url_https($theme['thumb_url']);?>" alt="Theme Image" border="0" class="theme-thumb" />
					</a>
									<a href="<?php echo 'http://blankrefer.com/?'.$theme['preview_url'];?>" target="_blank" class="btn btn-sm btn-link font-weight-semibold">Preview</a>
									<a href="<?php echo 'http://blankrefer.com/?'.$theme['buy_url'];?>" target="_blank" class="btn btn-sm btn-link font-weight-semibold">Order now</a>
							</li>
				<?php endforeach; ?>
					</ul>
			<?php else : 
				echo pm_alert_danger('No themes available at the moment.');
			endif; ?>
		<?php else : 
			echo pm_alert_danger('Sorry, couldn\'t retrieve data from the Theme Store.');
		endif;?>
		</div><!--.pm-themes-->
		</div>
	</div>
</div>

<div class="datatable-footer">
<div id="stack-controls-disabled" class="row list-controls">
		<div class="col-md-12">	
			<input name="views_from" type="hidden" value="2"  />
			<input type="hidden" name="settings_selected_tab" value="<?php echo ($selected_tab_view != '') ? $selected_tab_view:  't1';?>" />
			<input type="hidden" name="p" value="upload" /> 
			<input type="hidden" name="do" value="upload-image" />
			<input type="hidden" name="upload-type" value="logo" />

			<div class="float-right">
				<div class="btn-group">
					<button type="submit" name="submit" value="Save" class="btn btn-sm btn-outline alpha-success text-success-400 border-success-400 border-2"><i class="mi-check"></i> Save changes</button>
				</div>
			</div>
		</div>
</div><!-- #list-controls -->
</div>
</form>

</div><!--.card-->
</div><!-- .content -->
<script type="text/javascript">
$(document).ready(function(){
	$('form[name="sitesettings"]').change(function(){
		phpmelody.prevent_leaving_without_saving = true;
	}).submit(function(){
		phpmelody.prevent_leaving_without_saving = false;
	});
});
</script>
<?php
include('footer.php');
