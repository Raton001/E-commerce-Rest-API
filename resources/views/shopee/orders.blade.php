<div class="table-responsive">
 <table class="table table-striped">
      <thead>
          <tr class="text-left">
            <th></th>
            <th>
               <fieldset>
                <div class="checkbox checkbox-info checkbox-glow">
                    <input type="checkbox" id="ship_all" checked>
                    <label for="ship_all"></label>
                </div>
            </fieldset>
            </th>
              <th>Order ID</th>
              <th>Status</th>
<!--               <th>Total</th>
       
              <th>Buyer Username</th>
              <th>Created Time</th>
              <th>Ship By</th>
              <th>Carrier</th> -->
              <th>Airway Bill</th>
              <th>Action</th>
          </tr>
      </thead>
      <tbody>
         <form method="post" action="/shopee/<?php echo $account;?>/order/request/bundle/shipment">
           @csrf
           <?php
            if (!is_array($data)) {
            ?>
            <tr>
              <td colspan="9">No Record</td>
            </tr>
            <?php
           
           } else {

            $count=0;
            $action ='';
            foreach ($data as $k => $v) {
            
              $count++;

                  if ($title == 'READY_TO_SHIP') {
                  if (isset($axisStatus->{$v->ordersn})) {
                 
                        switch ($axisStatus->{$v->ordersn}->status) {
                        case 'completed':
                          $status =  "<div class='badge badge-pill badge-secondary mr-1 mb-1'>Packed</div>";
                         $checkStatus = 'disabled';
                          $action='<a href="/shopee/'.$account.'/order/ship/'.$v->ordersn.'" data-item-id="'.$v->ordersn.'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';

                          break;
                        case 'pending':
                          $status = "<div class='badge badge-pill badge-success mr-1 mb-1'>Shipment Created</div>";
                         $checkStatus = 'disabled';
                          $action='<a href="/shopee/'.$account.'/order/ship/'.$v->ordersn.'" data-item-id="'.$v->ordersn.'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';

                          break;
                        case 'under process':
                          $status ="<div class='badge badge-pill badge-warning mr-1 mb-1'>Preparing</div>";
                         $checkStatus = 'disabled';
                          $action='<a href="/shopee/'.$account.'/order/ship/'.$v->ordersn.'" data-item-id="'.$v->ordersn.'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';

                          break;

                        case 'Deleted':
                          $status = "<div class='badge badge-pill badge-danger mr-1 mb-1'>Deleted</div>";
                         $checkStatus = 'disabled';
                          $action='<a href="/shopee/'.$account.'/order/ship/'.$v->ordersn.'" data-item-id="'.$v->ordersn.'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';

                          break;

                           case 'on hold':
                          $status = "<div class='badge badge-pill badge-danger mr-1 mb-1' style='background-color:#ccc;'>On Hold</div>";
                         $checkStatus = 'disabled';
                          $action='<a href="/shopee/'.$account.'/order/ship/'.$v->ordersn.'" data-item-id="'.$v->ordersn.'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';

                          break;
 
                        default:
                
                         $status = "<div class='badge badge-pill badge-info mr-1 mb-1'>Create Shipment</div>";
                         $checkStatus = 'checked';
                          $action='<a href="/shopee/'.$account.'/order/ship/'.$v->ordersn.'" data-item-id="'.$v->ordersn.'"><small class="text-muted">Ship Now</span></a>';
                          break;
                      } 
                  } else {
                    $status = "<div class='badge badge-pill badge-info mr-1 mb-1'>Create Shipment</div>";
                    $checkStatus = 'checked';
                     $action='<a href="/shopee/'.$account.'/order/ship/'.$v->ordersn.'" data-item-id="'.$v->ordersn.'"><small class="text-muted">Ship Now</span></a>';

                  }
                } else {
                  $status = '-';
                  $checkStatus = 'disabled';
                  $action='<a href="/shopee/'.$account.'/order/ship/'.$v->ordersn.'" data-item-id="'.$v->ordersn.'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';

                 
                }
                 ?>
            
             <tr id="<?php echo $v->ordersn;?>">
                <td>
                  <?php  echo $count;?>
                </td>
                <td>
                  <fieldset>
                      <div class="checkbox checkbox-info checkbox-glow">

                      <input type="checkbox" name="orders[]" id="ship_<?php echo $v->ordersn;?>" value="<?php echo $v->ordersn;?>" <?php echo $checkStatus;?>>
                      <label for="ship_<?php echo $v->ordersn;?>"></label>

                    </div>
                  </fieldset>
                </td>
                <td><?php echo $v->ordersn;?></td>
                <td><?php echo $status;?></td>
<!--                 <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td> -->
                <td>
                 <?php
                        
                
                  if ($title == 'READY_TO_SHIP') {
   
                      if (isset($v->airway)) {

                        ?>
                         <span class="bullet bullet-dark bullet-sm"></span>

                        <a href="<?php echo $v->airway;?>" target="_blank">
                          <small class="text-muted">Click</small>
                        </a>
                                
                          <?php
                        }else {


                          ?>
                        <span class="bullet bullet-danger bullet-sm"></span>
                          <a  target="_blank" href="https://seller.shopee.com.my/portal/sale/order">
                            <small class="text-muted">Arrange Shipment</small></a>
                          <?php
                            }
                        } else {
                          echo "-";
                        }

                        ?>
                </td>
                <td><?php echo $action;?></td>
     


              </tr>
              <?php
            }
           }
           ?>

            <?php
           if ($title == 'READY_TO_SHIP') {
            ?>
            <tr>
             <td colspan="9" style="--bs-table-accent-bg:none;">
             </td>
             <td style="--bs-table-accent-bg:none;">
                <button type="submit" class="btn btn-secondary btn-block subtotal-preview-btn">Submit</button>
             </td>
           </tr>
            <?php
           }

           ?>
           
          </form>
            <tfoot>
            <tr>
                <td colspan="7">

                
                 <nav aria-label="Page navigation">
                        <ul class="pagination pagination-borderless justify-content-center mt-2">
                          <?php
                          if ($pagination) {
                            ?>
                            <li class="page-item active" aria-current="page"><a class="page-link" href="/<?php echo $marketplace;?>/<?php echo $account;?>/orders/<?php echo $page;?>">Next</a></li>
                            <?php
                          }
                          ?>
                    
                        </ul>
                    </nav>
             </td>
            </tr>
         </tfoot>
      </tbody>
      
  </table>
</div>
