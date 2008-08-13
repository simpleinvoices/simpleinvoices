$(document).ready(init);

function init(){

	/*
	Load the jquery datePicker with out config
	*/
	if($.datePicker){
		$.datePicker.setDateFormat('ymd','-');
		$('input.date-picker').datePicker({startDate:'01/01/1970'});
		$('input#date2').datePicker({endDate:'01/01/1970'});
	}
	if($(".showdownloads")){
		$(".showdownloads").click(function(){
				var offset = $(this).offset();
				$(this)
					.next(".downloads")
						.css("top", offset.top + "px")
						.css("left", offset.left + "px")
						.css("position", "absolute")
						.css("background-color", "#F1F1F1")
						.css("padding", "5px")
						.css("border", "solid 1px #CCC")
						.hover(function(){}, function(){$(this).hide()})
						.show();
				return false;
			})
	}
	if($("#ac_me")){
		$("#ac_me").autocomplete("auto_complete_search.php", { minChars:1, matchSubset:1, matchContains:1, cacheLength:10, onItemSelect:selectItem, formatItem:formatItem, selectOnly:1 });
	}
	
	if ($('#container-1'))
		$('#container-1').tabs();
			
	if($('#trigger-tab'))
		$('#trigger-tab').after('<p><a href="#" onclick="$(\'#container-1\').triggerTab(3); return false;">Activate third tab</a></p>');
				
	if($('#custom-tab-by-hash')){
		$('#custom-tab-by-hash').click(function() {
		    var win = window.open(this.href, '', 'directories,location,menubar,resizable,scrollbars,status,toolbar');
		    win.focus();
		});
	}
}

function selectItem(li) {
	if (li.extra)
        document.getElementById("js_total").innerHTML= " " + li.extra[0] + " "
}

function formatItem(row) {
	return row[0] + "<br><i>" + row[1] + "</i>";
}

/*
 function: siModal
 description: a wrapper function for jqModal, 
 example use in a template
   <a href="docs.php?t=help&p=required_field" name=".a_biller_name" onclick="siModal(jQuery(this))" >
 -  put the link in href
 -  put the target div class name in name
 -  and call this function onclick
   
  example target div:   <div class="jqmNotice a_biller_name"></div>
   
  in target div remember to put in jqmNotice to load the jqModal notice css
*/
function siModal(url)
	{
		
		var url_request = url.attr('href');
		var url_target = url.attr('name');
		var url_trigger = $(this);
		
		/*
		console.log("URL href:  %s ", url_request);
		console.log("URL Target:  %s ", url_target);
		*/
		
		 $(url_target)
		    .jqm(
				{
					ajax: url_request,
					trigger: url_trigger,
					overlay: 0,
					onShow: function(h) {
				        /* callback executed when a trigger click. Show notice */
				        h.w.css('opacity',0.92).show(); 
				    },
				    onHide: function(h) {
				        /* callback executed on window hide. Hide notice, overlay. */
				        h.w.slideUp("slow",function() { if(h.o) h.o.remove(); }); } 
					}  
			);
	
	} 
	


