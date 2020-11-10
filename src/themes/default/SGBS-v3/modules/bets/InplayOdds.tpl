<div>
    <div class="cell mainpadding">
        <div class="container">

            <div class="content">
                <header class="tabheader">
                    <ul class="tab">
                        <li class="inplay">
                            <a href="{site_url}bets/inplayBet">پیش بینی زنده</a>
                        </li>
                        <li class="upcoming">
                            <a href="{site_url}bets/upComing">پیش بینی پیش از Openی</a>
                        </li>
                        <li class="history">
                            <a href="{site_url}bets/myrecords">سابقه پیش بینی ها</a>
                        </li>
                    </ul>
                </header>
            </div>
            <div class="maincontent clearfix" eventid="{$matches->id}">
                <div class="content">
                    <ul class="odds inplay">
                        <li>
                            <input type="hidden" class="host" value="{$matches->teams->home->name|fa}">
                            <input type="hidden" class="guest" value="{$matches->teams->away->name|fa}">
                            <div id="{$match->id}"
                            <div class="eventscore" style="margin-top:3px;">
                                <table class="">
                                    <tbody>
                                        <tr>
                                            <th>
                                                {$matches->league}
                                            </th>
                                            <th class="htScore text-center">نتیجه</th>
                                            <th></th>
                                        </tr>
                                        <tr>
                                            {assign var=scores value=explode('-',$matches->result)}
                                            <td class="host">{$matches->teams->home->name|fa}</td>
                                            <td class="hScore">{if isset($scores[0])} {$scores[0]|persian_number}{else}۰{/if}</td>
                                            <td style="color:#0c0;text-align:center;background-color:rgba(0,0,0,0.25);font-size:16px; width:170px" class="borderleft" rowspan="2">

                                                {assign var=time value=$matches->time + $matches->break_point}
                                                <b class="time inplaytime">{$time|persian_number}:۰۰</b>
                                                {if $matches->break_point > 0 AND $matches->time > $matches->break_point AND $matches->state == 1015 }
                                                    <input class="eninplaytime" type="hidden" value="HT">
                                                {else if $matches->break_point > 0}
                                                    <input class="eninplaytime" type="hidden" value="{$time}:{$matches->id % 100}">
                                                {else}
                                                    <input class="eninplaytime" type="hidden" value="{$time}:{$matches->id % 100}">
                                                {/if}

                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="guest" style="border-top:1px solid #454545">
                                                {$matches->teams->away->name|fa}
                                            </td>
                                            <td class="aScore">
                                                {if isset($scores[1])} {$scores[1]|persian_number}{else}۰{/if}
                                            </td>
                                        </tr>
                                    </tbody></table>
                            </div>
                            {foreach $odds as $key => $val}
                                {if $val->name eq '2-Way Corners' OR $val->name eq 'Alternative Match Goals' OR $val->name eq 'First Half Goals' OR $val->name eq '1st Half Asian Handicap (0-0)' OR $val->name eq 'Last Team to Score' OR  $val->name|strpos:'Handicap' OR $val->name|strpos:'Corner'  !== false OR $val->name|strpos:'Goal Line' !== false OR $val->name|strpos:'Clean Sheet' OR $val->name eq '1st Goalscorer' OR $val->name eq 'To Score at Any Time' OR $val->name eq 'To Score 2 or More' OR $val->name eq 'To Score a Hat Trick' OR $val->name|strpos:'mins' !== false OR $val->name|strpos:'Ten Minute' !== false OR $val->name|strpos:'Exact Goals' !== false OR $val->id eq 50364 OR $val->id eq 50356 OR $val->id eq 50342 OR $val->id eq 50166  OR $val->id eq 10566  OR $val->id eq 50396 OR $val->id eq 50180 OR $val->id eq 10148 OR $val->id eq 50246 OR $val->id eq 50162 OR $val->id eq 50180 OR $val->id eq 50181 OR $val->name eq 'Draw No Bet' OR $val->id eq 50365 OR $val->id eq 50366 OR $val->id eq 50390 OR $val->id eq 50391 OR $val->id eq 10565 OR $val->name eq 'Alternative 1st Half Asian (0-0)' OR $val->name|strpos:'Asian' !== false OR $val->name|strpos:'Alternative' !== false OR $val->name eq '1st Goal' OR $val->name|strpos:'Ten' !== false OR $val->name|strpos:'End' !== false OR $val->name|strpos:'5th Goal' !== false OR $val->name|strpos:'1st Half' OR $val->id eq 1778OR OR $val->name eq 'Game Won In Extra Time' OR $val->name eq 'Game Won After Penalties' OR $val->name == 'Method of Qualification' OR $val->name == 'To Qualify' OR $val->name eq '4th Goal' OR $val->name eq '3rd Goal' OR $val->name eq '5th Goal' OR $val->name eq '6th Goal' OR $val->name eq '2nd Goal' OR $val->name eq 'Time of 3rd Goal' OR $val->name eq 'Time of 2nd Goal' OR $val->name eq 'Time of 1st Goal' OR $val->name|strpos:'Time of' !== false OR $val->name eq 'Half Time/Full Time' OR $val->name eq 'Half Time Correct Score' OR $val->name eq '1x2 1st Half' OR $val->name eq 'Half Time Correct Score' OR  $val->name eq 'Number of Cards'}
                                    {continue}
                                {/if}
                                
