@extends('layouts.app')


@section('title')
    {{ $title }}
@endsection

@section('headertitle')
 <h3> Add Delivery Order</h3>
@endsection

@section('content')


{{-- @if(session()->has('message'))

<div class="alert alert-warning alert-dismissible fade show" role="alert">
    <strong>{{session()->get('message')}}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

@endif --}}
<form id="myform">

  <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

   <p id="myElem" style="display: none" class="alert alert-secondary" role="alert" >Delivery Order has been Saved</p>
    <input type="hidden" id="uuid" value="{{$model->uuid}}">
    <input type="hidden" id="doc_id" value="{{$model->id}}">

    <div class="row mt-5 mx-1" >
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
            <label> Date </label>
            <input type="date" name="doc_dt" id="doc_dt" class="form-control" value="{{$model->doc_dt}}" required>
          </div>

        <div class="col-4 col-sm-4 py-2  wow fadeInLeft">
            <label> Customer PO ref</label>
            <input type="text" name="po_ref" id="po_ref" value="{{$model->po_ref}}" class="form-control">
        </div>

        <div class="col-4 col-sm-4 py-2 wow fadeInRight" data-wow-delay="300ms" >
            <label> Sales Person </label>
           <select class="form-select" aria-label="Default select example" name="sales_pic" id="sales_pic"  required>
             <option value="{{$sales_person->id ?? ''}}" >{{$sales_person->name ?? ''}}</option>
              @foreach ($sales_pic as $user )
                <option value="{{$user->sales_pic}}">{{$user->name}}</option>
              @endforeach
           </select>

       </div>

         <div class="col-4 col-sm-4 py-2 wow fadeInRight" data-wow-delay="300ms">
            <label> Customer </label>
           <select class="form-select" aria-label="Default select example" name="customer_id" id="cust_id"  required>
             <option value="{{$model->customer_name}}" >{{$model->customer_name}}</option>
             @foreach ($customers as $customer)
               <option value="{{$customer->customer_id}}">{{$customer->name}}</option>
             @endforeach
           </select>
         </div>

         <div class="col-4 col-sm-4 py-2  wow fadeInLeft" >
            <label> Customer Name </label>
            <input type="text" name="customer_name" id="customer_name" value="{{$model->customer_name}}" class="form-control" required>
          </div>

          <div class="col-4 col-sm-4 py-2  wow fadeInLeft">
            <label> Address </label>
          <input type="text" name="address-1" id="address" class="form-control" value="{{$model->customer_address1}}" required>
        </div>

        <div class="col-4 col-sm-4 py-2  wow fadeInLeft">
            <label> Address 2 </label>
            <input type="text" name="address-2" id="address2" value="{{$model->customer_address2}}" class="form-control">
        </div>

        <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
            <label> Postcode </label>
            <input type="text" name="postcode"  id="postcode" class="form-control" value="{{$model->customer_postcode}}" required>
        </div>



        <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
            <label> City </label>
            <input type="text" name="city"  id="city" class="form-control" value="{{$model->customer_city}}" required>
        </div>

        {{-- <div class="col-4 col-sm-4 py-2 wow fadeInRight" data-wow-delay="300ms">
            <label> State </label>
           <select class="form-select" aria-label="Default select example"   name="state" id="state" required>
            <option value="{{$customer_state->code}}" >{{$customer_state->label}}</option>
            @foreach ($states as $state)
              <option value="{{$state->label}}">{{$state->label}}</option>
             @endforeach

           </select>
         </div> --}}

        <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
            <label> State</label>
            <input type="text" name="state" id="state" class="form-control" value="{{$customer_state->label ?? ''}}" required>
        </div>


        <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
            <label> Contact Person</label>
           <div class="d-flex justify-content-between">
            <input type="text" name="contact-person" placeholder="Click Select to choose from Contact Person" id="contactperson" value="{{$model->customer_pic}}" class="form-control"  required >
            <button class='btn btn-warning' type='button' data-toggle="modal" data-target="#myModal" id="test2">Select</button>
           </div>
        </div>

        <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
            <label> Contact No </label>
            <input type="text" name="contact-no" id="contactno" class="form-control" value="{{$model->customer_phone}}" required>
        </div>

      <div>
        <table class="table table-bordered table-striped" id="wrapper_product_list">
            <thead>
                <tr class="thead-dark">
                    <th>SKU</th>
                    <th>Description</th>
                    <th width="10%">Stock Owner</th>
                    <th width="11%">Qty</th>
                    <th width="10%">UoM</th>
                    <th width="10%">Unit Price (RM)</th>
                    <th width="10%">Discount (%)</th>
                    <th width="10%">Total (RM)</th>
                    <th>Remarks</th>
                    <th width="1%">Action</th>
                </tr>
            </thead>
            <tbody>
             @php
              $modelItem = App\DeliveryOrderItem::where(['doc_id' => $model->id])->orderBy('id', 'ASC')->get();
             @endphp

             @if(!empty($modelItem))
             @foreach ($modelItem as $rowItem)
                @php
                    $ownertypeV = '';
                    $ownertypeC = '';

                    if ($rowItem->owner_type == 'vendor')
                        $ownertypeV = 'selected';
                    if ($rowItem->owner_type == 'company')
                        $ownertypeC = 'selected';

                    $stock_owned_vendor = App\StockLog::balancestockbyowner($rowItem->product_id, 'vendor', App\Product::getvendor($rowItem->product_id));
                    $stock_owned_com = App\StockLog::balancestockbyowner($rowItem->product_id, 'company', $model->company_id);
                    $stock_all = $stock_owned_vendor + $stock_owned_com;

                    echo '<tr id="rowItem_' . $rowItem->id . '">
                            <td style="vertical-align:middle;">' . App\Product::getsku($rowItem->product_id) . '</td>
                            <td style="vertical-align:middle;">
                                <input type="hidden" value="' . $rowItem->id . '" name="item_id[]">
                                ' . App\Product::getname($rowItem->product_id) . '
                            </td>
                            <td>
                                <select class="form-control kt-selectpicker" name="item_ownertype_' . $rowItem->id . '">
                                    <option value="company" ' . $ownertypeC . '>Company</option>
                                    <option value="vendor" ' . $ownertypeV . '>Vendor</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" value="' . $rowItem->quantity . '" name="item_quantity_' . $rowItem->id . '" onchange="updatetotal(' . $rowItem->id . ')">
                                <span class="kt-badge kt-badge--inline kt-badge--success mt-2 tooltip-stock" data-toggle="tooltip" data-placement="bottom" data-html="true" title="Vendor : ' . number_format($stock_owned_vendor) . '<br>Company : ' . number_format($stock_owned_com) . '">Current stock : ' . number_format($stock_all) . '</span>
                            </td>
                            <td><input type="text" class="form-control" value="' . $rowItem->uom . '" name="item_uom_' . $rowItem->id . '"></td>
                            <td><input type="text" class="form-control" value="' . $rowItem->unit_price . '" name="item_unitprice_' . $rowItem->id . '" onchange="updatetotal(' . $rowItem->id . ')"></td>
                            <td><input type="text" class="form-control" value="' . $rowItem->discount . '" name="item_discount_' . $rowItem->id . '" onchange="updatetotal(' . $rowItem->id . ')"></td>
                            <td><input type="text" class="form-control" value="' . $rowItem->total . '" name="item_total_' . $rowItem->id . '"></td>
                            <td><input type="text" class="form-control" value="' . $rowItem->remarks . '" name="item_remarks_' . $rowItem->id . '"></td>
                            <td align="center"><i class="fas fa-trash-alt kt-font-danger" style="cursor:pointer;" title="Delete product" onclick="deleteproduct(' . $rowItem->id . ')"></i></td>
                        </tr>';
                @endphp

             @endforeach


             @endif

            </tbody>
        </table>
      </div>
 </div>

