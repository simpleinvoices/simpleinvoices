{*
/*
 * Script: ./extensions/matts_luxury_pack/templates/default/system_defaults/edit.tpl
 * 	Edit a System Preference
 *
 * Authors:
 *	 yumatechnical@gmail.com
 *
 * Last edited:
 * 	 2016-08-30
 *
 * License:
 *	 GPL v2 or above
 *
 * Website:
 * 	http://www.simpleinvoices.org
 */
*}
<form name="frmpost" action="index.php?module=system_defaults&amp;view=save" method="post" onsubmit="return frmpost_Validator(this)">

	<div class="si_center">
		<h3>{$LANG.edit} {$description|htmlsafe}</h3>
	</div>

	<div class="si_form">
		<table>
			<tr>
				<th>{$description|htmlsafe}</th>
				<td>{$value}</td>
			</tr>
		</table>
	</div>

	<div class="si_toolbar si_toolbar_form">
            <button type="submit" class="positive" name="submit" value="{$LANG.save}">
                <img class="button_img" src="./images/common/tick.png" alt="tick" />
                {$LANG.save}
            </button>

            <a href="./index.php?module=system_defaults&view=manage" class="negative">
                <img src="./images/common/cross.png" alt="cross" />
                {$LANG.cancel}
            </a>
    </div>


	<input type="hidden" name="name" value="{$default|htmlsafe}">
	<input type="hidden" name="op" value="update_system_defaults" />
 </form>
