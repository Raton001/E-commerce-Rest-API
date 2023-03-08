@extends('layouts.app')


@section('title')
    {{ $title }}
@endsection

@section('headertitle')
 <h3> Update Sales Invoice</h3>
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

    <div class="row mt-5 mx-1" >
         <input type="hidden" class="form-control" id="uuid"  value="{{$invoice->uuid}}">

          <div class="col-4 col-sm-4 py-2 wow fadeInLeft" data-wow-delay="300ms" onchange="calcpaymentdue()" >
            <label> Invoice Date </label>
            <input type="date" name="doc_dt" id="invoice-doc_dt"  class="form-control" value="{{$invoice->doc_dt}}"  required>
          </div>

          <div class="col-4 col-sm-4 py-2 wow fadeInRight" data-wow-delay="300ms">
             <label> Invoice Term </label>
            <select class="form-select" aria-label="Default select example" name="invoice_term"  id="invoice-term"  onchange="calcpaymentdue()" required>
            <option value="{{$invoiceterm->code}}">{{$invoiceterm->code}}</option>
                @foreach ($daysparam as $term)
                 <option  value="{{$term->code}}"> {{$term->code}}</option>
                @endforeach
            </select>
          </div>

          <div class="col-4 col-sm-4 py-2 wow fadeInLeft" data-wow-delay="300ms">
            <label> Payment Due </label>
            <input type="date" name="doc_dt" id="payment_due" class="form-control"  value="{{$invoice->payment_due}}" required>
          </div>

          <div class="col-4 col-sm-4 py-2 wow fadeInRight" data-wow-delay="300ms" >
            <label> Sales Person </label>
           <select class="form-select" aria-label="Default select example" name="sales_pic" id="sales_pic"  required>
             <option value="" >{{$sales_person->name}}</option>
              @foreach ($sales_pic as $user )
                <option value="{{$user->sales_pic}}">{{$user->name}}</option>
              @endforeach
           </select>
       </div>


         <div class="col-4 col-sm-4 py-2 wow fadeInRight" data-wow-delay="300ms">
            <label> Customer </label>
           <select class="form-select" aria-label="Default select example" name="customer_id" id="cust_id"  required>
             <option value="{{$invoice->customer_id}}" >{{$invoice->customer_name}}</option>
             @foreach ($customers as $customer)
               <option value="{{$customer->customer_id}}">{{$customer->name}}</option>
             @endforeach

           </select>
         </div>

         <div class="col-4 col-sm-4 py-2  wow fadeInLeft" >
            <label> Customer Name </label>
            <input type="text" name="customer_name"  id="customer_name" class="form-control" value="{{$invoice->customer_name}}" required>
          </div>

          <div class="col-4 col-sm-4 py-2  wow fadeInLeft">
            <label> Address </label>
          <input type="text" name="address-1" id="address" class="form-control" value="{{$invoice->customer_address1}}" required>
        </div>

        <div class="col-4 col-sm-4 py-2  wow fadeInLeft">
            <label> Address 2 </label>
            <input type="text" name="address-2" id="address2" class="form-control" value="{{$invoice->customer_address2}}">
        </div>

        <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
            <label> Postcode </label>
            <input type="text" name="postcode"  id="postcode" class="form-control" value="{{$invoice->customer_postcode}}" required>
        </div>



        <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
            <label> City </label>
            <input type="text" name="city"  id="city" class="form-control" value="{{$invoice->customer_city}}" required>
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
            <input type="text" name="state" id="state" class="form-control" value="{{$invoice->customer_state}}" required>
        </div>

        <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
            <label> Contact Person</label>
           <div class="d-flex justify-content-between">
            <input type="text" name="contact-person"  id="contactperson" class="form-control" value="{{$invoice->customer_pic}}" required>
            <button class='btn btn-warning' type='button' data-toggle="modal" data-target="#myModal" id="test2">Select</button>
           </div>
        </div>

        <div class="col-4 py-2 wow fadeInUp" data-wow-delay="300ms">
            <label> Contact No </label>
            <input type="text" name="contact-no" id="contactno" class="form-control" value="{{$invoice->customer_phone}}" required>
        </div>

        <div class="col-4 col-sm-4 py-2  wow fadeInLeft">
            <label> Customer PO ref</label>
            <input type="text" name="po_ref" id="po_ref"  class="form-control" value="{{$invoice->po_ref}}">
        </div>

        <div class="mt-2 col-3  wow fadeInUp">
            <div class='form-group-custom'>
                <label class=" control-label">Invoice Payment Status</label>
                <?php
                $paid_display ='';
                if ($invoice->payment_status == 'Paid')
                {
                    $paid_display = '&nbsp;&nbsp;<span class="btn btn-label-success  btn-bold btn-block">Paid on '.date('d M Y',strtotime($invoice->paid_dt)).'</span>';
                }
                else
                {
                    $paid_display = '&nbsp;&nbsp;<span class="btn btn-warning  btn-bold btn-block">Pending</span>';
                }
                echo $paid_display;
                ?>
            </div>
        </div>

        <div class="col-12">
            <label> Remark</label>
            <div class='form-group-custom'>
              <textarea id="txtid" name="remark" rows="4" cols="160" maxlength="200"> </textarea>
            </div>
        </div>

         <table class="table table-striped table-bordered">
            <thead>
                <tr class="thead-dark">
                    <th width="1%">No</th>
                    <th >Product</th>
                    <th width="10%">Quantity</th>
                    <th width="10%">UoM</th>
                    <th width="10%">Unit Price (MYR)</th>
                    <th width="10%">Discount (%)</th>
                    <th width="10%">Total (MYR)</th>
                </tr>
            </thead>
            <tbody>
            @if (!empty($itmes))
                @php
                 $count =1;
                @endphp
               @foreach ( $itmes as $rowItem)
                @php
                $products = DB::table('product')->where('id', $rowItem->product_id)->get();
                @endphp
                <tr>

                    <td align="center" style="vertical-align:middle;">{{$count++}}<input type="hidden" class="item" name="item_id[]" value="{{$rowItem->id}}"></td>

                    @foreach ($products as $product)
                    <td style="vertical-align:middle;">{{$product->name}}</td>
                    @endforeach
                    @php
                   echo
                      ' <td><input type="text" class="form-control" value="' . $rowItem->quantity . '"  id="quantity" name="item_quantity_' . $rowItem->id . '" onchange="updatetotal(' . $rowItem->id . ')"></td>
                        <td><input type="text" class="form-control" value="' . $rowItem->uom . '" name="item_uom_' . $rowItem->id . '"></td>
                        <td><input type="text" class="form-control" value="' . $rowItem->unit_price . '" name="item_unitprice_' . $rowItem->id . '" onchange="updatetotal(' . $rowItem->id . ')"></td>
                        <td><input type="text" class="form-control" value="' . $rowItem->discount . '" name="item_discount_' . $rowItem->id . '" onchange="updatetotal(' . $rowItem->id . ')"></td>
                        <td><input type="text" class="form-control item_total" value="' . $rowItem->total . '" name="item_total_' . $rowItem->id . '"></td>';
                    @endphp
                </tr>

               @endforeach
             @else
               <tr><td colspan="7">No record found</td></tr>
            @endif
        </tbody>
       <tfoot>
        <tr style="height:60px;">
            <th colspan="6" class="text-right" style="vertical-align: middle;">Sub Total</th>
            <td style="vertical-align: middle;"  ><span id="subtotal" ></span></td>
        </tr>
        <tr>
            <th colspan="5" class="text-right" style="vertical-align: middle;">
                Tax
                <span data-toggle="kt-tooltip" data-placement="top" data-original-title="Fill the TAX input if needed only">
                    <i class="fa fa-info-circle"></i>
                </span>
            </th>
            <td class="pb-0">
                <div class="form-group-custom" onchange="updategrandtotal()"  >
                    <div class="input-group-append"><span class="input-group-text"> <input style="width:80%" type="text" id="invoice-tax" > %</span></div>
                    {{-- <div class='input-group-append'><span class='input-group-text'>%</span></div> --}}
                </div>

            </td>
            <td style="vertical-align: middle;"><span  id="taxvalue"></span>{{$invoice->tax}}</td>
        </tr>
        <tr style="height:60px;">
            <th colspan="6" class="text-right" style="vertical-align: middle;">Grand Total</th>
            <td class="pb-0">

                <div class='form-group-custom' id="invoice-grandtotal">
                  {{$invoice->grandtotal}}
               </div>
            </td>
           {{-- <td style="vertical-align: middle;"><span id="grandtotal"></span></td> --}}
        </tr>
    </tfoot>
 </table>
     </div>
