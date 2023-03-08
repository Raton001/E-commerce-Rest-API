@extends('layouts.app')

@section('assets')

{{Html::style('assets/css/plugins/forms/wizard.css')}}
@endsection

@section('title')
    Shops
@endsection

@section('content')

<?php
$marketplaces = array(
    '1'=>'ebay',
    '2'=>'shopee',
    '3'=>'lazada',
    '4'=>'amazon',
    '5'=>'all'
);
?>
<div class="card-header">
    <div class="row">
  <div class="col-8">
    <ul id="portfolio-flters" class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">
        <?php
        foreach ($marketplaces as $key => $marketplace) {
           ?>
            <li data-filter=".filter-<?php echo $marketplace;?>"  class="filter"><?php echo ucfirst($marketplace);?></li>
           <?php
        }
        ?>
       
       


    </ul>
  </div>
  
</div>
</div>
   <div class="card-body">
  
        <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">
             <?php
             
             foreach ($marketplaces as $key => $marketplace) {
               
              

                
             ?>
            <div class="hidden col-lg-12 col-md-12 portfolio-item filter-<?php echo $marketplace;?>">
                 <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                    <i class="bx bx-store font-medium-5">
                        <?php 
                        if (isset($shops[$key])) {
                             echo sizeof($shops[$key]);
                        }
                       ?>
                            
                        </i>
                </div>
                <div class="table-responsive">
                 <table class="table table-striped">
                      <thead>
                          <tr class="text-left">
                            <th></th>
                            
                              <th>Shop Name</th>
                              <th>Total Users</th>
                              <th>View Users</th>

                             

                          </tr>
                      </thead>
                      <tbody>
                        <?php
              
                            if (isset($shops[$key])) {
                                $i = 0;
                                foreach ($shops[$key] as $k => $shop) {
                                   
                                $i++;
                                    ?>
                                   <tr>
                                    <td>
                                        <?php echo $i;?>
                                    </td>
                                       <td>

                                           <a href="/<?php echo $marketplaces[$key];?>/shop/<?php echo $shop[0]->account;?>"><?php echo $k;?></a>
                                   
                                       </td>
                                       <td>
                                          <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                                            <i class="bx bx-user font-medium-5"><?php echo sizeof($shop);?></i>
                                        </div>
                                       </td>
                                       <td>
                                           <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#shop_<?php echo $shop[0]->account;?>">View </button>
                                       </td>
                                   </tr>
                                   <!--modal-->
                                   <div class="modal fade" id="shop_<?php echo $shop[0]->account;?>" tabindex="-1" role="dialog" aria-labelledby="shop_<?php echo $shop[0]->account;?>Label" aria-hidden="true">
                                      <div class="modal-dialog modal-xl" role="document">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <h5 class="modal-title" id="shop_<?php echo $shop[0]->account;?>Label">Shop #<?php echo $shop[0]->shopname;?></h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                              <span aria-hidden="true">&times;</span>
                                            </button>
                                          </div>
                                          <div class="modal-body">
                                             <?php
                                             $j=0;
                                                foreach ($shop as $k2 => $v2) {
                                                    $j++;
                                                     ?>
                                                     <div class="col-3">
                                                        <div class="card text-left">
                                                           
                                                              
                                                                <h5 class="mb-0">
                                                                    <i class="bx bx-user font-medium-5"><?php echo $j;?></i>
                                                                    <?php
                                                                    echo $v2->name;
                                                                    ?>
                                                                        
                                                                </h5>
                                                               
                                                            </div>

                                                        </div>

                                                     <?php
                                                  }
                                               ?>
                                          </div>
                                      </div>
                                    </div>
                                   <?php
                               }
                            }
                       ?>
                      </tbody>
                  </table>
                </div>
            </div>
             <?php
              
             }
             ?>


        </div>
</div>


@endsection

@section('assets')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

{{Html::script('assets/js/scripts/navs/navs.js')}}
{{Html::script('assets/js/scripts/forms/wizard-steps.js')}}

@endsection
