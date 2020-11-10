{add_js file='bundles/updates.js' part='footer'}

<div class="cell mainpadding">

    <!------------------------marquee start-------------------->
    <div class="cotainer">
        <div class="predict-div">
            <button class="btn predict-btn"><i class="fa fa-file-text-o"></i><div class="notification"></div></button>
            <button class="btn go-top-btn"> <i class="fa fa-chevron-up"></i></button>
        </div>
        <script>
            $(document).ready(function () {
                $(".predict-btn").click(function () {
                    $('html, body').animate({
                        scrollTop: $("#predict").offset().top
                    }, 1);
                });
                $(".go-top-btn").click(function () {
                    $('html, body').animate({
                        scrollTop: $("#top").offset().top
                    }, 1);
                });
            });
        </script>

        <!------------------------marquee end-------------------->
        <input id="page-name" value="{$pageName}" type="hidden">
        <input id="lastupdate" type="hidden" value="{$lastUpdated}">


        <!------------------------ predict buttom end -------------------->
        <div class="maincontent clearfix">

            <ul class="col-xs-12 nopadding odds inplay">
                <li class="col-lg-2 col-md-2 col-sm-12 col-xs-12 hide-on-sm ">
                    <ul class="side-sports animated">
                        <h3>ورزش ها</h3>

                       {foreach $sport_upcoming_type as $sport_type_value }
			<li class="has-children "  data-sportcat="{$sport_type_value['en']}">
				<a href="{site_url}bets/upComing/0/{$sport_type_value['en']}">
					<img class="" width="20px" height="20px" src="{site_url}/attachment/sport_logo/{$sport_type_value['logo']}" alt="{$sport_type_value['fa']}">
            		<label>{$sport_type_value['fa']}</label>
       			</a> 
			</li>
        {/foreach} 
                    </ul>
                </li>
                <li class="col-lg-7 col-md-7 col-sm-12 col-xs-12 nopadding">
                   {$image_url = "{site_url}attachment/preevent_sport/{$soccer_type}.png"}
                    
                    {$style = "background: url({$image_url}) no-repeat;background-size: 100% 100%;"}
                   
                    <div class="col-xs-12 nopadding play-box">
                        <div class="col-xs-12  play nopadding" style="{$style}" >
                          <div class="col-xs-12 play-reasult">
							{$home_alt = $matches->homeTeam->name}

							<div class="result-table">
								<P>{$matches->competition->name}</P>
                         	<table>
                          
                           {if $soccer_type == 'soccer'}
                            
                            
                                <thead>
                                    <tr>
                                        <th width="65%"> <span class="inplaytime" style="direction: rtl;color:white">' {$matches->minute}</span></th>
                                        <th width="5%">گل</th> 
                                        <th width="10%" >کارت زرد</th>
                                        <th width="10%">کارت قرمز</th>
                                        <th width="5%">کرنر</th>
                                        <th width="5%">پنالتی</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <span>{$matches->homeTeam->name}</span></td>
										<td>{$matches->home_score}</td>
										<td>{if isset($matches->homeTeam->stats->yellowcards)}
												{$matches->homeTeam->stats->yellowcards}
											{else}
												0
											{/if}
										</td>
										<td>{if isset($matches->homeTeam->stats->red_cards)}
												{$matches->homeTeam->stats->red_cards}
											{else}
												0
											{/if}
										</td>
										<td>{if isset($matches->homeTeam->stats->corners)}
												{$matches->homeTeam->stats->corners}
											{else}
												0
											{/if}
										</td>
										<td>{if $matches->home_score_penalties != null }
												{$matches->home_score_penalties}
											{else}
												0
										{/if}
                                   		</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <span>{$matches->awayTeam->name}</span> </td>
                                        <td>{$matches->away_score}</td>
										<td>{if isset($matches->awayTeam->stats->yellowcards)}
												{$matches->awayTeam->stats->yellowcards}
											{else}
												0
											{/if}
										</td>
										<td>{if isset($matches->awayTeam->stats->red_cards)}
												{$matches->awayTeam->stats->red_cards}
											{else}
												0
											{/if}
										</td>
										<td>{if isset($matches->awayTeam->stats->corners)}
												{$matches->awayTeam->stats->corners}
											{else}
												0
											{/if}
										</td>
										<td>{if $matches->away_score_penalties != null}
												{$matches->away_score_penalties}
											{else}
												0
											{/if}
										</td>
                                    </tr>
                                </tbody>
                        
                          {elseif $soccer_type=='tennis' OR $soccer_type=='volleyball' OR $soccer_type == 'basket'}
                             
                                <thead>
                                    <tr>
                                        <th width="65%"> <span class="inplaytime" style="direction: rtl;color:white">' {$matches->minute}</span></th>
                                        <th width="10%">ست اول</th> 
                                        <th width="10%" >ست دوم</th>
                                        <th width="10%">ست سوم</th>
                                        <th width="10%">ست چهارم</th>
                                        {if isset($matches->homeTeam->s5)}
                                        <th width="10%">ست پنجم</th>
                                        {/if}
                                        <th width="10%">نتیجه Openی</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <span>{$matches->homeTeam->name}</span></td>
										<td>{$matches->homeTeam->s1}</td>
										<td>{$matches->homeTeam->s2}</td>
										<td>{$matches->homeTeam->s3}</td>
										<td>{$matches->homeTeam->s4}</td>
										{if isset($matches->homeTeam->s5)}
										<td>{$matches->homeTeam->s5}</td>
										{/if}
										<td>{$matches->homeTeam->score}</td>
										
                                    </tr>
                                    <tr>
                                        <td>
                                            <span>{$matches->awayTeam->name}</span> </td>
                                        <td>{$matches->awayTeam->s1}</td>
										<td>{$matches->awayTeam->s2}</td>
										<td>{$matches->awayTeam->s3}</td>
										<td>{$matches->awayTeam->s4}</td>
										{if isset($matches->awayTeam->s5)}
										<td>{$matches->awayTeam->s5}</td>
										{/if}
										<td>{$matches->awayTeam->score}</td>
										
                                    </tr>
                                </tbody>
                                
                         {else if $soccer_type == 'baseball'}
                            <thead>
                                    <tr>
                                        <th width="65%"> <span class="inplaytime" style="direction: rtl;color:white">' {$matches->minute}</span></th>
                                        <th width="10%">ست اول</th> 
                                        <th width="10%" >ست دوم</th>
                                        <th width="10%">ست سوم</th>
                                        <th width="10%">ست چهارم</th>
                                        <th width="10%">ست پنجم</th>
                                        <th width="10%">نتیجه Openی</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <span>{$matches->homeTeam->name}</span></td>
										<td>{$matches->homeTeam->in1}</td>
										<td>{$matches->homeTeam->in2}</td>
										<td>{$matches->homeTeam->in3}</td>
										<td>{$matches->homeTeam->in4}</td>
										<td>{$matches->homeTeam->in5}</td>
										<td>{$matches->homeTeam->score}</td>
										
                                    </tr>
                                    <tr>
                                        <td>
                                            <span>{$matches->awayTeam->name}</span> </td>
                                        <td>{$matches->awayTeam->in1}</td>
										<td>{$matches->awayTeam->in2}</td>
										<td>{$matches->awayTeam->in3}</td>
										<td>{$matches->awayTeam->in4}</td>
										<td>{$matches->awayTeam->in5}</td>
										<td>{$matches->awayTeam->score}</td>
										
                                    </tr>
                                </tbody>
                                
						{else if $soccer_type == 'rugby'}
                            <thead>
                                    <tr>
                                        <th width="65%"> <span class="inplaytime" style="direction: rtl;color:white">' {$matches->minute}</span></th>
                                        <th width="10%">ست اول</th> 
                                        <th width="10%" >ست دوم</th>
                                        <th width="10%">ست سوم</th>
                                        <th width="10%">ست چهارم</th>
                                        <th width="10%">نتیجه Openی</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>
                                            <span>{$matches->homeTeam->name}</span></td>
										<td>{$matches->homeTeam->t1}</td>
										<td>{$matches->homeTeam->t2}</td>
										<td>{$matches->homeTeam->ot}</td>
										<td>{$matches->homeTeam->ot2}</td>
										<td>{$matches->homeTeam->score}</td>
										
                                    </tr>
                                    <tr>
                                        <td>
                                            <span>{$matches->awayTeam->name}</span> </td>
                                        <td>{$matches->awayTeam->t1}</td>
										<td>{$matches->awayTeam->t2}</td>
										<td>{$matches->awayTeam->ot}</td>
										<td>{$matches->awayTeam->ot2}</td>
										<td>{$matches->awayTeam->score}</td>
										
                                    </tr>
                                </tbody>
                                
						
                            
						 {/if}
                            </table>

                        </div>
						</div>
                             
                        </div>
                        
                    </div>
                   
                    <input type="hidden" class="host" value="{$matches->homeTeam->name|fa}">
                    <input type="hidden" class="guest" value="{$matches->awayTeam->name|fa}">
                    {foreach $odds as $key => $val}
                        {if $val->type eq 'Home/Away' OR $val->type eq '3Way Handicap'}
                            {continue}
                        {/if}
