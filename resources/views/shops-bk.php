@extends('layouts.app')

@section('assets')

{{Html::style('assets/css/plugins/forms/wizard.css')}}
@endsection

@section('title')
    Shops
@endsection

@section('content')
<div class="card">
    <?php

    foreach ($shops as $key => $shop) {
       ?>
       <div class="card-header">
        <div class="row">
            <div class="col-9">
                <div class="mx-auto mb-50">
                    <h2>
                        <?php
                        switch ($key) {
                            case '1':
                                echo 'eBay';
                               
                                break;
                            
                            case '2':
                                echo 'Shopee';
                               
                                break;
                            case '3':
                                echo 'Lazada';
                               
                                break;
                            case '4':
                                echo 'Lazada';
                               
                                break;
                            
                            
                            default:
                                
                                break;
                        }
                         ?>
                            
                        </h2>
                    
                </div>
                 
            </div>
             <div class="col-3">
                <div class="badge-circle badge-circle-lg badge-circle-light-danger mx-auto mb-50">
                    <i class="bx bx-store font-medium-5"><?php echo sizeof($shop);?></i>
                </div>
            </div>
        </div>
        
       </div>

        <div class="card-body">
            <div class="row">
                
            <?php
            $i = 0;
            
            foreach ($shop as $k => $v) {
                $i++;
                $j = 0;
                ?>
                 
                <h3>
                    <i class="bx bx-store font-medium-5 badge-circle-light-danger"><?php echo $i;?></i>

                    <?php echo $k;?>
                    <i class="bx bx-user font-medium-5 badge-circle-light-danger"><?php echo sizeof($v);?></i>
                </h3>
                <?php
                  foreach ($v as $k2 => $v2) {
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
                  
               <?php
               
            }
            ?>
        </div>
            </div>

       <?php
    }
?>
    

   
</div>


@endsection

@section('assets')
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

{{Html::script('assets/js/scripts/navs/navs.js')}}
{{Html::script('assets/js/scripts/forms/wizard-steps.js')}}

@endsection
