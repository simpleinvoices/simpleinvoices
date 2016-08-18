/*
* CongelarFilaColumna is a  jQuery plugin that allows freezing of rows and columns.
* Scrolling can be enabled. Both rows and columns can be frozen. Rows to be frozen 
* should be placed in 'thead' (whole frozen header). You can freeze rows and columns combined with colspan or rowspan.
*
* Copyright (c) 2016
* Author: Agustin Rios Reyes.
* Email:  nitsugario@gmail.com
*
* Licensed under MIT
* http://www.opensource.org/licenses/mit-license.php
*
*/
(function($)
{
	$.fn.CongelarFilaColumna = function (method)
	{
		
		var defaults =
		{
			width:      '100%',
			height:     '100%',
			Columnas:  1, //Number of columns fixed
			soloThead : false //only the fixed header
		};
		var settings = {};
		var methods  =
		{
			
			init: function (options)
			{
				settings = $.extend({}, defaults, options);

				return this.each(function ()
				{
					var $tabla = $(this); 

					if (helpers._EsUnaTabla($tabla))
					{ 
						methods.setup.apply(this, Array.prototype.slice.call(arguments, 1));
					}
					else
					{
						$.error('La Tabla No es válida. :( ');
					};
				});
			},
			setup: function ()
			{
				var $wrapper,
					$tabla      = $(this), 
					tabla       = this,
					contenedor  = $(this).parent(), 
					classT      = $tabla.attr("class"); 

				helpers._ObtenerAsignarAnchoAlto($tabla); 

				var thead       = $tabla.find("thead").clone(),
					anchotabla  = $tabla.width(),
					tablaTH     = $("<table>").attr("class",classT).append(thead),
					$tablaTHCol = $("<table>").attr("class",classT).append($("<thead>")),
					$tablaTBCol = $("<table>").attr("class",classT).append($("<tbody>"));

				settings.scrollbarOffset  = helpers._OptenerWidthBarraScroll();
				$tabla.css({'width':anchotabla});
				
				helpers._ClonarHeaderColumnasACongelar($tabla,$tablaTHCol,'thead',settings.Columnas);
				helpers._ClonarHeaderColumnasACongelar($tabla,$tablaTBCol,'tbody',settings.Columnas);

				if (!$tabla.closest('.fht-table-wrapper').length)
				{
					$tabla.addClass('fht-table');
					$tabla.wrap('<div class="fht-table-wrapper"></div>');
				}

				$wrapper = $tabla.closest('.fht-table-wrapper');

				if ( $wrapper.find('.fht-fixed-column').length == 0)
				{
					$tabla.wrap('<div class="fht-fixed-body"></div>');
					if( !settings.soloThead )
						$('<div class="fht-fixed-column"></div>').prependTo($wrapper);
					$fixedBody    = $wrapper.find('.fht-fixed-body');
				}

				$wrapper.css({
					width: settings.width,
					height: settings.height
				});

				if (!$tabla.hasClass('fht-table-init'))
				{
					$tabla.wrap('<div class="fht-tbody"><div class="fht-tbody-conten"></div></div>');
				}

				$tabla.closest('.fht-tbody-conten').css({'width':anchotabla});
				$divBody = $wrapper.find('div.fht-tbody');

				if (!$tabla.hasClass('fht-table-init'))
				{

					$divHead = $('<div class="fht-thead"></div>').prependTo($fixedBody).css({'width':anchotabla});
					
					tablaTH.css({'width':anchotabla});

					$divHead.append(tablaTH).css({'width':anchotabla});
				}
				else
				{
					$divHead = $wrapper.find('div.fht-thead');
				}

				$tabla.css({
					'margin-top': -$divHead.outerHeight(true)
				});

				var tbodyHeight = $wrapper.height() - $divHead.outerHeight(true);

				$divBody.css({
					'height': tbodyHeight
				});

				$tabla.addClass('fht-table-init');

				if( !settings.soloThead )
					var $fixedColumn    = $wrapper.find('.fht-fixed-column');

				var	$thead          = $('<div class="fht-thead"></div>').append($tablaTHCol),
					$tbody          = $('<div class="fht-tbody"></div>').append($tablaTBCol),
					$fixedBody      = $wrapper.find('.fht-fixed-body'),
					fixedBodyWidth  = $wrapper.width(),
					fixedBodyHeight = $fixedBody.find('.fht-tbody').outerHeight() - settings.scrollbarOffset;

				if( !settings.soloThead )
					$thead.appendTo($fixedColumn);

				var AnchoCol = $thead.find('table').width()+1;

				$thead.find('table').css({'width': AnchoCol});
				$tbody.find('table').css({'width': AnchoCol});

				if( !settings.soloThead )
				{
					$tbody.appendTo($fixedColumn).css({
						'margin-top': "-0.8px",
						'height': fixedBodyHeight+2,
						'background-color': '#ffffff'
					});
					
					$fixedColumn.css({
						'width':  AnchoCol
					});
				}
				
				$fixedBody.css({
					'width': fixedBodyWidth
				});

				helpers._bindScroll($divBody);
				return tabla;
			}
		}
		var helpers  =
		{
			/*
			* return boolean
			* valida si el elemento tien un thead y un tbody.
			*/
			_EsUnaTabla: function($obj)
			{
				var $tabla = $obj,
				TieneTable = $tabla.is('table'),
				TieneThead = $tabla.find('thead').length > 0,
				TieneTbody = $tabla.find('tbody').length > 0;

				if (TieneTable && TieneThead && TieneTbody)
				{
					return true;
				}

				return false;
			},
			_ObtenerAsignarAnchoAlto: function($obj)
			{
				var $tabla = $obj;

				$tabla.find("tr").each(function()
				{
					var $ThTb     = $(this).parent(),
						esthead   = $ThTb.is("thead"),
						estbody   = $ThTb.is("tbody"),
						TipoThTd  = "";

					if (esthead)
					{
						TipoThTd  = "th";
					}
					else if(estbody)
					{
						TipoThTd  = "td";
					};

					$(this).find(TipoThTd).each(function()
					{
						var alto  = $(this).height(),
							ancho = $(this).width();
						$(this).css({'width':ancho,'height':alto});
					});
				});
			},
			_ClonarHeaderColumnasACongelar: function($tabla,$donde,tipothtd,cuantasCols)
			{
				var ThTd           = "",
					rowspanAntes   = false,
					colspanAntes   = false,
					mascolumnas    = true,
					cuantosColspan = 0,
					cuentaColumnas = 0,
					cuentaRowspan  = 0,
					cuentafilass   = 0,
					$trThTd        = $tabla.find(tipothtd),
					$newRow;

				if (tipothtd == "thead")
				{
					ThTd  = "th";
				}
				else if(tipothtd == "tbody")
				{
					ThTd  = "td";
				};

				$trThTd.find("tr").each(function()
				{
					if ( cuantasCols == 1 )
					{
						if (!rowspanAntes)
						{
							var tr = $(this).clone(true).html("");

							$(this).find(ThTd).each(function(index)
							{
								if (index == 0)
									{
										$newRow = tr.appendTo($donde.find(tipothtd));
										if (!colspanAntes) {

											$newRow.append($(this).clone());
										}
										else
										{
											console.log("Error: colspan in 1 column.");
										}

										if ( $(this).attr( "rowspan" ) ) {
											rowspanAntes  = true;
											cuentaRowspan = $(this).attr("rowspan");
										};
										
										if ( $(this).attr( "colspan" ) ) {
											colspanAntes   = true;
											cuantosColspan = $(this).attr("colspan");
										}
										if (colspanAntes) {
											if (cuantosColspan == cuantasCols) {
												colspanAntes   = false;
												mascolumnas    = false;
												cuentaColumnas = cuantasCols;
											}else if( (cuantasCols-cuantosColspan) > 0){
												colspanAntes   = false;
												mascolumnas    = true;
												cuentaColumnas = cuantosColspan;
											}
										}
									}
							});

							colspanAntes   = false;
							mascolumnas    = true;
							cuantosColspan = 0;
							cuentaColumnas = 1;

							if (rowspanAntes) {
								cuentaRowspan--;
							}
						}
						else
						{
							cuentaRowspan--;
							var tr = $(this).clone(true).html("");
							tr.appendTo($donde.find(tipothtd));
						}

						if ( cuentaRowspan == 0 )
						{
							rowspanAntes = false;

						}
					}
					else
					{
						if (!rowspanAntes)
						{

							var tr = $(this).clone(true).html("");
							
							$(this).find(ThTd).each(function(index)
							{
								if(cuentaColumnas < cuantasCols )
								if (index < cuantasCols && mascolumnas)
								{
									if (index == 0)
									{
										$newRow = tr.appendTo($donde.find(tipothtd));

										if (!colspanAntes)
										{
											$newRow.append($(this).clone());
										}

										if ( $(this).attr( "rowspan" ) )
										{
											rowspanAntes  = true;
											cuentaRowspan = $(this).attr("rowspan");
										};
										
										if ( $(this).attr( "colspan" ) )
										{
											colspanAntes   = true;
											cuantosColspan = $(this).attr("colspan");
										}

										if (colspanAntes)
										{
											if (cuantosColspan == cuantasCols)
											{
												colspanAntes   = false;
												mascolumnas    = false;
												cuentaColumnas = cuantasCols;
											}
											else if( (cuantasCols-cuantosColspan) > 0)
											{
												colspanAntes   = false;
												mascolumnas    = true;
												cuentaColumnas = cuantosColspan;
											}
										}
									}
									else
									{
										if (colspanAntes)
										{
											if (cuantosColspan == cuantasCols)
											{
												colspanAntes = false;
												mascolumnas  = false;
											}
											else if(cuantosColspan < cuantasCols)
											{
												colspanAntes = false;
												mascolumnas  = true;
											}
										}
										else
										{
											if (!colspanAntes)
											{
												if ( $(this).attr( "colspan" ) )
												{
													colspanAntes   = true;
													cuantosColspan = $(this).attr("colspan");
													cuentaColumnas = parseInt(cuentaColumnas) + parseInt(cuantosColspan);
												}
												else
												{
													cuentaColumnas++;
												}

												$newRow.append($(this).clone());
											}

											if ( $(this).attr( "rowspan" ) )
											{
												rowspanAntes  = true;
												cuentaRowspan = $(this).attr("rowspan");
											};

											if (colspanAntes)
											{
												if (cuantosColspan == cuantasCols)
												{
													colspanAntes   = false;
													mascolumnas    = false;
													cuentaColumnas = cuantasCols;
												}
												else if(cuentaColumnas == cuantasCols)
												{
													colspanAntes   = false;
													mascolumnas    = false;
													cuentaColumnas = cuantasCols;
												}
												else if( (cuantasCols-cuantosColspan) > 0)
												{
													colspanAntes   = false;
													mascolumnas    = true;
												}
											}
										}								
									}
								}
							});

							colspanAntes   = false;
							mascolumnas    = true;
							cuantosColspan = 0;
							cuentaColumnas = 1;

							if (rowspanAntes) {
								cuentaRowspan--;
							}
						}
						else
						{
							cuentaRowspan--;
							var tr = $(this).clone(true).html("");
							tr.appendTo($donde.find(tipothtd));
						}

						if ( cuentaRowspan == 0 )
						{
							rowspanAntes = false;

						}
					}

					cuentafilass++;
				});
			},
			_bindScroll: function($obj)
			{
				var $tabla   = $obj,
					$wrapper = $tabla.closest('.fht-table-wrapper'),
					$thead   = $tabla.siblings('.fht-thead');

				$tabla.bind('scroll', function()
				{
					if( !settings.soloThead )
					{
						var $fixedColumns = $wrapper.find('.fht-fixed-column');

						$fixedColumns.find('.fht-tbody table').css({
							'margin-top': -$tabla.scrollTop()
						});
					}

					$thead.find('table').css({
						'margin-left': -this.scrollLeft
					});

				});
			},
			_OptenerWidthBarraScroll: function()
			{
				var scrollbarWidth = 0;

				if (!scrollbarWidth)
				{
					if (/msie/.test(navigator.userAgent.toLowerCase()))
					{
						var $textarea1 = $('<textarea cols="10" rows="2"></textarea>').css({ position: 'absolute', top: -1000, left: -1000 }).appendTo('body'),
							$textarea2 = $('<textarea cols="10" rows="2" style="overflow: hidden;"></textarea>').css({ position: 'absolute', top: -1000, left: -1000 }).appendTo('body');

						scrollbarWidth = $textarea1.width() - $textarea2.width() + 2; 
						$textarea1.add($textarea2).remove();

					}
					else
					{
						var $div = $('<div />').css({ width: 100, height: 100, overflow: 'auto', position: 'absolute', top: -1000, left: -1000 })
											.prependTo('body').append('<div />').find('div')
											.css({ width: '100%', height: 200 });

						scrollbarWidth = 100 - $div.width();
						$div.parent().remove();

					}
				}

				return scrollbarWidth;
			}
		}
		
		if (methods[method])
		{
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
		}
		else if (typeof method === 'object' || !method)
		{
			return methods.init.apply(this, arguments);
		}
		else
		{
			$.error('El Método "' +  method + '" No existe en el plugin congelaheaderunocolumn! :( ');
		}
	}
})(jQuery);