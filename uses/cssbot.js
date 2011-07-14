$(function() {
	if(!shortcut) alert("add shortcut.js");
    shortcut.add("Ctrl+Right", function() {
	   var v = parseInt($(".bot").css("margin-left").replace("px",""))+1;
       $(".bot").css("margin-left",v);
	   status_update();
	});

    shortcut.add("Ctrl+Left", function() {
	   var v = parseInt($(".bot").css("margin-left").replace("px",""))-1;
       $(".bot").css("margin-left",v);
	   status_update();
	});

    shortcut.add("Ctrl+Up", function() {
	   var v = parseInt($(".bot").css("margin-top").replace("px",""))-1;
       $(".bot").css("margin-top",v);
	   status_update();
	});

    shortcut.add("Ctrl+Down", function() {
	   var v = parseInt($(".bot").css("margin-top").replace("px",""))+1;
       $(".bot").css("margin-top",v);
	   status_update();
	});


	function status_update() {
	  var v = $(".bot").css("margin-left")+" "+$(".bot").css("margin-top");
      $(document).attr("title",v);
	}


});