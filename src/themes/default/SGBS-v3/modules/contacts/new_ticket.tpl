<div class="more-pad">
    <div class="cell">
        <div class="container">
            <div class="maincontent clearfix">
                <div class="content content-full">
                    <header class="clearfix">
                        <h1 class="mark-side">Support</h1>
                        <span class="optionKey">
                            <a class="newticket btn btn-sm hidden" href="{site_url}contacts/tickets/new-ticket">Send new ticket</a>
                            <a class="newticket  btn btn-sm" href="{site_url}contacts/tickets/ticket-list">Ticket history</a>
                        </span>
                    </header>
                    <section class="formbox mt-20 row_100 clearfix">
                        <form action="{$action}" method="POST">
                            <div class="support_form createform" style="display: block;">
                                <div class="signupform halfwidth">
                                    <div class="wrapsignupinput">
                                        <input class="input" data-val="true" data-val-required="Ticket title is required." id="Subject" name="subject" placeholder="Ticket title" value="" type="text">
                                    </div>
                                    <div class="wrapsignupinput">
                                        <textarea class="input textarea" cols="20" data-val="true" data-val-required="Ticket text is required." id="Message" name="content" placeholder="       " rows="2"></textarea>
                                    </div>
                                    <div class="text-center">
                                        <input class="btn btn-lg btn-green floatright" value="Send" type="submit">
                                    </div>
                                </div>
                            </div>
                        </form>          
                    </section>
                    <div class="row">
                        <p class="text-black">
           Create a ticket for each item and continue the relevant ticket until the problem is completely solved, and to expedite the troubleshooting, please avoid creating new and varied tickets.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