</form>

        <div class="mb-2" >
            <a href="{{route('delivery.order')}}">
                <button type="submit" class="btn btn-primary mt-3 wow zoomIn">Back To DO</button>
            </a>
            <a href="{{route('sales.invoice')}}">
                <button type="submit" class="btn btn-primary mt-3 wow zoomIn">Back To Sales Invoice</button>
            </a>
            <a href="{{route('sales.invoice.overview')}}">
                <button type="submit" class="btn btn-primary mt-3 wow zoomIn">Back To Invoice OV</button>
            </a>
            <button class="btn btn-primary mt-3 wow zoomIn">Reset</button>

                <button class="btn btn-primary mt-3 wow zoomIn"  id="store">Save</button>

            <button class="btn btn-info mt-3 wow zoomIn" onclick="view()">View Invoice</button>

        </div>



  {{-- Modal       --}}

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


 //save Updated delivery order
 $('#store').on('click', function(){

   var value = $("#myform").serialize();
   //alert(value);

        var uuid = $('#uuid').val();
        var invoice_doc_dt = $('#invoice-doc_dt').val();
        var invoice_term = $('#invoice-term').val();
        var invoice_due = $('#payment_due').val();
        var sales_pic = $('#sales_pic').val();
        // var customer_pic = $('#customer_pic').val();
        var cust_id = $('#cust_id').val();
        var customer_name = $('#customer_name').val();
        var address = $('#address').val();
        var address2 = $('#address2').val();
        var postcode = $('#postcode').val();
        var city = $('#city').val();
        var state = $('#state').val();
        var contactperson = $('#contactperson').val();
        var contactno = $('#contactno').val();
        var check = $('#quantity').val();
        var tax = $('#invoice-tax').val();
        var grandtotal = $('#invoice-grandtotal').html();
        var items =[];
        var item_quantity = 0;
        var item_uom = 0;
        var item_unitprice = 0;
        var item_discount = 0;
        var item_total = 0 ;

    $( ".item" ).each(function() {
        //items =items + parseInt($(this).val());

        var item_val = $(this).val();
            items.push(item_val);

        $.each(items, function(k, v) {
         item_quantity = parseInt($("input[name=item_quantity_" + v + "]").val());
         item_uom = $("input[name=item_uom_" + v + "]").val();
         item_unitprice = parseFloat($("input[name=item_unitprice_" + v + "]").val());
         item_discount = parseFloat($("input[name=item_discount_" + v + "]").val());
         item_total = parseFloat($("input[name=item_total_" + v + "]").val());
        });

    });


     if (items.length == 0) {
        $('#error_message').show();
     } else {

        $.ajax({
            url: "/save-updated-invoice/",
            method: "POST",
            data: {
                   "_token": $('#token').val(),
                    "value":value,
                    "uuid": uuid,
                    "invoice_doc_dt":invoice_doc_dt,
                    "invoice_term":invoice_term,
                    "invoice_due":invoice_due,
                    "sales_pic":sales_pic,
                    "cust_id":cust_id,
                    "customer_name":customer_name,
                    "address":address,
                    "address2":address2,
                    "postcode":postcode,
                    "city":city,
                    "state":state,
                    "contactperson":contactperson,
                    "contactno":contactno,
                    "items": items,
                    "item_quantity": item_quantity,
                    "item_uom":item_uom,
                    "item_unitprice":item_unitprice,
                    "item_discount":item_discount,
                    "item_total":item_total,
                    "tax":tax,
                    "grandtotal":grandtotal,
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

    }

  });


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



  //update total

  function updatetotal(item_id) {
        var quantity = parseInt($("input[name=item_quantity_" + item_id + "]").val());
        var unitprice = parseFloat($("input[name=item_unitprice_" + item_id + "]").val());
        var discount = parseFloat($("input[name=item_discount_" + item_id + "]").val());
        var total = quantity * unitprice - (discount / 100 * (unitprice * quantity));
        $("input[name=item_total_" + item_id + "]").val(total.toFixed(2));
        updategrandtotal();
    }


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



//view invoice
function view()
{
    var uuid = $('#uuid').val();
   $.ajax({
        url: '/invoice-download/'  +uuid,
        type: 'get',
         success: function(response){

            var newWindow = window.open();
            newWindow.document.write(response);

         }

    });

}


@endsection

</script>


@endsection