</form>


    <div class="mb-2 ml-1 d-flex"  >
        <a href="{{route('delivery.order')}}">
            <button type="submit" class="btn btn-primary mt-3 wow zoomIn">Back</button>
        </a>
        <button class="btn btn-warning mt-3 ml-1 wow zoomIn" id="reset">Reset</button>
        <button type="submit" class="btn btn-primary mt-3 ml-1 wow zoomIn" id="update" >Save</button>
        <button class="btn btn-outline-warning mt-3 ml-1" type="button" data-toggle="modal" data-target="#Modal" id="add" onclick="addproduct()" > Add Product </button>
        <button class="btn btn-outline-info mt-3 ml-1" type="button"   id="view" onclick="view()" > View DO Doc  </button>
    </div>





  {{-- Modal       --}}
  <div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title" id="exampleModalLongTitle">Product List</h5></div>
            <div class="modal-body">
                <form autocomplete="off">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Enter product name here..." id="product_keyword">
                        <div class="input-group-append"><button class="btn btn-success" type="button" onclick="addproduct()">Search</button></div>
                    </div>
                </form>
                {{-- <input type="hidden" class="form-control" id="doc_id">
                <input type="hidden" class="form-control" id="uuid"> --}}
                <div id="productlist_wrapper" class="mt-4"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary"  data-dismiss="modal" onclick="saveproduct()">Add product</button>
            </div>
        </div>
    </div>
</div>


{{-- Modal --}}

 <div class="container">
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

  </div>




<script>


