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
        <h4 class="card-title"> <?php echo $data->name;?></h4>
        <h6 class="card-subtitle"> <?php echo $data->sku;?></h6>
    </div>

  <div class="row">
        <div class="col-12">
            <div id="carousel-example-card" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carousel-example-card" data-slide-to="0" class="active"></li>
                <li data-target="#carousel-example-card" data-slide-to="1"></li>
                <li data-target="#carousel-example-card" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner rounded-0" role="listbox">

                <?php
                
                for($i = 1;$i<4;$i++) {
                      
                        ?>
                        <div class="carousel-item <?php echo ($i==1?'active':'');?>">
                             <img src="/assets/images/banner/banner-<?php echo $i;?>.jpg" class="img-fluid" alt="dummy"  style="object-fit: cover;">
                        </div>
                        <?php
                    }

                // if (!empty($data->photo_order)) {

                
                //     $images = json_decode($data->photo_order);
                //     $i=0;
                //     foreach ($images as $key => $value) {
                //         $i++;
                       ?>
                      <!--   <div class="carousel-item <?php #echo ($i==1?'active':'');?>">
                           <img class="img-fluid" src="<?php #echo $value->name;?>" alt="banner">
                        </div> -->
                       <?php
                //     }
                // } else {

                    
                //     for($i = 1;$i<4;$i++) {
                      
                        ?>
                       <!--  <div class="carousel-item <?php #echo ($i==1?'active':'');?>">
                             <img src="/assets/images/banner/banner-<?php #echo $i;?>.jpg" class="img-fluid" alt="dummy"  style="object-fit: cover;">
                        </div> -->
                        <?php
                //     }
               

                // }
                    
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
    </div>
                                
                    
   </div>

   <div class="col-6">
   

    <div class="">
    <div class="card-body">
        <h6>
             <i class="cursor-pointer bx bx-dots-vertical-rounded float-right" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="/product/edit/<?php echo $data->id;?>">Edit</a>
                <a class="dropdown-item" href="javascript:void(0);">Option 2</a>
                <a class="dropdown-item" href="javascript:void(0);">Option 3</a>
            </div>
        </h6>
        <div class="user-profile-event">
            <div class="pb-1 d-flex align-items-center">
                <i class="cursor-pointer bx bx-radio-circle-marked text-primary mr-25"></i>
                <small> <?php echo $data->vendor_name;?> </small>
            </div>
            <h6 class="text-bold-500 font-small-3">SME</h6>
        </div>
        <hr>
        <div class="user-profile-event">
            <div class="pb-1 d-flex align-items-center">
                <i class="cursor-pointer bx bx-radio-circle-marked text-primary mr-25"></i>
                <small> <?php echo $data->brand_name;?> </small>
            </div>
            <h6 class="text-bold-500 font-small-3">Brand</h6>
        </div>
        <hr>
        <div class="user-profile-event">
            <div class="pb-1 d-flex align-items-center">
                <i class="cursor-pointer bx bx-radio-circle-marked text-primary mr-25"></i>
                <small> <?php echo $data->category;?> </small>
            </div>
            <h6 class="text-bold-500 font-small-3">Category</h6>
        </div>
        <hr>

        
        <h6>
            
            MYR<?php echo $data->selling_price;?><span class="text-muted"> 
            <?php
            if ($data->status = '1') {

            ?>
             <div class="badge badge-success mr-1 mb-1">ACTIVE</div></span>
            <?php
            } else {
                ?>
             <div class="badge badge-warning mr-1 mb-1">INACTIVE</div></span>

                <?php
            }
            ?>

            </h6>

    </div>
</div>


    <div class="card2">

         <div class="collapse-icon accordion-icon-rotate">
                                
            <div class="accordion" id="cardAccordion">

                 <div class="card">
                    <div class="card-header" id="heading<?php echo $data->id;?>" data-toggle="collapse" data-target="#collapse<?php echo $data->id;?>" aria-expanded="false" aria-controls="collapse<?php echo $data->id;?>" role="button">
                        <span class="collapsed collapse-title">Specification</span>
                    </div>
                    <div id="collapse<?php echo $data->id;?>" class="collapse pt-1" aria-labelledby="headingOne" data-parent="#cardAccordion">
                        <div class="card-body">
                             <ul class="list-group list-group-flush">
                           
                              <li class="list-group-item">
                                  <div class="row">
                                      <div class="col-4">
                                          Weight
                                      </div>
                                      <div class="col-4">
                                          <?php echo $data->weight;?>
                                      </div>
                                  </div>
                              </li>
                              <li class="list-group-item">
                                  <div class="row">
                                      <div class="col-4">
                                          Manufacturer SKU
                                      </div>
                                      <div class="col-4">
                                          <?php echo $data->manufacturer_code;?>
                                      </div>
                                  </div>
                              </li>
       
                              
                            </ul>
                        </div>
                    </div>
                </div>
<?php 
$created=date_create($data->created_dt);
$updated=date_create($data->updated_dt);

