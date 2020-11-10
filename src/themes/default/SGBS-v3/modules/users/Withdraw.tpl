<div>
    <div class="cell more-pad">
        <div class="container">
            <div class="maincontent">
                {include file="partials/dashboard_menu.tpl"}
                <div class="content col-lg-10 col-md-9 col-sm-12 col-xs-12">
                    <header>
                        <h1 class="reg_head">&#1583;&#1585;&#1740;&#1575;&#1601;&#1578; &#1580;&#1575;&#1740;&#1586;&#1607;</h1>
                    </header>
                    <div class="row topup-form">

                        <div class="col-xs-12 clearfix withdraw-top">
                            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <ul class="text-black">
                                    <li>
                                        &#1581;&#1583;&#1575;&#1602;&#1604; &#1605;&#1576;&#1604;&#1594; &#1602;&#1575;&#1576;&#1604; &#1576;&#1585;&#1583;&#1575;&#1588;&#1578; 50000 &#1578;&#1608;&#1605;&#1575;&#1606; &#1605;&#1740; &#1576;&#1575;&#1588;&#1583;.
                                    </li>
                                    <li>
                                     Prizes are deposited in the form of a smart system (TRX).<br>
                                    </li>
                                    <li>
                                        &#1662;&#1585; &#1705;&#1585;&#1583;&#1606; &#1605;&#1608;&#1575;&#1585;&#1583; &#1587;&#1578;&#1575;&#1585;&#1607; &#1583;&#1575;&#1585; &#1575;&#1604;&#1586;&#1575;&#1605;&#1740; &#1575;&#1587;&#1578;
                                    </li>
                                </ul>
                            </div>

                            <div class="report col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12 withdraw-price">
                                    <span class="report-title">&#1605;&#1576;&#1604;&#1594; &#1602;&#1575;&#1576;&#1604; &#1576;&#1585;&#1583;&#1575;&#1588;&#1578;</span>
                                    <span class="report-data">{$cash|price_format}</span>
                                </div>
                            </div>
                        </div>
                        <div class="clearboth"></div>
                        <section class=" topup-content">
                            <div class="topup-form" style="margin-bottom:10px;">
                                <section class="sitebox">
                                    <div class="btn"></div>
                                    <form action="{$action}" method="post">
                                        <div class="siteform">
                                            <div class="col-xs-12">
                                                <label class="label col-md-3 col-xs-12" for="Amount">&#1605;&#1576;&#1604;&#1594; &#1576;&#1607; &#1578;&#1608;&#1605;&#1575;&#1606; *</label>
                                                <input class="input col-md-9 col-xs-12 ltrinput centre" data-val="true" data-val-number="The field &#1605;&#1576;&#1604;&#1594; &#1576;&#1607; &#1578;&#1608;&#1605;&#1575;&#1606; must be a number." data-val-range="You have only 1340 available." data-val-range-max="1340" data-val-range-min="50000" data-val-regex="&#1605;&#1576;&#1604;&#1594; &#1576;&#1607; &#1578;&#1608;&#1605;&#1575;&#1606; &#1576;&#1575;&#1740;&#1583; &#1576;&#1575; &#1601;&#1585;&#1605;&#1578; &#1583;&#1585;&#1587;&#1578; &#1608;&#1575;&#1585;&#1583; &#1588;&#1608;&#1583;. " data-val-regex-pattern="^\d+$" data-val-required="&#1608;&#1575;&#1585;&#1583; &#1705;&#1585;&#1583;&#1606; &#1605;&#1576;&#1604;&#1594; &#1576;&#1607; &#1578;&#1608;&#1605;&#1575;&#1606; &#1575;&#1604;&#1586;&#1575;&#1605;&#1740; &#1575;&#1587;&#1578;." id="Amount" name="amount" value="" type="text">
                                                <p><span></span> <span id="change_amount_tron" /></span> </p>
                                                <span class="col-xs-12 field-validation-valid error_message" data-valmsg-for="Amount" data-valmsg-replace="true">{form_error('amount')}</span>
                                                <span class="col-xs-12 error_message max_error"></span>
                                            </div>
                                            {*<div class="col-xs-12" >
                                                <label class="label col-md-3 col-xs-12" for="AccountHolder">&#1606;&#1575;&#1605; &#1589;&#1575;&#1581;&#1576; &#1581;&#1587;&#1575;&#1576;*</label>
                                                <input class="input col-md-9 col-xs-12" data-val="true" value="-" data-val-required="&#1608;&#1575;&#1585;&#1583; &#1705;&#1585;&#1583;&#1606; &#1606;&#1575;&#1605; &#1589;&#1575;&#1581;&#1576; &#1581;&#1587;&#1575;&#1576; &#1575;&#1604;&#1586;&#1575;&#1605;&#1740; &#1575;&#1587;&#1578;." id="AccountHolder" name="account_holder" value="" type="text">
                                                <span class="col-xs-12 field-validation-valid error_message" data-valmsg-for="AccountHolder" data-valmsg-replace="true">{form_error('account_holder')}</span>
                                            </div>

                                            <div class="col-xs-12" >
                                                <label class="label col-md-3 col-xs-12" for="BankName">&#1576;&#1575;&#1606;&#1705;*</label>
                                                <input class="input col-md-9 col-xs-12 ltrinput centre" value="-" data-val="true" data-val-required="&#1608;&#1575;&#1585;&#1583; &#1705;&#1585;&#1583;&#1606; &#1576;&#1575;&#1606;&#1705; &#1575;&#1604;&#1586;&#1575;&#1605;&#1740; &#1575;&#1587;&#1578;." id="BankName" name="bank_name" value="" type="text">
                                                <span class="col-xs-12 field-validation-valid error_message" data-valmsg-for="BankName" data-valmsg-replace="true">{form_error('bank_name')}</span>
                                            </div>
                                            <div  class="col-xs-12" >
                                                <label class="label col-md-3 col-xs-12" for="AccountNumber">شماره حساب*</label>
                                                <input  class="input col-md-9 col-xs-12 ltrinput centre" id="AccountNumber" name="account_number" value="" type="text">
                                                <span class="col-xs-12 field-validation-valid error_message" data-valmsg-for="AccountNumber" data-valmsg-replace="true">{form_error('account_number')}</span>
                                            </div>

                                            <div class="col-xs-12" >
                                                <label class="label col-md-3 col-xs-12" for="CardNo">&#1588;&#1605;&#1575;&#1585;&#1607; &#1705;&#1575;&#1585;&#1578;*</label>
                                                <input class="input col-md-9 col-xs-12 ltrinput centre" value="-" data-val="true" data-val-required="&#1608;&#1575;&#1585;&#1583; &#1705;&#1585;&#1583;&#1606; &#1588;&#1605;&#1575;&#1585;&#1607; &#1705;&#1575;&#1585;&#1578; &#1575;&#1604;&#1586;&#1575;&#1605;&#1740; &#1575;&#1587;&#1578;." id="CardNo" name="card_no" value="" type="text">
                                                <span class="col-xs-12 field-validation-valid error_message" data-valmsg-for="CardNo" data-valmsg-replace="true">{form_error('card_no')}</span>
                                            </div>
                                            <div class="col-xs-12">
                                                <label class="label col-md-3 col-xs-12" for="Sheba">شماره شبا</label>
                                                <input class="input col-md-9 col-xs-12 ltrinput centre" data-val="true" data-val-required="&#1608;&#1575;&#1585;&#1583; &#1705;&#1585;&#1583;&#1606; &#1588;&#1605;&#1575;&#1585;&#1607; &#1588;&#1576;&#1575; &#1575;&#1604;&#1586;&#1575;&#1605;&#1740; &#1575;&#1587;&#1578;." id="Sheba" name="sheba" value="" type="text">
                                                <span class="col-xs-12 field-validation-valid error_message" data-valmsg-for="Sheba" data-valmsg-replace="true">{form_error('sheba')}</span>
                                            </div>
                                            <div class="col-xs-12" >
                                                <label class="label col-md-3 col-xs-12" for='webmoney'>شماره حساب پرفکت مانی  </label>
                                                <input class="input col-md-9 col-xs-12  ltrinput centre" data-val="true" data-val-required="&#1608;&#1575;&#1585;&#1583; &#1705;&#1585;&#1583;&#1606; &#1588;&#1605;&#1575;&#1585;&#1607; &#1588;&#1576;&#1575; &#1575;&#1604;&#1586;&#1575;&#1605;&#1740; &#1575;&#1587;&#1578;." id="webmoney" name="webmoney" value="" type="text">
                                                <span class="col-xs-12 field-validation-valid error_message" data-valmsg-for="Sheba" data-valmsg-replace="true">{form_error('webmoney')}</span>
                                            </div>*}
                                            <div class="col-xs-12" >
                                                <label class="label col-md-3 col-xs-12" for='tron_address'>Address Wallet</label>
                                                <input class="input col-md-9 col-xs-12  ltrinput centre" id="tron_address" name="tron_address" value="" type="text">
                                            </div>
                                            <div class="withdraw-btn">
                                                <input class="btn btn-green btn-lg floatright" value="&#1583;&#1585;&#1582;&#1608;&#1575;&#1587;&#1578;" type="submit" name="submit_withdraw">
                                            </div>
                                        </div>
                                    </form>                           
                                </section>
                            </div>
                        </section>

                        <section class="formbox row_100 clearfix">
                            <div class="support_messages" style="display: block;">
                                <table class="leaguetable support">
                                    <tbody>
                                        <tr>
                                            <th>شناسه درخواست</th>   
											<th>تاریخ درخواست</th>
                                            <th>مبلغ درخواستی</th>
                                            <th>Status</th>
                                            <th>عملیات</th>
                                        </tr>
                                        {foreach $withdrawList as $val}
                                            <tr>
                                                <td>    
                                                    {$val->id}
                                                </td>
                                                <td>
                                                    <time datetime="">
                                                        {jdate format='j F Y - h:i a' date=$val->created_at}
                                                    </time>
                                                </td>
                                                <td>
                                                    {$val->amount|price_format}
                                                </td>
                                                <td>
                                                    {if $val->status eq 0}
                                                         <span class="text-info">  
															 لغو شده از طرف مدیر
														 </span>
                                                    {else if $val->status eq 1}
                                                         <span class="text-info"> 
															 پرداخت شد
														 </span>
                                                    {else if $val->status eq 2}
                                                       <span class="text-info">  
														   درحال بررسی
													   </span>
                                                    {else if $val->status eq 3}
                                                       <span class="text-info"> 
														    لغو شده از طرف User
														</span>
                                                    {/if}
                                                </td>
												
                                                <td>
													{if $val->status eq 2}
                                                    <a href="{site_url}/users/Withdraw/{$val->id}" class="btn btn-danger text-white ">
														 لغو درخواست از طرف User
													</a>
													{/if}
                                                </td>
                                            </tr>
                                        {/foreach}
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#Amount').keyup( function(){

                var change_amount 	= {$ratio_tron},
                    amount 			= $(this).val(),
                    change_unit		= amount / change_amount;

//				$('#'+ change_amount_id).val( change_unit );
                $('#change_amount_tron').html( change_unit + ' ترون ' );



            })
        });
    </script>