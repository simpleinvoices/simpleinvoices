{*
/*
* Script: ./extensions/matts_luxury_pack/templates/default/iframe_customers/save.tpl
* 	 Customer save template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Soif
*
* License:
*	 GPL v2 or above
*
* Website:
*	http://www.simpleinvoices.org
*/
*}

{if $saved == true }
	<div class="si_message_ok">{$LANG.save_customer_success}</div>
{else}
	<div class="si_message_error">{$LANG.save_customer_failure}</div>
{/if}

/*
{if $smarty.post.cancel == null }
{*	<meta http-equiv="refresh" content="2;url=index.php?module=customers&amp;view=manage" />*}
	<meta http-equiv="refresh" content="2;url={$path|substr:3}" />
{else}
{*	<meta http-equiv="refresh" content="0;url=index.php?module=customers&amp;view=manage" />*}
	<meta http-equiv="refresh" content="0;url={$path|substr:3}" />
{/if}
*/

{*/simple/extensions/matts_luxury_pack/templates/default/iframe_customers/refresh.tpl*}
<script type="text/javascript">
/*$("#customer_id").html(optionList).selectmenu('refresh', true);*/
$("#customer_id").append('<option value="' + {$last_id+1} + '">' + {$name} + '</option>').selectmenu('refresh', true);
$("#ship_to_customer_id").append('<option value="' + {$last_id+1} + '">' + {$name} + '</option>').selectmenu('refresh', true);
</script>