<table class="table inplaytable nohover" style="margin-top:3px;">
<tr class="inplayheader">
	<th>
		<b>{$val->name|fa}</b>
	</th>
</tr>
<tr class="odddetails eventodd">

	<td style="padding:0">
		<div data-eventid="{$matches->id}" data-marketid="" class="eventodds" data-name="{$val->name}">
			<span class="eventsuspended hidden">غیر فعال</span>

			{assign var=i value=0}
			{foreach $val->type as $odd}
				{if $i % 3 eq 0}
					<ul>
					{/if}

					<li style="width:176px;box-sizing: border-box;">
						<b class="ellipsis">
							{if $val->name == '1x2'}
								{if $odd->type == 1}
									{$matches->teams->home->name|fa}
								{else if $odd->type == 2 }
									{$matches->teams->away->name|fa}
								{else}
									مساوی
								{/if}
							{else}
								{if isset($odd->type)}
									{if $odd->type == '1'}
										میزبان
									{else if $odd->type == '2'}
										میهمان
									{else}
										{if $odd->type == {$matches->teams->home->name|con:'/':'Draw'}}
											میزبان/مساوی
										{else if $odd->type == {$matches->teams->away->name|con:'/':'Draw'} }
											میهمان/مساوی
										{else if $odd->type == {'Draw'|con:'/':$matches->teams->away->name} }
											مساوی/میهمان
										{else if $odd->type == {'Draw'|con:'/':$matches->teams->home->name} }
											مساوی/میزبان
										{else if $odd->type == {$matches->teams->away->name|con:'/':$matches->teams->home->name} }
											میهمان/میزبان
										{else if $odd->type == {$matches->teams->home->name|con:'/':$matches->teams->away->name} }
											میزبان/میهمان

										{else if $odd->type == {$matches->teams->home->name|con:'/':$matches->teams->home->name} }
											میزبان/میزبان
										{else if $odd->type == {$matches->teams->away->name|con:'/':$matches->teams->away->name} }
											میهمان/میهمان
										{else if $odd->type == {'Draw/Draw'} }
											مساوی/مساوی
										{else}
										{$odd->type|fa}
										{/if}
									{/if}
								{else}

								{$odd->name|fa}{if isset($odd->handicap)} {$odd->handicap}{/if}
								{/if}
							{/if}
						</b>
					</li>
					<li data-runnerid="{$matches->id|con:'-404-': {$val->name} :'-':{$odd->name}}" data-pick="{$odd->name}{if isset($odd->handicap)} {$odd->handicap}{/if}" data-points="" class="inplaybtn eventodd">
						<i></i>
						<span>
							{$odd->odd}
						</span>
					</li>

					{assign var=i value=$i+1}
					{if $i % 3 eq 0 AND $i != 1}
				</ul>
				{/if}
			{/foreach}
		</div>
	</td>
</tr>
</table>
	{/foreach}
                                                        </li>
                                                        <li>
                                                            <div class="margin-left-3px">
                                                                <div>
                                                                    <table class="livescore betslip" style="margin-top:3px;">
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
                                                                    <div class="bettotal hidden">

                                                                        <table class="livescore multiple"></table>
                                                                        <ul class="bettotal">
                                                                            <li>مجموع مبالغ <span class="totalstake">0</span></li>
                                                                            <li>برد احتمالی (TRX) <span class="totalwin">0</span></li>
                                                                        </ul>
                                                                        <table class="livescore">
                                                                            <tr>
                                                                                <td><a class="deleteall" href="javascript:void(0)">حذف همه</a></td>
                                                                                <td><button class="totobutton smallbutton placebet disabled">ثبت شرط</button></td>
                                                                            </tr>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                                <div class="alertbox alertbox2 hidden"></div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {literal}
                                    <script>

                                        setInterval(function() {
                                            updateTimers();
                                        }, 1000);
                                    </script>
                                {/literal}