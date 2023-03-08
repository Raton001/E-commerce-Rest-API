           <ul class="list-group list-group-flush">
             <li class="list-group-item">
                 <div class="row">
                    <div class="col-1">

                    </div>
                    <div class="col-2">
                        Name
                    </div>
                     <div class="col-3">
                        Recommended
                    </div>
                     <div class="col-3">
                        Value
                    </div>
                    <div class="col-3"></div>
                </div>
            </li>
<?php
$i=0;


if (isset($template[$marketplace]->attributes)) {
$keys = [];
if (is_array($attributes[$marketplace]) && sizeof($attributes[$marketplace])> 0) {
    
$keys = array_column($attributes[$marketplace], 'id');

}
foreach ($template[$marketplace]->attributes as $key => $value) {

    $i++;
   //check each attribute if already has value
   $hasValue = array_search($value->attribute_id, $keys);


  ?>

  <li class="list-group-item">
     
    <div class="row">

       <div class="col-1">
        <div class="row">
            <div class="col-12">
             <?php echo $i;?>
                
            </div>
          
        </div>

        </div>
       <div class="col-3">
        <?php if ($value->is_mandatory) { ?>
                <i class="cursor-pointer bx bx-radio-circle-marked text-danger"></i><?php } ?>
         <div class="user-profile-event">

            <h6 class="text-bold-500 font-small-3">
                <?php echo $value->attribute_name;?>
                <input type="hidden" name="attribute[<?php echo $i;?>][name]" value="<?php echo $value->attribute_name;?>">
                <input type="hidden" name="attribute[<?php echo $i;?>][id]" value="<?php echo $value->attribute_id;?>">

               
            </h6>

        </div>
       </div>
      <div class="col-3">
           <?php
           if (is_array($value->options)) {
            if (sizeof($value->options) > 0) {

            
            ?>
            <select  class="form-control">
            <?php
            foreach ($value->options as $k => $v) {
              ?>
              <option><?php echo $v;?></option>
              <?php
            }
            ?>
            </select>
            <?php
            }
           } else {
            echo $value->options;
           }
           ?>
      </div>
      <div class="col-3">
        <?php

        if (sizeof($keys)> 0) {
            ?>
             <input type="text" class="form-control" name="attribute[<?php echo $i;?>][value]" value="<?php  echo ($value->attribute_id == $attributes[$marketplace][$hasValue]->id? $attributes[$marketplace][$hasValue]->value:'');?>">
            <?php
        } else {
            ?>
             <input type="text" class="form-control" name="attribute[<?php echo $i;?>][value]" value="">
            <?php
        }
        ?>
        
      </div>
      <div class="col-md-2 col-12 form-group">
        <?php if (!$value->is_mandatory) { ?>
        <button class="btn btn-icon btn-danger rounded-circle" type="button" data-repeater-delete-attribute>
            <i class="bx bx-x"></i>
        </button>
    <?php } ?>
    </div>
  </div>
  </li>
  <?php
}
}
?>
</ul>
<ul class="list-group list-group-flush">
             <li class="list-group-item">
                 <div class="row">
                    <div class="col-1">

                    </div>
                    <div class="col-2">
                        Name
                    </div>
        
                     <div class="col-3">
                        Value
                    </div>
                    <div class="col-3"></div>
                </div>
            </li>
    <?php 
    if (isset($customs[$marketplace])) {

    
    if (sizeof($customs[$marketplace]) > 0) {
        $i = 0;

        foreach ($customs[$marketplace] as $key => $value) {
            if ($value->name !='') {

            $i++;
           ?>
            <li class="list-group-item">
             <div class="col-3">
            <?php echo $i;?>

             </div>
           <div class="col-3">
              <input type="text" name="custom[<?php echo $i;?>][name]" class="form-control" value="<?php echo $value->name;?>">
           </div>
           <div class="col-3">
              <input type="text" name="custom[<?php echo $i;?>][value]"  class="form-control" value="<?php echo $value->value;?>">
               
           </div>
         <div class="col-3">
                    <button class="btn btn-icon btn-danger rounded-circle" type="button" data-repeater-delete-attribute>
                <i class="bx bx-x"></i>
            </button>
           </div>
       </li>
           <?php
            }

        }
    }
    }
    ?>

</ul>
<section class="contact-repeater">
            <div data-repeater-list="contact">
                <div class="row">
                    <div class="col-12 mb-2">
                        <button class="btn btn-icon rounded-circle btn-primary" type="button" data-repeater-create>
                            <i class="bx bx-plus"></i>
                        </button>
                        <span class="ml-1 font-weight-bold text-primary">ADD NEW</span>
                    </div>

                  
                </div>
                <div class="row justify-content-between" data-repeater-item data-custom-attribute>
                    <div class="col-md-3 col-12 form-group d-flex align-items-center">
                        <i class="bx bx-menu mr-1"></i>
                       
                        <input type="text" class="form-control" name="attribute[name][]" placeholder="Name">

                    </div>
                    <div class="col-md-3 col-12 form-group">
                        
                         <input type="text" class="form-control" name="attribute[value][]" placeholder="Value">
                    </div>
                  
                    <div class="col-md-3 col-12 form-group">
                        <button class="btn btn-icon btn-danger rounded-circle" type="button" data-repeater-delete>
                            <i class="bx bx-x"></i>
                        </button>
                    </div>
                </div>
               

            </div>
</section>

                                    