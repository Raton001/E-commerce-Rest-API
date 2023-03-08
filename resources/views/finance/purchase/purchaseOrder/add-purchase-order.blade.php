@extends('layouts.app')


@section('title')
    {{ $title }}
@endsection

@section('headertitle')
 <h3> Add Purchase Order</h3>
@endsection

@section('content')

<form id="myform">

    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

     <p id="myElem" style="display: none" class="alert alert-secondary" role="alert" >Quotation has been Saved</p>

      <div class="row mt-5 mx-1" >
          <div class="col-4 col-sm-4 py-2 wow fadeInRight" data-wow-delay="300ms">
               <label> Company </label>
              <select class="form-select" aria-label="Default select example" name="company_id" id="company_id" required >
              <option value="">-Please choose-</option>
                  @foreach ($companies as $company)
                   <option value="{{$company->company_id}}">{{$company->name}}</option>
                  @endforeach
              </select>
            </div>

            <div class="col-4 col-sm-4 py-2 wow fadeInLeft" data-wow-delay="300ms">
              <label> Purchase  Date </label>
              <input type="date" name="doc_dt" id="doc_dt" class="form-control" value="{{ date("Y-m-d") }}" required>
            </div>


           <div class="col-4 col-sm-4 py-2 wow fadeInRight" data-wow-delay="300ms">
              <label> Vendor </label>
             <select class="form-select" aria-label="Default select example" name="customer_id" id="cust_id"  required>
               <option value="" >-Please choose-</option>
               @foreach ($vendors as $vendor)
                 <option value="{{$vendor->id}}">{{$vendor->name}}</option>
               @endforeach
             </select>
           </div>

           <div class="col-4 col-sm-4 py-2  wow fadeInLeft" >
              <label> Vendor Name </label>
              <input type="text" name="customer_name" value="" id="vendor_name" class="form-control" required>
            </div>

            <div class="col-4 col-sm-4 py-2  wow fadeInLeft">
              <label> Vendor Address </label>
            <input type="text" name="address-1" id="address" class="form-control" required>
          </div>

          <div class="col-4 col-sm-4 py-2  wow fadeInLeft">
              <label> Address 2 </label>
              <input type="text" name="address-2" id="address2" class="form-control">
          </div>

          <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
              <label> Postcode </label>
              <input type="text" name="postcode"  id="postcode" class="form-control" required>
          </div>



          <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
              <label> City </label>
              <input type="text" name="city"  id="city" class="form-control" required>
          </div>


          <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
              <label> State</label>
              <input type="text" name="state" id="state" class="form-control" required>
          </div>

          <div class="col-4 col-sm-4 py-2 wow fadeInRight" data-wow-delay="300ms">
              <label> Country </label>
             <select class="form-select" aria-label="Default select example"   name="country" id="country" required>
              @foreach ($countries as $country)
               <option value="{{$country->code}}">{{$country->label}}</option>
               @endforeach

             </select>
           </div>

          <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
              <label> Contact Person</label>
             <div class="d-flex justify-content-between">
              <input type="text" name="contact-person" placeholder="Click Select to choose from Contact Person" id="contactperson" class="form-control"  required >
              <button class='btn btn-warning' type='button' data-toggle="modal" data-target="#myModal" id="test2">Select</button>
             </div>
          </div>

          <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
              <label> Contact No </label>
              <input type="text" name="contact-no" id="contactno" class="form-control" required>
          </div>

          <div class="col-10  py-2">
            <label> Notes / Instructions </label>
            <div class='form-group-custom'>
              <textarea id="txtid" name="notes" rows="4" cols="130" maxlength="200"> </textarea>
            </div>
        </div>

        <input type="hidden" class="form-control" id="purchase_id">
        <input type="hidden" class="form-control" id="uuid">

        <table class="table table-borderer table-striped" id="wrapper_product_list">
            <thead>
                <tr class="thead-dark">
                    <th>Description</th>
                    <th width="12%">Qty</th>
                    <th width="12%">UoM</th>
                    <th width="12%">Unit Price (RM)</th>
                    <th width="12%">Discount (%)</th>
                    <th width="12%">Total (RM)</th>
                    <th width="1%">Action</th>
                    <th width="1%"></th>
                </tr>
            </thead>
            <tbody> </tbody>
        </table>


          {{-- product table coming from controller ajax call --}}
          {{-- <div id="wrapper_product_list"></div>
          </div> --}}
  </form>

</div>
      <div class="mb-2 ml-1 d-flex"  >
        <a href="{{route('purchase.order')}}">
            <button type="submit" class="btn btn-primary mt-3 wow zoomIn">Back</button>
      </a>
        <button class="btn btn-warning mt-3 ml-1 wow zoomIn" id="reset">Reset</button>

        <button type="submit" class="btn btn-primary mt-3 ml-1 wow zoomIn" id="store" >Save</button>

        {{-- <div class="ml-1" id="buttonsaveall" >
        </div> --}}

        <div class="ml-1" id="buttonadd" >
        </div>

        <div class="ml-1" id="buttonview">
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



