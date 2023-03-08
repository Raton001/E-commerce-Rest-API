@extends('layouts.app')

@section('title')
    {{ $title }}
@endsection

@section('headertitle')
 <h3> Add Sales Return</h3>
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
                <select class="form-select" aria-label="Default select example" name="doc_no" id="id"  required>
                    <option value="" >-Please choose-</option>
                    @foreach ($do_arr as $do )
                        <option value="{{$do->id}}">{{$do->doc_no}}</option>
                    @endforeach
                </select>
                <span class='form-text text-muted font-weight-light'>Only DO with status 'Consigned' will display on the list</span>
         </div>

         <div class="col-4 col-sm-3 py-2  wow fadeInLeft" >
            <label> Customer</label>
            <input type="text" id="grnparent-customer-label" class="form-control">
            <input type="hidden" id="grnparent-customer_id" name="GrnParent[customer_id]">
          </div>

         <div class="col-4 col-sm-3 py-2  wow fadeInLeft" >
            <label> Company</label>
            <input type="text" id="grnparent-company-label" class="form-control">
            <input type="hidden" id="grnparent-company_id" name="GrnParent[company_id]">
          </div>

          <div class="col-4 col-sm-3 py-2 wow fadeInLeft" data-wow-delay="300ms">
            <label> Received Date </label>
            <input type="date" name="date_rcv" id="date_rcv" class="form-control" value="{{ date("Y-m-d") }}" required>
          </div>

          {{-- product table coming from response --}}
          <div id="table">
        </div>

  </div>
</form>

    <div class="mb-2 d-flex">
        <a href="{{route('sales.return')}}">
            <button  class="btn btn-primary mt-3 wow zoomIn">Back</button>
        </a>
    <button class="btn btn-warning mt-3 ml-1 wow zoomIn" id="reset">Reset</button>

    <button  class="btn btn-primary mt-3 ml-1 wow zoomIn" id="create" >Submit</button>

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
                    <input type="hidden" class="form-control" id="parent_id">
                    <input type="hidden" class="form-control" id="uuid">
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
  $('#create').on('click', function(){
    var id = $('#id').val();
    var customer_id = $('#grnparent-customer_id').val();
    var company_id = $('#grnparent-company_id').val();
    var datercv = $('#date_rcv').val();

   $.ajax({
          url:'/create-sales-return/',
          type:'post',
          dataType:'json',
          data:{
             "_token": $('#token').val(),
             id:id,
             customer_id:customer_id,
             company_id:company_id,
             datercv:datercv,
          },
         success: function(response){
            if (response != null) {
                //console.log(response);
                $("#myElem").show();
                setTimeout(function() { $("#myElem").hide(); }, 5000);
                $('#id').prop('disabled', true);
                $('#create').prop('disabled', true);
                $('#table').html(response[1]);
                $('#button').html(response[2]);
                $('#parent_id').val(response[0].id);
                $('#uuid').val(response[0].uuid);

                //console.log(response.id);

            }

         }

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



