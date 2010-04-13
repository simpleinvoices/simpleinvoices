{*
<div>

<!-- Welcome message - start -->

<div class="welcome">
	<h2>Reports welcome message</h2>
	
	Thank you for choosing Simple Invoices! There are just a couple of things to do before you can start invoicing<br /><br />
	1 - Setup yourself up as a biller - <a href="index.php?module=biller&view=add">click here</a><br />
	
	<br /><br />
	Already know Simple Invoices by heart? You can <a href="">hide this text</a> forever then  <a href="">click here</a>
	

</div>
*}
<!-- Welcome message - end -->
<!-- Do stuff menu  - start -->
<br />
<table align="center">
<tr>
<td>

<h2>Summary<a name="sales" href=""></a></h2>
<table class="buttons" >
    <tr>
        <td>
            <a href="index.php?module=reports&view=report_summary" class="">
                <img src="./images/famfam/money.png" alt="" />
                Summary report
            </a>
    </tr>
</table>
<br />
<h2>{$LANG.sales}<a name="sales" href=""></a></h2>
<table class="buttons" >
    <tr>
        <td>
            <a href="index.php?module=reports&view=report_sales_total" class="">
                <img src="./images/famfam/money.png" alt="" />
                {$LANG.total_sales}
            </a>
            <a href="index.php?module=reports&view=report_sales_by_periods" class="">
                <img src="./images/famfam/money.png" alt="" />
                {$LANG.monthly_sales_per_year}
            </a>
            <a href="index.php?module=reports&view=report_sales_customers_total" class="">
                <img src="./images/famfam/money.png" alt="" />
                {$LANG.sales_by_customers} 
            </a>                              </td>
    </tr>
</table>
<br />
<h2>{$LANG.expense}</h2>
<table class="buttons" >
    <tr>
        <td>

            <a href="index.php?module=reports&view=report_tax_vs_sales_by_period" class="">
                <img src="./images/famfam/money_delete.png" alt="" />
                Monthly tax summary per year
            </a>
            <a href="index.php?module=reports&view=report_expense_account_by_period" class="">
                <img src="./images/famfam/money_delete.png" alt="" />
                Expense accounts summary
            </a>
            

        </td>
    </tr>
</table>
<br />
<h2>{$LANG.tax}</h2>
<table class="buttons" >
    <tr>
        <td>

            <a href="index.php?module=reports&view=report_tax_total" class="">
                <img src="./images/famfam/money_delete.png" alt="" />
                {$LANG.total_taxes}
            </a>
            

        </td>
    </tr>
</table>
<br />

{if $defaults.inventory == "1"}
    <h2>{$LANG.profit}</h2>
    <table class="buttons" >
        <tr>
            <td>

                <a href="index.php?module=reports&view=report_invoice_profit" class="">
                    <img src="./images/famfam/money.png" alt="" />
                    {$LANG.profit_per_invoice}
                </a>
                

            </td>
        </tr>
    </table>
    <br />
{/if}

<h2>{$LANG.products}</h2>
<table class="buttons" >
    <tr>
        <td>

            <a href="index.php?module=reports&view=report_products_sold_total" class="">
                <img src="./images/famfam/cart.png" alt="" />
                {$LANG.product_sales
            </a>

            <a href="index.php?module=reports&view=report_products_sold_by_customer" class="">
                <img src="./images/famfam/cart.png" alt="" />
                {$LANG.products_by_customer}
            </a>            

        </td>
    </tr>
</table>
<br />

<h2>{$LANG.biller_sales}</h2>
<table class="buttons" >
    <tr>
        <td>

            <a href="index.php?module=reports&view=report_biller_total" class="">
                <img src="./images/famfam/user_suit.png" alt="" />
                {$LANG.biller_sales}
            </a>

            <a href="index.php?module=reports&view=report_biller_by_customer" class="">
                <img src="./images/famfam/user_suit.png" alt="" />
                {$LANG.biller_sales_by_customer_totals} {* TODO change this - remove total *}
            </a>            

        </td>
    </tr>
</table>
<br />

		


<h2>{$LANG.debtors}</h2>
<table class="buttons" >
    <tr>
        <td>
             <a href="index.php?module=reports&view=report_debtors_by_amount" class="">
                <img src="./images/famfam/vcard.png" alt="" />
                {$LANG.debtors_by_amount_owed}
            </a>
    
    
           <a href="index.php?module=reports&view=report_debtors_by_aging" class="">
                <img src="./images/famfam/vcard.png" alt="" />
                 {$LANG.debtors_by_aging_periods}
            </a>
            
             <a href="index.php?module=reports&view=report_debtors_owing_by_customer" class="">
                <img src="./images/famfam/vcard.png" alt="" />
                 {$LANG.total_owed_per_customer}
            </a>
    
    
           <a href="index.php?module=reports&view=report_debtors_aging_total" class="">
                <img src="./images/famfam/vcard.png" alt="" />
                 {$LANG.total_by_aging_periods}
            </a>
        </td>
    </tr>

 
</table>
<br />

<h2>{$LANG.Other}</h2>
<table class="buttons" >
    <tr>
        <td>

            <a href="index.php?module=reports&view=database_log" class="">
                <img src="./images/famfam/database.png" alt="" />
                {$LANG.database_log}
            </a>

        </td>
    </tr>
</table>


</td>
</tr>
</table>
<br />
