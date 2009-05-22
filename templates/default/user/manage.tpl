{*
/*
* Script: manage.tpl
* 	Biller manage template
*
*
* License:
*	 GPL v3 or above
*/
*}
<table class="buttons" align="center">
    <tr>
        <td>

            <a href="index.php?module=user&view=add" class="positive">
                <img src="./images/common/add.png" alt="" />
                {$LANG.user_add}
            </a>

        </td>
    </tr>
</table>
{if $number_of_rows.count == 0}

	<br />
	<br />
	<span class="welcome">{$LANG.no_users}</span>
	<br />
	<br />
	<br />
	<br />


{else}
	
	<br />
	<table id="manageGrid" style="display:none"></table>
	{include file='../modules/user/manage.js.php' LANG=$LANG}
	
{/if}