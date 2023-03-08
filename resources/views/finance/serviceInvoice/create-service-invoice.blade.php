@extends('layouts.app')


@section('title')
    {{ $title }}
@endsection

@section('headertitle')
 <h3> Service Invoice</h3>
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

          <div class="col-4 col-sm-4 py-2 wow fadeInLeft" data-wow-delay="300ms" onchange="calcpaymentdue()" >
            <label> Invoice Date </label>
            <input type="date" name="doc_dt" id="invoice-doc_dt"  class="form-control" value="{{ date("Y-m-d") }}"  required>
          </div>

          <div class="col-4 col-sm-4 py-2 wow fadeInRight" data-wow-delay="300ms">
            <label> Invoice Term </label>
          <select class="form-select" aria-label="Default select example" name="invoice_term"  id="invoice-term"  onchange="calcpaymentdue()" required>
           <option value="{{$invoiceterm->code}}" >{{$invoiceterm->code}}</option>
               @foreach ($daysparam as $term)
                <option  value="{{$term->code}}"> {{$term->code}}</option>
               @endforeach
           </select>
         </div>

         <div class="col-4 col-sm-4 py-2 wow fadeInLeft" data-wow-delay="300ms">
            <label> Payment Due </label>
            <input type="date" name="doc_dt" id="payment_due" class="form-control"   value="{{ date("Y-m-d") }}" required>
          </div>


        <div class="col-4 col-sm-4 py-2 wow fadeInRight" data-wow-delay="300ms" >
            <label> Sales Person </label>
           <select class="form-select" aria-label="Default select example" name="sales_pic" id="sales_pic"  required>
             <option value="" >-Please choose-</option>
              @foreach ($sales_pic as $user )
                <option value="{{$user->sales_pic}}">{{$user->name}}</option>
              @endforeach
           </select>

       </div>

         <div class="col-4 col-sm-4 py-2 wow fadeInRight" data-wow-delay="300ms">
            <label> Customer </label>
           <select class="form-select" aria-label="Default select example" name="customer_id" id="cust_id"  required>
             <option value="" >-Please choose-</option>
             @foreach ($customers as $customer)
               <option value="{{$customer->customer_id}}">{{$customer->name}}</option>
             @endforeach

           </select>
         </div>

         <div class="col-4 col-sm-4 py-2  wow fadeInLeft" >
            <label> Customer Name </label>
            <input type="text" name="customer_name" value="" id="customer_name" class="form-control" required>
          </div>

          <div class="col-4 col-sm-4 py-2  wow fadeInLeft">
            <label> Address </label>
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
            <input type="text" name="state" id="state" class="form-control" required>
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
            <label> Remarks </label>
            <div class='form-group-custom'>
              <textarea id="txtid" name="notes" rows="4" cols="130" maxlength="200"> </textarea>
            </div>
        </div>

        {{-- product table coming from controller --}}
        <div id="table">
        </div>

        {{-- <div id="footer">
        </div> --}}

        </div>
</form>



    <div class="mb-2 ml-1 d-flex"  >
            <a href="{{route('cash.sales.invoice')}}">
                <button type="submit" class="btn btn-primary mt-3 wow zoomIn">Back</button>
        </a>
            <button class="btn btn-warning mt-3 ml-1 wow zoomIn" id="reset">Reset</button>

            <button type="submit" class="btn btn-primary mt-3 ml-1 wow zoomIn" id="store" >Save</button>

            <div class="ml-1" id="save">
            </div>


            <div class="ml-1" id="buttonadd" >
            </div>

            <div class="ml-1" id="buttonview" >
            </div>
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
                <input type="hidden" class="form-control" id="doc_id">
                <input type="hidden" class="form-control" id="uuid">
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
    $('#store').on('click', function(){

        var doc_dt = $('#invoice-doc_dt').val();
        var term = $('#invoice-term :selected').text();
        var payment_due = $('#payment_due').val();
        var company_id = $('#company_id').val();
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
        var remark = $('#txtid').val();

        $.ajax({

            url: '#',
            type: 'post',
            dataType: 'json',
            data: {
                  "_token": $('#token').val(),
                    doc_dt:doc_dt,
                    term:term,
                    payment_due:payment_due,
                    company_id:company_id,
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
                    remark:remark,

                  },
            success: function (response) {

                console.log(response);

                $('#company_id').prop('disabled', true);
                $('#store').hide();
                $('#save').show();


                $("#myElem").show();
                setTimeout(function() { $("#myElem").hide(); }, 3000);
                 $('#table').html(response[1]);
                //  $('#footer').html(response[2]);
                 $('#save').html(response[2]);
                 $('#buttonadd').html(response[3]);
                 $('#buttonview').html(response[4]);
                 $('#doc_id').val(response[0].id);
                 $('#uuid').val(response[0].uuid);


            },
         });

    });

