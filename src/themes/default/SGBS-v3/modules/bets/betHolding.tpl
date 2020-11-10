
   <div>
    <div class="cell more-pad">
        <div class="container">

            <div class="body_fraim transaction">
                <div class="responsive_tbl  maincontent clearfix">

                    <table class="table" style="table-layout: fixed;">
                        <thead>
                            <tr>
                                <th scope="col"> زمان </th>
                                <th scope="col"> نوع </th>
                                    <th scope="col"> ورزش </th>
                                <th scope="col"> میزبان </th>
                                <th scope="col"> میهمان </th>
                                <th scope="col"> نوع شرط </th>
                                <th scope="col"> انتخاب </th>
                                <th scope="col"> مبلغ (TRX)</th>
                                <th scope="col"> ضریب</th>
                            </tr>
                        </thead>
                        <tbody>
                            {if $betHolding != 0}
                            {foreach $betHolding as $key => $val}

                                {$id = $val['bets_id']}
                                <!--                               {var_dump($val['bets'])}-->
                                <tr onclick="window.location = base_url + 'bets/BetDetail/{$id}'">
                                    <td data-label="زمان">
                                        {jdate format='j F Y - H:i' date=$val['created']}
                                    </td>
                                    <td data-label="نوع" >
                                        {if $val['type'] == 1}
                                            تکی
                                        {else}
                                            میکس  {$val['type']} تایی
                                        {/if}
                                    </td>
									<td data-label="ورزش">
										{$sport_type[$val['soccer_type']]['fa']}
									</td>

                                    <td data-label="میزبان">
                                        <strong>
                                            {$val['home_team']|fa}
                                        </strong>
                                    </td>
                                    <td data-label="میهمان">
                                        <strong>
                                            {$val['away_team']|fa}
                                        </strong>
                                    </td>
                                    <td data-label="نوع شرط">{$val['bet_type']}</td>
                                    <td data-label="انتخاب">{$val['pick']|fa} </td>

                                    <td data-label="مبلغ (TRX)">
                                        {number_format($val['stake'])|persian_number}
                                    </td>
                                    <td data-label="ضریب">
                                        {$val['odd']|persian_number}
                                    </td>
                                
                                </tr>
                            {/foreach}
                            {/if}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

