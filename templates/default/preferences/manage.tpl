{*
/*
* Script: manage.tpl
* 	 Invoice Preferences manage template
*
* Authors:
*	 Justin Kelly, Ben Brown
*
* Last edited:
* 	 2007-09-22
*
* License:
*	 GPL v2 or above
*/
*}
{if preferences == null}
<P><em>{$LANG.no_preferences}.</em></p>
{else}
<h3>{$LANG.manage_preferences} :: <a href="index.php?module=preferences&view=add">{$LANG.add_new_preference}</a></h3>
<hr />
<table id="manageGrid" style="display:none"></table>

 {include file='../modules/preferences/manage.js.php'}

{/if}
<br />
<div style="text-align:center;"><a href="docs.php?t=help&p=inv_pref_what_the" rel="gb_page_center[450, 450]"><img src="./images/common/help-small.png"></img> What's all this "Invoice Preference" stuff about?</a></div>
