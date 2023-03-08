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
                  echo "<pre>";
                  
                foreach ($data as $k => $v){
                   if (isset($v['ItemID'])) {
                       
                  
                    $itemID = $v['ItemID'];
            ?>

            <tr id="{{ $v['ItemID'] }}">
                <input type="hidden" current-account name="account" value="{{ $account }}">
                <td>
                    <div class="checkbox">
                        <input type="checkbox" class="checkbox-input" data-bulk-list id="mainCheckBox_<?php echo $v['ItemID']; ?>">
                        <label for="mainCheckBox_<?php echo $v['ItemID']; ?>"></label>
                    </div>
                </td>
                <td>
                    <?php
                    if (isset($v['PictureDetails'])) {
                    ?>
                    <img src="<?php echo $v['PictureDetails']['GalleryURL']; ?>" style='width: 60px;'>

                    <?php
                    }
                    ?>
                </td>
                <td data-listing-title-wrapper>
                    <input data-listing-title type="text" name="title" value="{{ $v['Title'] }}" style="border:none; background:none;outline: none;width: 600px;" class="bg-light text-dark"><br />
                    <a href="#" class="hidden" data-update-title>Edit</a> 
                </td>
                <td class="text-center" data-listing-title-wrapper>
                    <input data-listing-available type="number" name="available" value="{{ $v['QuantityAvailable'] ?? 0 }}" style="border:none; background:none;outline: none;width: 50px;" class="bg-light text-dark"><br />
                    <a href="#" class="hidden" data-update-title>Edit</a>
                </td>
                <td class="text-center">
                    <?php
                    if (isset($v['SellingStatus']['QuantitySold'])) {
                        echo $v['SellingStatus']['QuantitySold'];
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
                        <input data-listing-price data-selling-price type="number" name="sellingPrice" style="width: 80px;" value="<?php echo $v['BuyItNowPrice']; ?>">
                        <div class="input-group-append">
                            <div class="spinner-grow spinner-grow text-light hidden" role="status" data-spinner>
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                    {{-- $ <?php echo number_format($v['BuyItNowPrice'], 2); ?> --}}
                </td>
                <td>
                    <table>
                        <tr>
                            <td>
                                <a data-toggle="modal" data-target="#listing_{{ $itemID }}">
                                            View 
                                        </a>
                                        <a href="/ebay/<?php echo $account; ?>/listing/<?php echo $v['ItemID']; ?>" alt="view">
                                            View 
                                        </a>
                                        
                                        <a  href="/ebay/<?php echo $account; ?>/listing/edit/<?php echo $v['ItemID']; ?>" alt="edit">
                                            Edit
                                        </a>
                                        <a  href="JavaScript:void(0);">
                                            Re-List
                                        </a>
                                        <a  href="JavaScript:void(0);">
                                            Sell Similar
                                        </a>
                                        <a  data-promo id="<?php echo $v['ItemID']; ?>" alt="Promotion">
                                            Create Promotion
                                        </a>
                                        <a  data-delete-itemid href="/ebay/<?php echo $account; ?>/listing/end/<?php echo $v['ItemID']; ?>" alt="Delete">
                                            Delete
                                        </a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            @include('ebay.listing-detail', ['data'=>$v])
            <?php
                   }
            
                }
              }

              ?>
             
         </tbody>
     </table>
   </div>

   
   