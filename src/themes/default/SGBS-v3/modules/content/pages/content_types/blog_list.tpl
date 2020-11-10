
<!-- breadcrumb-area start -->
<div class="breadcrumb-area">
</div>
<!-- breadcrumb-area end -->

<!-- blog-area start -->
<div class="blog-area rtl">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">
                <div class="archive-header">
                    <h1 class="archive-title">بلاگ پیانویاب</h1>
                </div>
                <!-- single-blog start -->
                {foreach from=$contentType.entries item=post}
                    <article class="blog-post-wrapper">
                        <div class="post-thumbnail">
                            <a href="{site_url|con:$contentType.slug:'/':$post.slug}"><img width="670" height="402" src="{site_url|con:'upload/content/entry/': $post.image}" alt="{$post.title}" /></a>
                        </div>
                        <div class="post-information">
                            <h2><a href="{site_url|con:$contentType.slug:'/':$post.slug}">{$post.title}</a></h2>
                            <div class="small-meta">
                                <span><i class="fa fa-calendar"></i> 
                                    {jdate date=$post.created_at format="j F Y"}
                                </span>
                                <a href="#"><i class="fa fa-comments-o"></i> 
                                    {$post->comments->where('status',1)->count()} نظر
                                </a>
                            </div>
                            <p>
                                {$post.short_story}
                            </p>
                            <a class="readmore pull-left" href="{site_url|con:$contentType.slug:'/':$post.slug}">
                                ادامه مطلب
                            </a>
                        </div>
                    </article>
                    <!-- single-blog end -->

                {/foreach}

                <!-- pagination start -->
<!--
                {*   <div class="pagination">
                <ul>
                <li class="active">1</li>
                <li><a href="#">2</a></li>
                <li><a href="#">3</a></li>
                <li><a href="#"><i class="fa fa-chevron-right"></i></a></li>
                </ul>
                </div>
                *}
-->
                <!-- pagination end -->
            </div>
            <!-- blog-left-sidebar start -->
            <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                <aside class="widget widget-categories">
                    <h3 class="sidebar-title">آرشیو وبلاگ</h3>
                    <ul class="sidebar-menu">
                        {foreach from=$contentType->entries item=blog}
                            <li><a href="{site_url()|con:'وبلاگ/':$blog.slug}">{$blog.title}</a></li>
                            {/foreach}
                    </ul>
                </aside>
            </div>
            <!-- blog-left-sidebar end -->
        </div>
    </div>
</div>
<!-- blog-area end -->
