<?php
/* template head */
/* end template head */ ob_start(); /* template body */ ?><style>
.JobDescTitle {
    
}

.JobDesc {
    
    
}
</style>
Project:<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'title',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["project"], false);?><br>
Posted: <?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'date_s',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["project"], false);?><br>
<div class=JobDescTitle>Job Description</div>
<div class=JobDesc>
<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'description',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["project"], false);?>

</div>
<a class=hrefbutton href=user/info/<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'username',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["employer"], false);?>>Employer info</a>
<br>
<a class=hrefbutton href=project/bid/<?php echo $this->readVarInto(array (  1 =>   array (    0 => '->',  ),  2 =>   array (    0 => 'id',  ),  3 =>   array (    0 => '',    1 => '',  ),), $this->scope["project"], false);?>>Bid on this project</a><?php  /* end template body */
return $this->buffer . ob_get_clean();
?>