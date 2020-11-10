<div class="mainpadding">
    <div class="cell">
        <div class="container">
            <div class="maincontent">
                {if isset($show_league_table)}
                    <!--            		{var_dump($show_league_table)}-->
                    <table class="table table-responsive">
                        <tr>
                            <th>ردیف</th>
                            <th>نام تیم</th>
                            <th>امتیاز</th>
                            <th>برد</th>
                            <th>مساوی</th>
                            <th>باخت</th>
                            <th>گل زده</th>
                            <th>گل خورده</th>
                            <th>تفاضل گل</th>
                            <th>Status تیم</th>
                        </tr>
                        {foreach $show_league_table as $key=>$value }
                            <tr>
                                <td>{$value->position}</td>
                                <td>{$value->team_name|fa}</td>
                                <td>{$value->points}</td>
                                <td>{$value->overall->won}</td>
                                <td>{$value->overall->draw}</td>
                                <td>{$value->overall->lost}</td>
                                <td>{$value->overall->goals_scored}</td>
                                <td>{$value->overall->goals_against}</td>
                                <td>{$value->total->goal_difference}</td>
                                <td>{$value->status|fa}</td>
                            </tr>
                        {/foreach}
                    </table>
                {else if isset($all_league)}
                    <!--						{var_dump($all_league)}-->
                    <div class="content content-full">
                        <div style="margin-top:10px">
                            {foreach $all_league as $continent=>$key}
                                <div class="block-header league-title"><h2>{$continent}</h2></div>
                                <ul class="blocks">
                                    {foreach $key as $value}
                                        <li>
                                            <a href="{site_url}leagueTables/{$value['leagues_id']}">
                                                <div class="sprite-country sprite-{$value['flag']}"></div>
                                                <h5>{$value['league_name_fa']}</h5>
                                            </a>
                                        </li>
                                    {/foreach}
                                </ul>	
                        {/foreach}
                    </div>


                {/if}
            </div>
        </div>
    </div>
</div>