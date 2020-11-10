<!-- breadcrumb-area start -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="breadcrumb">
                    <ul>
                        <li><a href="{site_url}">صفحه نخست</a> <i class="fa fa-angle-left"></i></li>
                        <li><a href="{site_url|con:$entry.contentType.slug}">وبلاگ</a> <i class="fa fa-angle-left"></i></li>
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
                    <h3 class="sidebar-title">آرشیو وبلاگ</h3>
                    <ul class="sidebar-menu">
                        {foreach from=$content_type->entries item=blog}
                            <li><a href="{site_url()|con:'وبلاگ/':$blog.slug}">{$blog.title}</a></li>
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
                            <span><i class="fa fa-comments-o"></i> <a href="#comment_section">{$entry->comments()->where('status',1)->get()->count()} نظر</a></span>
                        </div>
                        <div class="entry-content">
                            <p>
                                {$entry.full_story}
                            </p>

                        </div>
                    </div>
                </article>
                <div class="clear"></div>
                <div class="single-post-comments">
                    <div class="comments-area" id="comment_section">
                        <div class="comments-heading">
                            <h3>نظرات ({$entry->comments()->where('status',1)->get()->count()})</h3>
                        </div>
                        <div class="comments-list">
                            <ul>
                                {if $entry->comments->isEmpty()}
                                    <label class="text-muted">تاکنون نظری برای این مطلب درج نشده است</label>
                                {else}
                                    {foreach from=$entry->comments()->where('status',1)->get() item=cm}
                                        <li>
                                            <div class="comments-details">
                                                <div class="comments-list-img">
                                                    <img src="img/blog/comments/1.png" alt="" />
                                                </div>
                                                <div class="comments-content-wrap">
                                                    <span>
                                                        <b><a href="#">{$cm.user.first_name} {$cm.user.last_name}</a></b>
                                                        نویسنده
                                                        <span class="post-time"> {jdate date=$cm.created_at format="j F Y H:i:s"}</span>
                                                    </span>
                                                    <p>{$cm.comment}</p>
                                                </div>
                                            </div>
                                        </li>
                                    {/foreach}
                                {/if}
                            </ul>
                        </div>
                    </div>
                    <div class="comment-respond">
                        <h3 class="comment-reply-title">Send نظر </h3>
                        {if $is_logged_in}
                            <form action="{site_url()|con:'content/content/submitComment'}" method="POST">
                                <div class="row">
                                    <div class="col-md-12 comment-form-comment">
                                        <p>نظر</p>
                                        <textarea id="message" name="comment" cols="30" rows="10"></textarea>
                                        <input name="entry" value="{$entry.id}" type="hidden"/>
                                        <input type="submit" value="Send نظر" />
                                    </div>
                                </div>
                            </form>
                        {else}
                            <label class="text-danger">برای Send نظرابتدا <a href="{site_url|con:'users/login'}">ثبت نام</a> کنید و یا <a href="{site_url|con:'users/login'}">لاگین</a>  کنید.</label>
                        {/if}
                    </div>						
                </div>
                <!-- single-blog end -->
            </div>
        </div>
    </div>
</div>
<!-- blog-area end -->
