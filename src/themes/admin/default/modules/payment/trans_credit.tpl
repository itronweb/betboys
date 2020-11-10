<div id="page_content_inner">
    <h3 class="heading_a title-top">{$title}</h3>
    <div class="md-card uk-margin-medium-bottom">
        <div class="md-card-content">
            <table class="uk-table dataTable uk-table-striped" id="dt_default" role="grid" aria-describedby="dt_default_info">
                <thead>
                    <tr>
                        <th class="sorting">Internal transaction ID</th>
                        <th class="sorting">User</th>
                        <th class="sorting">Pay Amont</th>
                        <th class="sorting">شرح</th>
                        <th class="sorting">شماره پیگیری تراکنش</th>
                        <th class="sorting">Submission Date</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th class="sorting">Internal transaction ID</th>
                        <th class="sorting">User</th>
                        <th class="sorting">Pay Amont</th>
                        <th class="sorting">شرح</th>
                        <th class="sorting">شماره پیگیری تراکنش</th>
                        <th class="sorting">Submission Date</th>
                    </tr>
                </tfoot>
                <tbody class="uk-table uk-table-striped">
                    {foreach from=$transactions item=val}  
                        <tr>
                            <td>
                                {$val.id}
                            </td>
                            <td>
                                {$val.user.first_name} {$val.user.last_name} <span style="direction:ltr;"> ({$val.user.email})</span>
                            </td>
                            <td>
                                {$val.price|price_format}</td>
                            <td>{$val.description}</td>
                            <td> 
                                {$val.trans_id}
                            </td>
                            <td>

                                <label style="color:#8fdf82">
                                    {jdate format='j F Y H:i:s' date=$val.created_at}
                                </label>
                            </td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
        </div>
    </div>
</div>