<table class="table inplaytable nohover" id="top" style="margin-top:3px;">
	<tr class="inplayheader">
		<th>
			<b>{$val->type|fa}</b>
		</th>
	</tr>
	<tr class="odddetails eventodd" data-eventid="{$matches->id}" data-marketid="">
		<td class="nopadding">
			<div data-eventid="{$matches->id}" data-marketid="" data-eventname="{$val->type}" class="eventodds">

				<span class="eventsuspended hidden">غیر فعال</span>

				{assign var=i value=0}
				{foreach $val->odds->data as $odd}
					{if (strpos($val->type,'Over/Under') !== false OR strpos($val->type,'Total') !== false)  AND $i % 2 eq 0}
					{else if strpos($val->type,'Over/Under') === false AND $i % 3 eq 0}
					{/if}
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 inplaybtn eventodd event-btn" data-runnerid="{$matches->id|con:'-': $matches->odds->data[0]->bookmaker_id:'-': {$val->type} :'-':{$odd->label}}" data-bet-sport="{$soccer_type}" data-pick="{$odd->label}" data-points="" data-prev_odd="{$odd->value}">
						<ul>
							{if strpos($val->type,'Over/Under') !== false OR strpos($val->type,'Total') !== false}
								<li class="col-sm-9  col-xs-6">
							{else}
								<li class="col-sm-9  col-xs-6">
							{/if}
								<b class="ellipsis">
							{if $val->type == '1x2'}
							
								{if $odd->label == 1}
									میزبان {$odd->total}
								{else if $odd->label == 2 }
									میهمان {$odd->total}
								{else}
									مساوی
								{/if}
								
							{else if strpos($val->type,'Half Time Correct Score') !== false}
								{$correct_score = explode('-',$odd->label)}
								{$correct_score[0]} - {$correct_score[1]}
							{else if strpos($val->type,'Correct Score') !== false}
								{$correct_score = explode(':',$odd->label)}
								{$correct_score[0]} : {$correct_score[1]}
							{else if strpos($val->type,'Final Score') !== false}
								{$correct_score = explode('-',$odd->label)}
								{$correct_score[0]} - {$correct_score[1]}
							{else if strpos($val->type,'Exact Goals Number') !== false}
								{$odd->label}
							{else}
								{if $odd->label == '1'}
									میزبان {$odd->total}
								{else if $odd->label == '2'}
									میهمان {$odd->total}
								{else}
									{if $odd->label == {$matches->homeTeam->name|con:'/':'Draw'}}
										میزبان/مساوی
									{else if $odd->label == {$matches->awayTeam->name|con:'/':'Draw'} }
										میهمان/مساوی
									{else if $odd->label == {'Draw'|con:'/':$matches->awayTeam->name} }
										مساوی/میهمان
									{else if $odd->label == {'Draw'|con:'/':$matches->homeTeam->name} }
										مساوی/میزبان
									{else if $odd->label == {$matches->awayTeam->name|con:'/':$matches->homeTeam->name} }
										میهمان/میزبان
									{else if $odd->label == {$matches->homeTeam->name|con:'/':$matches->awayTeam->name} }
										میزبان/میهمان

									{else if $odd->label == {$matches->homeTeam->name|con:'/':$matches->homeTeam->name} }
										میزبان/میزبان
									{else if $odd->label == {$matches->awayTeam->name|con:'/':$matches->awayTeam->name} }
										میهمان/میهمان
									{else if $odd->label == {'Draw/Draw'} }
										مساوی/مساوی		
									{else}
										{$odd->label|fa}
									{/if}
								{/if}
							{/if}
						</b>
					</li>


					<li class="col-sm-3 col-xs-6" >
						<i class="col-xs-6 nopadding"></i>

						<span class="col-xs-6 nopadding">
							{if (!isset($odd->suspend) OR $odd->suspend == 0) AND $matches->minute <= 80}
								{$odd->value}
							{else}
								00.00
							{/if}
						</span>

					</li>
			</ul>
				</div>
				{assign var=i value=$i+1}
				{if (strpos($val->type,'Over/Under') !== false OR strpos($val->type,'Total') !== false) AND $i % 2 eq 0 AND $i != 1}

				{else if (strpos($val->type,'Over/Under') === false AND strpos($val->type,'Total') === false) AND $i % 3 eq 0 AND $i != 1}

				{/if}
			{/foreach}
		</div>
	</td>
