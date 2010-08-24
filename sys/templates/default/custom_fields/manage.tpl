{*
/*
* Script: manage.tpl
* 	 Custom fields manage template
*
* License:
*	 GPL v2 or above
*/
*}
{if $cfs == null}
<p><em>{$LANG.no_invoices}.</em></p>
{else}

<div style="text-align:center;">
<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_what_are_custom_fields" title="{$LANG.what_are_custom_fields}">{$LANG.what_are_custom_fields}<img src="./images/common/help-small.png" alt="" /></a> ::
<a class="cluetip" href="#"	rel="index.php?module=documentation&amp;view=view&amp;page=help_manage_custom_fields" title="{$LANG.whats_this_page_about}">{$LANG.whats_this_page_about}<img src="./images/common/help-small.png" alt="" /></a>
</div>

<table id="manageGrid" style="display:none"></table>

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
			<a title="{$LANG.view}" class="index_table" href="index.php?module=custom_fields&amp;view=details&submit={$cf.cf_id|urlencode}&action=view"><img src="images/common/view.png" height="16" border="0" align="absmiddle" alt="" /></a>
			<a title="{$LANG.edit}" class="index_table" href="index.php?module=custom_fields&amp;view=details&submit={$cf.cf_id|urlencode}&action=edit"><img src="images/common/edit.png" height="16" border="0" align="absmiddle" alt="" /></a> </td>
		<td class="index_table">{$cf.cf_id|htmlsafe}</td>
		<td class="index_table">{$cf.filed_name|htmlsafe}</td>
		<td class="index_table">{$cf.cf_custom_label|htmlsafe}</td>
	</tr>
	{/foreach}
</table>
*}
{/if}
