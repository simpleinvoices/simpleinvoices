{*
/*
* Script: manage.tpl
* 	 Custom fields manage template
*
* Authors:
*	 Justin Kelly, Nicolas Ruflin, Ben Brown
*
* Last edited:
* 	 2007-09-22
*
* License:
*	 GPL v2 or above
*/
*}
{if $cfs == null}
<p><em>{$LANG.no_invoices}.</em></p>
{else}
<h3>{$LANG.manage_custom_fields}</h3>
<div style="text-align:center;"><a href="docs.php?t=help&p=what_are_custom_fields" rel="gb_page_center[450, 450]">{$LANG.what_are_custom_fields}<img src="./images/common/help-small.png"></img></a> :: <a href="docs.php?t=help&p=manage_custom_fields" rel="gb_page_center[450, 450]">{$LANG.whats_this_page_about}<img src="./images/common/help-small.png"></img></a></div>
<hr />
<table id="flex2" style="display:none"></table>

 {include file='../modules/custom_fields/manage.js.php'}
{*
<table class="manage" id="live-grid" align="center">
	<colgroup>
		<col style='width:7%;' />
		<col style='width:10%;' />
		<col style='width:43%;' />
		<col style='width:40%;' />
	</colgroup>
	<thead>
		<tr class="sortHeader">
			<th class="noFilter sortable">{$LANG.actions}</th>
			<th class="index_table sortable">{$LANG.id}</th>
			<th class="index_table sortable">{$LANG.custom_field}</th>
			<th class="index_table sortable">{$LANG.custom_label}</th>
		</tr>
	</thead>
	{foreach from=$cfs item=cf}
	<tr class="index_table">
		<td class="index_table">
			<a title="{$LANG.view}" class="index_table" href="index.php?module=custom_fields&view=details&submit={$cf.cf_id}&action=view"><img src="images/common/view.png" height="16" border="0" align="absmiddle" /></a>
			<a title="{$LANG.edit}" class="index_table" href="index.php?module=custom_fields&view=details&submit={$cf.cf_id}&action=edit"><img src="images/common/edit.png" height="16" border="0" align="absmiddle" /></a> </td>
		<td class="index_table">{$cf.cf_id}</td>
		<td class="index_table">{$cf.filed_name}</td>
		<td class="index_table">{$cf.cf_custom_label}</td>
	</tr>
	{/foreach}
</table>
*}
{/if}
