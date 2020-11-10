<!--{add_js file='bundles/updates.js' part='footer'}-->
<div> 
  <!------------------------ predict buttom start -------------------->
  <div class="predict-div">
    <button class="btn predict-btn"><i class="fa fa-file-text-o"></i>
    <div class="notification"></div>
    </button>
    <button class="btn go-top-btn"> <i class="fa fa-chevron-up"></i></button>
  </div>
  <script>
        $(document).ready(function () {
            $(".predict-btn").click(function () {
                $('html, body').animate({
                    scrollTop: $("#predict").offset().top
                }, 1);
            });
            $(".go-top-btn").click(function () {
                $('html, body').animate({
                    scrollTop: $("#top").offset().top
                }, 1);
            });
            $('ul.tabSwitch li').click(function () {
                $('ul.tabSwitch  li.active').removeClass('active');
                $(this).addClass('active');
            });
            $('.owl-carousel.sports-slider').owlCarousel({
                rtl: true,
				dots:false,
				mouseDrag : true,
                touchDrag : true,
                nav: true,
                navText: ["<i class='fa fa-chevron-right'></i>", "<i class='fa fa-chevron-left'></i>"],
			    responsiveClass:true,
                responsive: {
                    1200: {
                        items: 12
                    },
                    992: {
                        items: 8
                    },
                    768: {
                        items: 6
                    },
                    0: {
                        items: 4
                    }
                }
            });
          $('.owl-carousel.side-slider').owlCarousel({
                rtl: true,
			  	loop:true,
				autoplay:true,
				autoplayTimeout:5000,
				autoplayHoverPause:true,
			    dots:true,
			    mouseDrag : true,
                touchDrag : true,
			  items:1
            });
			    $('.owl-carousel.top-slider').owlCarousel({
                rtl: true,
			  	loop:true,
				autoplay:true,
				autoplayTimeout:3000,
				autoplayHoverPause:true,
			    dots:true,
			    mouseDrag : true,
                touchDrag : true,
                nav: true,
                navText: ["<i class='fa fa-chevron-right'></i>", "<i class='fa fa-chevron-left'></i>"],
			    responsiveClass:true,
                responsive: {
                    1200: {
                        items: 2
                    },
                    992: {
                        items: 2
                    },
                    768: {
                        items:2
                    },
                    0: {
                        items: 1
                    }
                }
            });
			
			$(".sports-slider .item").click(function(){
				 $('.item.active').removeClass('active');
                $(this).addClass('active');
		     var sport =$(this).attr("data-sportcat");
		     $('div[data-sport]').addClass("hide");
				$('div[data-sport=' + sport + ']').removeClass("hide");
			});
				$(".item.all").click(function(){
				$('div[data-sport]').removeClass("hide");
			});
			
			// if click item in aside right go this sport
			
//			var url = window.location.pathname;
//			var urlSplit = url.split('/');
//			var sport = urlSplit[5];
//
//			if( sport != null ){
//
			
        });
    </script>
  <input id="page-name" value="{$pageName}" type="hidden">
  <!------------------------ predict buttom end -------------------->
  <div class="cell">
  <div class="body_fraim maincontent clearfix">
  <ul class="col-xs-12 nopadding odds inplay inplaybet res-inplay" id="top">
    <li class="col-lg-2 col-md-2 col-sm-12 col-xs-12 hide-on-sm nopadding">
      <ul class="side-sports animated">
        <h3>ورزش ها</h3>
       		
		{foreach $upcoming_soccer_type as $soccer_type_value }
			<li class="has-children "  data-sportcat="{$soccer_type_value['en']}">
				<a href="{site_url}bets/upComing/0/{$soccer_type_value['en']}">
					<img class="" width="20px" height="20px" src="{site_url}/attachment/sport_logo/{$soccer_type_value['logo']}" alt="{$soccer_type_value['fa']}">
            		<label>{$soccer_type_value['fa']}</label>
       			</a> 
			</li>
        {/foreach} 
      </ul>
    </li>
    <li class="col-lg-7 col-md-7 col-sm-12 col-xs-12 nopadding">
      <div class="col-xs-12 nopadding tab-div">
        <ul class="tab_switch_block tabSwitch">
          <li class="inplay active "> <a href="{site_url}bets/inplayBet">پیش بینی زنده</a> </li>
          <li class="upcoming"> <a href="{site_url}bets/upComing">پیش بینی Openی ها</a> </li>
          {*
          <li class="upcoming "> <a href="{site_url}bets/upComing/1">Openی های فردا</a> </li>
          <li class="upcoming "> <a href="{site_url}bets/upComing/2">Openی های دو روز آینده</a> </li>
          *}
          <li> <a  href="{site_url}bets/myrecords">سابقه پیش بینی ها</a> </li>
        </ul>
      </div>
      <!------------------------  sport slider -------------------->
      <div class="col-xs-12 owl-carousel owl-theme nopadding sports-slider">
        <div class="item active all"> 
			<a href="javascript:void(0)">
				<img class="img-responsive" src="{site_url}/attachment/sport_logo/cup.png" alt="عکس 1">
          <div class="slider-text">
            <p>همه ورزش ها</p>
            <p>{array_sum($soccer_number)}</p>
          </div>
          </a> </div>
        {foreach $soccer_type as $soccer_type_value }
		
        <div class="item" data-sportcat="{$soccer_type_value['en']}"> <a href="javascript:void(0)"> <img src="{site_url}/attachment/sport_logo/{$soccer_type_value['logo']}" alt="{$soccer_type_value['fa']}">
          <div class="slider-text">
            <p>{$soccer_type_value['fa']}</p>
            <p>{$soccer_number[$soccer_type_value['en']]}</p>
          </div>
          </a> </div>
        {/foreach} 
        <!--partner_item1 end-->
 
      </div>
      <!------------------------ End sport slider --------------------> 
      <i class="fa-loader fa fa-spinner fa-pulse" style="display: none;"></i>
      <div class="panel-group star-checkbox inplaytable nohover" id="accordion" role="tablist" aria-multiselectable="true"> 
        
        <!--------------------------------------------------------------------- --> 
        
        {$j=0}
        {$k=0}
        
        {foreach $competition as $soccer_key=>$soccer_value }
        
        {if sizeof($soccer_value) > 0}
        <div class="col-xs-12 panel nopadding sport-type" id="accordion1" data-sport="{$soccer_key}">
          <div class="col-xs-12 panel-heading sport-cat"  data-toggle="collapse" data-target="#{$soccer_key}" data-parent="#accordion1" data-soccer-type="{$soccer_key}">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion1" href="#{$soccer_key}" aria-expanded="true" aria-controls="collapseOne" class="match">
                <b class="mark-header">{$soccer_type[$soccer_key]['fa']} </b>
                <span>({$soccer_number[$soccer_key]})</span>
              </a>
            </h4>
          </div>
          <div  id="{$soccer_key}" class="col-xs-12 clearfix panel-collapse collapse in nopadding" role="tabpanel" aria-labelledby="headingOne"> 
            <!--------------------------start inner pannel --------------> 
            <div class="col-xs-12 panel nopadding" id="inner-panel">
            {foreach $soccer_value as $key => $matches1}
            
				{if !empty($matches1) }
              {foreach $matches1 as $key2 => $matches2}
              

              <div class="col-xs-12 panel-heading inplayheader inplayheaders" data-leagueid="{$matches2->competition->id}" data-toggle="collapse" data-target="#c{$k}" data-parent="#inner-panel" data-rankid="{$matches2->competition->id}">
              <h4 class="panel-title"> <a role="button" data-toggle="collapse" data-parent="#inner-panel" href="#c{$k}" aria-expanded="true" aria-controls="collapseOne" class="match"><b>{$matches2->competition->name}</b></a> </h4>
            </div>
            {break}
          {/foreach}
          {assign var=i value=0}
          <div  id="c{$k++}" class="col-xs-12 clearfix panel-collapse collapse in nopadding" role="tabpanel" aria-labelledby="headingOne"> {foreach $matches1 as $key1 => $match_team}
            
            {if !empty($match_team->odds->data)}
            {if !($match_team->status == 'LIVE' OR $match_team->status == 'HT')}
            {$hidden = 'hidden'}
            {else}
            {$hidden = ''}
            {/if}
            <div data-eventid="{$match_team->id}" data-marketid="" class="col-xs-12 panel-body odddetails {$hidden}" leagueid="{$match_team->competition->id}" sport-type="{$soccer_key}">
              <div class="col-lg-6 col-md-12 col-xs-12 p5 inner-pad">
                <div class="col-md-10 col-xs-12 nopadding">
                  <b class="host ellipsis">{$match_team->homeTeam->name}</b>
                  <span class="inplayscore">{$match_team->home_score} - {$match_team->away_score}</span>
                  <b class="guest ellipsis">{$match_team->awayTeam->name}</b>
                  <span class="inplaytime pull-left" style="direction: rtl">' {$match_team->minute}</span>
                  <input class="eninplaytime" type="hidden" value="{$match_team->period}">
                </div>
              </div>
              <div class="col-lg-5 col-md-12 col-xs-12 nopadding ">
                <div class='eventodds'> <span class="eventsuspended hidden">غیر فعال</span>
                  <ul class='mlodds'>
                    {$label = ['1','X','2']}
                    {foreach $label as $value}
                    {for $i=0;$i<3;$i++}
                    {if $value == $match_team->odds->data[0]->types->data[0]->odds->data[$i]->label }
                    <li data-runnerid="{$match_team->id|con:'-': $match_team->odds->data[0]->bookmaker_id:'-1x2-':$match_team->odds->data[0]->types->data[0]->odds->data[$i]->label}"
						data-pick="{$match_team->odds->data[0]->types->data[0]->odds->data[$i]->label}" data-points='' data-bet-sport="{$soccer_key}" class="col-sm-4 col-xs-4 inplaybtn eventodd"> <strong class="col-xs-2 nopadding text-right"> {if $value == '1'}
                      1
                      {else if $value == '2'}
                      2
                      {else}
                      x
                      {/if} </strong> 
                      <!--                                                                                    <i class="fa fa-caret-up up-arrow"></i>--> 
                      <span class="col-xs-10 nopadding text-left"> {if $match_team->odds->data[0]->types->data[0]->odds->data[$i]->suspend == 0 }
                      {$match_team->odds->data[0]->types->data[0]->odds->data[$i]->value}
                      {else}
                      00.00
                      {/if} </span> </li>
                    {/if}
                    {/for}
                    {/foreach}
                  </ul>
                </div>
              </div>
              <div class="col-xs-1 more-bet"> <a class="has-tip fa fa-plus-circle more" title="شرط های بیشتر" href="{site_url}bets/InplayOdds/{$match_team->id}/{$soccer_key}"></a> </div>
            </div>
            {assign var=i value=$i+1}
            {/if}
            {/foreach} </div>
          {else}
          <div class="col-xs-12"> هیچ مسابقه ای برگزار نخواهد شد. </div>
          {/if}
          
          {/foreach} 
			  </div>
      </div>
    </div>
    {/if}
    {/foreach}
    <input id="lastupdate" type="hidden" value="{$lastUpdated}">
    </div>
    </li>
    <li id="predict" class="col-lg-3 col-md-3 col-sm-12 col-xs-12 nopadding inplay-controller">
      <div class="col-lg-12 col-md-12 col-xs-12 nopadding">
        <div>
          <table class="livescore betslip">
            <tbody>
              <tr>
                <th>پیش بینی های من</th>
              </tr>
              <tr>
                <td><div class="nobet"> هنوز هیچ شرطی بسته نشده است. برای پیش بینی بروی ضریب مورد نظر خود کلیک کنید. </div>
                  <div class="selectedodds">
                    <div class="betlist"> </div>
                  </div></td>
              </tr>
            </tbody>
          </table>
          <div class="bettotal">
            <table class="livescore multiple">
            </table>
            <ul class="bettotal">
              <li>مجموع مبالغ <span class="totalstake">0</span></li>
              <li>برد احتمالی (TRX) <span class="totalwin">0</span></li>
            </ul>
            <div class="livescore">
              <div>
                <button class="totobutton smallbutton placebet disabled">ثبت شرط</button>
              </div>
              <div class="del"> <a class="deleteall" href="javascript:void(0)">حذف همه</a></div>
            </div>
          </div>
        </div>
        <div class="alertbox alertbox2 col-lg-12 col-md-12 col-xs-12 idden"></div>
      </div>
    </li>
  </ul>
</div>