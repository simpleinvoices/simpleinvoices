{if preferences == null}
		<P><em>{$LANG.no_preferences}.</em></p>
{else}

	<h3>{$LANG.manage_preferences} ::
	<a href="index.php?module=preferences&view=add">{$LANG.add_new_preference}</a></h3>

	<hr />
	

	<table align="center" class="ricoLiveGrid manage" id="rico_preferences">
	<colgroup>
		<col style='width:10%;' />
		<col style='width:10%;' />
		<col style='width:40%;' />
		<col style='width:10%;' />
	</colgroup>
	<thead>
	<tr class="sortHeader">
		<th class="noFilter sortable">{$LANG.actions}</th>
		<th class="index_table sortable">{$LANG.preference_id}</th>
		<th class="index_table sortable">{$LANG.description}</th>
		<th class="noFilter index_table sortable">{$LANG.enabled}</th>
	</tr>
	</thead>

  	{foreach from=$preferences item=preference}

 		<tr class="index_table">
		<td class="index_table">
		<a class="index_table"
		href="index.php?module=preferences&view=details&submit={$preference.pref_id}&action=view">{$LANG.view}</a> ::
		<a class="index_table"
		href="index.php?module=preferences&view=details&submit={$preference.pref_id}&action=edit">{$LANG.edit}</a> </td>
		<td class="index_table">{$preference.pref_id}</td>
		<td class="index_table">{$preference.pref_description}</td>
		<td class="index_table">{$preference.enabled}</td>
		</tr>

		{/foreach}
		</table>
	{/if}
<br />
<div style="text-align:center;"><a href="docs.php?t=help&p=inv_pref_what_the" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img> What's all this "Invoice Preference" stuff about?</a></div>