@section('js')


    //  $(document).ready(function() {
    //       $('#select').select2();

    //  });

    //showing Modals
    function displaymodal() {
        $('#product_keyword').val('');
        $('#productlist_wrapper').html('');
        $('#Modal').modal('show');
        $('#error_message').hide();
    }


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


    function changecontact(val) {
        var arr = val.split("<->");
        console.log(arr);
        $('#contactperson').val(arr[0]);
        $('#contactno').val(arr[1]);
    }

 //store delivery order
    $('#update').on('click', function(){

        var value = $("#myform").serialize();
        var uuid = $('#uuid').val();

        var company_id = $('#company_id').val();
        var doc_dt = $('#doc_dt').val();
        var po_ref = $('#po_ref').val();
        var sales_pic = $('#sales_pic').val();
        var customer_pic = $('#customer_pic').val();
        var cust_id = $('#cust_id').val();
        var customer_name = $('#customer_name').val();
        var address = $('#address').val();
        var address2 = $('#address2').val();
        var postcode = $('#postcode').val();
        var city = $('#city').val();
        var state = $('#state').val();
        var contactperson = $('#contactperson').val();
        var contactno = $('#contactno').val();

        $.ajax({

            url: '/save-updated-DO/',
            type: 'post',
            dataType: 'json',
            data: {
                  "_token": $('#token').val(),
                    value:value,
                    uuid:uuid,
                    company_id:company_id,
                    doc_dt:doc_dt,
                    po_ref:po_ref,
                    sales_pic:sales_pic,
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
                if (response !== '') {
                swal({
                position: 'center-center',
                type: 'success',
                title: 'Data has been updated',
                showConfirmButton: false,
                timer: 1500, })

                }

            },
         });

    });

//add product

function addproduct() {
    var com_id = $('#company_id').val();
    var doc_id = $('#doc_id').val();
    var product_keyword = $('#product_keyword').val();


    $.ajax({
        url: "/get-product-list/",
        method: "POST",
        data: {
            "_token": $('#token').val(),
            "com_id": com_id,
            "doc_id": doc_id,
            "product_keyword": product_keyword
            },
        success: function (response) {
            $('#productlist_wrapper').html(response);
            $('#error_message').hide();
        },
    });
}



//Save Product

function saveproduct() {
    var doc_id = $('#doc_id').val();
    var company_id = $('#company_id').val();
    var items = [];
    $('.product_id').each(function () {
        if ($(this).is(":checked")) {
            var item_val = $(this).val();
            items.push(item_val);
        }
    });
    if (items.length == 0) {
        $('#error_message').show();
    } else {
        $.ajax({
            url: "/add-product-delivery-order/",
            method: "POST",
            data: {
                  "_token": $('#token').val(),
                  "doc_id": doc_id,
                  "items": items,
                  "company_id": company_id
                 },
            success: function (response) {
                if (response !== '') {
                    $('#Modal').hide();
                    $('#wrapper_product_list').append(response);
                    $('#wrapper_product_list .kt-selectpicker').selectpicker('refresh');
                    $('#wrapper_product_list .tooltip-stock').tooltip('update');
                }
            },
        });
    }
}


//Update Total

function updatetotal(item_id) {
    var quantity = parseInt($("input[name=item_quantity_" + item_id + "]").val());
    var unitprice = parseFloat($("input[name=item_unitprice_" + item_id + "]").val());
    var discount = parseFloat($("input[name=item_discount_" + item_id + "]").val());
    var total = quantity * unitprice - (discount / 100 * (unitprice * quantity));
    $("input[name=item_total_" + item_id + "]").val(total.toFixed(2));
}


//Update product
   function update(item_id){
        var uuid = $('#uuid').val();
        var itemid = item_id;
        var item_quantity = parseInt($("input[name=item_quantity_" + item_id + "]").val());
        var item_uom = $("input[name=item_uom_" + item_id + "]").val();
        var item_unit_price = parseFloat($("input[name=item_unitprice_" + item_id + "]").val());
        var item_discount = parseFloat($("input[name=item_discount_" + item_id + "]").val());
        var item_total = parseFloat($("input[name=item_total_" + item_id + "]").val());
        var item_remarks = $("input[name=item_remarks_" + item_id + "]").val();
        var item_onwer_type = $("[name=item_ownertype_" + item_id + "]").val();

        $.ajax({

           url: "/update-product-delivery-order/",
           type:"POST",
           data:{
                 "_token": $('#token').val(),
                 "uuid": uuid,
                 "itemid": itemid,
                 "item_quantity": item_quantity,
                 "item_uom": item_uom,
                 "item_unit_price": item_unit_price,
                 "item_discount": item_discount,
                 "item_total": item_total,
                 "item_remarks": item_remarks,
                 "item_onwer_type": item_onwer_type,
                },
           success: function(response){
             if(response !== ''){
                swal({
                    position: 'center-center',
                    type: 'success',
                    title: 'Product has been updated',
                    showConfirmButton: false,
                    timer: 1500, })
                }
            }

        });
    }


  //Delete Product

  function deleteproduct(id) {
        //alert(id);
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
                url: "/delete-product-delivery-order/",
                method: "POST",
                data: {
                    "_token": $('#token').val(),
                      "id": id
                    },
                success: function (response) {
                    if (response === 'success') {
                        swal({
                        position: 'center-center',
                        type: 'success',
                        title: 'Product has been deleted',
                        showConfirmButton: false,
                        timer: 1500, })
                            $('#rowItem_' + id).remove();

                    }
                },

            });

        }


  //view DO
function view(){
  var uuid = $('#uuid').val();
    $.ajax({
    url: '/download-delivery-order/'  +uuid,
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



