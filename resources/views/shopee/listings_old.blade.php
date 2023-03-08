<div class="table-responsive">
    <table class="table table-striped table-bordered">
         <thead>
             <tr class="text-left">
                <th rowspan="2"></th>
                <th rowspan="2"></th>
                <th rowspan="2">Image</th>
                <th rowspan="2">Title</th>
                <th rowspan="2">SKU</th>
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
                 <td colspan="8">No Record</td>
               </tr>
               <?php
              
              } else{
                $count = 0;
                foreach ($data as $k => $v){
                    $count++;
                       
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
                <td><?php echo $count * $pagination['current_page'];?></td>
                <?php if (isset($v['detail']['item'])) {?>
                <td>
                    <?php
                    if (isset($v['detail']['item']['images'][0])) {
                    ?>
                    <img src="<?php echo $v['detail']['item']['images'][0]; ?>" style='width: 60px;'>

                    <?php
                    }
                    ?>
                </td>
                <td data-listing-title-wrapper>
                    <input data-listing-title type="text" name="title" value="{{ $v['detail']['item']['name'] }}" style="border:none; background:none;outline: none;width: 600px;" class="bg-light text-dark"><br />
                    <a href="#" class="hidden" data-update-title>Edit</a> 
                </td>
                <td><?php echo $v['detail']['item']['item_sku'];?></td>
                <td class="text-center" data-listing-title-wrapper>
                    <input data-listing-available type="number" name="available" value="{{ $v['detail']['item']['price'] ?? 0 }}" style="border:none; background:none;outline: none;width: 50px;" class="bg-light text-dark"><br />
                    <a href="#" class="hidden" data-update-title>Edit</a>
                </td>
                <td class="text-center">
                   0
                </td>
                <td>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">{{ $v['detail']['item']['currency']}}</span>
                        </div>
                        <input data-listing-price data-selling-price type="number" name="sellingPrice" class="text-right" style="width: 80px;" value="<?php echo number_format($v['detail']['item']['price'], 2); ?>">
                        <div class="input-group-append">
                            <div class="spinner-grow spinner-grow text-light hidden" role="status" data-spinner>
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                    
                </td>
                <?php }?>
                <td>
                    <table>
                        <tr>
                            <td>
                                 <a class="dropdown-item" data-toggle="modal" data-target="#listing_{{ $itemID }}">
                                            View 
                                        </a>
                                <!-- <div class="dropdown my-auto">
                                    <box-icon class="cursor-pointer dropdown-toggle nav-hide-arrow cursor-pointer" name='dots-vertical-rounded' id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></box-icon>
                                    <span class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                              
                                        <a class="dropdown-item" href="/ebay/<?php echo $account; ?>/listing/<?php echo $v['listing']['item_id']; ?>" alt="view">
                                            View 
                                        </a>
                                        
                                        <a class="dropdown-item" href="/ebay/<?php echo $account; ?>/listing/edit/<?php echo $v['listing']['item_id']; ?>" alt="edit">
                                            Edit
                                        </a>
                                        <a class="dropdown-item" href="JavaScript:void(0);">
                                            Re-List
                                        </a>
                                        <a class="dropdown-item" href="JavaScript:void(0);">
                                            Sell Similar
                                        </a>
                                        <a class="dropdown-item" data-promo id="<?php echo $v['listing']['item_id']; ?>" alt="Promotion">
                                            Create Promotion
                                        </a>
                                        <a class="dropdown-item" data-delete-itemid href="/ebay/<?php echo $account; ?>/listing/end/<?php echo $v['listing']['item_id']; ?>" alt="Delete">
                                            Delete
                                        </a>
                                    </span>
                                </div> -->
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            @include('shopee.listing-detail', ['data'=>$v])

            <?php
            
                }
              }

              ?>
             
         </tbody>
          <tfoot>
            <tr>
                <td colspan="7">

                
                 <nav aria-label="Page navigation">
                        <ul class="pagination pagination-borderless justify-content-center mt-2">
                            <!--<li class="page-item previous"><a class="page-link" href="javascript:void(0);">-->
                            <!--        <i class="bx bx-chevron-left"></i>-->
                            <!--    </a></li>-->
                            <?php
                            
                             for ($i=1;$i<$pagination['total_pages'];$i++) {
                                   ?>
                                 
                                    <li class="page-item <?php echo ($i== $pagination['current_page']? 'active':'');?>" <?php echo ($i== $pagination['current_page']? 'aria-current="page"':'');?>><a class="page-link" href="/<?php echo $marketplace;?>/<?php echo $shop;?>/listings/<?php echo $i;?>"><?php echo $i;?></a></li>
                                   <?php
                               }
                            ?>
                           
                            <!--<li class="page-item next"><a class="page-link" href="javascript:void(0);">-->
                            <!--        <i class="bx bx-chevron-right"></i>-->
                            <!--    </a></li>-->
                        </ul>
                    </nav>
             </td>
            </tr>
         </tfoot>
        
     </table>
   </div>

   
   