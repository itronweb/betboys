<div class="cotainer">
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
				URLhashListener:true,
                autoplayHoverPause:true,
                startPosition: 'URLHash',
			    responsiveClass:true,
                responsive: {
                    1200: {
                        items: 14
                    },
                    992: {
                        items: 8
                    },
                    768: {
                        items: 6
                    },
                    0: {
                        items: 6
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
                        items: 2,
//						center:true
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
			
		
			$(".side-sports li").click(function(){
					 var sport =$(this).attr("data-sportcat");
				$(".side-sports li.active").removeClass("active");
				$("this").addClass("active");
//				       $('html, body').animate({
//                         scrollTop: $(".sports-slider  #predict").offset().top
//                         }, 1);
					 $('.sports-slider .item.active').removeClass('active');
                	 $('[data-sportcat=' + sport + ']').addClass('active');
		     		 $('div[data-sport]').addClass("hide");
				     $('div[data-sport=' + sport + ']').removeClass("hide");
		});
			
				// if in inplayme page to upcoming in aside right go this sport
			var url = window.location.pathname;
			var urlSplit = url.split('/');
			var sport = urlSplit[5];
			if( sport != null ){
				if($('div[data-sportcat=' + sport + ']')){
					var taginfo = $('div[data-sportcat=' + sport + ']')
					$('div[data-sportcat=' + sport + ']').addClass("active");
					$(".sports-slider .item.all").removeClass("active");
					var sportType =$(taginfo).attr("data-sportcat");
					$('div[data-sport]').addClass("hide");
					$('div[data-sport=' + sportType + ']').removeClass("hide");
					
				}else{
						
				}
			}
			
      });
    </script> 

<!------------------------ predict buttom end -------------------->

<div class="body_fraim maincontent clearfix"> 
  <!--        <header class="tabheader PreTopShorting">-->
  <div class=""> {*
    <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12 form-group">
      <form class="search-upcoming" method="post">
        <fieldset>
          <div class="col-md-6 col-md-offset-3 col-sm-12 col-xs-12 form-group">
            <input class="col-sm-11 col-xs-10 form-control"name="search" placeholder="جستجو تیم مورد نظر ..."type="text">
            <button type="submit" value="submit" class="col-sm-1 col-xs-2 btn"><i class="fa fa-search" aria-hidden="true"></i></button>
          </div>
        </fieldset>
      </form>
    </div>
    *}
    <ul class="col-xs-12 nopadding odds inplay inplaybet res-inplay">
  <li class="col-lg-2 col-md-2 col-sm-12 col-xs-12 hide-on-sm nopadding">
      <ul class="side-sports animated">
        <h3>ورزش ها</h3>
       		
		{foreach $soccer_type as $soccer_type_value }
			<li class="" data-sportcat="{$soccer_type_value['en']}">
				<a href="#{$soccer_type_value['en']}s">
					<img class="" width="20px" height="20px" src="{site_url}/attachment/sport_logo/{$soccer_type_value['logo']}" alt="{$soccer_type_value['fa']}">
            		<label>{$soccer_type_value['fa']}</label>
       			</a> 
			</li>
        {/foreach} 
      </ul>
    </li>
		
      <li class="col-lg-7 col-md-7 col-sm-12 col-xs-12 panel-group nopadding ">
		<!----------------------------End top slider-------------------------->

      <div class="col-xs-12 nopadding tab-div">
        <ul class="tab_switch_block tabSwitch">
          <li class="inplay "> <a href="{site_url}bets/inplayBet">پیش بینی زنده</a> </li>
          <li class="upcoming  active"> <a href="{site_url}bets/upComing">پیش بینی Openی ها</a> </li>
          {*
          <li class="upcoming "> <a href="{site_url}bets/upComing/1">Openی های فردا</a> </li>
          <li class="upcoming "> <a href="{site_url}bets/upComing/2">Openی های دو روز آینده</a> </li>
          *}
          <li> <a  href="{site_url}bets/myrecords">سابقه پیش بینی ها</a> </li>
        </ul>
      </div>
		  
      <!------------------------  sport slider -------------------->
		  
      <div class="col-xs-12 owl-carousel nopadding sports-slider">
        <div class="item all active">
			<a href="javascript:void(0)">
				<img class="img-responsive" src="{site_url}/attachment/sport_logo/cup.png" alt="عکس 1">

				<div class="slider-text">
					<p>همه ورزش ها</p>
					<p>{array_sum($soccer_number)}</p>
			    </div>
            </a> 
		 </div>
		  
        <!--partner_item1 end-->
        {foreach $soccer_type as $soccer_type_value }
        <div class="item"  data-hash="{$soccer_type_value['en']}s" data-sportcat="{$soccer_type_value['en']}"> 
			<a href="javascript:void(0)"> 
				<img src="{site_url}/attachment/sport_logo/{$soccer_type_value['logo']}" alt="$soccer_type_value">
				<div class="slider-text">
					<p>{$soccer_type_value['fa']}</p>
					<p>{$soccer_number[$soccer_type_value['en']]}</p>
          		</div>
          	</a> 
		 </div>
        {/foreach} 
		  
		  
         <!--partner_item1 end-->
        
      </div>
      <!------------------------ End sport slider -------------------->
		  		
			
      <div class="col-xs-12 inplaytable nohover inner-headder">
        <div class="branchheader" id="top"> <span class="match"> <b>{jdate format='j F (l) ' date="+$day day" second_date=gmdate('Y-m-d')}</b> (<span class="totalevents">{$count|persian_number}</span>) </span>
          <select class="upcoming-select" id="upcoming-select-id">
            <option {if $day eq 0}selected=""{/if} value="{site_url}bets/upComing"> {jdate format='j F (l) ' date='+0 day' second_date=gmdate('Y-m-d')} </option>
            <option {if $day eq 1}selected=""{/if}  value="{site_url}bets/upComing/1"> {jdate format='j F (l) ' date='+1 day' second_date=gmdate('Y-m-d')} </option>
            <option {if $day eq 2}selected=""{/if}  value="{site_url}bets/upComing/2"> {jdate format='j F (l) ' date='+2 day' second_date=gmdate('Y-m-d')} </option>
            <option {if $day eq 3}selected=""{/if}  value="{site_url}bets/upComing/3"> {jdate format='j F (l) ' date='+3 day' second_date=gmdate('Y-m-d')} </option>
            <option {if $day eq 4}selected=""{/if}  value="{site_url}bets/upComing/4"> {jdate format='j F (l) ' date='+4 day' second_date=gmdate('Y-m-d')} </option>
          </select>
        </div>
      </div>
		  
