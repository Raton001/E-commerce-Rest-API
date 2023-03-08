@extends('layouts.app')

@section('content')
    <!-- <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body"> -->
                <!-- Dashboard Ecommerce Starts -->
                <section id="">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Search Result') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    

                    {{ __('Account') }}

                    <select data-selected-account>
                        <option>All</option>
                   <?php
                   if (isset($data)) {


                     foreach ($data as $key => $value) {
                       ?>
                       <option>
                           <?php echo $value;?>
                       </option>
                       <?php
                    }
                   }
                  

                   ?>
                   </select>

                   <!--data by default-->
                   <div data-listings-summary class="row" style="margin-top: 20px;">
                    
                     <div class="col-md-<?php echo ($account!=''? 12: 6);?>">
                        <div class="card">
                            <div class="card-header">
                              <div class="row">
                                <div class="col-md-6">
                                  <?php echo strtoupper($key);?>
                                </div>
                 
                              </div>
                            </div>

                            <div class="card-body">
                               <table class="table-bordered">
                                <tr>
                                  <th></th>
                                  <th>Image</th>
                                  <th>Title</th>
                                  <th>Quantity</th>
                                  <th>Price</th>
                                  <th>Action</th>

                                </tr>
                                 <?php
                                  if (isset($selling)) {
                                    $count = 0;
                                  
                                  foreach ($selling as $key => $value) {
                                    $count++;
                                     
                                      ?>
                                                                   <tr>
                                      <td><?php echo $count;?></td>

                                    <td>
                                        
                                  
                                    <?php
                                    foreach ($value['PictureDetails'] as $k => $v) {
                                       // foreach ($v['GalleryURL'] as $k2 => $v2) {
                                          ?>
                                          <img src="<?php  echo $v;?>" style='width: 60px;'>
                                          <?php
                                       // }
                                    }
                                    ?>
                                    
                                      </td>
                                    <td>
                                       <input type="text" name="title" value="<?php echo $value['Title'];?>" style="border:none; outline: none;width: 600px;">
                                      
                                       
                                        
                                      </td>
                                      <td>
                                        <input style="border:none; outline: none;width: 50px;" type="number" name="quantity" value="<?php echo (isset($value['Quantity'])? $value['Quantity']: '');?>">
                                      </td>
                                      <td>
                                        <div class="row">
                                          <div class="col-1">
                                            $
                                          </div>
                                          <div class="col-10">
                                            <input style="border:none; outline: none;width: 60px;" type="text" name="sellingPrice" value="<?php echo $value['BuyItNowPrice'];?>">
                                          </div>
                                        </div>
                                          
                                      </td>
                                      <td>
                                       
                                        <div class="row">
                                          <div class="col-3" style="width: 100px;">
                                            <a href="/ebay/<?php echo $key; ?>/listing/<?php echo $value['ItemID'];?>" alt="view">
                                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
  <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
  <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
</svg>
                                            </a>
                                          </div>
                                          <div class="col-3">
                                            <a href="/ebay/<?php echo $key; ?>/listing/edit/<?php echo $value['ItemID'];?>" alt="edit">
                                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
</svg>
                                            </a>
                                          </div>
                                          <div class="col-3">
                                            <a href="/ebay/<?php echo $key; ?>/listing/<?php echo $value['ItemID'];?>" alt="re-sell">
                                              <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-repeat" viewBox="0 0 16 16">
  <path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/>
  <path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/>
</svg>
                                            </a>
                                          </div>
                                          <div class="col-3">
                                            <a href="/ebay/<?php echo $key; ?>/listing/sellsimilar/<?php echo $value['ItemID'];?>" alt="sell similar">
                                              
<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard" viewBox="0 0 16 16">
  <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
  <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
</svg>
                                            </a>
                                          </div>
                                          <div class="col-3">
                                            <a href="/ebay/<?php echo $account; ?>/listing/end/<?php echo $value['ItemID'];?>" alt="sell similar">
                                             Delete
                                            </a>
                                          </div>
                                        </div>
                                        
                                          
                                      </td>
                                     
                                </tr>
                                       <?php
                                    }
                                  }
                                  ?>
                                 
                               </table>

   
                               
                            </div>
                        </div>
                    </div>
                   
                   </div>
                   <!--data populated on selection-->
                    <div data-listings></div>
                </div>
            </div>
        </div>
    </div>

</section>
<!-- </div>
</div>
</div> -->


@endsection
