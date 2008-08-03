

(function($){

   $.fn.linkedSelect = function(url,destination,params) {

      var params = $.extend({

         firstOption : 'Please Select',

         loadingText : 'Loading...'

      },params);

      var $dest = $(destination);

      return this.each(function(){

         $(this).bind('change', function() {

            var $$ = $(this);

            $dest.attr('disabled','false')
                 .append('<option value="">' +params.loadingText+ '</option>')
                 .ajaxStart(function(){

                    $$.show();

            });

            $.getJSON(url,{str: $$.val() }, function(j){

               if (j.length > 0) {

                  var options = '<option value="">' +params.firstOption+ '</option>';

                  for (var i = 0; i < j.length; i++) {

                     options += '<option value="' + j[i].optionValue + '">' + j[i].optionDisplay + '</option>';

                  }

               }

               $dest.removeAttr('disabled')
                    .html(options)
                    .find('option:first')
                    .attr('selected', 'selected');

            }); // end getJSON

         });  // end change

      }); // end return each

   };  // end function

})(jQuery);
