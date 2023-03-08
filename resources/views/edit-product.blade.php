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
<?php


$url = "/product/".$data->id;
?>
 <form id="form" action="{{ url($url) }}" method="post"  enctype="multipart/form-data" class="form form-vertical">
 @csrf

<div class="row">

   <div class="col-6">

    <div class="card-body">
        <h4 class="card-title">
             <input type="text" class="form-control" name="listing_name" placeholder="Listing Title" value=" <?php echo $data->name;?>"></h4>
        <h6 class="card-subtitle"> <?php echo $data->sku;?></h6>
   
    <div class="row">
        <div class="col-12">
            @include('images')
        </div>
    </div>
 
        </div>
   </div>

   <div class="col-6">
   

    <div class="">
    <div class="card-body">
        <h6>
            <span class="text-muted"> 
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

             <i class="cursor-pointer bx bx-dots-vertical-rounded float-right" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"></i>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="/product/<?php echo $data->id;?>">View</a>
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
                <input type="hidden" name="brand" value="<?php echo $data->brand_id;?>">
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

             <fieldset>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text">MYR</span>
                    </div>
                    <input type="text" class="form-control" name="selling_price" placeholder="Selling Price" value="<?php echo $data->selling_price;?>">
                    <div class="input-group-append">
                        <span class="input-group-text">.00</span>
                    </div>
                </div>
            </fieldset>

            
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
                            <div class="timeline-time"><?php echo  date_format($created,"jS M y H:i:s");?></div>
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

                                                                                <!--attribute starts-->
                                                                                @include('description', [
                                                                                'description'=>$description])
                                                                                <!--attribute ends-->
                  

                                                                                 
                                                                            </div>
                                                                            <div class="tab-pane" id="vertical-pill-2" role="tabpanel" aria-labelledby="stacked-pill-2" aria-expanded="false">

                                                                                <!--attribute starts-->
                                                                                @include('attributes', ['marketplace'=>$marketplace])
                                                                                <!--attribute ends-->
                                                                            </div>
                                                                            <div class="tab-pane" id="vertical-pill-3" role="tabpanel" aria-labelledby="stacked-pill-3" aria-expanded="false">
                                                                                <p class="card-text">
                                                                                   
                                                            <input type="text" id="category_id" value="<?php echo (isset($shopee_cat) ? $shopee_cat:'');?>"  class="form-control">
                                                            <input type="hidden" name="category_id" value="">

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

<div class="row">
    <div class="col-md-12">
         <input type="submit" value="SUBMIT" data-save>
    </div>
</div>
</form>
@endsection


@section('footer-scripts')


{{Html::script('assets/js/vendors/js/editors/quill/katex.min.js')}}
{{Html::script('assets/js/vendors/js/editors/quill/highlight.min.js')}}

{{Html::script('assets/js/vendors/js/editors/quill/quill.min.js')}}

{{Html::script('assets/js/vendors/js/editors/quill/editor-quill.js')}}

<script type="text/javascript">
    $('[data-repeater-delete-attribute]').on('click', function() {
  
        $(this).parents('li').remove();
    });

 //    $('body').on('click', '[data-save]', function(e) {
 // console.log('editor');
 //     e.preventDefault = false;
 //    var status = false;
 //    if (status == false) {

 //        var quill = new Quill ('.editor');
 //        var quillHtml = quill.root.innerHTML.trim();
 //        console.log(quillHtml);
 //        return false;
 //        $('#content').val(quillHtml);
 //        e.preventDefault = false;
 //    }
 

  
 //    });

    //reindex dynamic elements
    // $('[data-save]').on('click', function(e) {

    //     e.preventDefault();
    //     $('[data-custom-attribute]').each(function(a, b) {
          
    //         console.log(this);
    //       });
    //     return false;
    // });
</script>
@endsection