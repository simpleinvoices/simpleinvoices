{*
/*
* Script: quick_view.tpl
* 	 Quick view of invoice template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin
*
* Last edited:
* 	 2007-07-18
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

{if $smarty.get.stage == 1 }

	<br />
				{$LANG.confirm_delete} {$LANG.recurrence}: {$cron.index_name|htmlsafe}
            <br />
            <br />
        <form name="frmpost" action="index.php?module=cron&amp;view=delete&amp;stage=2&amp;id={$smarty.get.id|urlencode}" method="post">
        <table class="buttons" align="center">
            <tr>
                <td>
                    <button type="submit" class="positive" name="submit">
                        <img class="button_img" src="{$include_dir}sys/images/common/tick.png" alt="" /> 
                        {$LANG.yes}
                    </button>

                    <input type="hidden" name="doDelete" value="y" />
                
                    <a href="./index.php?module=cron&amp;view=manage" class="negative">
                        <img src="{$include_dir}sys/images/common/cross.png" alt="" />
                        {$LANG.cancel}
                    </a>
            
                </td>
            </tr>
        </table>
        </form>	
		</table>

{/if}

{if $smarty.get.stage == 2 }

	<div id="top"></b></div>
	<br /><br />
	{$LANG.recurrence} {$id|htmlsafe} {$LANG.deleted}
	<br /><br />

{/if}
