<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><script src="uses/jquery.js" type="text/javascript"></script> 
<script src="uses/ui/jquery.ui.core.js" type="text/javascript"></script> 
<script src="uses/ui/jquery.ui.widget.js" type="text/javascript"></script> 
<script src="uses/ui/jquery.ui.mouse.js" type="text/javascript"></script> 
<script src="uses/ui/jquery.ui.draggable.js" type="text/javascript"></script> 
<script src="uses/ui/jquery.ui.droppable.js" type="text/javascript"></script> 
<div style='width:1261px;margin-left:auto;margin-right:auto;'><iframe frameborder=0 src=marquee style='border:0;position:absolute;width:1261px;height:22px;z-index:100;margin-top:234px;overflow:hidden;'></iframe></div>

<div style='position:absolute;width:100%;text-align:center;margin-top:58px;background:url(images/dash_back.png)'>

<div style='margin-left:auto;margin-right:auto;width:1261px;height:208px;background:url(images/dash_title.png);'>




<div style='position:absolute;width:350px;margin-top:-55px;z-index:10;margin-left:900px;'>
<a style=float:right href=user/logout title=Logout><img src=images/dash_logout.png></a>
$register
</div>

  <div style='position:absolute;margin-top:-60px;'>
    <div style='text-align:left;position:absolute;font-size:18px;font-family:verdana;font-weight:bold;margin-top:20px;margin-left:320px;width:800px;z-index:2;'>
      $membership
    </div> 
   <?php echo href(0, 0, 300, 58, 'newlist');?>

   <img alt='logo' src=images/dash_logo.png>
  </div>

<div style='position:absolute;margin-left:71px;width:1100px;top:250px;font-size:48px;'>
  $subaccounts <br />
  
  <div style='position:absolute;margin-top:-75px;margin-left:0px;font-size:48px;'>
  <div style="font-size:18px;">Used <strong><?php echo images_usage_perc();?>%</strong> of your available space.</div>
    <a href="http://webpaper.co/images/manage"><img src="images/manage_all_images.png" border="0" width="310" height="45" /></a><br />
 </div>
 <div style="font-size:24px;">Welcome, <strong>$username</strong></div>
</div>


</div>
  
</div>

<div style="position:absolute;width:100%;margin-top:430px;">
  <div style="width:980px;margin-left:auto;margin-right:auto;">
    <?php 
$_fh1_data = (isset($this->scope["folders"]) ? $this->scope["folders"] : null);
if ($this->isArray($_fh1_data) === true)
{
	foreach ($_fh1_data as $this->scope['row']=>$this->scope['folder'])
	{
/* -- foreach start output */
?>
      <div style='margin-top:10px;font-size:20px;'><img src=images/newlist_folder.png> <?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'name',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["folder"], false);?> folder</div>
      <table class=folder style='width:400px;' folder_id=<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'id',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["folder"], false);?>>
      <?php 
$_fh0_data = $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'pages',  ),  3 =>   array (    0 => '',    1 => '',  ),), (isset($this->scope["folder"]) ? $this->scope["folder"]:null), true);
if ($this->isArray($_fh0_data) === true)
{
	foreach ($_fh0_data as $this->scope['page_num']=>$this->scope['page'])
	{
/* -- foreach start output */
?>
        <tr class=page_line page_id=<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'id',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["page"], false);?>><td  style='width:400px;cursor:pointer;border-top:2px solid #28a'><?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'title',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["page"], false);?><br>      
      <?php 
/* -- foreach end output */
	}
}?>

      <tr class=drag_accept_line><td style='height:10px'>
      </table>
    <?php 
/* -- foreach end output */
	}
}?>

  </div>
<div id=bottom_space style='height:200px;'>
</div>

</div>

<script>

$(function() {
  $(".page_line").draggable( {
          scroll: true,
          scrollSensitivity: 10,
          scrollSpeed: 10,
          zIndex: 1000,
          start: function() { 
                  $(this).css("position","absolute").css("margin-top",-$(document).scrollTop()).css("margin-left",-$(document).scrollLeft());
               },

          stop: function() { 
                   $(this).css("position","relative").css("left", 0).css("top",0).css("margin-left",0).css("margin-top",0);
                }
  }
  );
  
  $(".folder").droppable(
   {
     drop: function(event, ui) {
         if($(this).attr("folder_id") != ui.draggable.parents(".folder").attr("folder_id")) {
	    ui.draggable.css("position","relative").detach().insertBefore($(this).find(".drag_accept_line"));
            $(this).css('background',"#fff");
            $.get("folder/change/"+ui.draggable.attr("page_id")+"/"+$(this).attr("folder_id"));
         }
	 },
     over: function(event, ui) {
         if($(this).attr("folder_id") != ui.draggable.parents(".folder").attr("folder_id"))
           $(this).css("background","#eee");
     },
     out: function() {
         $(this).css('background',"#fff");
     }

   }     
    
  );
  
});
  
</script>  <?php  /* end template body */
return $this->buffer . ob_get_clean();
?>