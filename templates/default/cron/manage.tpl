{*
/*
* Script: manage.tpl
* 	 Manage invoices template
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

<table class="buttons" align="center">
    <tr>
        <td>

            <a href="index.php?module=cron&amp;view=add" class="positive">
                <img src="./images/common/add.png" alt="" />
                {$LANG.new_recurrence}
            </a>

        </td>
    </tr>
</table>

{if $number_of_crons.count == 0}
	
	<br />
	<br />
	<span class="welcome">{$LANG.no_crons}</span>
	<br />
	<br />
	<br />
	<br />
{else}


	<br />
	<table id="manageGrid" style="display:none"></table>
	{include file='../modules/cron/manage.js.php'}

{/if}


