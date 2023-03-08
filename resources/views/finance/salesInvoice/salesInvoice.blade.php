@extends('layouts.app')


@section('title')
    {{ $title }}
@endsection

@section('headertitle')
 <h3>Sales Invoice</h3>
@endsection

@section('content')

<style>
    .card {
       border: 1px solid #ccc;
       background-color: #f4f4f4;
       padding: 5px;
       margin-bottom: 15px;
     }
 </style>

<div class="my-3 mr-1" >
    <a href="#">
       <button type="button" class="btn btn-primary float-right" id="show" data-toggle="modal" data-target="#modalDo" > Add Invoice</button>
    </a>

 </div>

{{-- compnay name display --}}

<div class="d-flex justify-content-between">
  @foreach ( $companies  as $company )

   <?php
        $count = DB::table('invoice')
        ->where('com_id','=', $company->id)
        ->Where('deleted_status', '=', 0)
        ->where('type', '=', 'do')->count();
   ?>
   <div class="card ml-2 mx-2" style="width: 18rem; height: 7rem">
    <div class="card-body d-flex justify-content-between">
        <div class="pr-1"><img src="assets/images/company_logo/{{$company->logo}}" alt="" height="20" width="25"></div>
        <p class="card-text">{{$company->name}} </p>
        <span> {{$count}} </span>
    </div>
</div>

  @endforeach
</div>

{{-- compnay name display End  --}}


@if(session()->has('success'))

    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>{{session()->get('success')}}</strong>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif


<input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

<p id="myElem" style="display: none" class="alert alert-secondary" role="alert" >Invoice has been generated</p>

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



    </div>
</div>


{{-- <div class="modal" id="myModal">
    <div class="modal-dialog">
      <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Modal Heading</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body">
          Modal body..
        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

      </div>
    </div>
  </div>

</div> --}}


<div class="modal fade" id="modalDo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Select Delivery Order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
             <form>
                <select class="form-select" aria-label="Default select example" name="donumber" id="id"  required>
                    <option value="" >-Select Delivery Order-</option>
                     @foreach ($doArr as $donumber )
                       <option value="{{$donumber->id}}">{{$donumber->doc_no}}</option>
                     @endforeach
                  </select>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary" onclick="generate()">Generate Invoice</button>
            </div>
        </form>
        </div>
    </div>
</div>



<script>

@section('js')

    $('#show').on('click', function(){
      ('#modalDo').show();
    });



//Generate Invoice
function generate() {
    var do_no = $('#id').val();
    // console.log(do_no);
    if (do_no !== '') {
        $.ajax({
            url:"/create-sales-invoice/",
            method: "POST",
            data: {
                "_token": $('#token').val(),
                  "do_no": do_no
                },
            success: function (response) {
                if (response == 'success') {
                  swal({
                        position: 'center-center',
                        type: 'success',
                        title: 'Invoice has been generated',
                        showConfirmButton: false,
                        timer: 2000,
                    }).then(function (result) {
                        location.reload();
                    });
                }
            },
        });

    }

}

@endsection

</script>


@endsection




