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
{if $number_of_rows.count == 0}
	<p><em>{$LANG.no_billers}</em></p>
	<table class="buttons" align="center">
    <tr>
        <td>
            <a href="./index.php?module=billers&view=add" class="positive">
                <img src="./images/famfam/add.png" alt=""/>
                {$LANG.add_new_biller}
            </a>

        </td>
    </tr>
 </table>
	
{else}
	<table class="buttons" align="center">
    <tr>
        <td>
            <a href="./index.php?module=billers&view=add" class="positive">
                <img src="./images/famfam/add.png" alt=""/>
                {$LANG.add_new_biller}
            </a>

        </td>
    </tr>
 </table>

<table id="manageGrid" style="display:none"></table>
 {include file='../modules/billers/manage.js.php' LANG=$LANG}

{/if}
