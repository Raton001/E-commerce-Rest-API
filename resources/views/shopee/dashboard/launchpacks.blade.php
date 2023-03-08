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

$default = 'Tupperware';

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
                                      <!-- ======= Portfolio Section ======= -->
                                    <section id="portfolio" class="portfolio">
                                      <div class="container" data-aos="fade-up">

                                        <div class="section-title">
                                          <h2>Listings</h2>
                                          <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.</p>
                                        </div>

                                        <ul id="portfolio-flters" class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">
                                          <li data-filter="*" class="filter-active">All</li>

                                            <?php

                                            foreach ($brands as $key => $value) {
                                                $brand = explode('_', $value);

                                                ?>
                                                <li data-filter=".filter-<?php echo $brand[0];?>">
                                                    <?php echo $brand[1];?>
                                                </li>
                                                <?php
                                            }
                                            ?>

                                          
                                          
                                        </ul>

                                        <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">

                                            <?php

                                            foreach ($brands as $key => $value) {
                                                $brand = explode('_', $value);
                                                $i = 0;
                                                //loop through products
                                                foreach ($productL['product'][$value] as $k => $product) {
                                                    $i++;

                                                    ?>

                                           <div class="col-lg-4 col-md-6 portfolio-item filter-<?php echo $brand[0];?>">
                                            <div class="portfolio-img">
                                                <a class="dropdown-item" data-toggle="modal" data-target="#launchpack_{{ $k}}_{{ $product->id }}">
                                                <img src="/assets/img/portfolio/portfolio-<?php echo $i;?>.jpg" class="img-fluid" alt="">
                                            </a>
                                           
                                            </div>
                                            <div class="portfolio-info">
                                              <h4><?php echo (isset($product->listing_name)? $product->listing_name: $product->name);?></h4>
                                                    <p class="lead">
                                                     RM<span data-price><?php echo (isset($product->selling_price)? $product->selling_price: '');?> </span></p>

                                              <a href="/assets/img/portfolio/portfolio-<?php echo $i;?>.jpg" data-gallery="portfolioGallery" class="portfolio-lightbox preview-link" title="App 1"><i class="bx bx-plus"></i></a>
                                              <a href="portfolio-details.html" class="details-link" title="More Details"><i class="bx bx-link"></i></a>
                                            </div>
                                          </div>
                                                    <?php
                                                }
                                                ?>
                               
                                                <?php
                                            }
                                            ?>

                               

                                        </div>

                                      </div>
                                    </section><!-- End Portfolio Section -->
                                </div>
                            </div>


@endsection