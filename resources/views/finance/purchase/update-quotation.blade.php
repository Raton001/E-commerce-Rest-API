@extends('layouts.app')


@section('title')
    {{ $title }}
@endsection

@section('headertitle')
 <h3>Update Quotation</h3>
@endsection

@section('content')

<form id="myform">

    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

     <p id="myElem" style="display: none" class="alert alert-secondary" role="alert" >Quotation has been updated</p>

      <div class="row mt-5 mx-1" >
        <input type="hidden" class="form-control" value="{{$model->uuid}}" id="uuid">
        <input type="hidden" class="form-control" value="{{$model->id}}" id="item_id">
          <div class="col-4 col-sm-4 py-2 wow fadeInRight" data-wow-delay="300ms">
               <label> Company </label>
              <select class="form-select" aria-label="Default select example" name="company_id" id="company_id" disabled required >
              <option value="{{$modelcom->company_id}}">{{$modelcom->name}}</option>
                  @foreach ($companies as $company)
                   <option value="{{$company->company_id}}">{{$company->name}}</option>
                  @endforeach
              </select>
            </div>

            <div class="col-4 col-sm-4 py-2 wow fadeInLeft" data-wow-delay="300ms">
              <label> Quotation Date </label>
              <input type="date" name="doc_dt" id="doc_dt" class="form-control" value="{{ $model->doc_date }}" required>
            </div>

            <div class="col-4 col-sm-4 py-2 wow fadeInLeft" data-wow-delay="300ms">
              <label> Valid Until </label>
              <input type="date" name="valid_dt" id="valid_dt" class="form-control" value="{{ $model->valid_dt }}" required>
            </div>

           <div class="col-4 col-sm-4 py-2 wow fadeInRight" data-wow-delay="300ms">
              <label> Customer </label>
             <select class="form-select" aria-label="Default select example" name="customer_id" id="cust_id"  required>
               <option value="{{$model->customer_id}}" >{{$model->customer_name}}</option>
               @foreach ($customers as $customer)
                 <option value="{{$customer->customer_id}}">{{$customer->name}}</option>
               @endforeach
             </select>
           </div>

           <div class="col-4 col-sm-4 py-2  wow fadeInLeft" >
              <label> Customer Name </label>
              <input type="text" name="customer_name"  id="customer_name" class="form-control" value="{{$model->customer_name}}" required>
            </div>

            <div class="col-4 col-sm-4 py-2  wow fadeInLeft">
              <label> Customer Address </label>
            <input type="text" name="address-1" id="address" class="form-control" value="{{$model->customer_address1}}" required>
          </div>

          <div class="col-4 col-sm-4 py-2  wow fadeInLeft">
              <label> Address 2 </label>
              <input type="text" name="address-2" id="address2" class="form-control" value="{{$model->customer_address2}}">
          </div>

          <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
              <label> Postcode </label>
              <input type="text" name="postcode"  id="postcode" class="form-control"  value="{{$model->customer_postcode}}" required>
          </div>



          <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
              <label> City </label>
              <input type="text" name="city"  id="city" class="form-control" value="{{$model->customer_city}}" required>
          </div>

          {{-- <div class="col-4 col-sm-4 py-2 wow fadeInRight" data-wow-delay="300ms">
              <label> State </label>
             <select class="form-select" aria-label="Default select example"   name="state" id="state" required>
              @foreach ($states as $state)
               <option value="{{$state->label}}">{{$state->label}}</option>
               @endforeach

             </select>
           </div> --}}

          <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
              <label> State</label>
              <input type="text" name="state" id="state" class="form-control" value="{{$model->customer_state}}" required>
          </div>

          <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
            <label> Contact Person</label>
             <div class="d-flex justify-content-between">
              <input type="text" name="contact-person" placeholder="Click Select to choose from Contact Person" id="contactperson" class="form-control" value="{{$model->customer_pic}}" required >
              <button class='btn btn-warning' type='button' data-toggle="modal" data-target="#myModal" id="test2">Select</button>
             </div>
          </div>

          <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
              <label> Contact No </label>
              <input type="text" name="contact-no" id="contactno" class="form-control" value="{{$model->customer_phone}}" required>
          </div>

          {{-- product table coming from controller ajax call --}}
          <div id="wrapper_product_list"></div>
          </div>

       <div id ="hidetable">
          <table class="table table-bordered table-striped" >
            <thead>
            <tr class="thead-dark">
            <th width="1%">No</th>
            <th>Description</th>
            <th width="10%">Qty</th>
            <th width="10%">UoM</th>
            <th width="10%">Unit Price</th>
            <th width="10%">Total</th>
            <th width="1%">Action</th>
            </tr>
            </thead>
            <tbody>
                @php
                $products = DB::table('quotation_item')->where('doc_id' , $model->id)->orderBy('id' , 'ASC')->get();
                 // dd($products);
                 $count =1;
                 @endphp
                 @if (!empty( $products))
                  @foreach ( $products as $row)
                   @php
                    $count++;
                    echo '<tr>
                    <td>' . $count . '</td>
                    <td>' . $row->product_name . '</td>
                    <td>' . number_format($row->quantity) . '</td>
                    <td>' . $row->uom . '</td>
                    <td>RM ' . number_format((float) $row->unit_price, 2, '.', ',') . '</td>
                    <td>RM ' . number_format((float) $row->total_price, 2, '.', ',') . '</td>
                    <td align="center"><i class="fa-solid fa-trash-can" style="cursor:pointer;" title="Delete product" onclick="deleteproduct(' . $row->id . ')"></i></td>
                    </tr>';
                @endphp
                @endforeach
              @endif
            </tbody>
          </table>
        </div>



