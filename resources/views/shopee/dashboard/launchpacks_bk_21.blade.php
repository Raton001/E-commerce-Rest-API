@section('content')
<?php
ini_set('memory_limit', '-1');  
        $productL = $products[$key];
        // $packageL = $packages[$key];
        // 
        // if ($key == '275920176') {
        //     echo "<pre>";
        // var_dump(array_keys($productL));
        // exit;
        // }


        $brands = array_unique(array_merge(array_keys((array)$productL['product']), array_keys((array)$productL['package'])));
        // $proKeys = array_keys($productL);
// echo "<pre>";
// var_dump($brands);
// exit;


        ?>

        <input type="hidden" id="store" data-store-id="<?php echo $key;?>" value="<?php echo $key;?>">
        <input type="hidden" id="storename" data-store-name="<?php echo $value;?>" value="<?php echo $value;?>">

        <input type="hidden" id="marketplace" value="<?php echo $marketplace;?>">
 <div class="card bg-transparent shadow-none border">
                                <div class="card-header text-left">

                                    <div class="row" style="width: 100%">
                                        <div class="col-lg-8">
                                            <h4 class="card-title">Products</h4>
                                        </div>
                                        <div class="col-lg-2">
                                            <div launch-box>
                                                <h2>$<span data-price-total>0</span></h2>
                                                <p style="color: red;" class="hidden" data-launch-shopee-error-prompt>Selected listing total amount exceeded your 'Available Selling Limit'</p>
                                            </div>
                                        </div>

                                         <div class="col-lg-2">
                                            <button type="button" class="btn btn-secondary glow mr-1 mb-1" data-launch-listing>Launch</button>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="card-body">
                                    
                                    <ul class="nav nav-pills">
                                        <?php
                                        $i=0;
                                        foreach ($brands as $bkey => $brand) {
                                            $i++;
                                            ?>
                                            <li class="nav-item">
                                                <a class="nav-link <?php echo ($i == 1 ? 'active': '');?>" id="base-pill<?php echo trim($brand);?>" data-toggle="pill" href="#pill<?php echo trim($brand);?>" aria-expanded="true">
                                                     <?php echo trim($brand);//explode('_', $brand)[1];?>
                                                </a>
                                            </li>
                                            <?php
                                        }
                                        ?>
                                         <!--  -->
                                       
                                    </ul>

                                    <div class="tab-content">
                        
                                         <?php


                                        $i=0;
                                      

                                        foreach ($brands as $bkey => $brand) {
                                            $i++;
                                            ?>
                                             <div class="tab-pane <?php echo ($i == 1 ? 'active': '');?>" id="pill<?php echo trim($brand);?>" aria-labelledby="base-pill<?php echo trim($brand);?>">
                                           

                                            <!--content starts-->


                                            <div class="container" data-list-container>
                                                <div class="row">
                                                    <div class="col-lg-12 my-3">
                                                        <div class="pull-right">
                                                            <div class="btn-group">
                                                                <button class="btn btn-info" data-list-switch>
                                                                    List View
                                                                </button>
                                                                <button class="btn btn-danger" data-grid-switch>
                                                                    Grid View
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 
                                                <div id="products_<?php echo $brand;?>" class="row view-group text-left" data-list-item data-launchpacks>
                                                    <?php
                                                  
                                                  $j=0;
                                                    // foreach ($proKeys as $pk => $pv) {
                                                    

                                             

                                                if (isset($productL['package'][$brand]) || isset($productL['product'][$brand])) {

                                           
                                      

                                                    //products
                                                    if (isset($productL['product'][$brand])) {
                                                         foreach ($productL['product'][$brand] as $prokey => $product) {
                                                  
                                                            ?>
                                                           
                                                            <div class="item col-xs-3 col-lg-3" data-item>
                                                            
                                           
                                                            <div class="thumbnail card">
                                                                 <a class="dropdown-item" data-toggle="modal" data-target="#launchpack_{{ $key}}_{{ $product->id }}">
                                                                <div class="img-event">

                                                                    <?php
                                                                    if ($product->photo) {
                                                                        if(strpos($product->photo, ',') !== false){
                                                                            $gallery = explode(',', $product->photo);

                                                                            $photo = $gallery[0];
                                                                        } else {
                                                                            $photo = $product->photo;
                                                                        }
                                                                        ?>
                                                                      
                                                                <img class="group list-group-image img-fluid" src="http://ebx.axisdigitalleap.asia/web/uploads/product/<?php echo $photo;?>" alt="" />
                                                                        <?php
                                                                    } else {
                                                                        ?>
                                                                        <img class="group list-group-image img-fluid" src="http://placehold.it/400x250/000/fff" alt="" />
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                    
                                                                </div>
                                                            </a>
                                                                <div class="caption card-body">
                                                                    <div class="row" data-item-col>
                                                                         <a data-toggle="modal" data-target="#launchpack_{{ $key}}_{{ $product->id }}">
                                                                       <!--  <div class="col-lg-12" data-col-0> -->
                                                                            <h4 class="group card-title inner list-group-item-heading">
                                                                      <?php echo (isset($product->listing_name)? $product->listing_name: '');?></h4>
                                                                        <!-- </div> -->
                                                                    </a>

                                                                        <div class="col-lg-12" data-col-1>
                                                                           <small class="text-muted">
                                                                               <i class='bx bx-qr'></i> <?php echo (isset($product->sku)? $product->sku: '');?></small>
                                                                        </div>

                                                                        <div class="col-lg-12" data-col-2>
                                                                             
                                                                                <p class="lead">
                                                                                RM<span data-price><?php echo (isset($product->selling_price)? $product->selling_price: '');?></span></p>
                                                                        </div>

                                                                        <div class="col-lg-12" data-col-3>
                                                                            <fieldset>
                                                <div class="checkbox checkbox-info checkbox-glow">
                                                    <input type="checkbox" id="<?php echo (isset($product->id)? $product->id: '');?>_<?php echo (isset($product->eas_product_id)? $product->eas_product_id: '');?>_<?php echo (isset($product->sku)? $product->sku: '');?>">
                                                    <label for="<?php echo (isset($product->product_id)? $product->product_id: '');?>_<?php echo (isset($product->eas_product_id)? $product->eas_product_id: '');?>_<?php echo (isset($product->sku)? $product->sku: '');?>"></label>
                                                </div>
                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    
                                                                    
                                                                </div>
                                                            </div>
                                                        
                                                        </div>                         
                                                                                                    
                                                            @include('shopee.dashboard.launchpacks-detail-product', ['product'=>$product, 'key'=>$key]);

                                                            <?php
                                                        }

                                                    }
                                           
                                    

                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                            

                                            <!--content ends-->
                                        </div>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    
                                </div>
                            </div>


@endsection