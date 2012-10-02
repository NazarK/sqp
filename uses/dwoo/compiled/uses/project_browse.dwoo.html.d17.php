<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><link rel='stylesheet' href='uses/nice_table.css' type='text/css' />
<style>

#projects a {
  color: #000;
}

#projects a:visited {
  color: #649AE3;
}

#projects {
  padding-top:30px;
  margin-left: 20px;
}






</style>

<table id=projects class=nice_table>
  <tr>
    <th class=leftround>Project
    <th>Bids
    <th>Avg (USD)
    <th>Job Type
    <th>Started
    <th class=rightround>Left
      
<?php 
$_fh0_data = (isset($this->scope["projects"]) ? $this->scope["projects"] : null);
if ($this->isArray($_fh0_data) === true)
{
	foreach ($_fh0_data as $this->scope['line']=>$this->scope['prj'])
	{
/* -- foreach start output */
?>
  <?php if ((isset($this->scope["line"]) ? $this->scope["line"] : null) % 2 !== 0) {
?>  <tr>
  <?php 
}
else {
?> <tr class='even'>
  <?php 
}?>

    <td width=500px>
      <a class=link href=<?php echo $this->scope["base_url"];?>project/view/<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'id',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["prj"], false);?>><?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'title',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["prj"], false);?></a><br> <?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'description',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["prj"], false);?>

    <td><?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'bid_count',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["prj"], false);?>

    <td><?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'bid_avg',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["prj"], false);?>

    <td>&nbsp;
    <td><?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'date_s',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["prj"], false);?><br><?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'days_ago',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["prj"], false);?> days(s) ago
    <td>&nbsp;
<?php 
/* -- foreach end output */
	}
}?>

</table>


<script>
$(function() {
  $("#projects tr").click(
    function() {
      window.location = $(this).find(".link").attr("href");
      
    }
    
  )
  
  
  
});
</script><?php  /* end template body */
return $this->buffer . ob_get_clean();
?>