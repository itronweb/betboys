<div class="mainpadding">
    <div class="cell">
        <div class="container">
            <div class="maincontent">
                <div class="col-lg-2 col-md-3 col-sm-12 col-xs-12 menu">
                    <ul class="default_menu">
						{foreach $menu_group as $key=>$value }
						{$active = ($value['target'] == $page->slug) ? 'active' : ' ' }
						<li class="{$active}" >
							<a href="{$value['target']}" >{$value['title']}</a>
						</li> 
                       {/foreach}
                    </ul>
                </div>
                <div class="content box-pd">
                    <header class="LandingMatchShow">
                        <h1>{$page.name}</h1>
                    </header>
                    <div class="topup-content ">  
                        {$page.description}
                    </div>
                </div>
            </div>
        </div>
    </div>
	
	
	
	