           
<div>
    <div class="cell">
        <div class="container">
            <div class="maincontent clearfix">
                <div class="content">
                    <ul class="odds inplay">
                        <li>
                            <table class="table comingup nohover" style="margin-top:3px;">

                                <tr class="inplayheader">
                                    <th>
                                        <span class="match">
                                            <b>نزدیک ترین Openیهای امروز</b>
                                        </span>
                                    </th>
                                    <th style="width:44px"><b>1</b></th>
                                    <th style="width:44px"><b>X</b></th>
                                    <th style="width:44px"><b>2</b></th>
                                    <th style="width:25px"></th>
                                </tr>
                                {assign var=i value=0}
                                {foreach $matches as $match}
                                    <tr data-eventid="6512478" data-marketid="16640001" class="odddetails">
                                        <td>
                                            <span class="fa fa-clock-o timer"></span>

                                            <b class="host ellipsis">{$match[0]->name}<img class="team-logo" width="25" src="{$match[0]->logo}" /></b>
                                            <span class="vs">-</span>
                                            <b class="guest ellipsis"><img class="team-logo" width="25" src="{$match[1]->logo}" />{$match[1]->name}</b>

                                            <span class="inplaytime2">
                                                {$matchesToday->data[$i]->starting_time|persian_number}
                                            </span>


                                            <a class="has-tip fa fa-info-circle info2" title="لیگ برتر - قطر"></a>
                                        </td>
                                        <td style='padding:0' colspan='3'><div class='eventodds'>
                                                <span class="eventsuspended hidden">غیر فعال</span>
                                                <ul class='mlodds'>
                                                    <li data-runnerid="227735556" data-pick="{$match[0]->name}" data-points='' class="inplaybtn eventodd"><i></i><span>{$odds[0]->value}</span></li>
                                                    <li data-runnerid="227735559" data-pick="مساوی" data-points='' class="inplaybtn eventodd"><i></i><span>{$odds[2]->value}</span></li>
                                                    <li data-runnerid="227735560" data-pick="{$match[1]->name}" data-points='' class="inplaybtn eventodd"><i></i><span>{$odds[1]->value}</span></li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <a class="has-tip fa fa-plus-circle more" title="شرط های بیشتر" href="Sport/PreEvent/6512478.html"></a>
                                        </td>
                                    </tr>
                                    {assign var=i value=$i+1}
                                {/foreach}

                            </table>





                            <input id="lastupdate" type="hidden" value="636169751190261089" />
                            <input id="oddslastupdate" type="hidden" value="636169751190261089" />

                            <div class="floatleft" style="width:408px; max-height:206px;overflow: hidden">
                                <table class="table lastmatchresults nopointer floatleft" style="width:408px">
                                    <tr>
                                        <th colspan="3">
                                            آخرین نتایج فوتبال
                                        </th>
                                    </tr>
                                    <tr class="219312 FT">
                                        <td style="width:45%">
                                            Thanh Hoa FC                </td>
                                        <td style="width:10%">
                                            <a target="_blank" href="Soccer/MatchData/219312.html">
                                                ۲ - ۲                    </a>
                                        </td>
                                        <td style="width:45%">
                                            T&amp;T Hanoi                </td>
                                    </tr>
                                    <tr class="219308 FT">
                                        <td style="width:45%">
                                            Brazil Women&#39;s                </td>
                                        <td style="width:10%">
                                            <a target="_blank" href="Soccer/MatchData/219308.html">
                                                ۳ - ۱                    </a>
                                        </td>
                                        <td style="width:45%">
                                            Italy Women&#39;s                </td>
                                    </tr>
                                    <tr class="219307 FT">
                                        <td style="width:45%">
                                            Costa Rica Women&#39;s                </td>
                                        <td style="width:10%">
                                            <a target="_blank" href="Soccer/MatchData/219307.html">
                                                ۱ - ۳                    </a>
                                        </td>
                                        <td style="width:45%">
                                            Russia Women&#39;s                </td>
                                    </tr>
                                    <tr class="211797 FT">
                                        <td style="width:45%">
                                            وست برومویچ                </td>
                                        <td style="width:10%">
                                            <a target="_blank" href="Soccer/MatchData/211797.html">
                                                ۳ - ۱                    </a>
                                        </td>
                                        <td style="width:45%">
                                            سوانسی سیتی                </td>
                                    </tr>
                                    <tr class="211799 FT">
                                        <td style="width:45%">
                                            منچستر سیتی                </td>
                                        <td style="width:10%">
                                            <a target="_blank" href="Soccer/MatchData/211799.html">
                                                ۲ - ۰                    </a>
                                        </td>
                                        <td style="width:45%">
                                            واتفورد                </td>
                                    </tr>
                                    <tr class="211801 FT">
                                        <td style="width:45%">
                                            تاتنهام                </td>
                                        <td style="width:10%">
                                            <a target="_blank" href="Soccer/MatchData/211801.html">
                                                ۳ - ۰                    </a>
                                        </td>
                                        <td style="width:45%">
                                            هال سیتی                </td>
                                    </tr>

                                </table>
                            </div>




                        </li>
                        <li class="inplay-controller">
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