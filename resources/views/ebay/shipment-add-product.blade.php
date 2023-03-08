<div class="modal fade" id="shipment_<?php echo $key;?>" tabindex="-1" role="dialog" aria-labelledby="shipment_<?php echo $key;?>Label" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="shipment_<?php echo $key;?>Label"><?php echo $listing;?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <select id="smeItemSpecs" name="sme" class="form-control">
          <option>Select a Brand</option>
          <?php
            if (isset($sme)) {
            


            foreach ($sme as $key => $value) {
              
              ?>
             
              <option value="<?php echo $value->id."-".$value->brandID;?>">
                
                <?php echo $value->name;?>
                 
                </option>

              <?php

              }
            }
          ?>
        </select>

        <p id="product_list_total"></p>
        <select id="product_list" class="hidden form-control"></select>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
      </div>
    </div>
  </div>
</div>


