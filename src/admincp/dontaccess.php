<?php require_once('checklogin_root.php'); ?>

<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
	<meta charset="utf-8">
    
	<?php include('layouts/seo_root.php');?>

    <title><?php echo $stitle; ?></title>


	<!-- Theme JS files -->
	<script type="text/javascript" src="assets/js/core/app.js"></script>
	<!-- /theme JS files -->

</head>

<body>

	<!-- Main navbar -->
	<?php include('layouts/mainnav.php'); ?>
	<!-- /main navbar -->


	<!-- Page container -->
	<div class="page-container login-container">

		<!-- Page content -->
		<div class="page-content">

			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Content area -->
				<div class="content">

					<!-- Error wrapper -->
					<div class="container-fluid text-center">
						<h1 class="error-title offline-title">Don't Access</h1>
						<h6 class="text-semibold content-group">شما دسترسی لازم برای انجام این عملیات را ندارید.</h6>

						<div class="row">
							<div class="col-lg-4 col-lg-offset-4 col-sm-6 col-sm-offset-3">
								<form action="#" class="main-search">
									<div class="input-group content-group">

										<div class="input-group-btn">

										</div>
									</div>

									<div class="row">
										<div class="col-sm-6">
                                          <a href="<?php echo $_GET['ref']; ?>" class="btn btn-default btn-block content-group"><i class="icon-circle-right2 position-left"></i> بازگشت به صفحه قبلی</a>
										</div>

										<div class="col-sm-6">
											<a href="index.php" class="btn btn-primary btn-block content-group"><i class="icon-home4 position-left"></i> بازگشت به داشبورد</a>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
					<!-- /error wrapper -->


					<!-- Footer -->
					<?php include('layouts/footer.php');?>
					<!-- /footer -->

				</div>
				<!-- /content area -->

			</div>
			<!-- /main content -->

		</div>
		<!-- /page content -->

	</div>
	<!-- /page container -->

</body>
</html>
