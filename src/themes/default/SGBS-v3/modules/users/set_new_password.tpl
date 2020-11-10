<div class="register mainpadding body_fraim" role="main">
    <div class="container">
        <section class="col-lg-6 col-lg-offset-3 col-md-6 col-md-offset-3  col-sm-12 col-xs-12 nopadding signupbox">
            <div class="signupbox-header"> <h1> Reset password</h1></div>
            {form_open()}
            <div class="signupform clearfix">
                <div id="errors">
                    <p></p>
                </div>
                <div class="wrapsignupinput">
                    <input class="input" id="UserName" name="password" placeholder="New password" type="password">
                    <span class="field-validation-valid error_message" data-valmsg-for="UserName" data-valmsg-replace="true"></span>
					 <input type="hidden" value="{$user_id}"  name="user_id"/>
                    <input type="hidden" value="{$reminder_code}" name="reminder_code"/>
                </div>
                <div>
                    <input class="btn btn-lg btn-green  floatright" type="submit" id="submit_reset" value="Reset password">
                </div>
            </div>
            </form>   
        </section>
    </div>
</div>
