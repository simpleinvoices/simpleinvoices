{literal}
<script>

/**

jquery stuff for tab_menu extension

**/

$(document).ready(function(){

  	//TODO - grab the active page and put in here - so correct tab is open for that page
	$("#tabmenu > ul").tabs("select", '{/literal}{$active_tab} {literal}');
    
 });
 </script>
 {/literal}
 