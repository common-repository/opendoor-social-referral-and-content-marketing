(function( $ ) {
	
	var methods = {
		init : function(options){			

			//console.log("selectAdd :: init");
			//console.log(options);
			
			// defaults
			var initConfig = {
				"options" : {},
				"selected" : {},
				"input-name-root" : ""
			};
			
			$.extend(true, initConfig, options);
			
			this
				.data(initConfig);
			
			var addedOptions = $("<div></div>")
				.addClass("selected_options")
				.css({
					"border-bottom": "1px dashed #CCCCCC",
					"margin-bottom": "10px",
					"padding-bottom": "10px"
				})
				.toggle(false)
				.appendTo(this);
			
			var selectPointer = $("<select></select>")
				.appendTo(this);

			var myPointer = this;				
			
			$.each(initConfig["options"], function(optionId, optionInfo) {
				
				//console.log("optionId = " + optionId);
				//console.log("optionInfo >");
				//console.log(optionInfo);
				
				var optionAttr = {
					"value" : optionId
				}

				//console.log(typeof initConfig["selected"]);
				
				if(typeof initConfig["selected"] == "object"){
					
					if(!$.isEmptyObject(initConfig["selected"])){
					
						//console.log("initConfig[selected] >");
						//console.log(initConfig["selected"]);
					
						//console.log(typeof initConfig["selected"][optionId]);
					
						if(typeof initConfig["selected"][optionId] != "undefined"){
					
							myPointer
								.selectAdd("add_input", {
									"value" : optionId,
									"label" : optionInfo["label"]
								});
						}
					}
					
				}
				
				//console.log("selectAdd :: init > options");
				//console.log(optionInfo["label"]);
				
				var optionPointer = $("<option></option>")
					.attr(optionAttr)
					.html(optionInfo["label"])
					.appendTo(selectPointer);
				
			});
			
			
			
			$("<button>+ Add Industry</button>")
				.addClass("button")
				.addClass("button-secondary")
				.click(function(e) {
					e.preventDefault(); 
					
					myPointer
						.selectAdd("add_input");
				})
				.appendTo(this);
			

		},
		
		
		// close the edit-profile pannel
		add_input : function(dataIn, callBack){
			
			//console.log("selectAdd :: add_input");
			
			var inputValue = "";
			
			var valuePassed = false;
			if(typeof dataIn != "undefined"){
				if(typeof dataIn["value"] != "undefined"){
					valuePassed = true;
					inputValue = dataIn["value"];
				}
			}
			
			if(!valuePassed){
				inputValue = $("select", this).val();
			}
			
			//console.log("selectAdd :: add_input > inputValue");
			//console.log(inputValue);
			
			var name = (new Array("input[value='", inputValue,"']")).join("");

			var inputExists = false;
			if($(name, this).length >= 1){
				inputExists = true;
			}
			
			if(!inputExists){
				
				var addedInputWrap = $("<div></div>")
					.appendTo($("div.selected_options", this));
					
					
				addedInputWrap
					.addClass("input-wrap")
					.mouseover(function(){
						$("div.remove-industry", this).toggle(true);
						
						addedInputWrap
							.css({
								"background" : "#F7F7F7"
							});
							
					})
					.mouseout(function(){
						$("div.remove-industry", this).toggle(false);
						addedInputWrap
							.css({
								"background" : "none"
							});
					});
					
			
				$("<input>")
					.attr({
						"type" : "hidden",
						"name" : (new Array(this.data("input-name-root"), "[]")).join(""),
						"value" : inputValue
					})
					.appendTo(addedInputWrap);
				
				var removeLinkWrap = $("<div></div>")
					.css({
						"float" : "right",
						"vertical-align" : "center",
						"text-align" : "center",
						"padding" : "2px 10px"
					})
					.toggle(false)
					.mouseover(function(){
						$("div.remove-industry", this).toggle(true);
					})
					.mouseout(function(){
						$("div.remove-industry", this).toggle(false);
						
					})
					.addClass("remove-industry")
					.appendTo(addedInputWrap);
					
				$("<a>x</a>")
					.css({
						"cursor" : "pointer"
					})
					.click(function(e) {
		                e.preventDefault();
						
						addedInputWrap
							.remove();
		
						return false;
					})
					.appendTo(removeLinkWrap);
					
				
				$("<p></p>")
					.html(this.data("options")[inputValue]["label"])
					.appendTo(addedInputWrap);
					
					
			}
			
			$("div.selected_options", this)
				.toggle(true);

		},
		
		
	}
	
  $.fn.selectAdd = function( method ) {
    
    if ( methods[method] ) {
      return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
    } else if ( typeof method === 'object' || ! method ) {
      return methods.init.apply( this, arguments );
    } else {
      $.error( 'Method ' +  method + ' does not exist on jQuery.proProfile' );
    }    
  
  };

})( jQuery );

if(typeof opndr == "undefined") var opndr = {}