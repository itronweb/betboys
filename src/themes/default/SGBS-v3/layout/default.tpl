<!DOCTYPE html>
<html lang="en">

    {include file="partials/header.tpl"}
    <!-- End header -->
    <!-- Main Wrapper -->

    {if ($system_message != '')}
        <div class="container box-msg">
            <div class="">
                <div class="">
                    <div class=" {if $system_message['type'] eq 'success' }
                         {'msgsuccess'}
                    {elseif $system_message['type'] eq 'warning'}
                        {'msgwarning'}
                    {elseif $system_message['type'] eq 'fail'} 
                        {'msgerror'}
                    {/if}">


                    <h4><i class="fa fa-
                                                                          {if $system_message['type'] eq 'success' }
                                                                              {'check-square-o'}
                                                                          {elseif $system_message['type'] eq 'warning'}
                                                                              {'warning'}
                                                                          {elseif $system_message['type'] eq 'fail'} 
                                                                              {'remove'}
                                                                          {/if}
                                                                          fa-2x" style="vertical-align: middle;"></i>
                        <span class="semi-bold" style="padding-top: 9px ! important; font-size: 13px; border-bottom: 4px dashed rgb(221, 221, 221); margin-bottom: 9px ! important;">{$system_message['title']}</span>
                    </h4>

                    <p> {$system_message['message']}</p>
                </div>
            </div>
        </div>
    </div>


    {/if}
        {$content}
        {include file="partials/footer.tpl"}
    </html>