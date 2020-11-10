<div class="more-pad">
    <div class="cell">
        <div class="container">
            <div class="maincontent">
                {include file="partials/dashboard_menu.tpl"}
                <div class="col-lg-10 col-md-9 col-sm-12 col-xs-12 content">
                    <header>
                        <h1 class="reg_head">Account Summary</h1>
                    </header>
                    <div class="report">
                        <div class="col-lg-6 col-md-6 col-xs-12">
                            <div class="inner-card">
                                <span class="report-title">User ID</span>
                                <span class="report-data">{$user->id|persian_number}</span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12">
                            <div class="inner-card">
                                <span class="report-title">Balace </span>
                                <span class="report-data">{$user->cash|price_format}</span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12">
                            <div class="inner-card">
                                <span class="report-title">Total bets</span>
                                <span class="report-data">{$totalBetCount|persian_number} ({$totalStake|price_format})</span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12">
                            <div class="inner-card">
                                <span class="report-title">Total Prizes</span>
                                <span class="report-data">{$giftCount|persian_number} ({$totalGift|price_format})</span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12">
                            <div class="inner-card">
                                <span class="report-title">Total deposit</span>
                                <span class="report-data">{$creditSum|price_format}</span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-xs-12">
                            <div class="inner-card">
                                <span class="report-title">Total withdraw</span>
                                <span class="report-data">{$withdrawSum|price_format}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