</form>


    <div class="mb-2 ml-1 d-flex" >
        <a href="{{route('quotation')}}">
            <button type="submit" class="btn btn-primary mt-3 wow zoomIn">Back</button>
      </a>
        <button class="btn btn-warning mt-3 ml-1 wow zoomIn" id="reset">Reset</button>

        <button type="submit" class="btn btn-primary mt-3 ml-1 wow zoomIn" id="update" >Save</button>
        <button class="btn btn-outline-warning mt-3 ml-1" type="button" data-toggle="modal" data-target="#Modal"  id="add" onclick="addproduct()" > Add Product </button>
        <button class="btn btn-outline-info mt-3 ml-1" type="button"   id="view" onclick="view()" > View Quotation  </button>

   </div>

</div>

  {{-- Modal --}}

<div class="modal fade" id="myModal">
    <div class="modal-dialog">
    <div class="modal-content">
        <!-- Modal Header -->
        <div class="modal-header">
        <h4 class="modal-title">Choose Contact Person</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>

        <!-- Modal body -->
        <div class="modal-body"> <div id="show"> </div>

        </div>

        <!-- Modal footer -->
        <div class="modal-footer">
        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>

    </div>
    </div>
</div>



{{-- Modal for add product--}}
 <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="exampleModalLongTitle">Add Product</h5></div>
            <div class="modal-body">
                <form autocomplete="false">
                    <input type="hidden" class="form-control" id = "abc">
                    <input type="hidden" class="form-control" id = "item_uuid">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Description</label>
                                <input type="text" class="form-control" id="item_product_name">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>UoM</label>
                                <input type="text" class="form-control" id="item_uom">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="text" class="form-control" value="0" id="item_quantity" onchange="updatetotalprice()">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Unit Price</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><div class="input-group-text">MYR</div></div>
                                    <input type="text" class="form-control" value="0.00" id="item_unit_price"  onchange="updatetotalprice()">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label>Total</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><div class="input-group-text">MYR</div></div>
                                    <input type="text" class="form-control" value="0.00" id="item_total_price">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="fa-font-bold font-danger " id="error_message">Please complete the details above</div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary"  onclick="saveproduct()">Add product</button>
            </div>
        </div>
    </div>
</div>








<script>


 @section('js')


//get details
$('#cust_id').change(function() {

var id = $(this).val();
var url = '{{ route("getDetails", ":id") }}';
url = url.replace(':id', id);

$.ajax({
    url: url,
    type: 'get',
    dataType: 'json',
    success: function(response) {
    if (response != null) {
        $('#customer_name').val(response.name);
        $('#address').val(response.address1);
        $('#address2').val(response.address2);
        $('#postcode').val(response.postcode);
        $('#city').val(response.city);
        $('#state').val(response.state);
        $('#contactperson').val(response.contact_name);
        $('#contactno').val(response.contact_no);

    }
}
});
});


//changing the contact person
$('#test2').on('click', function() {
    var id = $('#cust_id').val();
    var type = 'ctc_cust';
    $.ajax({
        url: '/choose-contact-person/' +id+'/'+type,
        type: 'get',
        dataType: 'json',
        success: function (response) {
            //console.log(response);
            $('#show').html(response);
            $('#myModal').show();

            },
        });
    });


//change contact
function changecontact(val) {
    var arr = val.split("<->");
    console.log(arr);
    $('#contactperson').val(arr[0]);
    $('#contactno').val(arr[1]);
}



