

			$.accordian = function(items, first, options) {


				var active = first;
				var running = 0;

				var titles = options && options.titles || '.title';
				var contents = options && options.contents || '.content';
				var onClick = options && options.onClick || function(){};
				var onShow = options && options.onShow || function(){};
				var onHide = options && options.onHide || function(){};
				var showSpeed = options && options.showSpeed || 'slow';
				var hideSpeed = options && options.hideSpeed || 'fast';


				$(items).not(active).children(contents).hide();
				$(items).not(active).each(onHide);
				$(active).each(onShow);


				$(items).children(titles).click(function(e){


					var p = $(contents, this.parentNode);
					$(this.parentNode).each(onClick);


					if (running || !p.is(":hidden")) return false;

					running = 2;


					$(active).children(contents).not(':hidden').slideUp(hideSpeed, function(){--running;});
					p.slideDown(showSpeed, function(){--running;});

					$(active).each(onHide);
					active = '#' + $(this.parentNode)[0].id;

					$(active).each(onShow);

					return false;

				});



			};

			function simpleLog(message) {
				$('<div>' + message + '</div>').appendTo('#log');
			}

			$(function(){


/*				$.accordian('#list1 > div', '#item11'); */
                                $.accordian('#list1 > div', '#item11', {
                                        titles:'.title',
                                        contents:'.content',
                                        onClick:function(){simpleLog(this.id + ' clicked')},
                                        onShow:function(){simpleLog(this.id + ' shown'); $(this).removeClass('off1').addClass('on1');},
                                        onHide:function(){simpleLog(this.id + ' hidden'); $(this).removeClass('on1').addClass('off1');},
                                        showSpeed:250,
                                        hideSpeed:250
                                });

				$.accordian('#list2 > div', '#item22', {
					titles:'.mytitle',
					contents:'.mycontent',
					onClick:function(){simpleLog(this.id + ' clicked')},
					onShow:function(){simpleLog(this.id + ' shown'); $(this).removeClass('off').addClass('on');},
					onHide:function(){simpleLog(this.id + ' hidden'); $(this).removeClass('on').addClass('off');},
					showSpeed:550,
					hideSpeed:550
				});


			});

