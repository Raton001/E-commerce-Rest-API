//launchpack
var $inputs;
var item =0;
var store ='';
var policy = {};
var message = [];

$('[data-btn-submit]').on('click', function(e) {
  e.preventDefault();
  console.log('submit form');
  $('.loading').removeClass('hidden');

    var checkedListing = [];
    var checkedStore = [];

    $('[data-error-msg]').html('');

    $('[data-listing]:checked').each(function() {
        item = $(this).attr('id');
        
        if(jQuery.inArray(item, checkedListing) === -1) {
           checkedListing.push(item);
        } 
    });

    $('[data-store-name]:checked').each(function() {

      store = $(this).val();
      $(this).parents('.row').next('#accordion').find('[data-shipment-policy]').css('background', 'yellow');
      
      var sid = $(this).parents('.row').next('#accordion').find('[data-shipment-policy] option:selected').data('id');
      var sname = $(this).parents('.row').next('#accordion').find('[data-shipment-policy] option:selected').data('name');

       var pid = $(this).parents('.row').next('#accordion').find('[data-payment-policy] option:selected').data('id');
      var pname = $(this).parents('.row').next('#accordion').find('[data-payment-policy] option:selected').data('name');

       var rid = $(this).parents('.row').next('#accordion').find('[data-return-policy] option:selected').data('id');
      var rname = $(this).parents('.row').next('#accordion').find('[data-return-policy] option:selected').data('name');

       policy[store] = {
              'shipment':[sid, sname],
              'payment':[pid, pname],
              'return':[rid, rname]};

      if(jQuery.inArray(store, checkedStore) === -1) {
         checkedStore.push(store);
      } 
  });

  var $inputs = $('#listingForm').find('[data-input]');
  if ($inputs =='') {
      alert('no input found');
      return false;
  }

  var values = {};
  $inputs.each(function() {
      values[this.name] = $(this).val();
  });

  if ($('#listingForm').find('[data-desc]')) {
  values[$('#listingForm').find('[data-desc]').attr('id')] = $('#listingForm').find('[data-desc]').text();

  }

  $.ajax({
                url: "http://127.0.0.1:8000/ebay/verify",
                method: "POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    "form":values,
                    'policies':JSON.stringify(policy),
                    'action':'verify',
                    'checked':checkedListing,
                    'store':checkedStore
                },
                dataType:'json',
        
                success: function (response) {

                  console.log(response);
                  
               var totalListing = 0;

                    for(var i =0; i< checkedStore.length; i++) {

                       var warning = 0;
                        var error = 0;
                        var success = 0;
                        var ack = '';
                    
                    for(var j = 0; j < response[checkedStore[i]].length; j++) {
                        ack = response[checkedStore[i]][j]['Ack'];
                      

                        if (response[checkedStore[i]][j]['Errors']) {
                            var code = response[checkedStore[i]][j]['Errors']['SeverityCode'];
                      
                            var toastcolor = 'warning';
                      
                            if (code === 'Warning') {
                                toastcolor = 'warning';
                        success++;

                               
                            } else if (code === 'Error') {
                                toastcolor = 'error';
                        success = 0;

                            } else if (code === 'Success') {
                                toastcolor = 'success';
                        success++;

                            } else {
                                toastcolor = 'error';
                                code = 'Failure';
                        success = 0;

                                
                            }
                            var msg = '';

                            var totalError  = response[checkedStore[i]][j]['Errors'].length;
                            

                            if (totalError <= 1) {
                                msg = response[checkedStore[i]][j]['Errors']['ShortMessage'];
                                toastr[toastcolor](msg, code, {
                                              positionClass: 'toast-top-right',
                                              rtl: 'rtl'
                                            });
                            } else {
                                msg = response[checkedStore[i]][j]['Errors']['ShortMessage'];
                               

                                
                                toastr[toastcolor](msg, code, {
                                              positionClass: 'toast-top-right',
                                              rtl: 'rtl',
                                              timeOut: 0,
                                            });
                            }

                            
                            
                        }
                    }

                    }
                         

    $('[data-launch-ebay]').attr('disabled', false);
    $('[data-launch-ebay]').find('[data-spinner]').addClass('hidden');
    $('[data-spinner-text]').text('Launch to eBay');



          Swal.fire({
     title: '<strong>Listings Verified!</u></strong>',
      html:(warning+parseInt(success))+' Ready to submit',
      icon: 'success',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: '<i class="bx bx-like"></i>Confirm Submission',
      confirmButtonClass: 'btn btn-primary',
      confirmButtonAriaLabel: 'Thumbs up, great!',
      cancelButtonClass: 'btn btn-danger ml-1',
      cancelButtonText:'<i class="bx bx-dislike"></i>Cancel',
      cancelButtonAriaLabel: 'Cancel',
      buttonsStyling: false,
    }).then(function (result) {
      if (result.value) {

    $('[data-launch-ebay]').attr('disabled', true);
    $('[data-launch-ebay]').find('[data-spinner]').removeClass('hidden');
    $('[data-spinner-text]').text('Launching');

          $.ajax({
                url: "/ebay/addListing",
                 method: "POST",
                data:{
                    "_token": "{{ csrf_token() }}",
                    "form":values,
                    'policies':JSON.stringify(policy),
                    'action':'verify',
                    'checked':checkedListing,
                    'store':checkedStore
                },
                dataType:'json',

                success: function (response) {
                        $('[data-launch-ebay]').attr('disabled', false);
    $('[data-launch-ebay]').find('[data-spinner]').addClass('hidden');
    $('[data-spinner-text]').text('Launch to eBay');
                    Swal.fire(
                  {
                    icon: "success",
                    title: 'Launched to eBay!',
                    text: 'Your listings are launched.',
                    confirmButtonClass: 'btn btn-success',
                  }
                )

                }
            });

        
      }
    })



              

              

                }

              });


 });