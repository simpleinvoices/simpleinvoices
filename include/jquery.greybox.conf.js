  var GB_ANIMATION = true;
    $(document).ready(function(){
      $("a.greybox").click(function(){
        var t = this.title || $(this).text() || this.href;
  GB_show(t,this.href,470,600);
        return false;
      });
    });

