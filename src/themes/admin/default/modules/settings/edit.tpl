<div id="page_content_inner">
    <h3 class="heading_a title-top uk-margin-small-bottom">
        {'تنظیمات سیستم'}
    </h3>
    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">
            <div class="uk-width-1-1 ">
                <form method="POST" action="" id="form_validation" class="uk-form-stacked">
                    <div data-uk-grid-margin="" class="uk-grid">
                        <div class="uk-width-medium-2-3">
                            <div class="uk-form-row">
                                <div class="  uk-margin-top">
                                    <label>عنوان سایت </label>
                                    <input type="text" maxlength="60" id="input_counter" class="{if  (NULL != form_error('site_name'))}{' md-input-danger '}{/if}input-count md-input" name="site_name" value="{set_value('site_name', (isset($site_name)) ? $site_name : '')}" >
                                    <div id="input_counter_counter" class="text-count-wrapper">
                                    </div><span class="md-input-bar"></span>
                                </div>
                                <span class="error_page_content">
                                    {form_error('site_name')}  
                                </span>
                                <div class="  uk-margin-top">
                                    <label>متن فوتر سایت</label>
                                    <input type="text" id="input_counter" class="{if  (NULL != form_error('footer'))}{' md-input-danger '}{/if}input-count md-input" name="footer" value="{set_value('footer', (isset($footer)) ? $footer : '')}" >
                                    <div id="input_counter_counter" class="text-count-wrapper">
                                    </div><span class="md-input-bar"></span>
                                </div>
                                <span class="error_page_content">
                                    {form_error('footer')}  
                                </span>

                                <div class="  uk-margin-top">
                                    <label>Home سایت</label>
                                    <select class="data-md-one-selectize" data-md-selectize-bottom=""  name="homepage">
                                        <option>انتخاب Home</option>
                                        {foreach from=$Pages item=page}
                                            <option value="{$page->id}" {if $page->id == $homepage->value}{'selected'}{/if}>
                                                {$page->name}
                                            </option>
                                        {/foreach}
                                    </select>
                                </div>
                                <span class="error_page_content">
                                    {form_error('homepage')}  
                                </span>

                                <div class="  uk-margin-top">
                                    <label>صفحه خطای 404</label>
                                    <select class="data-md-one-selectize" data-md-selectize-bottom=""  name="custom_error_page">
                                        <option>صفحه خطای 404</option>
                                        {foreach from=$Pages item=page}
                                            <option value="{$page->id}" {if $page->id == $custom_error_page->value}{'selected'}{/if}>
                                                {$page->name}
                                            </option>
                                        {/foreach}
                                    </select>
                                </div>
                                <span class="error_page_content">
                                    {form_error('custom_error_page')}  
                                </span>

                                <div class="  uk-margin-top">
                                    <label>قالب سایت</label>
                                    <select class="data-md-one-selectize" data-md-selectize-bottom=""  name="theme">
                                        <option>قالب سایت</option>
                                        {foreach from=$themes item=theme}
                                            {if $theme['type'] === 'dir'}
                                                <option value="{$theme['path']}" {if isset($current_theme) and $theme['path'] === $current_theme}{'selected'}{/if}>
                                                    {$theme['path']}
                                                </option>
                                            {/if}
                                        {/foreach}
                                    </select>
                                </div>
                                <span class="error_page_content">
                                    {form_error('custom_error_page')}  
                                </span>
                                <div class="  uk-margin-top">
                                    <h3 class="heading_a">Status سایت</h3>
                                    <span class="icheck-inline">
                                        <input type="radio" id="site_status0" name="site_status" value="0" data-md-icheck {if (isset($site_status) AND $site_status==0)}{'checked'}{/if}>
                                        <label for="site_status0" class="inline-label">در حال بروزرسانی</label>
                                    </span>
                                    <span class="icheck-inline">
                                        <input type="radio"  id="site_status1" name="site_status" value="1" data-md-icheck {if (isset($site_status) AND $site_status==1)}{'checked'}{/if}>
                                        <label for="site_status1" class="inline-label">آنلاین</label>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="uk-width-medium-1-4">
                        <button  type="submit" class="md-btn md-btn-flat md-btn-success btn-list"><span>ذخیره</span></button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">
            <div class="uk-width-1-1 ">

                <label>اطلاعات سرور: </label>
                <label class="text-info">{$server_signature}</label>
                <br/>
                <label>نمایش خطا: </label>
                <label class="text-info">{$display_errors}</label>
                <br/>
                <label>post_max_size: </label>
                <label class="text-info">{$post_max_size}</label>
            </div>
        </div>
    </div>
</div> 