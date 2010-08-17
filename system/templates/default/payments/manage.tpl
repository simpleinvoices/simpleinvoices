{*
/*
* Script: manage.tpl
* 	 Payments manage template
*
*
* Last edited:
* 	 2008-09-01
*
* License:
*	 GPL v3 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

	<table class="buttons" align="center">
    <tr>
        <td>
            <a href="./index.php?module=payments&amp;view=process&amp;op=pay_invoice" class="positive">
                <img src="./images/famfam/add.png" alt=""/>
                {$LANG.process_payment}
            </a>

        </td>
    </tr>
	</table>

 
	{if $smarty.get.id }

        <table class="buttons" align="center">
        <tr>
            </td>
            <td>
                <a href="./index.php?module=payments&amp;view=process&amp;id={$smarty.get.id|urlencode}&amp;op=pay_selected_invoice" class="positive">
                    <img src="./images/famfam/money.png" alt=""/>
                    {$LANG.payments_filtered_invoice}
                </a>

            </td>
        </tr>
        </table>
        {if $payments == null}
        	<br />
        	<br />
        	<span class="welcome">{$LANG.no_payments_invoice}</span>
        	<br />
        	<br />
        {else}
            <br />
        	<table id="manageGrid" style="display:none"></table>
        	{include file='../modules/payments/manage.js.php' get=$smarty.get}
        {/if}

	{elseif $smarty.get.c_id }


        {if $payments == null}
        	<br />
        	<br />
        	<span class="welcome">{$LANG.no_payments_customer}</span>
        	<br />
        	<br />
        {else}
        	<br />
    	    <table id="manageGrid" style="display:none"></table>
        	{include file='../modules/payments/manage.js.php' get=$smarty.get}
        {/if}

	{else}

        {if $payments == null}
        	<br />
        	<br />
        	<span class="welcome">{$LANG.no_payments}</span>
        	<br />
        	<br />
        {else}
        	<br />
        	<table id="manageGrid" style="display:none"></table>
        	{include file='../modules/payments/manage.js.php' get=$smarty.get}
        {/if}

	{/if}

<br />
<div style="text-align:center;">
<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_wheres_the_edit_button" title="{$LANG.wheres_the_edit_button}"><img src="./images/common/help-small.png" alt="" /> Wheres the Edit button?</a>
</div>
