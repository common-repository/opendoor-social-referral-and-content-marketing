(function( $ ) {
	
	var methods = {
		init : function(options){			

			//console.log("targetContent :: init");
			//console.log(options);
			
			// defaults
			var initConfig = {
				"options" : {},
				"selected" : {},
				"input-name-root" : "target-content",
				"uri" : ""
			};
			
			$.extend(true, initConfig, options);
			
			this
				.data(initConfig);
				
			$("<table border=1></table>")
				.addClass("form-table")
				.css({
					"border" : "1px solid #CCCCCC"
				})
				.html('<tbody>	\
							<tr><td class="uri_pattern" style="vertical-align:top;"></td><td class="industry_targets" style="vertical-align:top;"></td></tr>	\
						</tbody>	\
				')
				.appendTo(this);

			this
				.targetContent("add_uri_input");
			
			
			this
				.targetContent("create_select_add");
			
		},
		
		
		create_select_add : function(dataIn, callBack){
		
			$("<em>Target industries...</em>")
				.appendTo($("td.industry_targets", this));
		
			var industryTargetWrap = $("<div></div>")
				.css({
					"white-space" : "nowrap"
				})
				.appendTo($("td.industry_targets", this));
			
			industryTargetWrap
				.selectAdd({
					"options" : this.data("options"),
					"selected" : this.data("selected"),
					"input-name-root" : (new Array(this.data("input-name-root"), "[indids]")).join('')
				});
				
                
			
			
		},
		
		// close the edit-profile pannel
		add_uri_input : function(dataIn, callBack){
			
			var urlLabel = $("<div></div>")
				.appendTo($("td.uri_pattern", this));
				
		
			urlLabel
				.mouseover(function(){
					$("div.remove-uri", this).toggle(true);
					/*
					urlLabel
						.css({
							"background" : "#F7F7F7"
						});
					*/	
				})
				.mouseout(function(){
					$("div.remove-uri", this).toggle(false);
					/*
					urlLabel
						.css({
							"background" : "none"
						});
					*/
				});
				
			var removeLinkWrap = $("<div></div>")
				.css({
					"float" : "right",
					"vertical-align" : "center",
					"text-align" : "center",
					"padding" : "0px 10px"
				})
				.toggle(false)
				.mouseover(function(){
					$("div.remove-uri", this).toggle(true);
				})
				.mouseout(function(){
					$("div.remove-uri", this).toggle(false);
					
				})
				.addClass("remove-uri")
				.appendTo(urlLabel);
				
			var myPointer = this;
				
			$("<a>remove</a>")
				.css({
					"cursor" : "pointer",
					"font-size" : "12px"
				})
				.click(function(e) {
	                e.preventDefault();
					
					myPointer
						.remove();
	
					return false;
				})
				.appendTo(removeLinkWrap);
			
			
				
			$("<em>URLs containing...</em>")
				.appendTo(urlLabel);		
				
			
			$("<input>")
				.attr({
					"type" : "text",
					"name" : (new Array(this.data("input-name-root"), "[uri]")).join(''),
					"value" : this.data("uri")
				})
				.css({
					"width" : "100%"
				})
				.appendTo($("td.uri_pattern", this));
	        
		}
		
		
	}
	
  $.fn.targetContent = function( method ) {
    
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