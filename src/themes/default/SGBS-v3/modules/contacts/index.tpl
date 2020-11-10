{literal}<style>.error-container > p {color:red ; font-size:13px;} .error-input{border-color:red!important;}</style>{/literal}
<!-- contact-area start -->
<div class="contact-area">
    <div class="container">
        <div class="row">
            <!-- contact-info start -->
            <div class="col-md-6 col-sm-12 col-xs-12 rtl pull-right">
                <div class="contact-info">
                    <h3 style="padding:5px;margin-right: 10px">Information</h3>
                    <ul>
                        <li>
                            <i class="fa fa-map-marker"></i> <strong>Address</strong>:

                        </li>
                        <li>
                            <i class="fa fa-phone"></i> <strong>Tel</strong>:
                            {'02188954095'|persian_number}
                        </li>
                    
                        <li>
                            <i class="fa fa-envelope"></i> <strong>Email</strong>:    
                            <a href="#"></a>
                        </li>
                    </ul>
                </div>
            </div>
            <!-- contact-info end -->
            <div class="col-md-6 col-sm-12 col-xs-12 rtl">
                <div class="contact-form">
                    <h3><i class="fa fa-envelope-o"></i>Send messege</h3>
                    <div class="row">
                        <form action="{$action}" method="POST">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" name="name_family" placeholder="* Name & Last name" {if isset($user) AND ($user.first_name != '' AND $user.last_name != '')} value="{$user.first_name} {$user.last_name}" {/if}  value="{set_value('name_family','')}" {if form_error('name_family') != NULL}class="error-input"{/if} />
                                <label class="error-container">{form_error('name_family')}</label>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="email" placeholder="Address Email *" name="email" {if isset($user)} value="{$user.email}" {/if} value="{set_value('email','')}" {if form_error('email') != NULL}class="error-input"{/if} />
                                <label class="error-container">{form_error('email')}</label>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" placeholder="Address *" name="address" value="{set_value('address','')}" {if form_error('address') != NULL}class="error-input"{/if} />
                                <label class="error-container">{form_error('address')}</label>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <input type="text" placeholder="Tel No *" name="tell" value="{set_value('tel','')}" {if form_error('tell') != NULL}class="error-input"{/if}/>
                                <label class="error-container">{form_error('tell')}</label>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <input type="text" placeholder="Subject" data-original-title="Enter  Subject " name="subject" value="{set_value('subject','')}" {if form_error('subject') != NULL}class="error-input"{/if} />
                                <label class="error-container">{form_error('subject')}</label>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <textarea {if form_error('message') != NULL}class="error-input"{/if}  id="message" cols="30" rows="10" placeholder="messege" name="message">{set_value('message','')}</textarea>
                                <label class="error-container">{form_error('message')}</label>
                                <input type="submit" name="submit_btn" value="Send" class="pull-left" />
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- contact-area end -->
