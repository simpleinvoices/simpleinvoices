{*/simple/extensions/matts_luxury_pack/templates/default/iframe_customers/refresh.tpl*}

<script type="text/javascript">
/*$("#customer_id").html(optionList).selectmenu('refresh', true);*/
$("#customer_id").hide().append('<option value="' + {$last_id+1} + '">' + {$name} + '</option>').show();
/*$("#customer_id").append('<option value="' + {$last_id+1} + '">' + {$name} + '</option>').selectmenu('refresh', true);*/
$('select').val({$last_id+1});
/*$("#ship_to_customer_id").append('<option value="' + {$last_id+1} + '">' + {$name} + '</option>').selectmenu('refresh', true);*/
$("#ship_to_customer_id").hide().append('<option value="' + {$last_id+1} + '">' + {$name} + '</option>').show();
/*$('select').val({$last_id+1});*/
</script>

