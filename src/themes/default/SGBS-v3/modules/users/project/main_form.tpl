
<form action="{$action}" method="POST" >
    عنوان نوآوری؟
    شکل نوآوری
    نحوه ی رسیدن به پاسخ
    و ....

    <input name="title" placeholder="عنوان نوآوری"  value="{set_value('title','')}" {if form_error('title') != NULL}class="error-input"{/if}/>
    <label class="error-container">{form_error('title')}</label>


    <input type="submit" name="submit_btn"/>

</form>