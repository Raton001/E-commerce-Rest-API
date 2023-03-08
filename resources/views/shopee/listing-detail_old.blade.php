<?php
$image_gallery = [];
$product_detail = [];
if (isset($v['detail']['item'])) {
    $image_gallery = $v['detail']['item']['images'];
   $product_detail = $v['detail']['item'];
}

?>



<div class="modal fade" id="listing_<?php echo $v['listing']['item_id'];?>" tabindex="-1" role="dialog" aria-labelledby="listing_<?php echo $v['listing']['item_id'];?>Label" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="listing_<?php echo $v['listing']['item_id'];?>Label">Listing #<?php echo $v['listing']['item_id'];?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          

         
          <div class="row d-flex flex-column">
              
           <?php  if (sizeof($product_detail) > 0) {?>
            {{-- IMAGES GALLERY --}}
            <div class="swiper-coverflow swiper-container">
              <div class="swiper-wrapper">

                @foreach ($image_gallery as $url)
                <div class="swiper-slide"> <img class="img-fluid" src="{{ $url }}" alt="{{ $url }}" style="width:300px; height:300px;"></div>
                @endforeach
                  
              </div>
              <!-- Add Pagination -->
              <div class="swiper-pagination"></div>
            </div>
  
            {{-- PRODUCT DETAILS --}}
            <div class="row mx-1">
              <div class="card">
                <div class="card-body">
                  <p class="h5 card-title text-dark">Product Details [ ITEM SKU : {{ $product_detail['item_sku'] }} ]</p>

                  <div class="form-row">

                    <div class="form-group col-md-9">
                      <label>Product Name</label>
                      <p class="h6 text-dark">{{ $product_detail['name'] }}</p>
                    </div>

                    <div class="form-row col-md-3">
                      <div class="form-group col">
                        <label>Currency</label>
                        <p class="h6 text-dark">{{ $product_detail['currency'] }}</p>
                      </div>
                      <div class="form-group col">
                        <label>Price</label>
                        <p class="h6 text-dark text-justify">{{ number_format($product_detail['price'], 2) }}</p>
                      </div>
                    </div>

                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label>Desription</label>
                      @php
                          $description = nl2br($product_detail['description']);
                      @endphp
                      <p class="h6 text-dark text-justify">{{ $product_detail['description'] }}</p>
                    </div>
                  </div>
                  
                </div>
              </div>
              
              
            </div>

            {{-- PRODUCT INFO --}}
            <div class="row mx-1">
              <div class="card">
                <div class="card-body">
                  <p class="h5 card-title text-dark">Product INFO</p>

                  <div class="form-row">

                    <div class="form-group col-md-6">
                      <label>Update Time</label>
                      <p class="h6 text-dark">{{ date('d/m/Y H:i:s A', $product_detail['update_time']) }}</p>
                    </div>
                    <div class="form-group col-md-6">
                      <label>Create Time</label>
                      <p class="h6 text-dark">{{ date('d/m/Y H:i:s A', $product_detail['create_time']) }}</p>
                    </div>

                  </div>
                  <span class="border-bottom row"></span>
                  <div class="form-row">

                    <div class="form-group col-md-3">
                      <label>Product Condition</label>
                      <p class="h6 text-dark">{{ $product_detail['condition'] }}</p>
                    </div>

                    <div class="form-group col-md-3">
                      <label>Product Weight</label>
                      <p class="h6 text-dark">{{ $product_detail['weight'] }} KG</p>
                    </div>

                    <div class="form-group col-md-3">
                      <label>Package Width</label>
                      <p class="h6 text-dark">{{ $product_detail['package_width'] }}</p>
                    </div>

                    <div class="form-group col-md-3">
                      <label>Package Height</label>
                      <p class="h6 text-dark">{{ $product_detail['package_height'] }}</p>
                    </div>
                   

                  </div>
                  
                </div>
              </div>
              
              
            </div>

            {{-- PRODUCT PERFORMANCE --}}
            <div class="row mx-1">
              <div class="card">
                <div class="card-body">
                  <p class="h5 card-title text-dark">Product Performance</p>

                  <div class="form-row">

                    <div class="form-group col-md-3">
                      <label><i class="bx bxs-shopping-bag"></i>&nbsp;&nbsp;Sales</label>
                      <p class="h6 text-dark">{{ $product_detail['sales'] }}</p>
                    </div>
                    <div class="form-group col-md-3">
                      <label><i class="bx bxs-star"></i>&nbsp;&nbsp;Rating Star</label>
                      <p class="h6 text-dark">{{ number_format($product_detail['rating_star'], 2) }}</p>
                    </div>
                    <div class="form-group col-md-3">
                      <label><i class="bx bxs-happy"></i>&nbsp;&nbsp;Views</label>
                      <p class="h6 text-dark">{{ $product_detail['views'] }}</p>
                    </div>
                    <div class="form-group col-md-3">
                      <label><i class="bx bxs-like"></i>&nbsp;&nbsp;Likes</label>
                      <p class="h6 text-dark">{{ $product_detail['likes'] }}</p>
                    </div>
                    
                  </div>
                  
                </div>
              </div>
              
              
            </div>
            <?php }?>

          </div>
            
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
      </div>
    </div>
  </div>