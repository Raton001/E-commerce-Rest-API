@extends('layouts.app')

@section('assets')

@endsection

@section('title')
    {{ $title }}
@endsection

@section('shopname')
    {{ $shopname }}

@endsection

@section('content')

<div class="row">

   <div class="col-6">

    <div class="card-body">
        <h4 class="card-title"> <?php echo $data['item']['name'];?></h4>
        <h6 class="card-subtitle"> <?php echo $data['item']['item_sku'];?></h6>
    </div>
    <div id="carousel-example-card" class="carousel slide" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carousel-example-card" data-slide-to="0" class="active"></li>
            <li data-target="#carousel-example-card" data-slide-to="1"></li>
            <li data-target="#carousel-example-card" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner rounded-0" role="listbox">

            <?php
            $i = 0;
                $images = $data['item']['images'];
                foreach ($images as $key => $value) {
                    $i++;
                   ?>
                    <div class="carousel-item <?php echo ($i==1?'active':'');?>">
                        <img class="img-fluid" src="<?php echo $value;?>" alt="banner">
                    </div>
                   <?php
                }
                
                ?>
            
        </div>
        <a class="carousel-control-prev" href="#carousel-example-card" role="button" data-slide="prev">
            <span class="bx bx-chevron-left icon-prev" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carousel-example-card" role="button" data-slide="next">
            <span class="bx bx-chevron-right icon-next" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
                                
                    
   </div>

   <div class="col-6">

    <div class="">
    <div class="card-body">
        <h6>
            
            <?php echo $data['item']['currency'];?><?php echo $data['item']['price'];?><span class="text-muted"> 
            <?php
            if ($data['item']['condition'] = 'NEW') {

            ?>
             <div class="badge badge-success mr-1 mb-1"><?php echo $data['item']['condition'];?></div></span>
            <?php
            } else {
                ?>
             <div class="badge badge-warning mr-1 mb-1"><?php echo $data['item']['condition'];?></div></span>

                <?php
            }
            ?>
            

            <!-- <div class="dropdown"> -->
           <i class="cursor-pointer bx bx-dots-vertical-rounded float-right" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="/<?php echo $marketplace;?>/<?php echo $account;?>/listing/<?php echo $listing_id;?>/edit">Edit</a>
                <a class="dropdown-item" href="javascript:void(0);">Option 2</a>
                <a class="dropdown-item" href="javascript:void(0);">Option 3</a>
            </div>
        <!-- </div> -->
            </h6>

           

            <div class="row">
                <div class="col-8">
                    <div class="mb-1 font-small-2">
                    <?php
                    $class = 'text-muted';
                    $starClass = 'bx-star';
                   
                    if (isset($data['item']['rating_star'])) {
                        $rating = $data['item']['rating_star'];

                        for($i=0; $i<5;$i++) {
                           if ($rating > 0) {
                            $rating--;
                            $class = 'text-warning';
                            $starClass = 'bxs-star';

                           }
                            ?>
                            
                            <i class="cursor-pointer bx <?php echo $starClass;?> <?php echo $class;?>"></i>

                            <?php
                        }
                    }
                    ?>
                    <span class="ml-50 text-muted text-bold-500"><?php echo $data['item']['rating_star'];?> (<?php echo $data['item']['views'];?> views)</span>
                   
                </div>  
                </div>

                <div class="col-4">
                    <div class="fonticon-wrap">
                        <i class="bx bx-like"></i>&nbsp;<?php echo $data['item']['likes'];?>
                        <i class="bx bx-comment"></i>&nbsp;<?php echo $data['item']['cmt_count'];?>

                    </div>
                </div>
            </div>



        <div class="d-flex">
            <button class="btn btn-sm btn-light-primary d-flex mr-50"><i class="cursor-pointer bx bx-like font-small-3 mb-25 mr-sm-25"></i><span class="d-none d-sm-block">Like</span></button>
            <button class="btn btn-sm btn-light-primary d-flex"><i class="cursor-pointer bx bx-share-alt font-small-3 mb-25 mr-sm-25"></i><span class="d-none d-sm-block">Share</span></button>
        </div>
    </div>