//add product
function addproduct() {
    var com_id = $('#company_id').val();
    var doc_id = $('#doc_id').val();

     console.log(com_id);
     console.log(doc_id);

    $.ajax({
        url: "/product-list-cash-sales/",
        method: "POST",
        data: {
            "_token": $('#token').val(),
            "com_id": com_id,
            "doc_id": doc_id,
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
            url: "/add-product-cash-sale/",
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



 //update total
 function updatetotal(item_id) {
        var quantity = parseInt($("input[name=item_quantity_" + item_id + "]").val());
        var unitprice = parseFloat($("input[name=item_unitprice_" + item_id + "]").val());
        var discount = parseFloat($("input[name=item_discount_" + item_id + "]").val());
        var total = quantity * unitprice - (discount / 100 * (unitprice * quantity));
        $("input[name=item_total_" + item_id + "]").val(total.toFixed(2));
        updategrandtotal();
    }


//upgrade total
function updategrandtotal() {
        var subtotal = 0;
        $('.item_total').each(function () {
            subtotal += parseFloat($(this).val());
        });

        var subtotal_label = subtotal.toFixed(2);
        //subtotal_label = subtotal_label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        var tax = $('#invoice-tax').val();
        var tax_value = tax / 100 * subtotal;
        var tax_label = tax_value.toFixed(2);
        //tax_label = tax_label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        var grandtotal = tax_value + subtotal;
        var grandtotal_label = grandtotal.toFixed(2);
        //grandtotal_label = grandtotal_label.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");

        $('#subtotal').html(subtotal_label);
        $('#taxvalue').html(tax_label);
        //$('#grandtotal').html(grandtotal_label);
        $('#invoice-grandtotal').html(grandtotal_label);
    }


 //calculate payment due
    function calcpaymentdue()
     {
       // alert("ok");
        var invoice_dt = $('#invoice-doc_dt').val();
        var invoice_term = $('#invoice-term :selected').text();

        console.log(invoice_dt);
        console.log(invoice_term);

        $.ajax({
            url: '/calpaymentdue/',
            method: 'post',
            dataType: 'json',
            data: {
                "_token": $('#token').val(),
                invoice_dt: invoice_dt,
                invoice_term: invoice_term,
            },
            success: function (response) {
                if (response != null) {
                console.log(response);
                $('#payment_due').val(response);
                $("#payment_due").datepicker("destroy");
                $('#payment_due').datepicker({
                    todayHighlight: true,
                    orientation: 'bottom left',
                    format: 'yyyy-mm-dd',
                    clearBtn: true,
                });
                $('#payment_due').datepicker("refresh");
                }
            },
        });
     }


  //save all
  function saveall(){

    var value = $("#myform").serialize();

    var doc_dt = $('#invoice-doc_dt').val();
    var uuid = $('#uuid').val();
    var term = $('#invoice-term :selected').text();
    var payment_due = $('#payment_due').val();
    var company_id = $('#company_id').val();
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
    var remark = $('#txtid').val();
    var tax = $('#invoice-tax').val();
    var grandtotal = $('#invoice-grandtotal').html();

    $.ajax({

        url: '/save-all/',
        type: 'post',
        dataType: 'json',
        data: {

            "_token": $('#token').val(),
            uuid:uuid,
            value:value,
            doc_dt:doc_dt,
            term:term,
            payment_due:payment_due,
            company_id:company_id,
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
            remark:remark,
            tax:tax,
            grandtotal:grandtotal,

      },
     success: function (response) {

         console.log(response);

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


}

//view or download invoice
function view()
{
    var uuid = $('#uuid').val();
     $.ajax({
        url: '/cash-invoice-download/'  +uuid,
        type: 'get',
         success: function(response){

            var newWindow = window.open();
            newWindow.document.write(response);

         }

    });
}


  //Delete Product
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
                url: "/delete-product-cash-sale/",
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




 //reset
$('#reset').on('click', function(){
$('#myform').trigger("reset");
});

@endsection

</script>


@endsection



