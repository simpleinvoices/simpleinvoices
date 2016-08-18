/*
* jQuery SuperBox! 0.9.1
* Copyright (c) 2009 Pierre Bertet (pierrebertet.net)
* Licensed under the MIT (MIT-LICENSE.txt)
*
*/
(function(i){var l,t,r,q,a,p,h,o,j,w,b={boxId:"superbox",boxClasses:"",overlayOpacity:0.8,boxWidth:"600",boxHeight:"400",loadTxt:"Loading...",closeTxt:"Close",prevTxt:"Previous",nextTxt:"Next",beforeShow:function(){}},x={},m=false,s=i([]);
i.superbox=function(){w=i.extend({},b,i.superbox.settings);if(i.browser.msie&&i.browser.version<7){s=s.add("select")
}n();z()};function z(){i("a[rel^=superbox],area[rel^=superbox]").each(function(){var D=i(this),F=D.attr("rel"),B=F.match(/^superbox\[([^#\.\]]+)/)[1],E=F.replace("superbox","").match(/([#\.][^#\.\]]+)/g)||[],C=w.boxId,A=w.boxClasses;
this._relSettings=F.replace("superbox["+B+E.join("")+"]","");i.each(E,function(G,H){if(H.substr(0,1)=="#"){C=H.substr(1)
}else{if(H.substr(0,1)=="."){A+=" "+H.substr(1)}}});if(B.search(/^image|gallery|iframe|content|ajax$/)!=-1){D.superbox(B,{boxId:C,boxClasses:A})
}})}i.fn.superbox=function(B,A){A=i.extend({},w,A);i.superbox[B](this,A)};i.extend(i.superbox,{image:function(C,A,B){var E=f(C.get(0)),D=false;
if(E&&B=="gallery"){D=E[1]}else{if(E){D=E[0]}}C.click(function(F){F.preventDefault();
k();if(B=="gallery"){c(C,E[0])}y(function(){var H=false,G;if(D){H=D.split("x")}G=i('<img src="'+C.attr("href")+'" title="'+(C.attr("title")||C.text())+'" />');
G.load(function(){g(G,H);e({boxClasses:"image "+A.boxClasses,boxId:A.boxId});u()}).appendTo($innerbox)
})})},gallery:function(B,A){var C=f(B.get(0));if(!x[C[0]]){x[C[0]]=[]}x[C[0]].push(B);
B.get(0)._superboxGroupKey=(x[C[0]].length-1);i.superbox.image(B,A,"gallery")},iframe:function(B,A){var C=f(B.get(0));
B.click(function(D){D.preventDefault();k();y(function(){var F=false,E;if(C){F=C[0].split("x")
}A=i.extend({},A,{boxWidth:F[0]||A.boxWidth,boxHeight:F[1]||A.boxHeight});E=i('<iframe src="'+B.attr("href")+'" name="'+B.attr("href")+'" frameborder="0" scrolling="auto" hspace="0" width="'+A.boxWidth+'" height="'+A.boxHeight+'"></iframe>');
E.load(function(){q.width(A.boxWidth+"px");$innerbox.height(A.boxHeight+"px");e({boxClasses:"iframe "+A.boxClasses,boxId:A.boxId});
u()}).appendTo($innerbox)})})},content:function(B,A){var C=f(B.get(0));B.click(function(D){D.preventDefault();
k();y(function(){var E=false;if(C){E=C[0].split("x")}A=i.extend({},A,{boxWidth:E[0]||A.boxWidth,boxHeight:E[1]||A.boxHeight});
q.width(A.boxWidth+"px");$innerbox.height(A.boxHeight+"px");i(B.attr("href")).clone().appendTo($innerbox).show();
e({boxClasses:"content "+A.boxClasses,boxId:A.boxId});u()})})},ajax:function(B,A){var C=f(B.get(0));
B.click(function(D){D.preventDefault();k();y(function(){var E=false;if(C&&C[3]){E=C[3].split("x")
}A=i.extend({},A,{boxWidth:E[0]||A.boxWidth,boxHeight:E[1]||A.boxHeight});q.width(A.boxWidth+"px");
$innerbox.height(A.boxHeight+"px");i.get(C[2],function(F){i(F).appendTo($innerbox)
});e({boxClasses:"ajax "+A.boxClasses,boxId:A.boxId});u()})})}});function f(A){return A._relSettings.match(/([^\[\]]+)/g)
}function g(A,B){q.width(A.width()+($innerbox.css("paddingLeft").slice(0,-2)-0)+($innerbox.css("paddingRight").slice(0,-2)-0));
$innerbox.height(A.height());if(B&&B[0]!=""){q.width(B[0]+"px")}if(B&&B[1]!=""&&B[1]>A.height()){$innerbox.height(B[1]+"px")
}}function c(C,D){h.show();m=true;var A=C.get(0)._superboxGroupKey+1,B=A-2;if(x[D][A]){o.removeClass("disabled").unbind("click").bind("click",function(){x[D][A].click()
})}else{o.addClass("disabled").unbind("click")}if(x[D][B]){j.removeClass("disabled").unbind("click").bind("click",function(){x[D][B].click()
})}else{j.addClass("disabled").unbind("click")}}function e(A){q.attr("id",A.boxId).attr("class",A.boxClasses)
}function d(){i(document).unbind("keydown");p.hide();h.hide();t.hide().css({position:"fixed",top:0});
$innerbox.empty()}function v(A){d();l.fadeOut(300,function(){s.show()});m=false}function y(B){var A=function(){if(i.browser.msie&&i.browser.version<7){t.css({position:"absolute",top:"50%"})
}s.hide();p.show();B()};if(m){l.css("opacity",w.overlayOpacity).show();A()}else{l.css("opacity",0).show().fadeTo(300,w.overlayOpacity,A)
}}function k(){t.show();$innerbox.empty();q.css({position:"absolute",top:"-99999px"})
}function u(A,B){p.hide();i(document).unbind("keydown").bind("keydown",function(C){if(C.keyCode==27){v()
}if(C.keyCode==39&&o.is(":visible")){o.click()}if(C.keyCode==37&&j.is(":visible")){j.click()
}});q.css({position:"static",top:0,opacity:0});if(i.browser.msie&&i.browser.version<8){q.css({position:"relative",top:"-50%"});
if(i.browser.msie&&i.browser.version<7){t.css({position:"absolute",top:"50%"})}}if(i(window).height()<t.height()){t.css({position:"absolute",top:(t.offset().top+10)+"px"})
}w.beforeShow();q.fadeTo(300,1)}function n(){if(!i.superbox.elementsReady){l=i('<div id="superbox-overlay"></div>').appendTo("body").hide();
t=i('<div id="superbox-wrapper"></div>').appendTo("body").hide();r=i('<div id="superbox-container"></div>').appendTo(t);
q=i('<div id="superbox"></div>').appendTo(r);$innerbox=i('<div id="superbox-innerbox"></div>').appendTo(q);
h=i('<p class="nextprev"></p>').appendTo(q).hide();j=i('<a class="prev"><strong><span>'+w.prevTxt+"</span></strong></a>").appendTo(h);
o=i('<a class="next"><strong><span>'+w.nextTxt+"</span></strong></a>").appendTo(h);
a=i('<p class="close"><a id="sb-close"><strong><span>'+w.closeTxt+"</span></strong></a></p>").prependTo(q).find("a");
p=i('<p class="loading">'+w.loadTxt+"</p>").appendTo(r).hide();l.add(t).add(a).click(function(){v()
});q.click(function(A){A.stopPropagation()});i.superbox.elementsReady=true}}})(jQuery);
