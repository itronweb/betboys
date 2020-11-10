<div class="more-pad">
    <div class="cell">
        <div class="container">
            <div class="maincontent">
                {include file="partials/dashboard_menu.tpl"}
                <div class="col-lg-10 col-md-9 col-sm-12 col-xs-12 content">
                    <header class="LandingMatchShow">
                        <h1>حساب خود را شارژ کنید</h1>
                    </header>
                    {if isset($error)}
                        <p style="color: red">{$error}</p>
                    {/if}
                    <section class="topup-content">

                        <p class="description text-black">برای Deposit مبلغ مورد نظر خود را در فرم زیر وارد کنید و کلید پرداخت را بزنید.</p>
                        <div class="topup-form clearfix">
                            <div>
                                <form action="{site_url}payment/credit" method="post">
									 <div class="col-xs-12 centre">
                                    	<label class="col-lg-2 col-md-2 col-sm-12 col-xs-12 label" for="ptype">انتخاب نحوه پرداخت</label>
										<select name="ptype" class="col-lg-8 col-md-8 col-sm-12 col-xs-12 amountinput mt-15" id="ptype">
											<option class="method_type" value="0" data-method = "0">انتخاب نحوه پرداخت</option>
											{foreach $getway as $getway_value }
											<option class="method_type" value="{$getway_value['id']}-{$getway_value['paymethodid']}" data-method = "{$getway_value['paymethodid']}">
												{$getway_value['name']}
											</option>
											{/foreach}
										</select>
                                	</div>
									
						
									<div class="col-xs-12 centre amountinput amount_box hidden">
										<label class="col-lg-2 col-md-2  col-sm-12 col-xs-12 label" for="Amount">مبلغ به ریال</label>
										<input autocomplete="off" class="col-lg-8 col-md-8  col-sm-12 col-xs-12 input ltrinput centre" data-val="true" data-val-number="The field مبلغ به ریال must be a number." data-val-range="حداقل مبلغ افزایش موجودی ۱۰۰۰ TRX است." data-val-range-max="2147483647" data-val-range-min="1000" data-val-regex="مبلغ به TRX باید با فرمت درست وارد شود. " data-val-regex-pattern="^\d+$" data-val-required="وارد کردن مبلغ به TRX الزامی است." id="Amount" name="amount" type="text" value="">

										<span class="field-validation-valid error_message" data-valmsg-for="Amount" data-valmsg-replace="true"></span>
										<span class="error_message max_error"></span>
                                	</div>

									<div class="col-xs-12 centre amountinput kartbekart">
										<label class="col-lg-2 col-md-2  col-sm-12 col-xs-12 label" for="Amount">شماره کارت شما</label>
										<input autocomplete="off" class="col-lg-8 col-md-8  col-sm-12 col-xs-12 input ltrinput centre" data-val="true" data-val-number="The field مبلغ به ریال must be a number." data-val-range="حداقل مبلغ افزایش موجودی ۱۰۰۰ TRX است." data-val-range-max="2147483647" data-val-range-min="1000" data-val-regex="مبلغ به TRX باید با فرمت درست وارد شود. " data-val-regex-pattern="^\d+$" data-val-required="وارد کردن مبلغ به TRX الزامی است." id="customer_card" name="customer_card" type="text" value="">

										<span class="field-validation-valid error_message" data-valmsg-for="Amount" data-valmsg-replace="true"></span>
										<span class="error_message max_error"></span>
									</div>
									 <div class="col-xs-12 centre amountinput kartbekart">
										<label class="col-lg-2 col-md-2  col-sm-12 col-xs-12 label" for="Amount">کد پیگیری</label>
										<input autocomplete="off" class="col-lg-8 col-md-8  col-sm-12 col-xs-12 input ltrinput centre" id="pay_code" name="pay_code" type="text" value="">

										<span class="field-validation-valid error_message" data-valmsg-for="Amount" data-valmsg-replace="true"></span>
										<span class="error_message max_error"></span>
									</div>

									<div class="col-xs-12 centre">
                                        <input class="btn  floatright credit-btn" type="submit" value="پرداخت">
                                    </div>

									
									
									
                                </form>  
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
<!--

<script>
        $(document).ready(function () {
			$(".kartbekart").addClass('hidden');
            $("#ptype option").click(function (e) {
				var method = $(this).attr("data-method");
                if ( method == '3' ){
					$(".kartbekart").removeClass('hidden');
				}else{
					$(".kartbekart").addClass('hidden');
				}
            });
        });
    </script>
-->
	<script>
        $(document).ready(function () {
			$(".kartbekart").addClass('hidden');

            $('#ptype').change(function(){
                var method = $(this).children(":selected").attr("data-method");

				if ( method != '0' ){
                    $(".amount_box").removeClass('hidden');
                }else{
                    $(".amount_box").addClass('hidden');
                }
                if ( method == '3' ){
                    $(".kartbekart").removeClass('hidden');
                }else{
                    $(".kartbekart").addClass('hidden');
                }
            });
        });
</script>
