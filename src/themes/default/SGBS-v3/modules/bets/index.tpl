   {if !empty($matches) }
    {assign var=i value=0}
    {foreach $matches as $match_team}
        {if !empty($match_team->odds->data) AND $match_team->minute == 0 AND {searchArray key='type' val='1x2' array=$match_team->odds->data[0]->types->data} != null AND ($match_team->status != 'FT' OR $matche_team->status != 'FT_PEN')}
            {assign var=i value=$i+1}
        {/if}
    {/foreach}
{/if}
<div>
    <script src="{assets_url}/Content/MENU.JS"></script>
    <div class="main-splash" data="0">
		
		<div class="left splash-container">
    <script src="{assets_url}/Content/slider.js"></script>
			<section id="slider" class="container">
		<ul class="slider-wrapper">
		{foreach $slideshow as $key_slide=>$value_slide}
		<li class="current-slide">
			<img src="{site_url}attachment/slideshow/{$value_slide['image']}" alt="{$value_slide['title']}">
		</li>
		{/foreach}
 

		</ul>
		<!-- Sombras -->
		<div class="slider-shadow"></div>
		
	</section>

		</div>

		<div class="left thumb-container">

			<a href="{site_url}bets/inplayBet">
				<img src="{assets_url}/images/mfootball.png"><br>
				پیش بینی زنده			</a>
			<a href="/newGames/games/crash">
				<img src="{assets_url}/images/mpoker.png"><br>
		CRASH			</a>
			<a href="{site_url}help">
				<img src="{assets_url}/images/mbonus.png"><br>
				راهنمای سایت		</a>

		</div>

		<div class="clear"></div>

		
		<div class="main-games-box">

			
			<a href="{site_url}casino/games/roulette/" class="game-link">
				<img src="{assets_url}/images/rol.jpg">
				<!--<div class="game-link-title">Crash</div>
				<div class="game-link-sub"> ۳۰  نفر User آنلاین</div>-->
			</a>

			
			<a href="{site_url}casino/games/baccarat/" class="game-link game-middle">
				<img src="{assets_url}/images/baca.jpg">
				<!--<div class="game-link-title">پاسور</div>
				<div class="game-link-sub"> ۵۲  نفر User آنلاین</div>-->
			</a>

			
			<a href="{site_url}casino/games/slot/" class="game-link">
				<img src="{assets_url}/images/slot.jpg">
				<!--<div class="game-link-title">بلک جک</div>
				<div class="game-link-sub">   نفر User آنلاین</div>-->
			</a>

			
		
		
			
			<div class="clear"></div>

		</div>

		
	</div>
</div>
<script src="{assets_url}/bundles/owl.carousel.min.js" type="text/javascript"></script>
<script>
                        $(document).ready(function () {
                            $('.owl-carousel').owlCarousel({
								loop:true,
								touchDrag:true,
								pullDrag:true,
								mouseDrag:true,
                                rtl: true,
                                autoplay: true,
                                autoplayTimeout:3000,
                                autoplayHoverPause:true,
                                items: 1,
                         
                            });

                            $('.root-slider-indicator a').click(function () {
                                $('.root-slider-indicator a.active').removeClass('active');
                                $(this).addClass('active');
                            });
                        });
</script>