//update quotation
$('#update').on('click', function(){
    var uuid = $('#uuid').val();
    var company_id = $('#company_id').val();
    var doc_dt = $('#doc_dt').val();
    var valid_dt = $('#valid_dt').val();
    var customer_pic = $('#customer_pic').val();
    var cust_id = $('#cust_id').val();
    console.log(cust_id);
    var customer_name = $('#customer_name').val();
    var address = $('#address').val();
    var address2 = $('#address2').val();
    var postcode = $('#postcode').val();
    var city = $('#city').val();
    var state = $('#state').val();
    var contactperson = $('#contactperson').val();
    var contactno = $('#contactno').val();

    $.ajax({
            url: '/save-updated-quotation/',
            type: 'post',
            dataType: 'json',
            data: {
                "_token": $('#token').val(),
                    uuid:uuid,
                    company_id:company_id,
                    doc_dt:doc_dt,
                    valid_dt:valid_dt,
                    cust_id:cust_id,
                    customer_name:customer_name,
                    address:address,
                    address2:address2,
                    postcode:postcode,
                    city:city,
                    state:state,
                    contactperson:contactperson,
                    contactno:contactno,

                },
         success: function (response) {


            if (response != null) {
             console.log(response);
            $("#myElem").show();
            setTimeout(function() { $("#myElem").hide(); }, 3000);

      }
    }
    });

 });

//add product
function addproduct() {
    $('#item_product_name').val('');
    $('#item_uom').val('');
    $('#item_quantity').val('0');
    $('#item_unit_price').val('0.00');
    $('#item_total_price').val('0.00');
    $('#error_message').hide();
}

//update product price
function updatetotalprice() {
    var quantity = parseInt($('#item_quantity').val());
    var price = parseFloat($('#item_unit_price').val());
    var total = quantity * price;
    $('#item_total_price').val((Math.round(total * 100) / 100).toFixed(2));
}


//save product
function saveproduct() {
    var doc_id = $('#item_id').val();
    var product_name = $('#item_product_name').val();
    var uom = $('#item_uom').val();
    var quantity = $('#item_quantity').val();
    var unit_price = $('#item_unit_price').val();
    var total_price = $('#item_total_price').val();
    if (product_name !== '' && uom !== '' && quantity !== '0' && unit_price !== '0.00' && total_price !== '0.00') {

        $.ajax({
            url: "/add-product-quotation/",
            method: "POST",
            data: {
                    "_token": $('#token').val(),
                    "doc_id": doc_id,
                    "product_name": product_name,
                    "uom": uom, "quantity": quantity,
                    "unit_price": unit_price,
                    "total_price": total_price
                    },

            success: function (response) {
                if (response === '1') {
                    swal({
                    position: 'center-center',
                    type: 'success',
                    title: 'Data has been saved',
                    showConfirmButton: false,
                    timer: 1500, })
                    $('#Modal').hide();
                    $('#hidetable').hide();
                    updateproductlist();
                } else {
                    swal({
                        position: 'center-center',
                        type: 'error',
                        title: 'Data failed to saved. Please try again',
                        showConfirmButton: false,
                        timer: 1500, })
                        window.reload();
                }
            },
        });
    } else {
        $('#error_message').show();
      }
 }

 //update products list
 function updateproductlist() {

  var doc_id = $('#item_id').val();

    $.ajax({
        url: "/update-product-list-quotation/",
        method: "POST",
        data: {
               "_token": $('#token').val(),
               "doc_id": doc_id
              },
        success: function (response) {
            $('#wrapper_product_list').html(response);
        },
    });
  }


  //delete product
  function deleteproduct(id) {

        swal({
            title: 'Are you sure to delete this product?',
            text: "You won't be able to revert this!",
            // icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
         })
            $.ajax({
                url: "/delete-product-quotation/",
                method: "POST",
                data: {
                       "_token": $('#token').val(),
                       "id": id
                       },
                success: function (response) {
                    if (response === 'success'){
                        swal({
                        position: 'center-center',
                        type: 'success',
                        title: 'Product has been deleted',
                        showConfirmButton: false,
                        timer: 1500, })
                        $('#hidetable').hide();
                        updateproductlist();
                    }
                },
           });

    }


//view Quotation

function view(){
    var uuid = $('#uuid').val();

    $.ajax({
        url: '/download-quotation/'  +uuid,
        type: 'get',
         success: function(response){
            var newWindow = window.open();
            newWindow.document.write(response);

         }

    });

}





 //reset
 $('#reset').on('click', function(){
$('#myform').trigger("reset");
});


 @endsection

</script>


@endsection
