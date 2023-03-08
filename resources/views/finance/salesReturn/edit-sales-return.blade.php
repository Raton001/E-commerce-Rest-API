@extends('layouts.app')

@section('title')
    {{ $title }}
@endsection

@section('headertitle')
 <h3> Update Sales Return</h3>
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


<p id="myElem" style="display: none;" class="alert alert-secondary" role="alert">Sales Return has been Saved</p>

<form id="myform">
  <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">


  <div class="row mt-5 mx-1" >
        <div class="col-4 col-sm-3 py-2 wow fadeInRight" data-wow-delay="300ms" >
            <label>  Do Number </label>
                <select class="form-select" aria-label="Default select example" name="doc_no" id="id" disabled required>
                    <option value="{{$do_no->id}}" >{{$do_no->doc_no}}</option>
                    @foreach ($do_arr as $do)
                        <option value="{{$do->id}}">{{$do->doc_no}}</option>
                    @endforeach
                </select>
                <span class='form-text text-muted font-weight-light'>Only DO with status 'Consigned' will display on the list</span>
         </div>

         <div class="col-4 col-sm-3 py-2  wow fadeInLeft" >
            <label> Customer</label>
            <input type="text" id="grnparent-customer-label" class="form-control" value="{{$do_no->customer_name}}">
            <input type="hidden" id="grnparent-customer_id" name="GrnParent[customer_id]">
          </div>

         <div class="col-4 col-sm-3 py-2  wow fadeInLeft" >
            <label> Company</label>
            <input type="text" id="grnparent-company-label" class="form-control" value="{{$modelcom}}">
            <input type="hidden" id="grnparent-company_id" name="GrnParent[company_id]">
          </div>

          <div class="col-4 col-sm-3 py-2 wow fadeInLeft" data-wow-delay="300ms">
            <label> Received Date </label>
            <input type="date" name="date_rcv" id="date_rcv" class="form-control" value="{{ $model->received_dt }}" required>
          </div>

          {{-- product table coming from response
          <div id="table">
        </div> --}}
        <div>
            <table class="table table-bordered" id="wrapper_product_list">
                <thead>
                    <tr class="thead-dark">
                        <th>Product</th>
                        <th>Status Inspection</th>
                        <th width="10%">Balance Qty</th>
                        <th width="10%">Qty (Return)</th>
                        <th>Remarks</th>
                        <th width="1%">Delete</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                         $modelItem = App\Grn::where(['parent_id' => $model->id])->orderBy('id', 'ASC')->get();
                    @endphp

                     @if(!empty($modelItem))
                        @foreach ($modelItem as $rowItem)
                         @php
                            $deletebtn = '';
                            $inputdisabled = 'readonly';
                            $formreadonly = 'form-readonly';
                            if ($rowItem->status_inspection == 'pending') {
                                $inputdisabled = '';
                                $formreadonly = '';
                                $deletebtn = '<i class="fas fa-trash-alt kt-font-danger" style="cursor:pointer;" title="Delete product" onclick="deleteproduct(' . $rowItem->id . ')"></i>';
                            }
                            echo '<tr id="rowItem_' . $rowItem->id . '">
                                    <td style="vertical-align:middle;">
                                        <input type="hidden" value="' . $rowItem->id . '" name="item_id[]">
                                        ' . App\Product::getname($rowItem->product_id) . '
                                    </td>
                                    <td style="vertical-align:middle;">' . ucwords($rowItem->status_inspection) . '</td>
                                    <td style="vertical-align:middle;">' . App\DeliveryOrderItem::getqtysend($rowItem->do_id, $rowItem->product_id) . '</td>
                                    <td><input ' . $inputdisabled . ' type="text" class="form-control ' . $formreadonly . '" value="' . $rowItem->quantity . '" name="item_quantity_' . $rowItem->id . '" onchange="updatetotal(' . $rowItem->id . ')"></td>
                                    <td><input type="text" class="form-control" value="' . $rowItem->remarks . '" name="item_remarks_' . $rowItem->id . '"></td>
                                    <td align="center">' . $deletebtn . '</td>
                                </tr>';

                         @endphp

                        @endforeach

                    @endif


                </tbody>
            </table>
        </div>



  </div>
