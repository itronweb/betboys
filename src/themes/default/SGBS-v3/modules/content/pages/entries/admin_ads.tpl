<!-- breadcrumb-area start -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb">
                    <ul>
                        <li><a href="{site_url}">صفحه نخست</a> <i class="fa fa-angle-left"></i></li>
                        <li>{$entry.title}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb-area end -->

<!-- blog-area start -->
<div class="blog-area single-blog rtl">
    <div class="container">
        <div class="row">
            <!-- blog-left-sidebar start -->
            <div class="col-lg-3 col-md-3 pull-right">
                <!-- widget-categories start -->
                <aside class="widget widget-categories">
                    <h3 class="sidebar-title">سایر تبلیغات</h3>
                    <ul class="sidebar-menu">
                        {foreach from=$content_type->entries item=ads}
                            <li><a href="{site_url()|con:'تبلیغات/':$ads.slug}">{$ads.title}</a></li>
                            {/foreach}
                    </ul>
                </aside>
            </div>
            <!-- blog-left-sidebar end -->
            <div class="col-lg-9 col-md-9 rtl">
                <!-- single-blog start -->
                <article class="blog-post-wrapper">
                    <div class="post-thumbnail">
                        <img style="max-height: 500px;" src="{site_url|con:'upload/content/entry/': $entry.image}" alt="{$entry.title}" />
                    </div>
                    <div class="post-information">
                        <h2>{$entry.title}</h2>
                        <div class="entry-meta">
                            <span class="author-meta"><i class="fa fa-user"></i> ادمین</span>
                            <span><i class="fa fa-clock-o"></i> {jdate date=$entry.created_at format="j F Y"}</span>
                            <span>
                                <i class="fa fa-tags"></i>
                                {foreach from=explode(',',$entry.keyword) item=val}
                                    <a href="#">{$val}</a>,
                                {/foreach}

                            </span>
                        </div>
                        <div class="entry-content">
                            <p>
                                {$entry.full_story}
                            </p>

                        </div>
                    </div>
                </article>
                <div class="clear"></div>
                <!-- single-blog end -->
            </div>
        </div>
    </div>
</div>
<!-- blog-area end -->
