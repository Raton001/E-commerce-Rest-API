@extends('layouts.app')

@section('assets')

@endsection

@section('title')
    {{ $title }}
@endsection


@section('content')
<div class="card bg-transparent shadow-none border">
    <div class="card-header text-left">
        <!--status-->
        <ul id="portfolio-flters" class="d-flex justify-content-center" data-aos="fade-up" data-aos-delay="100">
          <li data-filter="*" class="filter-active">All</li>

            <?php
            if (isset($keys)) {
     
                foreach ($keys as $b => $brand) {

                    ?>
                    <li data-filter=".filter-<?php echo (isset($brand->id)? $brand->id: $brand);?>">
                        <?php echo (isset($brand->name)? $brand->name: $brand);?>
                    </li>
                    <?php
                }
            }
            ?>

          
        </ul>

    </div>
<div class="card-body">


<?php 
if (isset($role) && $role == 'admin') {
?>
 <form method="post" action="/<?php echo $form;?>">

<?php
} 
?>
           @csrf
<input type="hidden" id="brand_id" value="<?php echo implode(',', array_column((array)$keys, 'id'));?>">

        <div class="row portfolio-container" data-aos="fade-up" data-aos-delay="200">
         <div class="row">
                <div class="col-12 text-center">
                  <div class="spinner-border text-secondary loading-csc" role="status" style="margin-top:100px;">
                    <span class="sr-only">Loading...</span>
                </div>
                    
                </div>
            </div>
           <?php
                if (isset($keys)) {
                foreach ($keys as $b => $brand) {
                    ?>
                    <div class="col-lg-12 col-md-6 portfolio-item filter-<?php echo (isset($brand->id)? $brand->id: $brand);?>" data-filter-<?php echo (isset($brand->id)? $brand->id: $brand);?>>

<input type="hidden" name="brand_id" id="selected_brand_<?php echo (isset($brand->id)? $brand->id: $brand);?>" value="<?php echo (isset($brand->id)? $brand->id: $brand);?>">


                        <!--data table starts-->
                        <table id="target" class="table table-striped" style="width:100%">
                            <thead class="hidden">
                                <tr>
                                    <!--standard columns-->
                                    <th>#</th>
                                    <th>
                                        <fieldset>
                                            <div class="checkbox checkbox-info checkbox-glow">
                                                <input type="checkbox" id="ship_all">
                                                <label for="ship_all"></label>
                                            </div>
                                        </fieldset>
                                    </th>
                                
                                    
                                    @include($page)

                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <!--data table ends-->
                        </div>
                            <?php
                        }
                    }
                ?>


            </div>

            <button type="submit" class="btn btn-secondary btn-block subtotal-preview-btn hidden" data-submit>Submit</button>
           
           
          </form>

    </div>

</div>

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">User List</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">

        <table>
          <thead>
          </thead>
          <tbody class="table">
              <tr>
                  <td></td>
                  <td></td>
              </tr>
              <tr>
              <td><i class="bx bx-user font-medium-5"></i></td>
        <td>{{$article}}</td>
              </tr>
          </tbody>
      </table>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
        </div>
    </div>
    </div> 

@endsection

