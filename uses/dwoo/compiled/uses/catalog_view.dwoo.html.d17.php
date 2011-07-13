<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><div id=right style='position:absolute;margin-left:450px;width:400px;'>
<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'specifications',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["item"], false);?>

</div>

<div id=left>
<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'title',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["item"], false);?><br>
<img width=400px src=img/upload/catalog/<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'image_file',  ),  3 =>   array (    0 => '',    1 => '',    2 => '',  ),), $this->scope["images"]["0"], false);?>><br>

<?php 
$_fh0_data = (isset($this->scope["images"]) ? $this->scope["images"] : null);
if ($this->isArray($_fh0_data) === true)
{
	foreach ($_fh0_data as $this->scope['num']=>$this->scope['image'])
	{
/* -- foreach start output */
?>
<img src=img/upload/catalog/<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'image_file',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["image"], false);?>

<?php 
/* -- foreach end output */
	}
}?>

</div>

<?php  /* end template body */
return $this->buffer . ob_get_clean();
?>