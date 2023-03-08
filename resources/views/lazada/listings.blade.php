<div class="table-responsive">
    <table class="table table-striped table-bordered">
         <thead>
             <tr class="text-left">
                <th rowspan="2"></th>
                <th rowspan="2">Image</th>
                <th rowspan="2">Title</th>
                <th colspan="2" class="text-center">Quantity</th>
                <th rowspan="2">Price</th>
                <th rowspan="2">Action</th>
             </tr>
             <tr>
                 <th class="text-center">Available</th>
                 <th class="text-center">Sold</th>
             </tr>
         </thead>
         <tbody>
            <?php
   
               if (!is_array($data)) {
               ?>
               <tr>
                 <td colspan="7">No Record</td>
               </tr>
               <?php
              
              } else{
                foreach ($data as $k => $v){
                    $itemID = $v['listing']['item_id'];
            ?>

            <tr id="{{ $v['listing']['item_id'] }}">
                <input type="hidden" current-account name="account" value="{{ $account }}">
                <td>
                    <div class="checkbox">
                        <input type="checkbox" class="checkbox-input" data-bulk-list id="mainCheckBox_<?php echo $v['listing']['item_id']; ?>">
                        <label for="mainCheckBox_<?php echo $v['listing']['item_id']; ?>"></label>
                    </div>
                </td>
                 <td>
                    <?php

                    if (array_column($v['listing']['skus'], 'Images')) {
                        if (isset(array_column($v['listing']['skus'], 'Images')[0][0])) {

                        
                    ?>
                    <img src="<?php echo array_column($v['listing']['skus'], 'Images')[0][0]; ?>" style='width: 60px;'>

                    <?php
                    }
                    }
                    ?>
                </td>
                <td data-listing-title-wrapper>
                    <input data-listing-title type="text" name="title" value="{{ $v['listing']['attributes']['name'] }}" style="border:none; background:none;outline: none;width: 600px;" class="bg-light text-dark"><br />
                    <a href="#" class="hidden" data-update-title>Edit</a> 
                </td>
               
                <td class="text-center" data-listing-title-wrapper>
                    <input data-listing-available type="number" name="available" value="<?php echo array_column($v['listing']['skus'], 'quantity')[0];?>" style="border:none; background:none;outline: none;width: 50px;" class="bg-light text-dark"><br />
                    <a href="#" class="hidden" data-update-title>Edit</a>
                </td>
                <td class="text-center">
                    <?php
                    if (isset(array_column($v['listing']['skus'], 'quantity')[0])) {
                        echo array_column($v['listing']['skus'], 'quantity')[0];
                    }else{
                        echo "0";
                    }
                    ?>
                </td>
                <td>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">$</span>
                        </div>
                    
                        <input data-listing-price data-selling-price type="number" name="sellingPrice" style="width: 80px;" value="<?php echo array_column($v['listing']['skus'], 'price')[0];?>">
                        <div class="input-group-append">
                            <div class="spinner-grow spinner-grow text-light hidden" role="status" data-spinner>
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
               
                </td>
                <td>
                    <table>
                        <tr>
                            <td>
                                 <a class="dropdown-item" data-toggle="modal" data-target="#listing_{{ $itemID }}">
                                            View 
                                        </a>
                               
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            @include('lazada.listing-detail', ['data'=>$v])
            <?php
            
                }
              }

              ?>
             
         </tbody>
     </table>
   </div>

   
   