{*
/*
* Script: manage.tpl
* 	 Extensions manage template
*
* Authors:
*	 Justin Kelly, Ben Brown, Marcel van Dorp
*
* Last edited:
* 	 2009-02-12
*
* License:
*	 GPL v2 or above
*/
*}
{if $exts == null}
<p><em>No extensions registered</em></p>
{else}
<table id="manageGrid" style="display:none"></table>

 {include file='../extensions/manage_extensions/modules/extensions/manage.js.php'}
 
{*
<table class="manage" id="live-grid" align="center">
	<colgroup>
		<col style="width:7%;" />
		<col style="width:10%;" />
		<col style="width:33%;" />
		<col style="width:10%;" />
		<col style="width:15%;" />
	</colgroup>
	<thead>
		<tr class="sortHeader">
			<th class="noFilter sortable">{$LANG.actions}</th>
			<th class="index_table sortable">{$LANG.tax_id}</th>
			<th class="index_table sortable">{$LANG.tax_description}</th>
			<th class="index_table sortable">{$LANG.tax_percentage}</th>
			<th class="noFilter index_table sortable">{$LANG.enabled}</th>
		</tr>
	</thead>
	{foreach from=$exts item=ext}
	<tr class="index_table">
		<td class="index_table"><a title="{$LANG.view}" class="index_table" href="./index.php?module=extensions&amp;view=details&amp;submit={$ext.id}&amp;action=view"><img src="images/common/view.png" height="16" border="0" align="absmiddle" alt="{$LANG.view}" /></a>
			<a title="{$LANG.edit}" class="index_table" href="./index.php?module=extensions&amp;view=details&amp;submit={$ext.id}&amp;action=edit"><img src="images/common/edit.png" height="16" border="0" align="absmiddle" alt="{$LANG.edit}" /></a></td>
		<td class="index_table">{$ext.id}</td>
		<td class="index_table">{$ext.name|escape:html}</td>
		<td class="index_table">{$ext.description|escape:html}</td>
		<td class="index_table">{$ext.enabled|escape:html}</td>
	</tr>
	{/foreach}
</table>
*}
{/if}
