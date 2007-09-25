{*
/*
* Script: manage.tpl
* 	Biller manage template
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
{if $billers == null}
<P><em>{$LANG.no_billers}.</em></p>
{else}
<h3>{$LANG.manage_billers} :: <a href='index.php?module=billers&view=add'>{$LANG.add_new_biller}</a></h3>
<hr />
<table class="manage" id="live-grid" align="center">
	<colgroup>
		<col style="width:7%;" />
		<col style="width:10%;" />
		<col style="width:48%;" />
		<!-- <col style="width:10%;" />
			<col style="width:10%;" /> -->
		<col style="width:25%;" />
		<col style="width:10%;" />
	</colgroup>
	<thead>
		<tr class="sortHeader">
			<th class="noFilter sortable">{$LANG.actions}</th>
			<th class=" index_table sortable">{$LANG.biller_id}</th>
			<th class="index_table sortable">{$LANG.biller_name}</th>
			<th class="index_table sortable">{$LANG.email}</th>
			<th class="noFilter index_table sortable">{$LANG.enabled}</th>
		</tr>
	</thead>
	{foreach from=$billers item=biller}
	<tr class="index_table">
		<td class="index_table"><a title="{$LANG.view}" class="index_table" href="index.php?module=billers&view=details&id={$biller.id}&action=view"><img src="images/common/view.png" height="16" border="0" align="absmiddle"/></a>
			<a title="{$LANG.edit}" class="index_table" href="index.php?module=billers&view=details&id={$biller.id}&action=edit"><img src="images/common/edit.png" height="16" border="0" align="absmiddle" /></a></td>
		<td class="index_table">{$biller.id}</td>
		<td class="index_table">{$biller.name}</td>
		<!-- <td class="index_table">{$biller.phone}</td>
			<td class="index_table">{$biller.mobile_phone}</td> -->
		<td class="index_table">{$biller.email}</td>
		<td class="index_table">{$biller.enabled}</td>
	</tr>
	{/foreach}
</table>
{/if}
