<div>
    <div class="cell" style="margin-top: 120px;">
        <div class="container">
            <div class="maincontent clearfix">
                <div class="content">
                    <header>
                        <h1 class="mark-side">جزئیات پیش بینی</h1>
                    </header>
                    <div class="row">
                        <ul class="accountsummary betsummary">
                            <li>
                                <span>نوع : </span>
                                <span>
                                    {if $betRecord->type == 1}
                                        تکی
                                    {else}
                                        میکس  {$betRecord->type} تایی
                                    {/if}
                                </span>
                            </li>
                            <li>
                                <span>شناسه شرط : </span>
                                <span>
                                    {$betRecord->id}
                                </span></li>
                            <li><span>زمان : </span><span>{jdate format='j F Y - H:i ' date=$betRecord->created_at}</span></li>
                            <li>
                                <span>مبلغ (TRX) :</span>
                                <span>
                                    {$betRecord->stake|price_format}
                                </span>
                            </li>
                            <li><span>ضریب : </span><span>{$betRecord->effective_odd|persian_number}</span></li>
                            <li>

                                {assign var=winning value=$betRecord->stake * $betRecord->effective_odd}
                                {if $betRecord->status eq 0}
                                    <span>مبلغ برد احتمالی:</span>
                                    <span> {$winning|price_format}</span>
                                {else if $betRecord->status eq 1 }
                                    <span>مبلغ برد:</span>
                                    {$winning|price_format}
                                {else if $betRecord->status eq 2}
                                    <span>مبلغ برد:</span>
                                    <span style="color:red">{0|persian_number}</span>
                                {/if}

                            </li>
                        </ul>
                    </div>
                    <div class="responsive_tbl">
                        <table class="table nopointer table-pad">
                            <thead>
                                <tr>
                                    <th>
                                        زمان
                                    </th>
                                    <th>
                                        مسابقه
                                    </th>
                                    <th>
                                        شرط
                                    </th>
                                    <th>
                                        انتخاب
                                    </th>
                                    <th>
                                        ضریب
                                    </th>
                                    <th>
                                        ضریب موثر
                                    </th>
                                    <th>
                                        نتیجه
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {foreach $betRecord->bet_form as $val}
                                    <tr>
                                        <td data-label="زمان">{jdate format='j F Y - H:i' date=$val->created_at}</td>
                                        <td data-label="مسابقه"><strong>{$val->home_team|fa}</strong>-<strong>{$val->away_team|fa}</strong></td>
                                        <td data-label="شرط">
                                            {$val->bet_type|fa}
                                        </td>
                                        <td data-label="انتخاب">{$val->pick|fa} </td>
                                        <td data-label="ضریب">{$val->odd|persian_number}</td>
                                        <td data-label="ضریب موثر">
                                            {if $val->result_status eq 2}
                                                <span style="color:red">{0|persian_number}</span>
                                            {else if $val->result_status eq 0}

                                            {else if $val->result_status eq 1}
                                                {if $val->odd == 1}
                                                    Openی معوقه / کنسل
                                                {else}
                                                    {$val->odd|persian_number}
                                                {/if}
                                            {/if}
                                        </td>
                                        <td data-label="نتیجه" style="direction: ltr;">
                                            <span class="bold">{$val->home_score_ft|persian_number} - {$val->away_score_ft|persian_number}</span>
                                        </td></tr>
                                    {/foreach}

                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