<script>


 @section('js')


//get details

$('#cust_id').change(function() {

var id = $(this).val();
var url = '{{ route("getVendorDetails", ":id") }}';
url = url.replace(':id', id);

$.ajax({
    url: url,
    type: 'get',
    dataType: 'json',
    success: function(response) {
    if (response != null) {
        $('#vendor_name').val(response.name);
        $('#address').val(response.address1);
        $('#address2').val(response.address2);
        $('#postcode').val(response.postcode);
        $('#city').val(response.city);
        $('#state').val(response.state);
        $('#country').val(response.country);
        $('#contactperson').val(response.contact_name);
        $('#contactno').val(response.contact_no);

    }
}
});
});


//changing the contact person

$('#test2').on('click', function() {
    var id = $('#cust_id').val();
    var type = 'ctc_vendor';
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



//save PO
$('#store').on('click', function(){
    var company_id = $('#company_id').val();
    var doc_dt = $('#doc_dt').val();
    var cust_id = $('#cust_id').val();
    var vendor_name = $('#vendor_name').val();
    var address = $('#address').val();
    var address2 = $('#address2').val();
    var postcode = $('#postcode').val();
    var city = $('#city').val();
    var state = $('#state').val();
    var country = $('#country').val();
    var contactperson = $('#contactperson').val();
    var contactno = $('#contactno').val();
    var notes = $('#txtid').val();

    $.ajax({
            url: '/create-purchase-order/',
            type: 'post',
            dataType: 'json',
            data: {
                "_token": $('#token').val(),
                    company_id:company_id,
                    doc_dt:doc_dt,
                    cust_id:cust_id,
                    vendor_name:vendor_name,
                    address:address,
                    address2:address2,
                    postcode:postcode,
                    city:city,
                    state:state,
                    country:country,
                    contactperson:contactperson,
                    contactno:contactno,
                    notes:notes,

                },
         success: function (response) {

             console.log(response);

            $('#company_id').prop('disabled', true);
            $('#store').prop('disabled', true);
            // $('#store').hide();


            $("#myElem").show();
            setTimeout(function() { $("#myElem").hide(); }, 3000);
           // $('#table').html(response[1]);
            $('#buttonadd').html(response[1]);
            $('#buttonview').html(response[2]);
            // $('#buttonsaveall').html(response[3]);
            $('#purchase_id').val(response[0].id);
            $('#uuid').val(response[0].uuid);


    },
    });

});


//add product

function addproduct() {
    var purchase_id = $('#purchase_id').val();
    $.ajax({
        url: "/addproduct-purchase-order/",
        method: "POST",
        data: {
                "_token": $('#token').val(),
                "purchase_id": purchase_id
             },
        success: function (response) {
            console.log(response);
            $('#wrapper_product_list').append(response);
        },
    });
}


//update total
function updatetotal(item_id) {
    var quantity = parseInt($("input[name=item_quantity_" + item_id + "]").val());
    var unitprice = parseFloat($("input[name=item_unitprice_" + item_id + "]").val());
    var discount = parseFloat($("input[name=item_discount_" + item_id + "]").val());
    var total = quantity * unitprice - (discount / 100 * (unitprice * quantity));
    $("input[name=item_totalprice_" + item_id + "]").val(total.toFixed(2));
}


//update product
function update(item_id){

        var uuid = $('#uuid').val();
        var itemid = item_id;
        var item_productname = $("input[name=item_productname_" + item_id + "]").val();
        var item_quantity = $("input[name=item_quantity_" + item_id + "]").val();
        var item_uom = $("input[name=item_uom_" + item_id + "]").val();
        var item_unitprice = $("input[name=item_unitprice_" + item_id + "]").val();
        var item_discount = $("input[name=item_discount_" + item_id + "]").val();
        var item_totalprice = $("input[name=item_totalprice_" + item_id + "]").val();

        $.ajax({

            url: "/update-product-PO/",
            type: "POST",
            data:{
                "_token": $('#token').val(),
                "uuid": uuid,
                "itemid": itemid,
                "item_productname":item_productname,
                "item_quantity": item_quantity,
                "item_uom":item_uom,
                "item_unitprice":item_unitprice,
                "item_discount":item_discount,
                "item_totalprice": item_totalprice,
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




//delete product
function deleteproduct(id) {
    $.ajax({
        url: "/delete-product-PO/",
        method: "POST",
        data: {
            "_token": $('#token').val(),
            "id": id
             },
        success: function (response) {
            if (response === 'success')
                $('#row_' + id).remove();
        },
    });
 }



 //view Quotation
function view(){
    var uuid = $('#uuid').val();

    $.ajax({
        url: '/download-PO/'  +uuid,
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
