<div class="more-pad">
    <div class="cell" >
        <div class="container">
            <div class="body_fraim">

                <div class="col-xs-12 nopadding tab-div">
                    <ul class="tab_switch_block tabSwitch">
                        <li class="inplay">
                            <a href="{site_url}bets/inplayBet">پیش بینی زنده</a>
                        </li>
                        <li class="upcoming">
                            <a href="{site_url}bets/upComing">Openی های امروز</a>
                        </li>
                        {*                        <li class="upcoming ">
                        <a href="{site_url}bets/upComing/1">Openی های فردا</a>
                        </li>
                        <li class="upcoming ">
                        <a href="{site_url}bets/upComing/2">Openی های دو روز آینده</a>
                        </li>*}
                        <li class=" active">
                            <a  href="{site_url}bets/myrecords">سابقه پیش بینی ها</a>
                        </li>
                    </ul>
                </div>

                <div class="responsive_tbl star-checkbox maincontent clearfix ">
                
                        <table class="table" >
                            <caption>شرط های من</caption>
                            <thead>
                                <tr>
                                    <th scope="col"> زمان </th>
                                    <th scope="col"> نوع </th>
                                    <th scope="col"> ورزش </th>
                                    <th scope="col"> میزبان </th>
                                    <th scope="col"> میهمان </th>
                                    <th scope="col"> نوع شرط </th>
                                    <th scope="col"> انتخاب </th>
                                    <th scope="col">نتیجه </th>
                                    <th scope="col"> مبلغ (TRX)</th>
                                    <th scope="col"> ضریب</th>
                                    <th scope="col" width="11%">مبلغ برد (TRX) </th>
                                    <th scope="col"> جزییات بیشتر</th>
                                </tr>
                            </thead>
                            <tbody>
    						{if !empty($myBet)}
                                 {foreach $myBet as $values}
                                  {$length = sizeof($values) }
								
                                   {foreach $values as $key=> $val }
										{if $key != $length-1}
											{$class_tr = 'mix-game'}
										{else}
											{$class_tr = ''}
										{/if}
                                   
											<tr class= "{$class_tr}">

												<td data-label="زمان">
													{jdate format='j F Y - H:i' date=$val['created']}
												</td>
												<td data-label="نوع">
													{if $val['type'] == 1}
														تکی
													{else}
														میکس  {$val['type']} تایی
													{/if}
												</td>
												<td data-label="ورزش">
													{$sport_type[$val['soccer_type']]['fa']}
												</td>


												<td data-label="شرط">
													<span >
														{$val['home_team']|fa}
													</span>
												</td>
												<td>
													<span>
														{$val['away_team']|fa}
													</span>
												</td>
												<td>
													{$val['bet_type']}
												</td>
												<td data-label="انتخاب">{$val['pick']|fa} </td>
												<td data-label="نتیجه" style=" direction: rtl;">
													<span style="width:100%;text-align:center" class="bold">{$val['home_score_ft']|persian_number} - {$val['away_score_ft']|persian_number}</span>
												</td>
												<td data-label="مبلغ (TRX)">
													{number_format($val['stake'])|persian_number}
												</td>
												<td data-label="ضریب">
													{$val['effective_odd']|persian_number}
												</td>
												<td data-label="مبلغ برد (TRX)" class="prizeTD">
													<span style="color:#FECE01">
														<b>

															{if $val['result_status'] eq 0}
																مشخص نشده
															{else if $val['result_status'] eq 1 }
																{assign var=winning value=$val['stake'] * $val['odd']} 
																<span style="color:green">  {number_format($winning)|persian_number} (برد)</span>
															{else if $val['result_status'] eq 2}
																<span style="color:red">{0|persian_number} (باخت)</span> 
															{else if $val['result_status'] eq 3}
																<span >{$val['stake']|persian_number}</span>
															{else if $val['result_status'] eq 4}
																{assign var=winning value=($val['stake'] * ((($val['odd']-1)/2)+1))}
																<span style="color:green">
																{number_format($winning)|persian_number} (نیم برد) </span>
															{else if $val['result_status'] eq 5}
																<span style="color:red">{$val['stake']/2} (نیم باخت)</span>
															{/if}
														</b>
													</span>
												</td>
												{if $key == 0 }
												<td rowspan="{$values[0]['type']}" class="link" data-label="جزییات بیشتر" onclick="window.location = base_url + 'bets/BetDetail/{$val['bets_id']}'">بیشتر</td>
												{/if}

											</tr>
                                     	{/foreach}
                           		 {/foreach}

								{else}
							<tr>
								<td colspan="12">
									محتوایی جهت نمایش وجود ندارد.
								</td> 
							</tr>
                    {/if}
								
                            </tbody>
                        </table>
					
						

                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            $('ul.tabSwitch li').click(function () {
                $('ul.tabSwitch  li.active').removeClass('active');
                $(this).addClass('active');
            });
        });
    </script>