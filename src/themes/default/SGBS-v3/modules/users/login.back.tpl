<!-- Modal -->
<div id="forgotModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-md">
        <!-- Modal content-->
        <div class="modal-content">
            <form action="{site_url()|con:'users/resetPassword'}" method="POST" id="forgotpass-form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">   <i class="fa fa-user" aria-hidden="true"></i>Password recovery</h4>
                </div>
                <div class="modal-body rtl">
                    <p>
                        <label>شماره موبایل <span class="required">*</span></label>
                        <input type="text" name="mobile" style="    background: #fff none repeat scroll 0 0;
                               border: 1px solid #e8e8e9;
                               border-radius: 0;
                               height: 32px;
                               padding: 0 0 0 10px;
                               width: 100%;"/>
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-default pull-left">Password recovery</button>
                    <i class="fa fa-refresh fa-spin fa-2x pull-right" style="display:none;"></i>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- entry-header-area start -->
<div class="entry-header-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="entry-header">
                    <h1 class="entry-title">Profile</h1>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- entry-header-area end -->
<!-- my-account-area start -->
<div class="my-account-area">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                {form_open()}
                <div class="form-fields rtl">
                    <h2><i class="fa fa-user" aria-hidden="true"></i> login </h2>
                    <p>
                        <label>E-mail  <span class="required">*</span></label>
                        <input type="text" name="email"/>
                    </p>
                    <p>
                        <label>کلمه عبور <span class="required">*</span></label>
                        <input type="password" name="password"/>
                    </p>
                </div>
                <div class="form-action">
                    <p class="lost_password"><a class="btn" data-toggle="modal" data-backdrop="true" data-keyboard="true" href="#forgotModal">Forget password ?؟</a></p>
                    <input type="submit" value="ورود" />
                    <label><input type="checkbox" name="remember_me" value="1"/>  Remember me </label>
                </div>
                {form_close()}
            </div>
            <div class="col-md-6 rtl">
                {form_open('',['id'=>'register-form'])}
                <div class="form-fields">
                    <h2><i class="fa fa-user-plus" aria-hidden="true"></i> ثبت نام</h2>
                    <p>
                        <label>E-mail   <span class="required">*</span></label>
                        <input type="text" name="email" />
                    </p>
                    <p>
                        <label>کلمه عبور <span class="required">*</span></label>
                        <input name="password" type="password" />
                    </p>
                </div>
                <div class="form-action">
                    <input type="submit" value="ثبت نام" /><i class="fa fa-refresh fa-spin fa-3x pull-left" style="display:none;"></i>
                </div>
                {form_close()}
            </div>
        </div>
    </div>
</div>
<!-- my-account-area end -->
