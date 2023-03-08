<div class="modal fade" id="launchpack_{{ $key}}_<?php echo $product->id;?>" tabindex="-1" role="dialog" aria-labelledby="launchpack_{{ $key}}_<?php echo $product->id;?>Label" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="launchpack_{{ $key}}_<?php echo $product->id;?>Label">Listing #<?php echo (isset($product->name)? $product->name: '');?></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        
        <div class="modal-body">
          

         
          <div class="row d-flex flex-column">

            <?php

     
            
            if (isset($product)) {
   
              
              
                ?>
            {{-- IMAGES GALLERY --}}
            <div class="swiper-coverflow swiper-container">
              <div class="swiper-wrapper">

                <?php
                if (isset($product->photo)) {

                
                 if(strpos($product->photo, ',') !== false){
                        $gallery = explode(',', $product->photo);


                  foreach ($gallery as $url) {
if ($url != '') {


                ?>
               <div class="swiper-slide"> <img class="img-fluid" src="http://dev.axisdigitalleap.asia/web/<?php echo $url;?>" alt="{{ $url }}" style="width:300px; height:300px;"></div> 
                
              <?php }
}
                   
                  } else {

        
                ?>

                <div class="swiper-slide"> <img class="img-fluid" src="http://dev.axisdigitalleap.asia/web/<?php echo $product->photo;?>" alt="{{ $product->photo }}" style="width:300px; height:300px;"></div>
                <?php
                 }
                 }

                 ?>


                  
              </div>
              <!-- Add Pagination -->
              <div class="swiper-pagination"></div>
            </div>
                {{-- PRODUCT DETAILS --}}
            <div class="row mx-1">
              <div class="card">
                <div class="card-body">
                  <p class="h5 card-title text-dark">Product Details [ ITEM SKU : <?php echo (isset($product->sku)? $product->sku: '');?> ]</p>

                  <div class="form-row">

                    <div class="form-group col-md-9">
                      <label>Product Name</label>
                      <p class="h6 text-dark"><?php echo (isset($product->name)? $product->name: '');?></p>
                    </div>

                    <div class="form-row col-md-3">
                      <div class="form-group col">
                        <label>Price Member</label>
                        <p class="h6 text-dark"><?php echo (isset($product->price_member)? $product->price_member: '');?></p>
                      </div>
                      <div class="form-group col">
                        <label>Selling Price</label>
                        <p class="h6 text-dark text-justify"><?php echo (isset($product->selling_price)? $product->selling_price: '');?></p>
                      </div>
                    </div>

                  </div>


                   <div class="form-row">
                    <div class="form-group">
                       <label>Attributes</label>
           
                      <?php
                      
                      if (isset($product->specs)) {
                        ?>
                        <table class="table table-striped">
                          <?php
                          foreach ($product->specs as $p => $spec) {
                            ?>
                            <tr>
                              <td><p class="h6 text-dark text-justify"><?php echo $spec->label?></p></td>
                              <!-- <td><p class="h6 text-dark text-justify"><?php echo $spec->name?></p></td> -->
                              <td><p class="h6 text-dark text-justify"><?php echo $spec->value?></p></td>

                            </tr>
                            <?php
                          }
                          ?>
                        </table>
                        <?php
                      }
                      ?>  
                    </div>
                  </div>

                  <div class="form-row">
                    <div class="form-group">
                      <label>Desription</label>
           
                      <p class="h6 text-dark text-justify"><?php echo (isset($product->shopee_descr)? nl2br($product->shopee_descr): '');?></p>
                    </div>
                  </div>

                  
                </div>
              </div>
              
              
            </div>

                <?php

            }
            ?>
              
          </div>
            
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
        </div>
      </div>
    </div>
  </div>