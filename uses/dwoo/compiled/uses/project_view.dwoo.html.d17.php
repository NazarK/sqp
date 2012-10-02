<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><link rel='stylesheet' href='uses/nice_table.css' type='text/css' />
<style>
#wrap {
    width: 940px;
    margin: 0 auto;
    padding: 0 40px;
}
.ProjectTitle {
    margin-top: 10px;
    margin-bottom: 10px;
    font-weight: bold;
    font-size: 28px;
}
.ProjectDetails {
    color: #888;
    font-size: 12px;
}
.JobDescTitle {
    margin-top: 10px;
    font-weight: bold;
    font-size: 20px;
}
.JobDesc {
    margin-top: 10px;
    margin-bottom: 10px;
}

#bids {
    margin-top: 20px;
    width: 100%;
}

.apply {
    margin-top:20px;
}

.apply a {
    padding: 10px;
    background: #333;
    color: #fff;
    text-decoration: none;
    border-radius: 4px;
}

#bids tr {
  height: 40px;
}

</style>
<div id=wrap>
<div class=ProjectTitle><?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'title',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["project"], false);?></div>
<div class=ProjectDetails>Posted: <?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'date_s',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["project"], false);?> - <?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'days_ago',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["project"], false);?> day(s) ago - by <a class=hrefbutton href=user/info/<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'username',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["employer"], false);?>><?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'username',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["employer"], false);?></a>
</div>
<div class=JobDescTitle>Job Description</div>
<div class=JobDesc>
<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'description',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["project"], false);?>

</div>
<div class=ProjectDetails>
Applicants: <?php echo $this->scope["bid_count"];?> (avg <?php echo $this->scope["bid_avg"];?>)
</div>
<div class=apply>
<a href=project/apply/<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'id',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["project"], false);?>>Apply to this Job</a>
</div>

<table id=bids class=nice_table>
<tr><th class=leftround width=120px>Applicant<th width=160px>Bid<th class=rightround>Message

<?php 
$_fh0_data = (isset($this->scope["bids"]) ? $this->scope["bids"] : null);
if ($this->isArray($_fh0_data) === true)
{
	foreach ($_fh0_data as $this->scope['line']=>$this->scope['bid'])
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

    
    <td>
     <a href=user/info/<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'username',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["bid"], false);?>><?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'username',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["bid"], false);?></a>
    <td>
       $<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'bid',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["bid"], false);?> in <?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'days',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["bid"], false);?> day(s)
    <td>
      <?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'details',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["bid"], false);?>

<?php 
/* -- foreach end output */
	}
}?>


</table>
</div><?php  /* end template body */
return $this->buffer . ob_get_clean();
?>