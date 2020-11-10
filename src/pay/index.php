<?php
	include 'include.php';
	//sezar.world/gateway/index.php?price=1&user_id=1
	$invoice_id	=	random_string();
	$user_id	=	intval($_GET['user_id']);
?>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title>Sezar Company Payment Gateway</title>
  <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css'>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $blockchain_root ?>Resources/js/pay-now-button-v2.js"></script>
    <script type="text/javascript">
		$(document).ready(function() {
			$('.stage-paid').on('show', function() {
				window.location.href = './order_status.php?invoice_id=<?= $invoice_id; ?>';
			});
		});
	</script>
  <style>
	body{
		background: rgb(14, 14, 14);
	}
	.sezar-logo{
		margin: auto !important;
		display: block;
		padding-top: 25px;
	}
	.copyright{
		text-align: left;
		direction:rtl;
		float:left;
		padding-left : 15px;
		color: #CCC;
	}
  </style>
</head>

<body>
<img src="<?= $sezar_link ?>/sezar-cp/sezar-themes/sezar-v1/img/logo-footer.png" class="sezar-logo">
  <div class="row mx-0 pt-5 d-flex justify-content-center">
  <div class="col-xs-4 col-sm-6 col-md-5 col-lg-4 col-xl-3">
    <div class="card shadow-lg">
      <div class="blockchain-btn card-header card-header-divider text-center pt-4" data-create-url="create.php?invoice_id=<?= $invoice_id; ?>&user_id=<?= $user_id ?>"> 
		  <div class="blockchain stage-begin">
			<img src="https://apirone.com/static/promo/bitcoin_logo_vector.svg" class="img-fluid" style="max-height: 42px;padding-bottom: 10px;" width="205" alt=""><br>
		  </div>
            <div class="blockchain stage-loading" style="text-align:center">
                <img src="<?php echo @$blockchain_root; ?>Resources/loading-large.gif">
            </div>
		  <div class="blockchain stage-ready" style="text-align:center"> 
			  <div class='qr-code'></div>
			  <div class="card-body px-0">
				<p class="text-center"><small><strong>Please send <?php echo @$price_in_btc; ?> BTC to <br /> <b>[[address]]</b> <br /></strong></small></p>
				<p class="text-muted text-center">Scan QR code and top-up your<br>
				  Bitcoin balance for any amount.<br>
				  Payment will be credited after 1 confirmation.<br>
				  Invoice ID : <?= $invoice_id; ?>
				  <br>
				  <?php
					$row_user = dbGetRow('users', ['id'=>$user_id]); 
					?>
				  User ID : <?= $row_user['id'].' - '.$row_user['username']; ?>
				</p>
			  </div>
		  </div>
		  <div class="blockchain stage-paid">
		  	Payment Received <b>[[value]] BTC</b>. Thank You.  
		  </div>
		  <div class="blockchain stage-error">
		  	<font color="red">[[error]]</font>
		  </div>
        </div>
  </div>
<p class="copyright">Powered By <a href="<?= $sezar_link ?>/" target="_blank">SezarCompany</a></p>
</div>
</body>
</html>
