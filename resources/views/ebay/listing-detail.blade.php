<div class="modal fade" id="listing_<?php echo $v['ItemID'];?>" tabindex="-1" role="dialog" aria-labelledby="listing_<?php echo $v['ItemID'];?>Label" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="listing_<?php echo $v['ItemID'];?>Label">Order #<?php echo $v['ItemID'];?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <?php
  
            echo "<pre>";
            var_dump($v);
            ?>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
      </div>
    </div>
  </div>