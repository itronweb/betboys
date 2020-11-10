<div class="more-pad">
    <div class="cell">
        <div class="container">
            <div class="maincontent">
                {include file="partials/dashboard_menu.tpl"}
                <div class="col-lg-10 col-md-9 col-sm-12 col-xs-12 content">
                    <header>
                        <h1 class="reg_head">Referrals</h1>
                    </header>
                    <div class="row back-color">
                        <p>
   Those who are interested in acting as a site representative and attracting new users to the site can use the site representation or referral plan.
                             To use this scheme you can use the registration link and HTML code
                             Which is intended to place site banners on other websites.
                             Any user who registers on the site by clicking on these links will be your subset and you will receive for his activity on the commission site.
                        </p>
                        <br>
                        <h2>  How to calculate the commission  </h2>
                        <p>
                            <span class="stepbubble">۱</span>
             Each representative receives 15% of the site profit from each subset as a commission.
                        </p>
                        <p>
                            <span class="stepbubble">۲</span>

The representative commission is paid to the representative for life.
                        </p>
                        <p>
                            <span class="stepbubble">۳</span>
The team reserves the right to change the commission percentage in the future.                        </p>
                        <br>
                        <p>
     Below you can find your unique registration link.
                             You can also use the available HTML code if you want to place banners on your website or blog.
                        </p>
                        <p>



                        </p>
                        <ul class="inviteoptions">

                            <li><a class="registrationlink sprite-link" href="javascript:void(0)">       Your referral link            </a></li>

                        </ul>
                        <div class="inviteoptions" style="display: block;">

                            <div class="registrationlink" style="display: block;">

                                <input type="text" readonly value="{site_url}users/register/{$user->id}">
                            </div>
                            <div class="htmlcode hidden" style="display: none;">
                                <p>
You can put the following HTML code in your blog or website.


</p>
                                <textarea readonly>&lt;a style="line-height: 0; font: 0; color: transparent; display: block; width: 728px; height: 90px; background: url({assets_url}/Images/Banners/leaderboardfa.png);" href="{site_url}users/register/{$user->id}"&gt;                 The first decentralized casino on  Tron blockchain  &lt;/a&gt;</textarea>
                                <img style="width:515px;margin-top:10px" src="{assets_url}/Images/Banners/leaderboardfa.png">

                                <textarea readonly style="margin-top: 10px">&lt;a style="line-height: 0; font: 0; color: transparent; display: block; width: 336px; height: 280px; background: url(http://landabet.com/demo/assets/default/bet2016/Images/Banners/LargeRectanglefa.gif);" href="{site_url}users/register/{$user->id}"&gt;  The first decentralized casino on  Tron blockchain&lt;/a&gt;</textarea>
                                <img style="width:336px;margin-top:10px" src="/Images/Banners/LargeRectanglefa.gif">

                            </div>
                        </div>
                        <br>
                        {if $sub_count}
                            <div class="report">
                                <div>
                                    <span class="report-title">Number of referral users</span>
                                    <span class="report-data">{$sub_count|persian_number}</span>
                                </div>
                                <div>
                                    <span class="report-title">Your total income</span>
                                    <span class="report-data">{$affSum|price_format}</span>
                                </div>
                            </div>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
    </div>