$(function() {
	if(!shortcut) alert("add shortcut.js");
//	if($(".bot").length==0) return;
	$(".bot").css("border","1px dotted red");
	shortcut.add("Ctrl+Alt+Enter", function() {
	
	   var id = prompt("Enter Div ID to move");
	   $(".bot").removeClass("bot");
	   $("#"+id).addClass("bot");
	   $(id).addClass("bot");
	   status_update();
	});
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


    shortcut.add("Alt+Right", function() {
	   var val = "width";
	   var v = parseInt($(".bot").css(val).replace("px",""))+1;
       $(".bot").css(val,v);
	   status_update();
	});

    shortcut.add("Alt+Left", function() {
	   var val = "width";
	   var v = parseInt($(".bot").css(val).replace("px",""))-1;
       $(".bot").css(val,v);
	   status_update();
	});


	shortcut.add("Alt+Up", function() {
	   var val = "height";
	   var v = parseInt($(".bot").css(val).replace("px",""))-1;
       $(".bot").css(val,v);
	   status_update();
	});

    shortcut.add("Alt+Down", function() {
	   var val = "height";
	   var v = parseInt($(".bot").css(val).replace("px",""))+1;
       $(".bot").css(val,v);
	   status_update();
	});

    function status_update() {
	  if($("#bot_status").length==0)
        $("body").prepend("<div id=bot_status style='position:fixed;z-index:1000;'><textarea style='height:100px;width:200px;'></textarea></div>");

	  var v = $(".bot").css("margin-left")+" "+$(".bot").css("margin-top")+" "+$(".bot").css("width")+" "+$(".bot").css("height");
      $(document).attr("title",v);

      var b = $('.bot');
	  var v = "  margin-left: "+b.css("margin-left")+";\r\n  margin-top: "+b.css("margin-top")+";\r\n  width: "+b.css("width")+";\r\n  height:"+b.css("height")+";";
	  $("#bot_status textarea").val(v);
	}


});