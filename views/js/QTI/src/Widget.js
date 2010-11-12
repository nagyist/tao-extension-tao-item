/**
 * TAO QTI API
 * 
 * @author CRP Henri Tudor - TAO Team - {@link http://www.tao.lu}
 * @license GPLv2  http://www.opensource.org/licenses/gpl-2.0.php
 * @package taoItems
 * 
 * @requires jquery {@link http://www.jquery.com}
 */

/**
 * The QTIWidget class enables you to build a QTI widgets 
 * from XHTML elements and the given options
 * 
 * @namespace QTI
 * @class QTIWidget
 * @property {Object} options the interaction of parameters 
 */
var QTIWidget = function(options){
	
	//keep the current instance pointer
	var _this = this;

	/**
	 * To access the widget options 
	 * @type Object
	 */
	this.opts = options;

	//the interaction selector, all elements selected must be inside this element,
	// to be able to have some interactions in the same item
	var qti_item_id = "#"+this.opts["id"];
	
	/**
	 * Creates a choice list widget
	 * @see QTIWidget#simple_choice
	 * @see QTIWidget#multiple_choice
	 */
	this.choice = function(){
		var maxChoices =  parseInt(_this.opts["maxChoices"]);
		if(maxChoices > 1 || maxChoices == 0){
			_this.multiple_choice();
		}
		else{
			_this.simple_choice();
		}
	};

	/**
	 * Creates a simple choice list widget
	 */
	 this.simple_choice = function (){	
		$(qti_item_id).addClass('qti_simple_interaction');
		$(qti_item_id +" ul li").bind("click",function(){	
			$(qti_item_id+" ul li").removeClass("tabActive");		
			$(this).addClass("tabActive");					
		});
	};

	/**
	 * Creates a multiple choice list widget
	 */
	this.multiple_choice = function (){
		$(qti_item_id).addClass('qti_multi_interaction');
		$(qti_item_id+" ul li").bind("click",function(){
			if ($(this).hasClass("tabActive")) {
				$(this).removeClass("tabActive");
			}
			else {
				if ($(qti_item_id+" ul li.tabActive").length < _this.opts["maxChoices"] || _this.opts["maxChoices"] == 0) {
					$(this).addClass("tabActive");
				}
			}
		});
	};
	
	/**
	 * We use the html <i>select</i> widget,
	 * the function is listed only to keep the same behavior than the other
	 */
	this.inline_choice = function (){};

	/**
	 * Creates a sortable list widget,
	 * can be horizontal or vertical regarding the orientation parameter
	 */
	this.order = function(){
		
		var sortableOptions = {
			revert: true,
			axis : 'y',
			containment: qti_item_id,
			placeholder: 'sort-placeholder',
			tolerance: 'pointer'
		};
		
		//for an horizontal sortable list
		$(qti_item_id + " ul li").addClass('sort-vertical');
		if(_this.opts['orientation']){
			if(_this.opts['orientation'] == 'horizontal'){
				sortableOptions.axis = 'x';
				sortableOptions.placeholder = 'sort-placeholder-inline';
				sortableOptions['forcePlaceHolderWidth'] = true;
				$(qti_item_id+" ul li")
						.removeClass('sort-vertical')
							.addClass('sort-horizontal')
								.css('display', 'inline');
			}
		}
		
		$(qti_item_id+" ul").sortable(sortableOptions);
		$(qti_item_id+" ul, li").disableSelection();
	};

	/**
	 * Creates a pair association widget, 
	 * where words are dragged from a cloud to pair boxes
	 */
	this.associate = function(){
		
			// max size of a text box to define target max size
			// create empty element in order to droppe in any other element of the item
			$(qti_item_id+" .qti_choice_list li").wrapInner("<div></div>");
			// calculate max size of a word in the cloud
			var maxBoxSize=0;
			$(qti_item_id+" ul.qti_choice_list li > div").each(function(){	
				if ($(this).width()>maxBoxSize){
					maxBoxSize=$(this).width();		
				}
			});
					
			// give a size to the words cloud to avoid graphical "jump" when items are dropped
			$(qti_item_id+" .qti_associate_container .qti_choice_list").height(parseFloat($(".qti_associate_container").height()));
			
			// create the pair of box specified in the maxAssociations attribute	
			if(_this.opts["maxAssociations"] > 0) {
				var pairSize = _this.opts["maxAssociations"];
			}
			else{
				var pairSize = parseInt($(qti_item_id+" .qti_choice_list li").length / 2);
			}
			for (var a=pairSize; a>0; a--){
				var currentPairName=_this.opts["id"]+"_"+a;
				$(qti_item_id+" .qti_associate_container").after("<ul class='qti_association_pair' id='"+currentPairName+"'><li id='"+currentPairName+"_A"+"'></li><li id='"+currentPairName+"_B"+"'></li></ul>");		
			}
			
			// set the size of the drop box to the max size of the cloud words 
			$(qti_item_id+" .qti_association_pair li").width(maxBoxSize+4);
			
			// size the whole pair box to center it
			var pairBoxWidth=0;
			
			$(qti_item_id+" .qti_association_pair:first li").each(function(){
				pairBoxWidth+=$(this).width();
			});
			
			$(qti_item_id+" .qti_association_pair").width(pairBoxWidth+90);
			$(qti_item_id+" .qti_association_pair").css({position:"relative",margin:"0 auto",top:"10px"});	
			
			//place target boxes
			$(qti_item_id+" .qti_association_pair").each(function(){
				$(this).after("<div class='qti_link_associate'></div>");
				
				$(qti_item_id+" .qti_link_associate:last").css("top",$(this).offset().top+23);
				$(qti_item_id+" .qti_link_associate:last").css("left",parseFloat($(this).find("li:first").offset().left)+parseFloat($(this).find("li:first").width())+14);
			});
			
			$(qti_item_id).height( ($(qti_item_id+" .qti_link_associate:last").offset().top) - $(qti_item_id).offset().top); 
			
			//drag element from words cloud
			$(qti_item_id+" .qti_associate_container .qti_choice_list li > div").draggable({
				drag: function(event, ui){
					// label go on top of the others elements
					$(ui.helper).css("z-index","999");			
				},
				containment: qti_item_id,
				cursor:"move",
				revert: true
			});
			
			/**
			 * remove an element from the filled gap
			 * @param {jQuery} jElement
			 */
			var removeFilledPair = function(jElement){
				var filledId = jElement.attr("id").replace('pair_', '');
				var _matchMax = Number(_this.opts["matchMaxes"][filledId]["matchMax"]);
				var _current = Number(_this.opts["matchMaxes"][filledId]["current"]);
				
				if (_current > 0) {
					_this.opts["matchMaxes"][filledId]["current"] = _current - 1;
				}
				jElement.parents('li').removeClass('ui-state-highlight');
				jElement.remove();
				if(_current >= _matchMax){
					$("#"+filledId+" div").show();
				}
			};
			
			// pair box are droppable
			$(qti_item_id+" .qti_association_pair li").droppable({
				drop: function(event, ui){
					
					var draggedId = $(ui.draggable).parents().attr('id');
					
					//prevent of re-filling the gap and dragging between the gaps
					if($(this).find("#pair_"+draggedId).length > 0 || /^pair_/.test($(ui.draggable).attr('id'))){
						return false;
					}
					
					var _matchMax 	= Number(_this.opts["matchMaxes"][draggedId]["matchMax"]);
					var _current 	= Number(_this.opts["matchMaxes"][draggedId]["current"]);
					var _matchGroup = _this.opts["matchMaxes"][draggedId]["matchGroup"];
					
					//Check the matchGroup of the dropped item or the opposite in the pair 
					if(/A$/.test($(this).attr('id'))){
						 var opposite =$('#' + $(this).attr('id').replace(/_A$/, "_B")).find('.filled_pair:first');
					}
					else{
						var opposite = $('#' + $(this).attr('id').replace(/_B$/, "_A")).find('.filled_pair:first');
					}
					if(opposite.length > 0){
						var oppositeId = opposite.attr('id').replace('pair_', '');
						if(_matchGroup.length > 0){ 
							if($.inArray(oppositeId, _matchGroup) < 0){
								return false;
							}
						}
						
						var _oppositeMatchGroup = _this.opts["matchMaxes"][oppositeId]["matchGroup"];
						if(_oppositeMatchGroup.length > 0){
							if($.inArray(draggedId, _oppositeMatchGroup) < 0){
								return false;
							}
						}
					}
					
					
					// add class to highlight current dropped item in pair boxes
					$(this).addClass('ui-state-highlight');

					//remove the old element
					if($(this).html() != ''){
						$('.filled_pair', $(this)).each(function(){
							removeFilledPair($(this));
						});
					}
					
					// add new element inside the box that received the cloud element
					$(this).html("<div id='pair_"+draggedId+"' class='filled_pair'>"+$(ui.draggable).text()+"</div>");
					
					if (_current < _matchMax) {
						_current++;
						_this.opts["matchMaxes"][draggedId]["current"]=_current;
					}
					if (_current >= _matchMax) {
						$(ui.draggable).hide();
					}
					
					// give a size to the dropped item to overlapp perfectly the pair box
					$(qti_item_id+" .filled_pair").width($(qti_item_id+" .qti_association_pair li").width());
					$(qti_item_id+" .filled_pair").height($(qti_item_id+" .qti_association_pair li").height());
					$(ui.helper).css({top:"0",left:"0"});
					
					// give this new element the ability to be dragged
					$(qti_item_id+" .filled_pair").draggable({
						drag: function(event, ui){
							// element is on top of the other when it's dragged
							$(this).css("z-index", "999");
						},
						stop: function(event, ui) {
							removeFilledPair($(this));
							return true;
						 },
						 containment: qti_item_id,
						 cursor:"move"
					});
						
				},
				hoverClass: 'active'
			});
	};

	/**
	 * Creates a text entry widget
	 * 
	 * @see QTIWidget#string_interaction
	 */
	this.text_entry = function (){
		//adapt the field length
		if(_this.opts['expectedLength']){
			length = parseInt(_this.opts['expectedLength']);
			$(qti_item_id).css('width', (length * 10) + 'px')
							.attr('maxLength', length);
		}
		
		_this.string_interaction();
	};

	/**
	 * Creates a  extended text widget,
	 * it can be a big text area or a set of text entries regarding the context
	 * 
	 * @see QTIWidget#string_interaction
	 */
	this.extended_text = function (){
		
			
		//usual case: one textarea 
		if($(qti_item_id).get(0).nodeName.toLowerCase() == 'textarea') {
			
			//adapt the field length
			if(_this.opts['expectedLength'] || _this.opts_this.opts['expectedLines']){
				
				baseWidth 	= parseInt($(qti_item_id).css('width')) | 400;
				baseHeight 	= parseInt($(qti_item_id).css('height')) | 100;
				if(_this.opts['expectedLength']){
					length 		= parseInt(_this.opts['expectedLength']);
					width = length * 10;
					if( width > baseWidth){
						height = (width / baseWidth) * 16;
						if(height  > baseHeight){
							$(qti_item_id).css('height', height + 'px');
						}
					}
					$(qti_item_id).attr('maxLength', length);
				}
				if(_this.opts['expectedLines']){
					$(qti_item_id).css('height', (parseInt(_this.opts['expectedLines']) * 16) + 'px');
				}
			}
		
			_this.string_interaction();
		}
		
		//multiple text inputs
		if($(qti_item_id).get(0).nodeName.toLowerCase() == 'div') {
			//adapt the fields length
			if(_this.opts['expectedLength']){
				length = parseInt(_this.opts['expectedLength']);
				$(qti_item_id + " :text").css('width', (length * 10) + 'px')
											.attr('maxLength', length);
			}
			//apply the pattern to all fields
			if(_this.opts['patternMask']){
				var pattern = new RegExp("/^"+_this.opts['patternMask']+"$/");
				$(qti_item_id  + " :text").change(function(){
					$(this).removeClass('field-error');
					if(!pattern.test($(this).val())){
						$(this).addClass('field-error');
					}
				});
			}
		}
	};

	/**
	 * Initialize the parametrized behavoir of text input likes widgets 
	 * It supports now the Regex matching and string cloning 
	 */
	this.string_interaction = function(){
		
		//add the error class if the value don't match the given pattern
		if(_this.opts['patternMask']){
			var pattern = new RegExp("/^"+_this.opts['patternMask']+"$/");
			$(qti_item_id).change(function(){
				$(this).removeClass('field-error');
				if(!pattern.test($(this).val())){
					$(this).addClass('field-error');
				}
			});
		}
		
		//create a 2nd field to capture the string if the stringIdentifier has been defined
		if(_this.opts['stringIdentifier']){
			$(qti_item_id).after("<input type='hidden' id='"+_this.opts['stringIdentifier']+"' />");
			$("#"+_this.opts['stringIdentifier']).addClass('qti_text_entry_interaction');
			$(qti_item_id).change(function(){
				$("#"+_this.opts['stringIdentifier']).val($(this).val());
			});
		}
	};

	/**
	 * Creates a  hottext widget,
	 * it support 3 behaviors: 
	 * 	- without restriction, 
	 *  - one by one and 
	 *  - N at a time
	 */
	this.hottext = function(){
		
		//the hottext behavior depends on the maxChoice value
		var maxChoices = (_this.opts['maxChoices']) ? parseInt(_this.opts['maxChoices']) : 1;
		$(qti_item_id + " .hottext_choice").click(function(){
			
			//no behavior restriction
			if(maxChoices == 0){
				$(this).toggleClass('hottext_choice_on');
				$(this).toggleClass('hottext_choice_off');
			}
			
			//only one selected at a time 
			if(maxChoices == 1){
				$(qti_item_id + " .hottext_choice").removeClass('hottext_choice_on').addClass('hottext_choice_off');
				$(this).removeClass('hottext_choice_off').addClass('hottext_choice_on');
			}
			
			//there is only maxChoices selected at a time
			if(maxChoices > 1){
				if($(qti_item_id + " .hottext_choice_on").length < maxChoices || $(this).hasClass('hottext_choice_on') ){
					$(this).toggleClass('hottext_choice_on');
					$(this).toggleClass('hottext_choice_off');
				}
			}
		});
	};
	
	/**
	 * Creates a  gap match widget			
	 */
	this.gap_match = function(){
		
		//add the container to the words cloud 
		$(qti_item_id + " .qti_choice_list").wrap("<div class='qti_gap_match_container'></div>");
		$(qti_item_id + " .qti_choice_list li").wrapInner("<div></div>");
		
		//add breaker
		$(qti_item_id + " .qti_choice_list li:last").after("<li><div></div></li>");
		$(qti_item_id + " .qti_choice_list li:last").css('clear', 'both');
		
		//manage the cloud height and the words width
		$(qti_item_id+" .qti_choice_list").height(parseFloat($(".qti_gap_match_container").height()));
		var maxBoxSize=0;
		$(qti_item_id+" ul.qti_choice_list li > div").each(function(){	
			if ($(this).width()>maxBoxSize){
				maxBoxSize=$(this).width();		
			}
		});
		maxBoxSize = ((parseInt(maxBoxSize)/2) + 5) + 'px';
		$(qti_item_id+" .gap").css({"padding-left": maxBoxSize, "padding-right": maxBoxSize});
		
		//drag element from words cloud
		$(qti_item_id+" .qti_gap_match_container .qti_choice_list li > div").draggable({
			drag: function(event, ui){
				// label go on top of the others elements
				$(ui.helper).css("z-index","999");			
			},
			containment: qti_item_id,
			revert: true,
			cursor:"move"
		});
		
		/**
		 * remove an element from the filled gap
		 * @param {jQuery} jElement
		 */
		var removeFilledGap = function(jElement){
			var filledId = jElement.attr("id").replace('gap_', '');
			var _matchMax = Number(_this.opts["matchMaxes"][filledId]["matchMax"]);
			var _current = Number(_this.opts["matchMaxes"][filledId]["current"]);
			if (_current > 0) {
				_this.opts["matchMaxes"][filledId]["current"] = _current - 1;
			}
			jElement.parent().css({
				"padding-left": maxBoxSize, 
				"padding-right": maxBoxSize
			}).removeClass('dropped_gap');
			jElement.remove();
			if(_current >= _matchMax){
				$("#"+filledId+" div").show();
			}
		};
		
		// pair box are droppable
		$(qti_item_id+" .gap").droppable({
			drop: function(event, ui){
			
				var draggedId = $(ui.draggable).parent().attr("id");
	
				//prevent of re-filling the gap and dragging between the gaps
				if($(this).find("#gap_"+draggedId).length > 0 || /^gap_/.test($(ui.draggable).attr('id'))){
					return false;
				}
				
				var _matchMax 	= Number(_this.opts["matchMaxes"][draggedId]["matchMax"]);
				var _current 	= Number(_this.opts["matchMaxes"][draggedId]["current"]);
				var _matchGroup = _this.opts["matchMaxes"][draggedId]["matchGroup"];
				
				///if the matchGroup of the choice is defined and not found we cancel the drop 
				if(_matchGroup.length > 0){
					if($.inArray($(this).attr('id'), _matchGroup) < 0){
						return false;
					}
				}
				
				//check too the matchGroup of the gap
				var _gapMatchGroup = _this.opts["matchMaxes"][$(this).attr('id')]["matchGroup"];
				if(_gapMatchGroup.length > 0){
					if($.inArray(draggedId, _gapMatchGroup) < 0){
						return false;
					}
				}

				//remove the old element
				if($(this).html() != ''){
					$('.filled_gap', $(this)).each(function(){
						removeFilledGap($(this));
					});
				}
				
				$(this).css({'padding-left' : '5px', 'padding-right' : '5px'}).addClass('dropped_gap');
				
				// add the new element inside the box that received the cloud element
				$(this).html("<span id='gap_"+draggedId+"' class='filled_gap'>"+$(ui.draggable).text()+"</span>");
				
				if (_current < _matchMax) {
					_current++;
					_this.opts["matchMaxes"][draggedId]["current"] = _current;
				}
				if(_current >= _matchMax){
					$(ui.draggable).hide();
				}
				
				//enable to drop it back to remove it from the gap
				$(qti_item_id+" .filled_gap").draggable({
					drag: function(event, ui){
						// label go on top of the others elements
						$(ui.helper).css("z-index","999");
						$(this).parent().addClass('ui-state-highlight');
					},
					stop: function(){
						$(this).parent().removeClass('ui-state-highlight');
						removeFilledGap($(this));
					},
					revert: false,
					containment: qti_item_id,
					cursor:"move"
				});
				
			},
			hoverClass: 'active'
		});	
	};
	
	/**
	 * Create a match widget: 
	 * a matrix of choices to map to each others
	 */
	this.match = function(){
		
		//define the columns of the matrix from the last choice list
		$(qti_item_id + " .choice_list:last").addClass('choice_list_cols');
		var cols = new Array();
		$(qti_item_id + " .choice_list:last li").each(function(){
		/*	var content = $(this).html().wrap('div>');
			$(this).wrapInner('<div class="inline-col" />');*/
			cols.push(this.id);
		});
		
		//define the rows of the matrix from the first choice list
		$(qti_item_id + " .choice_list:first").addClass('choice_list_rows');
		var rows = new Array();
		$(qti_item_id + " .choice_list_rows li").each(function(){
			rows.push(this.id);
		});
		
		//insert the node container (it will contain the nodes of the matrix)
		$(qti_item_id + " .choice_list_cols").after("<div class='match_node_container'></div>");
		
		//make the display adjustment
		$(qti_item_id + " .match_node_container").height(parseInt( $(qti_item_id + " .choice_list:first").height()));
		$(qti_item_id + " .match_node_container").css('left', $(qti_item_id + " .choice_list_rows").width());
		
		//build all the nodes
		var i = 0;
		while(i < rows.length){
			var xnode = 'xnode_' + rows[i];
			var j = 0;
			while(j < cols.length){
				var ynode = 'ynode_' + cols[j];
				var node_id = 'match_node_'+i+'_'+j;
				
				//a node is a DIV with a ID made from X and Y indexes, and the classes of it's row and column
				$(qti_item_id + " .match_node_container").append("<div id='"+node_id+"' class='match_node "+xnode+" "+ynode+"'>&nbsp;</div>");
				
				//set the position and the size of the node
				left = 0;
				if(j > 0){
					p = $("#"+ 'match_node_'+i+'_'+(j-1)).position();
					left = parseInt(p.left)  + parseInt($("#"+ 'match_node_'+i+'_'+(j-1)).width()) + (12);
				}
				width =  parseFloat($("#"+ cols[j]).width());
				$(qti_item_id + " #"+node_id).css({
					'top' 	: ((i * 25) + i * 2 ) + 'px',
					'left'	: left + 'px',
					'width'	: width + 'px'
				});
				j++;
			}
			i++;
		}
		
		/**
		 * Exract the id from the rows and the columns from the node's classes
		 * 
		 * @param {jQuery} jElement the matrix node under the jQuery format
		 * @returns {Object} with xnode an ynode id 
		 */
		function getNodeXY(jElement){
			
			var x = null;
			var y = null;
			
			var classes = jElement.attr('class').split(' ');
			for(i in classes){
				if(/^xnode_/.test(classes[i])){
					x = {
						'id' 	: classes[i].replace('xnode_', ''),
						'class'	: classes[i]
					};
				}
				else if(/^ynode_/.test(classes[i])){
					y = {
							'id' 	: classes[i].replace('ynode_', ''),
							'class'	: classes[i]
						};
				}
				if(x != null && y != null){
					break;
				}
			}
			return {xnode: x, ynode: y};
		}
		
		/**
		 * Deactivate a node
		 * 
		 * @param {jQuery} jElement the matrix node under the jQuery format
		 */
		function deactivateNode(jElement){
			jElement.removeClass('tabActive');
			associations.splice(associations.indexOf(jElement.attr('id')), 1);
		}
		
		var maxAssociations = _this.opts['maxAssociations'];
		
		/*
		 * Activate / deactivate nodes regarding:
		 * 	- the maxAssociations options that should'nt be exceeded
		 *  - the matchMax option of the row and the column
		 *  - the matchGroup option defining who can be associated with who
		 */
		var associations = new Array();
		$(qti_item_id + " .match_node").click(function(){
			
			var elt =  $(this);	//prevent to many parsing
			
			if(elt.hasClass('tabActive')){
				deactivateNode(elt);
			}
			else{
				if(associations.length < maxAssociations || maxAssociations == 0){
					
					var nodeXY = getNodeXY(elt);
					
					//check the matchGroup for the current association
					var _rowMatchGroup = _this.opts["matchMaxes"][nodeXY.xnode.id]['matchGroup'];
					if(_rowMatchGroup.length > 0){
						if($.inArray(nodeXY.ynode.id, _rowMatchGroup) < 0){
							return false;
						}
					}
					var _colMatchGroup = _this.opts["matchMaxes"][nodeXY.ynode.id]['matchGroup'];
					if(_colMatchGroup.length > 0){
						if($.inArray(nodeXY.xnode.id, _colMatchGroup) < 0){
							return false;
						}
					}
					
					//test the matchMax of the row choice
					var rowMatch = _this.opts["matchMaxes"][nodeXY.xnode.id]['matchMax'];
					if(rowMatch == 1) {
						$(qti_item_id + " ." + nodeXY.xnode['class']).each(function(){
							deactivateNode($(this));
						});
					}
					else if(rowMatch > 1){
						var rowMatched = $(qti_item_id + " ." + nodeXY.xnode['class'] + ".tabActive").length;
						if(rowMatched >= rowMatch){
							return false;
						}
					}
					
					//test the matchMax of the column choice
					var colMatch = _this.opts["matchMaxes"][nodeXY.ynode.id]['matchMax'];
					if(colMatch == 1) {
						$("." + nodeXY.ynode['class']).each(function(){
							deactivateNode($(this));
						});
					}
					else if(colMatch > 1){
						var colMatched = $(qti_item_id + " ." + nodeXY.ynode['class']+ ".tabActive").length;
						if(colMatched >= colMatch){
							return false;
						}
					}
					
					elt.addClass('tabActive');
					associations.push(elt.attr('id'));
				}
			}
		});
	};
}