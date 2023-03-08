<?php

  $image_gallery = $v['listing']['skus']['0']['Images'];
  $product_detail = $v['listing'];
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
              
           
            {{-- IMAGES GALLERY --}}
            <div class="swiper-coverflow swiper-container">
              <div class="swiper-wrapper">

                @foreach ($image_gallery as $url)
                <div class="swiper-slide" > <img class="img-fluid" src="{{ $url }}" alt="{{ $url }}" style="width:300px; height:300px;"></div>
                @endforeach
                  
              </div>
              <!-- Add Pagination -->
              <div class="swiper-pagination"></div>
            </div>
  
            {{-- PRODUCT DETAILS --}}
            <div class="row mx-1">
              <div class="card">
                <div class="card-body">
                  <p class="h5 card-title text-dark">Product Details [ ITEM SKU : {{ $product_detail['skus'][0]['SellerSku'] }} ]</p>

                  <div class="form-row">

                    <div class="form-group col-md-9">
                      <label>Product Name</label>
                      <p class="h6 text-dark">{{ $product_detail['attributes']['name'] }}</p>
                    </div>

                    <div class="form-row col-md-3">
                      <div class="form-group col">
                        <label>Currency</label>
                        <p class="h6 text-dark">MYR</p>
                      </div>
                      <div class="form-group col">
                        <label>Price</label>
                        <p class="h6 text-dark text-justify">{{ number_format($product_detail['skus'][0]['price'], 2) }}</p>
                      </div>
                    </div>

                  </div>

                  <div class="form-row">

                    <div class="form-row col-md-9">
                      <div class="form-group col">
                        <label>Quantity Available</label>
                        <p class="h6 text-dark">{{ $product_detail['skus'][0]['multiWarehouseInventories'][0]['quantity'] }}</p>
                      </div>
                      <div class="form-group col">
                        <label>Brand</label>
                        <p class="h6 text-dark">{{ $product_detail['attributes']['brand'] }}</p>
                      </div>
                    </div>

                    <div class="form-row col-md-3">
                      <div class="form-group col">
                        <a href="{{ $product_detail['skus'][0]['Url'] }}" class="btn btn-secondary float-right" role="button">View On Lazada</a>
                      </div>
                    </div>
  
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label>SHORT DESRIPTION</label>
                      @php
                          // $description = nl2br($product_detail['attributes']['short_description']);
                      @endphp
                      <?php
                      if (isset($product_detail['attributes']['short_description'])) {
                        ?>
                      <p class="h6 text-dark text-justify">{{ $product_detail['attributes']['short_description'] }}</p>
                        
                        <?php
                      }

                      ?>
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label>DESRIPTION</label>
                      @php
                          $description = $product_detail['attributes']['description'];
                      @endphp
                      {{ $description }}
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
                      <p class="h6 text-dark">{{ date('d/m/Y H:i:s A', $product_detail['updated_time']) }}</p>
                    </div>
                    <div class="form-group col-md-6">
                      <label>Create Time</label>
                      <p class="h6 text-dark">{{ date('d/m/Y H:i:s A', $product_detail['created_time']) }}</p>
                    </div>

                  </div>
                  <span class="border-bottom row"></span>
                  <div class="form-row">

                    <div class="form-group col-sm">
                      <label>Product Status</label>
                      <p class="h6 text-dark">{{  $product_detail['skus'][0]['Status'] }}</p>
                    </div>

                    <div class="form-group col-sm">
                      <label>Product Length</label>
                      <p class="h6 text-dark">{{ $product_detail['skus'][0]['package_length'] }} KG</p>
                    </div>

                    <div class="form-group col-sm">
                      <label>Product Weight</label>
                      <p class="h6 text-dark">{{ $product_detail['skus'][0]['package_weight'] }} KG</p>
                    </div>

                    <div class="form-group col-sm">
                      <label>Package Width</label>
                      <p class="h6 text-dark">{{ $product_detail['skus'][0]['package_width'] }}</p>
                    </div>

                    <div class="form-group col-sm">
                      <label>Package Height</label>
                      <p class="h6 text-dark">{{ $product_detail['skus'][0]['package_height'] }}</p>
                    </div>
                   

                  </div>
                  
                </div>
              </div>
              
              
            </div>

          </div>
          
        	<?php
        	echo "<pre>";
        	var_dump($description);
        	?>
            
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
      </div>
    </div>
  </div>