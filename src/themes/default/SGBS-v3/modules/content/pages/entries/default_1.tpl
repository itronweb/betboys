<!-- END OF HEADER --> <!-- Internal Page Main titles boxes --> 
<section id="internal-title" class="container" data-background="parallax">
    <h1>{$contentType.name}</h1>
</section>
<!-- End of Internal Page Main titles boxes --> 
<section id="rooms-page" class="container">
    <!-- Rooms --> 
    <section id="rooms">
        <!-- Main Room container --> 
        <ul class="property-container clearfix">

            {foreach from=$contentType.entries item=post}
                <li class="property-boxes col-xs-6 col-md-4">
                    <!-- Room Image and its title --> 
                    <div class=prp-img>
                        <img src="{site_url|con:'upload/content/entry/': $post.image}" alt="{$post.title}">
                        <div class=price><span>{$post.title}</span></div>
                        <!-- Room's Price --> 
                    </div>
                    <div class=prp-detail>
                        <div class=title>{$post.title}</div>
                        <!-- Room's Title --> 
                        <div class=description>{$post.short_story}</div>
                        <a href="{site_url|con:$contentType.slug:'/':$post.slug}" class="btn colored">اطلاعات</a>
                    </div>
                </li>
            {/foreach}
            <!-- End of Room Box --> 
        </ul>
        <!-- End of Main Room container --> 
    </section>
    <!-- END OF Rooms --> 
</section>