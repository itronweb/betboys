<?php
	include 'include.php';
	$invoice_id = sanitize_output($_GET['invoice_id']);
	
	$row_invoice    = @array_pop( dbRead( 'invoices',    ['invoice_id'=>$invoice_id], 1 ) );
	if ( empty($row_invoice['id']) ) echo 'Not Found';
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
      <div class="card-header card-header-divider text-center pt-4"> 
		  <div class="blockchain stage-begin">
			<img src="https://apirone.com/static/promo/bitcoin_logo_vector.svg" class="img-fluid" style="max-height: 42px;padding-bottom: 10px;" width="205" alt=""><br>
		  </div>
		  <div class="blockchain stage-ready" style="text-align:center"> 
			  <div class='qr-code'></div>
			  <div class="card-body px-0">
				<p class="text-center"><small><strong>Invoice <?php echo @$invoice_id ?> </strong></small></p>
				<p class="text-muted text-center">Amount Due :<br> <?= $row_invoice['value'] * SZROnlinePrice('btc') ?> USD (<?= $row_invoice['value'] ?> BTC) <br>
				<p class="text-muted text-center"><?= $row_invoice['value'] * SZROnlinePrice('btc') * SZROnlinePrice('Dollar') ?> <br>
				  Invoice Status : 
				<?php 
					switch ( $row_invoice['status'] ) {
						case 0: 
						 $status = 'Pending';
						 break;
						case 1:
						 $status = 'Paid Succesfully';
						 break;
						case 2:
						 $status = 'Confirmed';
						break;
					}
					echo $status;
				?><br>
				  <?php 
						if ( $row_invoice['status'] == 1 ) {
							?>
						<p>
						Waiting for Payment Confirmation: <a href="./order_status.php?invoice_id=<?php echo $invoice_id ?>">Refresh</a>
						</p>
					<?php } elseif ( $row_invoice['status'] == 2 ) { ?>
						<p>
						Thank You for your purchase<br>
						<?php
							$amount	=	$row_invoice['value'] * SZROnlinePrice('btc') * $row_settings['crona_price'];
							$row_user = dbGetRow('users', ['id'=>$row_invoice['user_id']]); 
							// Edit Invoice Status To +Paid 
							if ( $row_invoice['status']  ==  2 ) {
								dbWrite( 'users', ['id'=>$row_invoice['user_id']], ['cash'=>$row_user['cash'] + $row_invoice['amount'] ] );
								dbWrite( 'invoices', ['invoice_id'=>$invoice_id], ['status'=>3] );
								dbWrite( 'transactions', ['trans_id'=>$invoice_id], ['status'=>1] );
							}
						?>
						Your Account Balance is : <?= $row_user['cash'] ?>
						</p>
					<?php } elseif ( $row_invoice['status'] == 3 ) { ?>
						<p>This Invoice Is Expire</p>
					<?php } ?>
				</p>
			  </div>
		  </div>
        </div>
  </div>
<p class="copyright">Powered By <a href="<?= $sezar_link ?>/" target="_blank">SezarCompany</a></p>
</div>
</body>
</html>
