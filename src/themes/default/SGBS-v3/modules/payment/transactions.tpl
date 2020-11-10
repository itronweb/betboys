 <div>
    <div class="cell more-pad">
        <div class="container">
            <div class="maincontent clearfix">
                {include file='partials/dashboard_menu.tpl'}
                <div class="col-lg-10 col-md-9 col-sm-12 col-xs-12 content transaction">
                    <header>
                        <h1>Transaction history</h1>
                    </header>
                    <div class="responsive_tbl">
                        <table class="table nopointer">
                            <thead>
                                <tr>
                                    <th width="20%" scope="col"> Transaction tracking number </th>
                                    <th width="20%" scope="col">Transaction   Time  </th>
                                    <th width="20%" scope="col"> Type of transaction </th>
                                    <th width="20%" scope="col"> Tron amont (TRX)   </th>
                                    <th width="20%" scope="col"> Balace  </th>
                                </tr>
                            </thead>
                            <tbody>
                                {if $transactions->isEmpty()}
                                    <tr>
                                        <td>No record found</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                {else}
                                    {foreach from=$transactions item=val} 
                                        <tr>
                                            <td data-label="Transaction tracking number ">
                                                {$val.trans_id}
                                            </td>
                                            <td data-label="   Time of transaction    " >
                                                {jdate format='j F Y' date=$val.created_at}
                                            </td>
                                            <td data-label="Type of transaction ">
                                                <b>{$val->description}</b>
                                            </td>
                                            <td data-label="  Amount (TRX) " style="font-weight:bold">
                                                {$val.price|price_format}                        
                                            </td>
                                            <td data-label="Balace " style="font-weight:bold">
                                                {$val.cash|price_format}
                                            </td>
                                        </tr>
                                    {/foreach}
                                {/if}
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
