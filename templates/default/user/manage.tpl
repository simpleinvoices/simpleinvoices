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
                <img src="./images/common/add.png" alt=""/>
                Add new User {* TODO $LANG  *}
            </a>

        </td>
    </tr>
</table>
<table id="manageGrid" style="display:none"></table>
 {include file='../modules/user/manage.js.php' LANG=$LANG}