$user = explode(',', $data->user_name);
$marketplaces = ['shopee', 'lazada', 'ebay', 'amazon'];
?>
                      <ul class="timeline">
                        <li class="timeline-item timeline-icon-success active">
                            <div class="timeline-time"><?php echo  date_format($created," jS M y H:i:s");?></div>
                            <h6 class="timeline-title">Created</h6>
                            <p class="timeline-text">by <a href="JavaScript:void(0);"><?php echo $user[0];?></a></p>
                            
                        </li>
                        <?php  if (isset($user[1])) {?>
                         <li class="timeline-item timeline-icon-success active">

                            <div class="timeline-time"><?php echo  date_format($updated,"jS M y H:i:s");?></div>
                            <h6 class="timeline-title">Updated</h6>
                            <p class="timeline-text">by <a href="JavaScript:void(0);"><?php echo $user[1];?></a></p>
                            
                        </li>
                    <?php }?>
                        </ul>  

                   
               
            </div>
                               

</div>
    </div>


</div>

<div class="row">
    <div class="col-12">

                            <div class="bg-transparent shadow-none">
           
                                    <ul class="nav nav-pills nav-fill">
                                        <?php
                                        $i=0;
                                        foreach ($marketplaces as $key => $marketplace) {
                                            $i++;
                                           ?>
                                           <li class="nav-item">
                                                <a class="nav-link <?php echo ($i==1?'active':'');?>" id="<?php echo $marketplace;?>-tab-fill" data-toggle="pill" href="#<?php echo $marketplace;?>-fill" aria-expanded="true">
                                                    <?php echo ucfirst($marketplace);?>
                                                </a>
                                            </li>
                                           <?php
                                        }
                                        ?>  

                                    </ul>
                                    <div class="tab-content">

                                        <?php
                                        $i=0;
                                        foreach ($marketplaces as $key => $marketplace) {
                                            $i++;
                                            switch ($marketplace) {
                                                case 'shopee':
                                                   $desc = $data->shopee_descr;
                                                   $cat = $data->shopee_cat_id;
                                                    break;
                                                 case 'lazada':
                                                   $desc = $data->lazada_descr;
                                                   $cat = $data->lazada_cat_id;

                                                    break;
                                                 case 'ebay':
                                                   $desc = $data->ebay_descr;
                                                   $cat = $data->ebay_cat_id;

                                                    break;
                                                 case 'amazon':
                                                   $desc = $data->amazon_descr;
                                                   $cat = $data->amazon_cat_id;

                                                    break;
                                                
                                                default:
                                                    $desc = $data->shopee_descr;
                                                    $cat = $data->shopee_cat_id;

                                                    break;
                                            }
                                           ?>
                                          <div class="tab-pane <?php echo ($i==1?'active':'');?>" id="<?php echo $marketplace;?>-fill" role="tabpanel" aria-labelledby="<?php echo $marketplace;?>-tab-fill" aria-expanded="false">
                                            
                                            <!--content starts-->
                                            <section id="stacked-pill">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <div class="bg-transparent shadow-none border">
                                                           
                                                            <div class="">
                                                               
                                                                <div class="row pills-stacked">
                                                                    <div class="col-md-2 col-sm-12">
                                                                        <ul class="nav nav-pills flex-column text-center text-md-left">
                                                                            <li class="nav-item">
                                                                                <a class="nav-link active" id="stacked-pill-1" data-toggle="pill" href="#vertical-pill-1" aria-expanded="true">
                                                                                    Description
                                                                                </a>
                                                                            </li>
                                                                            <li class="nav-item">
                                                                                <a class="nav-link" id="stacked-pill-2" data-toggle="pill" href="#vertical-pill-2" aria-expanded="false">
                                                                                    Attributes
                                                                                </a>
                                                                            </li>
                                                                            <li class="nav-item">
                                                                                <a class="nav-link" id="stacked-pill-3" data-toggle="pill" href="#vertical-pill-3" aria-expanded="false">
                                                                                    Category
                                                                                </a>
                                                                            </li>
                                                                            
                                                                        </ul>
                                                                    </div>
                                                                    <div class="col-md-10 col-sm-12">
                                                                        <div class="tab-content">
                                                                            <div role="tabpanel" class="tab-pane active" id="vertical-pill-1" aria-labelledby="stacked-pill-1" aria-expanded="true">
                                                                                 <p class="card-text">
                                                                                    <?php echo nl2br($desc);?>
                                                                                </p>
                                                                            </div>
                                                                            <div class="tab-pane" id="vertical-pill-2" role="tabpanel" aria-labelledby="stacked-pill-2" aria-expanded="false">
                                                                                <p class="card-text">
                                                                                   
                                                                                </p>
                                                                            </div>
                                                                            <div class="tab-pane" id="vertical-pill-3" role="tabpanel" aria-labelledby="stacked-pill-3" aria-expanded="false">
                                                                                <p class="card-text">
                                                                                   
                                                                                </p>
                                                                            </div>
                                                                            <div class="tab-pane" id="vertical-pill-3" role="tabpanel" aria-labelledby="stacked-pill-3" aria-expanded="false">
                                                                                <p class="card-text">
                                                                                   
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </section>
                                            <!--content ends-->
                                        </div>
                                           <?php
                                        }
                                        ?>  

                                    </div>
                               
                            </div>
                       

    </div>
</div>

@endsection


