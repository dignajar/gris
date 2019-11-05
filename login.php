<?php defined('BLUDIT') or die('Bludit CMS.'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="robots" content="noindex,nofollow">
	<meta name="generator" content="Bludit">

	<title><?php echo $layout['title'] ?></title>
	<link rel="icon" type="image/x-icon" href="<?php echo HTML_PATH_CORE_IMG.'favicon.png?version='.BLUDIT_VERSION ?>">

	<?php
		echo Theme::cssBootstrap();
		echo Theme::css(array(
			'bludit-bootstrap.css',
			'bludit.css'
		), DOMAIN_ADMIN_THEME_CSS);
	?>

	<!-- Plugins -->
	<?php Theme::plugins('loginHead') ?>
</head>
<body class="login">

<!-- Plugins -->
<?php Theme::plugins('loginBodyBegin') ?>

<div class="container">
	<div class="row justify-content-md-center pt-5">
		<div class="col-md-4 pt-5">
			<h1 class="text-center mb-5 mt-5 font-weight-normal" style="color: #555;">GRIS</h1>

			<?php if (Alert::defined()): ?>
			<div id="alert" class="alert <?php echo (Alert::status()==ALERT_STATUS_FAIL)?'alert-danger':'alert-success' ?>">
			<?php echo Alert::get() ?>
			</div>
			<?php endif; ?>

			<form method="post" action="" autocomplete="off">
				<input type="hidden" id="jstokenCSRF" name="tokenCSRF" value="<?php echo $security->getTokenCSRF() ?>">
				<div class="form-group">
					<input type="text" value="<?php echo (isset($_POST['username'])?$_POST['username']:'') ?>" class="form-control form-control-lg" id="jsusername" name="username" placeholder="Username" autofocus>
				</div>

				<div class="form-group">
					<input type="password" class="form-control form-control-lg" id="jspassword" name="password" placeholder="Password">
				</div>

				<div class="form-check">
					<input class="form-check-input" type="checkbox" value="true" id="jsremember" name="remember">
					<label class="form-check-label" for="jsremember">Remember me</label>
				</div>

				<div class="form-group mt-4">
					<button type="submit" class="btn btn-primary mr-2 w-100" name="save">Login</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Plugins -->
<?php Theme::plugins('loginBodyEnd') ?>

</body>
</html>