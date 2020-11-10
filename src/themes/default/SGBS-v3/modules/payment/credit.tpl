<div class="more-pad">
    <div class="cell">
        <div class="container">
            <div class="maincontent">
                {include file="partials/dashboard_menu.tpl"}
                <div class="col-lg-10 col-md-9 col-sm-12 col-xs-12 content">
                    <header class="LandingMatchShow">
                        <h1>Deposit</h1>
                    </header>
                    {if isset($error)}
                        <p style="color: red">{$error}</p>
                    {/if}
                    <section class="topup-content">

                        <p class="description text-black">To pay the Amount of TRX, enter the form below and press the Deposit key.</p>
                        <div class="topup-form clearfix">
                            <div>
								<div style="direction: ltr; text-align: center">
									<div>Your TRON Wallet: <code id="publicAddressState"></code></div>
									<div>Your TRON Balance: <code id="publicAddressBalance"></code></div>
									<div>Contract verified  address: : <code><a href="" id="ContractAddreslbl"></a></code></div>

								</div>
								<form action="{site_url}payment/credit" method="post" id="credit_from">

								<div class="col-xs-12 centre">
                                    	<label class="col-lg-2 col-md-2 col-sm-12 col-xs-12 label" for="ptype"> Select payment method</label>
										<select name="ptype" class="col-lg-8 col-md-8 col-sm-12 col-xs-12 amountinput mt-15" id="ptype">
											<option class="method_type" value="0" data-method = "0">Select payment method</option>
											{foreach $getway as $getway_value }
											<option class="method_type" value="{$getway_value['id']}-{$getway_value['paymethodid']}" data-method = "{$getway_value['paymethodid']}">
												{$getway_value['name']}
											</option>
											{/foreach}
										</select>
                                	</div>
									
						
									{foreach $unit_amount as $key=>$unit }
									<div class="col-xs-12 centre amountinput amount_box hidden {$key} ">
										<label class="col-lg-2 col-md-2  col-sm-12 col-xs-12 label" for="Amount_{$key}" id="labelText_{$key}">Amont of   {$unit['name_fa']}</label>
										<input autocomplete="off" class="col-lg-8 col-md-8  col-sm-12 col-xs-12 input ltrinput centre base_amount" data-val="true" data-val-number="The field مبلغ به {$unit['name_fa']} must be a number." data-val-range=" Minimum amont to Drpost is   {$unit['name_fa']}{$unit['min_amount']} ." data-val-range-max="2147483647" data-val-range-min="{$unit['min_amount']}" data-val-regex="مبلغ به {$unit['name_fa']} باید با فرمت درست وارد شود. " data-val-regex-pattern="^\d+$" data-amount="amount_{$key}" data-change-amount="change_amount_{$key}" data-val-required="وارد کردن مبلغ به {$unit['name_fa']} الزامی است." id="Amount_{$key}" name="amount_{$key}" type="text" value="">
										<br>
									<br>
										<p ><span> Deposit</span> <span id="change_amount_{$key}" style="margin-right: 40px" ></span> </p>
										<input class='hidden' autocomplete="off" class="col-lg-8 col-md-8  col-sm-12 col-xs-12 input ltrinput centre" id="amount_{$key}" type="text" value="{$unit['amount']}">

										<span class="field-validation-valid error_message" data-valmsg-for="Amount" data-valmsg-replace="true"></span>
										<span class="error_message max_error" id="tronError{$key}"></span>
                                	</div>
									{/foreach}
									<input autocomplete="off" id="tron_id" name="tron_id" type="hidden" value="">
									<div class="col-xs-12 centre">
                                        <input class="btn  floatright credit-btn submitbtn" id="btnsubmit" type="submit" value="پرداخت">
                                    </div>
                                </form>  
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>

	<script>
        $(document).ready(function () {
			$(".submitbtn").addClass('hidden');
			$(".tronGate").addClass('hidden');
            $('#ptype').change(function(){
                var method = $(this).children(":selected").attr("data-method");
				if ( method != '0' ){
					$(".amount_box").addClass('hidden');
                    $("."+ method).removeClass('hidden');
                }else{
                    $(".amount_box").addClass('hidden');
                }
                if ( method == '624' ){
					$(".submitbtn").removeClass('hidden');
					$(".tronGate").removeClass('hidden');
                }else{
					$(".submitbtn").addClass('hidden');
                    $(".tronGate").addClass('hidden');
                }
            });
			
			$('.base_amount').keyup( function(){
				var amount_id 		= $(this).attr('data-amount'),
					change_amount_id= $(this).attr('data-change-amount'),
					change_amount 	= $('#'+ amount_id).val(),
					amount 			= $(this).val(),
					change_unit		= amount * change_amount;
				$('#'+ change_amount_id).html( change_unit + ' IRT ' );

			})
        });
</script>

	<script src="{assets_url}/Scripts/tron/core.js"></script>
	<script src="{assets_url}/Scripts/tron/init.js?v={time()}"></script>
