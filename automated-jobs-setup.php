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
// | Copyright: (c) 2004-2016 PHPSUGAR. All rights reserved.
// +------------------------------------------------------------------------+

$showm = 'cron';

$_page_title = 'Automated Jobs Setup';
include('header.php');
include_once(ABSPATH . 'include/cron_functions.php');

// generate and save the secret key before outputting instructions 
$generated_key = false;
if (empty($config['cron_secret_key']))
{
	$generated_key = true;
	update_config('cron_secret_key', generate_cron_key(), true);
}
?>
<!-- Main content -->
<div class="content-wrapper">
<div class="page-header-wrapper"> 
	<div class="page-header page-header-light">
		<div class="page-header-content header-elements-md-inline">
		<div class="d-flex justify-content-between w-100">
			<div class="page-title d-flex">
				<h4><span class="font-weight-semibold"><?php echo $_page_title; ?></span></h4>
			</div>
			<div class="header-elements d-flex-inline align-self-center ml-auto">
				<div class="">
					<h5 class="font-weight-semibold mb-0 text-center"><?php echo pm_number_format($total_cron_jobs); ?></h5>
					<span class="text-muted font-size-sm">job<?php echo ($total_cron_jobs == 1) ? '' : 's';?></span>
				</div>
			</div>
		</div>
		</div>

		<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
			<div class="d-flex">
			<div class="d-horizontal-scroll">
				<div class="breadcrumb">
					<a href="index.php" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Home</a>
					<a href="automated-jobs.php" class="breadcrumb-item">Automated Jobs</a>
					<span class="breadcrumb-item active"><?php echo $_page_title; ?></span>
				</div>
			</div>
			</div>

			<div class="header-elements d-none d-md-block"><!--d-none-->
				<div class="breadcrumb justify-content-center">
				</div>
			</div>
		</div>
	</div>
	<!-- /page header -->
</div><!--.page-header-wrapper-->

	<!-- Content area -->
	<div class="content">	
	<?php if ($generated_key) :
		echo pm_alert_success('<h5>Your Secret Key was created. You can now create the cron job.</h5>');
	endif; ?>

	<div class="card">
		<div class="card-header">
			<h5>Creating the Cron Job</h5>
		</div>

		<div class="card-body">
			<p><strong>Automated Jobs</strong> require a cron job to work. Cron will execute <em>cron.php</em> on a regular basis to ensure all the automated jobs are executed in the background.</p>
			<p>Cron jobs can be usually created from your hosting panel (cPanel, Plesk, etc.). If you need step-by-step help with setting up a cron job please refer to this <a href="http://blankrefer.com/?http://help.phpmelody.com/how-to-create-a-cron-job/" target="_blank">support document</a>.</p>
			<p>Your cron job will have the following properties:</p>
			<ol class="list-unstyled">
				<li><strong>Cron Command</strong>:</li>
				<li><code>wget -q -O /dev/null "<?php echo _URL; ?>/cron.php?cron-key=<?php echo $config['cron_secret_key']; ?>"</code></li>

				<li class="mt-2"><strong>Recommended Run Interval</strong>:<br />
					Every 5 minutes (*/5 * * * *)
				</li>
			</ol>
			<p><strong>To confirm the cron job is working correctly</strong>, wait 5 to 10 minutes after creating it, then check the enabled <a href="automated-jobs.php">automated jobs</a>.<br /> The <strong>Last Performed</strong> date should change from "<em>Never</em>" to a specific time (e.g. 4 seconds ago).</p>
		</div>
	</div>
</div><!-- .content -->
<?php
include('footer.php');