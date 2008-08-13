$().ready(function() {
  $('.jqmNotice')
    .jqm(
		{
			ajax: '@href',
			trigger: '.jqmTrigger',
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
});