<!-----------------------main panel----------------------->
       {foreach $competition as $soccer_key=>$soccer_value }
       	{if sizeof($soccer_value) > 0}
        <div class="col-xs-12 panel nopadding" id="{$soccer_key}" data-sport="{$soccer_key}">
        
			<div class="col-xs-12 panel-heading sport-cat"  data-toggle="collapse" data-target="#mainpanel-{$soccer_key}" data-parent="#{$soccer_key}">
				<h4 class="panel-title"> 
				<a role="button" data-toggle="collapse" data-parent="#{$soccer_key}" href="#mainpanel-{$soccer_key}" aria-expanded="true" aria-controls="collapseOne" class="match">
					<b class="mark-header">{$soccer_type[$soccer_key]['fa']}  ({$soccer_number[$soccer_key]})</b>
				</a> 
				</h4>
			</div>
         	
          	<div id="mainpanel-{$soccer_key}"  class="col-xs-12 clearfix panel-collapse collapse in nopadding" role="tabpanel" aria-labelledby="headingOne"> 
            <!--------------------------start inner pannel --------------> 
            <div class="col-xs-12 panel nopadding" id="inner-panel-{$soccer_key}">
		  {$k=0}
        {foreach $soccer_value as $key => $val}
        
        {$leagues_array = reset($val)}
        <div class="col-xs-12 panel-heading inplayheader p5"  data-toggle="collapse" data-target="#{$soccer_key}{$k}" data-parent="#inner-panel-{$soccer_key}" data-leagueid="{$leagues_array->competition->id}" data-rankid="{$leagues_array->competition->id}">
        	<div class="col-sm-6 col-xs-12 panel-title"> 
				<span role="button" data-toggle="collapse" data-parent="#inner-panel-{$soccer_key}" href="#{$soccer_key}{$k}" aria-expanded="true" aria-controls="collapseOne" class="match">
					<b>{$leagues_array->competition->name}</b>
				</span>
       		</div>
        </div>
        <div id="{$soccer_key}{$k++}" class="col-xs-12 clearfix panel-collapse collapse in nopadding" role="tabpanel" aria-labelledby="headingOne"> {foreach $val as $match}
         
          {if {searchArray key='type' val='1x2' array=$match->odds->data[0]->types->data} != null }
          <div data-eventid="{$match->id}" class="col-xs-12 odddetails" data-leagueid="{$match->competition->id}" >
            <div class="col-sm-6 col-xs-11 p5">
				<span class="fa fa-futbol-o timer"></span>
				<div class="col-xs-8 col-xs-9 nopadding">
					<p class="col-xs-12"><b class="host ellipsis">{$match->homeTeam->name}</b></p>
					<p class="col-xs-12"><b class="guest ellipsis">{$match->awayTeam->name}</b> </p>
				</div>
				<span class="inplaytime2 pull-left"> {gmtfa time=$match->starting_time format='H:i'|persian_number}</span> 
			  </div>
            <div class="col-sm-5 col-xs-12 nopadding">
              <div class="eventodds"> <span class="eventsuspended hidden">غیر فعال</span>
                <ul class="mlodds">
				 	{$label = ['1','X','2']}
                   {$label_array = [['1','Home'],['X','Draw'],['2','Away']]}
                    
                    {for $i=0;$i<3;$i++}
                    {if in_array( $match->odds->data[0]->types->data[0]->odds->data[$i]->label, $label_array[$i] )  }
					
					
                    <li data-runnerid="{$match->id|con:'-': $match->odds->data[0]->bookmaker_id:'-1x2-':$match->odds->data[0]->types->data[0]->odds->data[$i]->label}"
						data-pick="{$match->odds->data[0]->types->data[0]->odds->data[$i]->label}" data-points='' data-bet-sport="{$soccer_key}" class="col-sm-4 col-xs-4 inplaybtn eventodd"> <strong class="col-xs-2 nopadding text-right"> 
						
                     {if $label_array[$i][0] == '1' || $label_array[$i][1] =='Home'}
                      1
                     {else if $label_array[$i][0] == '2' || $label_array[$i][1] == 'Away'}
                      2
                     {else}
                      x
                     {/if} </strong> 
                      <!--                                                                                    <i class="fa fa-caret-up up-arrow"></i>--> 
                      <span class="col-xs-10 nopadding text-left"> 
                      {if $match->odds->data[0]->types->data[0]->odds->data[$i]->suspend == 0 }
                      	{$match->odds->data[0]->types->data[0]->odds->data[$i]->value}
                      {else}
                     	00.00
                      {/if} </span> </li>
                    {/if}
                    {/for}
                    
                 
