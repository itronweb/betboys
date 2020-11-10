<div class="more-pad"">
    <div class="cell" style="width:90%;">
        <div class="container">
            <div class="maincontent clearfix">
                <div class="content content-full box-pd">
                    <header class="clearfix">
                        <h1 class="mark-side">Support</h1>
                        <span class="optionKey">
                            <a class="newticket btn btn-sm" href="{site_url}contacts/tickets/new-ticket">Send a new ticket</a>
                            <a class="newticket  btn btn-sm  hidden" href="{site_url}contacts/tickets/ticket-list">Ticket history</a>
                        </span>
                    </header>
                    <section class="formbox mt-20 row_100 clearfix">
                        <div class="support_messages" style="display: block;">
                            <table class="leaguetable support">
                                <tbody>
                                    <tr>
                                        <th>Ticket ID</th>
                                        <th>Date & Time </th>
                                        <th>Ticket title</th>
                                        <th>Status</th>
                                    </tr>
                                    {foreach from=$Tickets item=val}
                                        <tr>

                                            <td> <a href="{site_url}contacts/tickets/view-ticket/{$val->id}">TK-{$val->id}</a></td>

                                            <td>
                                                <a href="{site_url}contacts/tickets/view-ticket/{$val->id}"> <time datetime="">{jdate format='j F Y - h:i a' date=$val->created_at}</time></a>
                                            </td>
                                            <td><a href="{site_url}contacts/tickets/view-ticket/{$val->id}">{$val->subject}</a></td>
                                            <td>
                                                {if $val.status eq 0}
                                                   Waiting for an answer
                                                {else if  $val.status eq 1}
                                                    Open
                                                {else}
                                                    Closed
                                                {/if}
                                            </td>
                                            </a>
                                        </tr>
                                    {/foreach}
                                </tbody>
                            </table>
                        </div>
                    </section>
                    <div class="row">
                        <p>
                            Create a ticket for each item and continue the relevant ticket until the problem is completely solved, and to expedite the troubleshooting, please avoid creating new and varied tickets.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

