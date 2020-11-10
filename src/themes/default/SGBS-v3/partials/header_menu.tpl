<center>
		<div class="header desktop">
			<div class="head-top-place-holder"></div>
			<div class="head-top">
				<div class="pr15">
					<div class="left mt5"><a href="/"><img src="{assets_url}/images/logo.png" height="75"></a></div>
					<div class="left ml15 mt10"><a href="{site_url}contacts/tickets/ticket-list"><img src="{assets_url}/images/support1.png" class="mini-icon"></a></div>
					<div class="left ml10 mt10"><a href="{site_url}bets/myrecords"><img src="{assets_url}/images/bonus.png" class="mini-icon-yellow"></a></div> 
					<div class="left ml10 mt10"><a href="{site_url}contacts/tickets/ticket-list"><img src="{assets_url}/images/help.png" class="mini-icon"></a></div>

					<div class="right">
					{if !$is_logged_in}
							<a href="{site_url}users/login" class="link">Login </a>
							<a href="{site_url}users/register" class="link"> Sign up</a>
					{else}
							<a href="{site_url}dashboard" class="link">{$user->first_name}  {$user->last_name}</a>
							<a href="{site_url}payment/credit" class="link">{$user->cash|price_format}</a>
							<a href="{site_url}users/logout" class="link">Exit</a>
					{/if}
					</div>

					<div class="clear"></div>
				</div>
			</div>
			<div class="head-bottom">
				<div class="pr15">
					<span class="hijri_cal pull-left hide">     11/06/2020  <div class="digitalclock"></div> </span>
					<div class="left top-bar desktop">
						<div class="container inline">
							<a href="{site_url}bets/inplayBet">Sport (comming soon)</a>
							<a href="{site_url}bets/upcoming" class="">             </a>
							<a href="{site_url}bets/casino" class="">Decentralized casino</a>
							<a href="{site_url}Newgame/games/roulette" class="">  Roulette     </a>		
							<a href="{site_url}Newgame/games/baccarat/" class="">     Baccarat    </a>
														<a href="/newGames/games/crash" class="">CRASH</a>
							<a href="{site_url}Newgame/games/slot/" class="">   Slot     </a>		
							<a href="{site_url}contacts/tickets/ticket-list" class="">Support</a>
							<a href="{site_url}users/representation" class="">   Referral     </a>
						</div>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>

		<script>
			$(document).ready(function() {
				setInterval(function() {
					var d = new Date();
					var _hou = d.getHours();
					var _min = d.getMinutes();
					var _sec = d.getSeconds();
					_hou = _hou<10?('0'+_hou):(_hou);
					_min = _min<10?('0'+_min):(_min);
					_sec = _sec<10?('0'+_sec):(_sec);
					$(".js-clock-place").html(_hou+":"+_min+":"+_sec);
				}, 1000);
			});
		</script>
    <script>
        $(document).ready(function () {
            $(document).ready(function () {
                var sideslider = $('[data-toggle=collapse-side]');
                var sel = sideslider.attr('data-target');
                var sel2 = sideslider.attr('data-target-2');
                sideslider.click(function (event) {
                    $(sel).toggleClass('in');
                    $(sel2).toggleClass('out');
                });
            });
        });
    </script>

		<div class="header container mobile mobile-bar">
			<div class="icon"><a href="javascript:;" class="mobile-menu-action fa fa-bars"></a></div>
			<div class="icon"></div>
			<div class="logo"><a href="/default"><img src="{assets_url}/images/logo.png" class="mt5" height="35"></a></div>
			<div class="icon"></div>
			<div class="icon"></div>
			<div class="clear"></div>
		</div>
		<div class="mobile mobile-bar-holder"></div>

	</center>

	<div class="mobile-menu mobile">
		<div class="buttons">
			<a style="border-color:#CCC; border-width:1; background-color:#0e610f;" href="{site_url}users/login">Login to account   </a>
			<a href="{site_url}users/register" class="signup-button">sign up</a>
		</div>

		
		<div class="items">
			<a href="{site_url}">HOME</a>
			<a href="{site_url}bets/inplayBet">     </a>
			<a href="{site_url}bets/upcoming" class="">          </a>
			<a href="{site_url}bets/casino" class="">   Decentralized casino       </a>
			<a href="Newgame/games/roulette" class="">Roulette</a>		
			<a href="{site_url}/Newgame/games/baccarat" class="">Baccarat</a>
			<a href="{site_url}/Newgame/games/slot" class="">Slot</a>		
			<a href="{site_url}contacts/tickets/ticket-list" class="">Support</a>
			<a href="{site_url}users/representation" class="">Referral plan</a>
		</div>

	</div>