</div>

 <div class="row">

     <?php
     if ($data['item']['has_variation']) {
        ?>
        <h6>Variation</h6>
        <?php
        $variations = $data['item']['variations'];
        foreach ($variations as $key => $attribute) {
      ?>
    <div class="col-xl-4 col-md-4 col-sm-6">
            <div class="card text-center">
                <div class="card-body">
                    <div class="badge-circle badge-circle-lg badge-circle-light-info mx-auto my-1">
                        <i class="bx bx-edit-alt font-medium-5"></i>
                    </div>
                    <p class="text-muted mb-0 line-ellipsis"> <?php echo $attribute['price'];?></p>
                    <h2 class="mb-0"> <?php echo $attribute['name'];?></h2>
                </div>
            </div>
        </div>
      <?php
      }
    }
      ?>


</div>

    <div class="card2">

         <div class="collapse-icon accordion-icon-rotate">
                                
            <div class="accordion" id="cardAccordion">

                  <div class="card">
                    <div class="card-header" id="heading<?php echo $key;?>" data-toggle="collapse" data-target="#collapse<?php echo $key;?>" aria-expanded="false" aria-controls="collapse<?php echo $key;?>" role="button">
                        <span class="collapsed collapse-title">Attributes</span>
                    </div>
                    <div id="collapse<?php echo $key;?>" class="collapse pt-1" aria-labelledby="headingOne" data-parent="#cardAccordion">
                        <div class="card-body">
                           <ul class="list-group list-group-flush">
                            <?php

                            $attributes = $data['item']['attributes'];
                            foreach ($attributes as $key => $attribute) {
                              ?>
                              <li class="list-group-item">
                                  <div class="row">
                                      <div class="col-4">
                                          <?php echo $attribute['attribute_name'];?>
                                      </div>
                                      <div class="col-4">
                                           <?php echo $attribute['attribute_value'];?>
                                      </div>
                                  </div>
                              </li>
                              <?php
                            }
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>

                 <div class="card">
                    <div class="card-header" id="heading<?php echo $key;?>" data-toggle="collapse" data-target="#collapse<?php echo $key;?>" aria-expanded="false" aria-controls="collapse<?php echo $key;?>" role="button">
                        <span class="collapsed collapse-title">Specification</span>
                    </div>
                    <div id="collapse<?php echo $key;?>" class="collapse pt-1" aria-labelledby="headingOne" data-parent="#cardAccordion">
                        <div class="card-body">
                             <ul class="list-group list-group-flush">
                           
                              <li class="list-group-item">
                                  <div class="row">
                                      <div class="col-4">
                                          Weight
                                      </div>
                                      <div class="col-4">
                                          <?php echo $data['item']['weight'];?>
                                      </div>
                                  </div>
                              </li>

                              <li class="list-group-item">
                                  <div class="row">
                                      <div class="col-4">
                                          Length
                                      </div>
                                      <div class="col-4">
                                          <?php echo $data['item']['package_length'];?>
                                      </div>
                                  </div>
                              </li>
                              <li class="list-group-item">
                                  <div class="row">
                                      <div class="col-4">
                                          Width
                                      </div>
                                      <div class="col-4">
                                          <?php echo $data['item']['package_width'];?>
                                      </div>
                                  </div>
                              </li>
                              <li class="list-group-item">
                                  <div class="row">
                                      <div class="col-4">
                                          Height
                                      </div>
                                      <div class="col-4">
                                          <?php echo $data['item']['package_height'];?>
                                      </div>
                                  </div>
                              </li>

                              
                              
                            </ul>
                        </div>
                    </div>
                </div>

                       

                    <div class="user-profile-event">
                        <div class="pb-1 d-flex align-items-center">
                            <i class="cursor-pointer bx bx-radio-circle-marked text-primary mr-25"></i>
                            <small><?php echo date('d/M/Y:H:i:s', $data['item']['create_time']);?></small>
                        </div>
                        <h6 class="text-bold-500 font-small-3">Created</h6>
                    </div>
                    <hr>
                    <div class="user-profile-event">
                        <div class="pb-1 d-flex align-items-center">
                            <i class="cursor-pointer bx bx-radio-circle-marked text-primary mr-25"></i>
                           <small><?php echo date('d/M/Y:H:i:s', $data['item']['update_time']);?></small>
                        </div>
                        <div class="pb-1">
                            <h6 class="text-bold-500 font-small-3">Updated</h6>
                        </div>
                    </div>

                

               
            </div>
                               

</div>
    </div>


</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <p class="card-text">
                    <?php echo nl2br($data['item']['description']);?>
                </p>
            </div>
        </div>
         
    </div>
</div>

@endsection


