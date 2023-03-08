 <?php
       if (isset($policies)) {

        $i = 0;

         foreach ($policies as $k => $policy) {
         foreach ($policy as $key => $value) {

          $i++;
   ?>
   <div class="row">
    <div class="col-md-12">
      
      <input type="checkbox" name="store[]" data-store-name value="<?php echo $key;?>">&nbsp;&nbsp;
    </div>
  </div>
<div id="accordion" data-store-id="<?php echo $key;?>">

    <div class="card">
    <div class="card-header" id="headingOne">
      <span data-error-msg class="hidden"></span>
      <span data-sum-msg></span>

      <h5 class="mb-0">
        <button class="btn btn-link" data-toggle="collapse" data-target="#collapse<?php echo 'sidebar_'.$i;?>" aria-expanded="true" aria-controls="collapse<?php echo 'sidebar_'.$i;?>">
         <?php echo $key;?>
        </button>
      </h5>
    </div>

    <div id="collapse<?php echo 'sidebar_'.$i;?>" class="collapse" aria-labelledby="heading<?php echo 'sidebar_'.$i;?>" data-parent="#accordion">
      <div class="card-body" id="<?php echo $key;?>">

        <div class="row">
          <div class="col-6">
            Available Selling Limit
          </div>
   
          <div class="col-6" data-selling-limit>
           <?php
           if (isset($summary[$key]['Summary']['AmountLimitRemaining'])) {
               echo $summary[$key]['Summary']['AmountLimitRemaining'];
           }
           
           ?>
          </div>
        </div>
       <hr>
         <div class="row">
          <div class="col-6">
            Listing Total
          </div>
          <div class="col-6" data-listing-total>
           
          </div>
        </div>
        <hr>
        <div class="row">
          <div class="col-12">
             <div class="form-group">
                         
                            

                                    <div class="row">
                                      <div class="col-md-12">
                                        
                                            <fieldset class="form-group">
                                        
                                        <select data-shipment-policy class="custom-select">
                                         <?php
                                         foreach ($value['shipment'] as $k => $v) {

                                           ?>
                                          
                                           <option data-id="<?php echo $k;?>" data-name="<?php echo $v['name'];?>" value="<?php echo $key;?>[policy][shipment][id]" <?php echo ($v['default'] == true ? 'selected': '');?>><?php echo $v['name'];?></option>

                                           <?php
                                         }
                                         ?>
                                         </select>
                                       </fieldset>
                                      </div>
                                      <div class="col-md-12">
                                            <fieldset class="form-group">
                                
                                        <select data-payment-policy class="custom-select">
                                       <?php
                                       foreach ($value['payment'] as $k => $v) {
                                         ?>
                                        

                                         <option data-id="<?php echo $k;?>" data-name="<?php echo $v['name'];?>" value="<?php echo $key;?>[policy][payment][id]" <?php echo ($v['default'] == true ? 'selected': '');?>><?php echo $v['name'];?></option>
                                         

                                        
                                         <?php
                                       }
                                       ?>
                                       </select>
                                     </fieldset>

                                      </div>

                                      <div class="col-md-12">
                                            <fieldset class="form-group">
                                       
                                        <select data-return-policy class="custom-select">
                                        
                                         <?php
                                         foreach ($value['return'] as $k => $v) {
                                           ?>
                                         <option data-id="<?php echo $k;?>" data-name="<?php echo $v['name'];?>" value="<?php echo $key;?>[policy][return][id]" <?php echo ($v['default'] == true ? 'selected': '');?>><?php echo $v['name'];?></option>
                                          

                                          
                                           <?php
                                         }
                                         ?>
                                       </select>
                                     </fieldset>
                                      </div>

                                    </div>
                          </div>
          </div>
        </div>

      </div>
    </div>
  </div>


</div>
<?php
    }
  }
  }
  ?>