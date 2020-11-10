</div>
</section>
	<div class="mobile mobile-footer-bar">
		<a href="{site_url}" class="sport active">Home</a>

		<a href="{site_url}bets/casino" class="casino-m ">Decentralized online casino</a>
		<a href="{site_url}dashboard" class="account ">Profile</a>
	</div>
<footer class="col-lg-12 col-md-12 col-xs-12 footertop clearfix">
    <div class="col-md-4 col-sm-4 col-xs-12 footer_right">
        <h3 class="footer_head">General terms and conditions</h3>
        <ul class="gen_hel">
            {foreach $footer_right as $right}
                <li><a href="{site_url}{$right->target}">{$right->title}</a></li>
                {/foreach}
        </ul>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12 footer_center">
        <h3 class="footer_head">Links</h3>
        <ul class="gen_hel">
            {foreach $footer_mid as $middle}
                <li><a href="{$middle->target}">{$middle->title}</a></li>

            {/foreach}
        </ul>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12 footer_left">
        <h3 class="footer_head">Social media    </h3>
        <ul class="social" style="margin:auto !important;">
            <!--            <li class="fac"><a href="# " target="_blank"></a></li>-->
            <li class="ins"><a href="https://instagram.com/{setting name='instagram'}" target="_blank"></a></li>
            <li class="tel"><a href="https://t.me/{setting name='telegram'}" target="_blank"></a></li>
            <!--            <li class="goo"><a href="#" target="_blank"></a></li>-->
        </ul>
    </div>

<div class="copy_right">
    <div class="container">
        <a href="{site_url}" target="_blank">{date('Y')} &#169; {setting name='footer'}</a>
    </div>
    <div class="container" style="color:#999;">
       Designed and supported by <a href="                  " title="       " target="_blank"> TronTower LTD</a>
    </div>
</div>
</footer>



</div>
<script>
    $('.dropdown-toggle').dropdown();
</script>
<script src="{assets_url}/bundles/ajax.js"></script>
<script src="{assets_url}/bundles/jquery.chiz.js"></script>

<script src="{assets_url}/bundles/jqueryui.js"></script>
{footer_js}

<!--<script src="{assets_url}/bundles/updates.js"></script>-->
<script src="{assets_url}/bundles/sport.js"></script>
<script src="{assets_url}/bundles/accordian.js" type="text/javascript"></script>
<script src="{assets_url}/bundles/owl.carousel.min.js" type="text/javascript"></script>
</body>