<!--
                 
                  {$myArray = $match->odds->data[0]->types->data[{searchArray key='type' val='1x2' array=$match->odds->data[0]->types->data}]}
                  
                  <li  class="col-xs-4 inplaybtn  eventodd" data-runnerid="{$match->id|con:'-': $match->odds->data[0]->bookmaker_id:'-1x2-': {$myArray->odds->data[{searchArray key='label' val='1' array=$myArray->odds->data}]->label}}" data-bet-sport="{$soccer_key}"  data-pick="1"  data-pick="{$match->homeTeam->name}" data-points="">
                  <i> </i> <strong class="col-xs-2 nopadding text-right"> 1 </strong> <span class="col-xs-10 text-left nopadding">
					  {if isset($homeOdd)}
                  {$homeOdd}
                  {else}
                  {$myArray->odds->data[{searchArray key='label' val='1' array=$myArray->odds->data}]->value}    
                  {/if} </span>
                  </li>
					
					{$x = {searchArray key='label' val='X' array=$myArray->odds->data}}
					{if empty($x)}
					{$x = {searchArray key='label' val='Draw' array=$myArray->odds->data}}
					{/if}
                  <li class="col-xs-4 inplaybtn  eventodd" data-runnerid="{$match->id|con:'-': $match->odds->data[0]->bookmaker_id:'-1x2-':{$myArray->odds->data[$x]->label}}" data-bet-sport="{$soccer_key}"  data-pick="X" data-points=""> <i></i> <strong class="col-xs-2 nopadding text-right"> x</strong> <span  class="col-xs-10 text-left nopadding"> {if isset($drawOdd)}
                    {$drawOdd}
                    {else}
                    {$myArray->odds->data[$x]->value}    
                    {/if} </span> </li>
                  <li class="col-xs-4 inplaybtn  eventodd" data-runnerid="{$match->id|con:'-': $match->odds->data[0]->bookmaker_id:'-1x2-':{$myArray->odds->data[{searchArray key='label' val='2' array=$myArray->odds->data}]->label}}" data-bet-sport="{$soccer_key}" data-pick="2" data-points=""> <i></i> <strong class="col-xs-2 nopadding text-right"> 2 </strong> <span  class="col-xs-10 text-left nopadding"> {if isset($awayOdd)}
                    {$awayOdd}
                    {else}
                    {$myArray->odds->data[{searchArray key='label' val='2' array=$myArray->odds->data}]->value}    
                    {/if} </span> </li>
-->
                </ul>
              </div>
            </div>
            <div class="col-sm-1 col-xs-1 more-bet"> <a class="has-tip fa fa-plus-circle more" href="{site_url}bets/preEvents/{$match->id}/{$soccer_key}" data-original-title="شرط های بیشتر"></a> </div>
          </div>
          {/if}
          
          {/foreach} 
				</div>
        {/foreach} 
			  </div>
			</div>
		  </div>
    	{/if}
     	{/foreach}
      <i class="fa-loader fa fa-spinner fa-pulse" style="display: none;"></i>
      </li>
      <li  id="predict"  class="col-lg-3 col-md-3 col-sm-12 col-xs-12 nopadding inplay-controller">
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
          <div style="clear:both"></div>
          <div class="alertbox alertbox2 hidden"></div>
        </div>
      </li>
    </ul>
  </div>
</div>