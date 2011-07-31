<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><script src='uses/cloudzoom/cloud-zoom.1.0.2.min.js'></script>
<link rel=stylesheet href='uses/cloudzoom/cloud-zoom.css'>
<style>
a {
  color: #333;
}
.thumb {
  cursor: pointer;
  border: 1px solid #ddd;
  border-radius: 5px;
  vertical-align: top;
  margin: 3px;
  float: left;
  background: #fff;
  width: 90px;
  height: 70px;
  padding: 1px;
}

.img {
  display: none;
  height: 320px;
  width: 400px;
  margin-top: 20px;
}

.thumb img, .img img {
  display: none;
}

.tabContent {
  background: #fff;
  margin-bottom: 20px;
  padding: 20px;
  width: 900px;
  padding-bottom: 40px;
}
.tabTitle {
  width: 100px;
  float: left;
  height: 14px;
  background: #888;
  padding: 10px;
  text-align: center;
  cursor: pointer;
}

.tabTitle.active {
  background: #fb6;
  color: #fff;
}

#catalog_view {
  background: #fff;
}

.tabContent {
  display: none;
}

#catalog_view #top {
  height: 30px;
  background: url(images/site/catalog_view_path.jpg) no-repeat -2px 0px;
  color: #fff;
  padding-left: 10px;
  padding-top: 10px;
}
#catalog_view #top a {
  color: #fff;
  text-decoration: none;
}
</style>
<div id=catalog_view>

	<div id=top><?php echo catalog_path($this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'id',  ),  3 =>   array (    0 => '',    1 => '',  ),), (isset($this->scope["item"]) ? $this->scope["item"]:null), true));?></div>
	<div style='position:absolute;margin-top:20px;'><?php echo catalog_edit_html($this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'id',  ),  3 =>   array (    0 => '',    1 => '',  ),), (isset($this->scope["item"]) ? $this->scope["item"]:null), true));?></div>
	<div id=right style='padding-top:10px;position:absolute;margin-left:460px;width:440px;'>
	<div id=cart style='margin-bottom:10px;height:20px;margin-right:-20px;'>
	    <div style='float:right'>
  	    <form action=cart/add method=post>	
		<?php echo dict("цена");?>: <?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'price',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["item"], false);?> <?php echo dict("тенге");?>

		<input type=hidden name=id value=<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'id',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["item"], false);?>>
		<input name=quantity size=3 value=1>
		<input type=hidden name=back value="catalog/view/<?php echo arg(0);?>/<?php echo arg(1);?>">
		<input style='border:1px solid #888;padding:2px 10px;' type=submit value='<?php echo dict("заказ");?>'>
		<a href=cart>Корзина</a>
		</form>
		</div>
	</div>
	<div id=description>
	<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'description',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["item"], false);?>

	</div>
	</div>

	<div id=left style='padding:10px;padding-left:30px;padding-right:30px;overflow:auto;width:880px;'>
		<div id=zoomView style='width:460px;height:400px;position:absolute;margin-top:40px;margin-left:400px;z-index:10000;'></div>
		<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'title',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["item"], false);?><br>
		<?php if (count((isset($this->scope["images"]) ? $this->scope["images"] : null))) {
?>
		<div class=img style='width:500px;overflow:hidden;padding:5px;'>
		  <img class=fadeout src=images/site/blank.gif style='position:absolute;'>
		  <a href='img/upload/catalog/<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'image_file',  ),  3 =>   array (    0 => '',    1 => '',    2 => '',  ),), $this->scope["images"]["0"], false);?>' class = 'cloud-zoom' id='zoom1' rel="adjustX: 10, adjustY:-4, zoomWidth: 400, zoomHeight:400, position:'zoomView'">
			<img class=view src=img/upload/catalog/<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'image_file',  ),  3 =>   array (    0 => '',    1 => '',    2 => '',  ),), $this->scope["images"]["0"], false);?>>
		  </a>
		</div>
		<br>
		<?php 
}
else {
?>
		<div class=img></div>
		<?php 
}?>


		<div id=thumbs style='width:440px;'>
		<?php 
$_fh0_data = (isset($this->scope["images"]) ? $this->scope["images"] : null);
if ($this->isArray($_fh0_data) === true)
{
	foreach ($_fh0_data as $this->scope['num']=>$this->scope['image'])
	{
/* -- foreach start output */
?>
		  <div class='thumb'>
			<img num=<?php echo $this->scope["num"];?> src=img/upload/catalog/<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'image_file',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["image"], false);?>>
		  </div>
		<?php 
/* -- foreach end output */
	}
}?>

	    </div>

   </div>
<div id=tabTitles style='margin-left:4px;margin-right:4px;height:33px;'>
	<div class=tabTitle tab=overview>Overview</div>
	<div class=tabTitle tab=specifications>Specifications</div>
	<div class=tabTitle tab=download>Download</div>
	<div class=tabTitle tab=support>Support</div>
	<div class=tabTitle tab=related>Related Products</div>
</div>
<div id=hr style='background:url(images/site/catalog_view_hr.jpg) repeat-x;width:100%;height:40px'>
</div>
<div id=overview class=tabContent><?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'overview',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["item"], false);?></div>
<div id=specifications  class=tabContent><?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'specifications',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["item"], false);?></div>
<div id=download  class=tabContent><?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'download',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["item"], false);?></div>
<div id=support class=tabContent><?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'support',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["item"], false);?></div>
<div id=related class=tabContent><?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'related',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["item"], false);?></div>

<script src=uses/js/helper.js ></script>

<script>
$(function() {
  $(".img").first().show();
  $("a[href='<?php echo $this->scope["lang_dir"];?>catalog']").parent().parent().addClass("active");
  $(".tabContent").hide();
  $(".tabTitle").click( function() {
     $(".tabContent").hide();
     $("#"+$(this).attr("tab")+"").show();
	 $(".tabTitle.active").removeClass("active");
	 $(this).addClass("active");
  });

  $(".tabTitle").first().trigger("click");
  $(".img").first().show();

  $(".thumb").click( function() {
    var img = $(this).find("img");
    $(".img img.fadeout").show()
    .attr("src",$(".img img.view").attr("src"))
    .css("width",$(".img img.view").css("width"))
    .css("height",$(".img img.view").css("height"))
	.css("margin-left",$(".img img.view").css("margin-left")).fadeOut(200);
//	.css("margin-top",$(".img img.view").css("margin-top"))

    $(".img img.view").hide();
	$(".img a").attr("href",img.attr("src"));
	$(".img img.view").attr("src",img.attr("src")).fadeIn(200);

    $('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();
	return false;
  });

  imgFit(".thumb img",".thumb",true,false);
  imgFit(".img img.view",".img",false,false);
  $(".thumb img, .img img").show();
  $('.cloud-zoom, .cloud-zoom-gallery').CloudZoom();

/*  $(".img img.view").load( function() {
     if($(this).width()>$(this).height()) {
       $(this).css("width","400px");
	   $(this).css("height","");
	 } else {
       $(this).css("width","");
	   $(this).css("height","400px");
	 }
  });*/

});
</script><?php  /* end template body */
return $this->buffer . ob_get_clean();
?>