</form>

    <div class="mb-2 d-flex">
        <a href="{{route('sales.return')}}">
            <button  class="btn btn-primary mt-3 wow zoomIn">Back</button>
        </a>
    <button class="btn btn-warning mt-3 ml-1 wow zoomIn" id="reset">Reset</button>

    <button  class="btn btn-primary mt-3 ml-1 wow zoomIn" id="update" >Submit</button>
    <button class="btn btn-outline-warning mt-3 mt-3 ml-1 " type="button" data-toggle="modal" data-target="#myModal"  id="add" onclick="addproduct()" > Add Product </button>
    {{-- Add product button coming from response--}}
    <div class="ml-1" id="button" >
    </div>

</div>

{{-- Modal for Product List --}}

<div class="container">
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header"><h5 class="modal-title" id="exampleModalLongTitle">Product List</h5></div>
                <div class="modal-body">
                    <input type="hidden" class="form-control" id="parent_id" value="{{$model->id}}">
                    <input type="hidden" class="form-control" id="uuid" value="{{$model->uuid}}">
                    <div id="productlist"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-primary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" onclick="saveproduct()">Add product</button>
                </div>
            </div>
        </div>
    </div>
</div>






<script>

@section('js')



//get details about customer and company

 $('#id').change(function() {

        var id = $('#id').val();

        var url = '{{ route("get.do.details", ":id") }}';
        url = url.replace(':id', id);

        $.ajax({
            url: url,
            type: 'get',
            dataType: 'json',
        success: function(response) {
        if (response != null) {
            var arr = response.split("<->");
                $('#grnparent-company_id').val(arr[0]);
                $('#grnparent-company-label').val(arr[1]);
                $('#grnparent-customer_id').val(arr[2]);
                $('#grnparent-customer-label').val(arr[3]);
        }
    }
 });

});


 // create GrnParent data
  $('#update').on('click', function(){

    var value = $("#myform").serialize();
    var uuid = $('#uuid').val();
    var datercv = $('#date_rcv').val();

   $.ajax({
          url:'/save-updated-sales-return/',
          type:'post',
          dataType:'json',
          data:{
             "_token": $('#token').val(),
             value:value,
             uuid:uuid,
             datercv:datercv,
          },
         success: function(response){
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


// get productlist
function addproduct(){
    $('#productlist_wrapper').html('');
    var do_id = $('#id').val();
    var parent_id = $('#parent_id').val();

    $.ajax({
        url: '/get-product-list/'  +do_id+'/'+parent_id,
        type: 'get',
         success: function(response){
            //  console.log(response);
               $('#productlist').html(response);
                $('#error_message').hide();
                 $('#myModal').show();

         }

    });

};


//Save prodect
function saveproduct() {
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
            var do_id = $('#id').val();
            var cust_id = $('#grnparent-customer_id').val();
            var com_id = $('#grnparent-company_id').val();
            var received_dt = $('#date_rcv').val();
            var parent_id = $('#parent_id').val();
            // alert(received_dt);
            $.ajax({
                url: "/add-product/",
                method: "POST",
                data: {
                    "_token": $('#token').val(),
                     "items": items,
                     "do_id": do_id,
                     "cust_id": cust_id,
                     "com_id": com_id,
                     "received_dt": received_dt,
                     "parent_id": parent_id
                    },
                success: function (response) {
                    if (response !== '') {
                        $('#myModal').hide();
                        $('#wrapper_product_list').append(response);
                    }
                },
            });
        }
    }


    //update Product

    function update(item_id){
        var uuid = $('#uuid').val();
        var itemid = item_id;
        var item_remarks = $("input[name=item_remarks_" + item_id + "]").val();
        var item_quantity = parseInt($("input[name=item_quantity_" + item_id + "]").val());

        //  console.log(item_quantity);
        $.ajax({

            url: "/update-product-sales-return/",
            type:"POST",
            data:{
                "_token": $('#token').val(),
                "uuid": uuid,
                "itemid": itemid,
                "item_quantity": item_quantity,
                "item_remarks": item_remarks,
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
                url: "/delete-product/",
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