</tr>
</table>
		{/foreach}

                                                </li>
                                                <li class="col-lg-3 col-md-3 col-sm-12 col-xs-12 nopadding ">
                                                    <div class="col-lg-12 col-md-12 col-xs-12 pl-0">
											{if $animation_code != null }
												<div class="col-lg-11 col-lg-offset-1 col-md-12 col-xs-12 nopadding">
													<div class="animate-header">انیمیشن Openی</div>
													<div class="animate-play" >
														<iframe src="http://href.li/?https://cs.betradar.com/ls/widgets/?/hkjc/en/page/widgets_lmts#matchId={$animation_code}" width="100%" height="600px"  scrolling="no" seamless style="border: 0px; overflow: hidden;pointer-events: none;display: block;">
															</iframe>
<!--														<iframe src="{site_url}php/animation/matches_animation.php?id={$animation_code}"></iframe>-->
													</div>
												</div>
											{/if}
                                                        <div>
                                                            <table id="predict" class="livescore betslip" style="margin-top:3px;">
                                                                <tr><th>پیش بینی های من</th></tr>
                                                                <tr>
                                                                    <td>
                                                                        <div class="nobet">
                                                                            هنوز هیچ شرطی بسته نشده است. برای پیش بینی بروی ضریب مورد نظر خود کلیک کنید.
                                                                        </div>
                                                                        <div class="selectedodds">
                                                                            <div class="betlist">
                                                                            </div>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                            <div class="bettotal">

                                                                <table class="livescore multiple"></table>
                                                                <ul class="bettotal">
                                                                    <li>مجموع مبالغ <span class="totalstake">0</span></li>
                                                                    <li>برد احتمالی (TRX) <span class="totalwin">0</span></li>
                                                                </ul>
                                                                <div class="livescore">
                                                                    <div><button class="totobutton smallbutton placebet disabled">ثبت شرط</button></div>
                                                                    <div class="del"> <a class="deleteall" href="javascript:void(0)">حذف همه</a></div>
                                                                </div>
                                                            </div>
                                                            <div style="clear:both"></div>
                                                            <div class="alertbox alertbox2 hidden"></div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>


