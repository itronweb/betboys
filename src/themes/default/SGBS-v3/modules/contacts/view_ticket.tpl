<div>
<div class="cell more-pad">
  <div class="container">
    <div class="maincontent clearfix">
      <div class="content content-full text-black">
        <header class="clearfix">
          <h1 class="mark-side">Support</h1>
          <div class="row">
            <ul class="p10">
              <li> <b>Ticket ID :</b> TK-{$Ticket->id} </li>
              <li> <b>Ticket title :</b> {$Ticket->subject} </li>
              <li> <b>زمان ثبت :</b> {jdate format='j F Y - H:i' date=$Ticket->created_at} </li>
            </ul>
          </div>
        </header>
        <section class="formbox row_100 clearfix">
                        <div class="col-xs-12 ticketdetails row">
                                {foreach $Ticket->replies as $val}
                                    <div class="row mt10">
										 {if $val.user_id == $logged_in_user_id}
                                        <div class="col-md-2 col-xs-4">
                                           
                                                <img width="100" style="border-radius: 45%;padding-bottom:5px;margin-left: 15px;float:right;" src="{$Insta}" data="{assets_url}/images/user-white.png">
                                                <ul class="ticket_reply">
													<li>{$nameusr}</li>
													<li>{jdate format='j F Y - H:i' date=$val->created_at}</li>
												</ul>
                                            
                                        </div>
										{/if}
                                        <div class="col-md-10 col-xs-8">
                                            <div class="{if $val.user_id == $logged_in_user_id }bubble{else}bubblereply{/if}">
                                                {$val->content}
                                            </div>

                                        </div>
                                     {if $val.user_id != $logged_in_user_id}
                                        <div class="col-md-2 col-xs-4">
                                                <img width="100" style="border-radius: 45%;padding-bottom:5px;float:left;" src="{assets_url}/images/opr.png">
												<ul class="ticket_reply">
													<li>Support</li>
													<li>{jdate format='j F Y - H:i' date=$val->created_at}</li>
												</ul>
                                        </div>
										  {/if}
							</div>
                                {/foreach}
			 </div>              
       
          <form action="{$action}" method="post">
            <div class="col-xs-12 support_form createform">
              <div class="halfwidth">
                <div class="wrapsignupinput">
                  <textarea class="input textarea" cols="20" data-val="true" data-val-required="وارد کردن متن تیکت الزامی است." id="Message" name="content" placeholder="متن تیکت" rows="2"></textarea>
                </div>
                <div class="mt10">
                  <input class="btn btn-lg btn-yellow" type="submit" value="Send">
                </div>
              </div>
            </div>
          </form>
          </section>
          <div class="row">
            <p> Userان گرامی توجه داشته باشند، برای هر مورد یک تیکت ایجاد نمایید و تا حل شدن کامل مشکل، تیکت مربوطه را ادامه دهید و جهت تسریع در رفع مشکلات Userی لطفا از ایجاد تیکت های جدید و متنوع اجتناب فرمایید. </p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
