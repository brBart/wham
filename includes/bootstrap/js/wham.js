
    $("#more_nav").bind("click", function(e) {
    	e.preventDefault();
    	$(".nav-hidden").toggle();
    	if ($(this).prev().attr("class") == "icon-chevron-down"){
    		$(this).prev().removeClass("icon-chevron-down").addClass("icon-chevron-up");
    		$(this).html("less..");
    	}
    	else {
    		$(this).prev().removeClass("icon-chevron-up").addClass("icon-chevron-down");
    		$(this).html("more..");
    	}	
    });
    
