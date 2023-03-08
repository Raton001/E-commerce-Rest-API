$(function() {

//get shipment shop (step 3)
 $(".actions [href='#finish']").on('click', function() {
var shop = $('#shops').val();
 var marketplaceID = $('#marketplace').val();
 var shopName = $('#shops').text();
 var shopUserID = $('#shopUserID').val();
 var shopUserName = $('#shopUserName').val();
 var marketplace = 'ebay';

 var smeornot =  $('#smeornot').val();
 var reg_no =  $('#reg_no').val();


 if (marketplaceID == 1) {
  marketplace = 'ebay';
 } else if (marketplaceID == 2) {
  marketplace = 'shopee';

 } else  if (marketplaceID == 3) {
  marketplace = 'lazada';

 } else {
  marketplace = 'amazon';

 }

var edit = $('#edit-account').val();
     $.ajax({
          url: "/ebay/shipmentShopSave",
          method: "POST",
          data:{
              "_token": csrfToken,
              'shop':shop,
              'marketplaceID':marketplaceID,
              'shopName':shopName,
              'shopUserID':shopUserID,
              'shopUserName':shopUserName,
              'smeornot':smeornot,
              'reg_no':reg_no,
              'edit':edit
          },
          dataType:'json',
  
          success: function (response) {
          console.log(marketplace);
          window.location.href = '/'+marketplace+'/setup/complete';
        }
    });

 });




$('#getAxisShop').on('click', function(e) {

  e.preventDefault();
  var memberv2Username = $('#memberv2-username').val();
  var marketplace = $('#marketplacename').val();

   $.ajax({
          url: "/"+marketplace+"/shipmentShop",
          method: "POST",
          data:{
              "_token": csrfToken,
          
              'username':memberv2Username,
              'marketplace':marketplace
          },
          dataType:'json',
  
          success: function (response) {
            if (response == '') {
alert('No shop found for the given username. Contact system admin.');
            }
            $.each(response, function (i, v) {
              $('#shops').html('<option value="'+v.store_id+'">'+v.store_name+'</option>');
              $('#shopUserID').val(v.memberv2_userid);
              $('#shopUserName').val(v.memberv2_username);
              
              
            });
        }
    });

});

$('.loading').addClass('hidden');
;!(function ($) {
    $.fn.classes = function (callback) {
        var classes = [];
        $.each(this, function (i, v) {
            var splitClassName = v.className.split(/\s+/);
            for (var j = 0; j < splitClassName.length; j++) {
                var className = splitClassName[j];
                if (-1 === classes.indexOf(className)) {
                    classes.push(className);
                }
            }
        });
        if ('function' === typeof callback) {
            for (var i in classes) {
                callback(classes[i]);
            }
        }
        return classes;
    };
})(jQuery);

//theme
// updateTheme(localStorage.getItem("theme-mode"));

// function updateTheme(theme){
    
//     localStorage.setItem("theme-mode", theme);
//     var mobileTheme = theme.split('-layout')[0];

//     if (mobileTheme == 'semi-dark') {
//         mobileTheme = 'dark';
//     } else if (mobileTheme == 'boxicon') {
//         mobileTheme = 'light';
//     } else if (mobileTheme == '') {
//         mobileTheme = 'light';
//     }
//     $('body').removeClass('boxicon-layout dark-layout semi-dark-layout').addClass(theme);
//     $('.menu-fixed').removeClass('menu-boxicon menu-dark menu-semi-dark menu-light').addClass('menu-'+mobileTheme);

// }
// $('#theme a').on('click', function(e) {
//     e.preventDefault();
//     updateTheme($(this).attr('href'));
    
// });


    //refactored
    $('[data-sort-by-month] [data-date]').on('click', function() {
   
        var selection = $(this).data('date');
        console.log(selection);

             $.ajax({
                url: "/ebay/dashboard/sort",
                method: "POST",
                data:{
                    "_token": csrfToken,
                
                    'selection':selection
                },
                dataType:'json',
        
                success: function (response) {
              
                  $('[data-launchpacks] tbody').html(response);
              }
          });
    });

    $('#todo-search').on('keydown', function (e){

    if(e.keyCode == 13) {
   
         $.ajax({
                url: "/ebay/dashboard/search",
                method: "POST",
                data:{
                    "_token": csrfToken,
                
                    'search':$(this).val()
                },
                dataType:'json',
        
                success: function (response) {
              
                  $('[data-launchpacks] tbody').html(response);
              }
          });
    }
});

//     var selected = [];

//     $('#mainCheckBox').on('change', function() {


//         if ($(this).prop('checked')) {

//                $('#launchpacks').find('.checkbox-input').prop('checked', true);

//               $('#launchpacks .checkbox-input').each(function() {

//                      selected.push($(this).parents('tr').find('[data-price]').text());
//                     });

//         } else {

//                $('#launchpacks').find('.checkbox-input').prop('checked', false);

//   $('[data-launchpacks] .checkbox-input').each(function() {

//            selected.splice( $.inArray($(this).parents('tr').find('[data-price]').text(), selected), 1 );
//         });

//   $('[data-price-total]').text('0');
//         $('[data-launch-ebay]').addClass('hidden');
//         selected = [];
//         $('[data-launch-ebay-error-prompt]').addClass('hidden');



//         }
//     });

//    $('[data-launchpacks]').find('.checkbox-input').on('change', function() {

// if ($(this).prop("checked")) {
//     selected.push($(this).parents('[data-item]').find('[data-price]').text());
// } else {
//     selected.splice( $.inArray($(this).parents('[data-item]').find('[data-price]').text(), selected), 1 );

// }
        
//   console.log(selected);    
//  sum = 0;
// $.each(selected,function(){sum+=parseFloat(this) || 0;});
// $('[data-price-total]').text(sum.toFixed(2));

//     if (parseFloat($('[data-price-total]').text()) > parseFloat($('[data-available-limit]').text())) {

//         $('[data-launch-ebay]').addClass('hidden');
//         $('[data-launch-ebay-error-prompt]').removeClass('hidden');

//     } else if (parseFloat($('[data-price-total]').text()) <= 0) {

//         $('[data-launch-ebay]').addClass('hidden');
//         $('[data-launch-ebay-error-prompt]').addClass('hidden');

        

//     } else {
  
//         $('[data-launch-ebay]').removeClass('hidden');
//         $('[data-launch-ebay-error-prompt]').addClass('hidden');

//     }

// });


$('[data-add-item-specs]').on('click', function() {
    var $trLast =  $($(this).parents('[data-clone]')).find('input:last');
    $trNew = $trLast.clone();
    $trLast.after($trNew);
});

$('[data-launchpack-status]').on('click', function() {

    var id = $(this).parents('tr[data-launchpack-id] ').data('launchpack-id');
    var status = 0;
    if ($(this).is(':checked')) {

        status = 1;
    } else {
        status = 0;
        

    }

    $.ajax({
        url: "/ebay/updateStatus",
        method: "POST",
        data:{
            "_token": csrfToken,
            'id':id,
            'status':status
        },
        dataType:'json',

        success: function (response) {
   
        
        console.log(response);
        },
    });

});
//calculator

            $('[data-calculator-id]').on('click', function(e) {
                // e.preventDefault();
                var calculatorID = $(this).attr('id').split('_')[1];
                var store = $('#account').val();
                var status = 0;
               if ($(this).prop('checked')) {
                //activate
                status = 1;
               } else {
                //deactivate
                status = 0;
               }
 

               $.ajax({
                    url: "/ebay/config/calculator/"+store+"/updateStatus",
                    method: "POST",
                    data:{
                        "_token": csrfToken,
                        'id':calculatorID,
                        'status':status
                    },
                    dataType:'json',
            
                    success: function (response) {
                      console.log(response);
                      if (status == 1) {
                        toastr['success']('Successfully activated calculator '+calculatorID, 'success', {
                                              positionClass: 'toast-top-right',
                                              rtl: 'rtl'
                                            });
                      } else {
                          toastr['warning']('Successfully deactivated calculator '+calculatorID, 'warning', {
                                              positionClass: 'toast-top-right',
                                              rtl: 'rtl'
                                            });
                      }
               
                    },
                });

            });




  //launching starts
  $('[data-launch-ebay]').on('click', function() {
var launchpacks = [];
var selectedStore = [$('[data-selected-store]').val()];

  if (selectedStore.length <= 0) {
    Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: 'Select a store!',
      footer: '',
      confirmButtonClass: 'btn btn-primary',
      buttonsStyling: false,
    });

  return false; 

  }
    $('#launchpacks .checkbox-input').each(function() {

         if ($(this).is(":checked")) {
             
             launchpacks.push($(this).attr('id').split('_')[1]);
        }
    });
    console.log(launchpacks);

    $('[data-launch-ebay]').attr('disabled', true);
    $('[data-launch-ebay]').find('[data-spinner]').removeClass('hidden');
    $('[data-spinner-text]').text('Verifying');

    $.ajax({
                url: "/ebay/quicklaunch",
                method: "POST",
                data:{
                    "_token": csrfToken,
                    'id':launchpacks,
                    'store':selectedStore
                },
                dataType:'json',

                success: function (response) {
             console.log(response);

                        var checkedStore = selectedStore;
                  
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
                            var showToast = 0;
                      
                            if (code === 'Warning') {
                                toastcolor = 'warning';
                        success++;

                               
                            } else if (code === 'Error') {
                                toastcolor = 'error';
                        success = 0;
                        showToast = 1;

                            } else if (code === 'Success') {
                                toastcolor = 'success';

                        success++;

                            } else {
                                toastcolor = 'error';
                                code = 'Failure';
                                showToast = 1;
                        success = 0;

                                
                            }

                            var msg = '';

                            var totalError  = response[checkedStore[i]][j]['Errors'].length;
                            msg = response[checkedStore[i]][j]['Errors']['LongMessage'];

                            console.log(response[checkedStore[i]][j]['Errors']['ErrorParameters']);
                            if (totalError <= 1) {
                            

                                
                             
                              if (showToast == 1) {

                                toastr[toastcolor](msg, code, {
                                              positionClass: 'toast-top-right',
                                              rtl: 'rtl'
                                            });
                              }

                            } else {
                              if (showToast == 1) {

                              
                               toastr[toastcolor](msg, code, {
                                              positionClass: 'toast-top-right',
                                              rtl: 'rtl',
                                              timeOut: 0,
                                            });
                             }

                              for (var e = 0; e< totalError; e++) {
                               msg = response[checkedStore[i]][j]['Errors'][e]['LongMessage'];
                              if (showToast == 1) {

                                toastr[toastcolor](msg, code, {
                                              positionClass: 'toast-top-right',
                                              rtl: 'rtl',
                                              timeOut: 0,
                                            });
                              }
                              }


                                
                                
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

var selectedStore = [$('[data-selected-store]').val()];

          $.ajax({
                url: "/ebay/quicklaunch2",
                method: "POST",
                data:{
                    "_token": csrfToken,
                    'id':launchpacks,
                    'store':selectedStore
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



              

                },
            });

  });

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
    console.log($('#listingForm').find('[data-desc]').text());
  values[$('#listingForm').find('[data-desc]').attr('id')] = $('#listingForm').find('[data-desc]').text();

  }

  $.ajax({
                url: "/ebay/verify",
                method: "POST",
                data:{
                    "_token": csrfToken,
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
                            if (typeof(code) == 'undefined') {
                            code = ack;
                            
                            } 

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
                            msg = response[checkedStore[i]][j]['Errors']['LongMessage'];

                            console.log(response[checkedStore[i]][j]['Errors']['ErrorParameters']);
                            if (totalError <= 1) {
                            

                                
                             

                                toastr[toastcolor](msg, code, {
                                              positionClass: 'toast-top-right',
                                              rtl: 'rtl'
                                            });

                            } else {
                              
                               toastr[toastcolor](msg, code, {
                                              positionClass: 'toast-top-right',
                                              rtl: 'rtl',
                                              timeOut: 0,
                                            });

                              for (var e = 0; e< totalError; e++) {
                               msg = response[checkedStore[i]][j]['Errors'][e]['LongMessage'];
                                toastr[toastcolor](msg, code, {
                                              positionClass: 'toast-top-right',
                                              rtl: 'rtl',
                                              timeOut: 0,
                                            });
                              }


                                
                                
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
                    "_token": csrfToken,
                    "form":values,
                    'policies':JSON.stringify(policy),
                    'action':'verify',
                    'checked':checkedListing,
                    'store':checkedStore
                },
                dataType:'json',

                success: function (response) {
                  console.log(response);
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



              

                },

              });


 });
  
  $('[data-bulk-delete]').on('click', function() {
    var store = $('#account').val();
    var deleteIds = [];
    $('[data-bulk-list]:checked').each(function(x, y) {
      deleteIds.push($(y).attr('id').split('mainCheckBox_')[1]);
    });

       Swal.fire({
     title: '<strong>Are you sure to end the listing?</u></strong>',
      html:'',
      icon: 'warning',
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

    $.ajax({
                url: '/ebay/'+store+'/listing/end',
                method: "POST",
                data:{
                    "_token": csrfToken,
                    'id':deleteIds
                },
                dataType:'json',

                success: function (response) {
                 Swal.fire(
                  {
                    icon: "success",
                    title: 'Successfully Ended the listing!',
                    text: '',
                    confirmButtonClass: 'btn btn-success',
                  }
                )

                }
            });
  }
            });

  });
     

$('[data-delete-itemid]').on('click', function(e) {

  e.preventDefault();
  var flag = false;
  var deleteurl = $(this).attr('href');

   Swal.fire({
     title: '<strong>Are you sure to end the listing?</u></strong>',
      html:'',
      icon: 'warning',
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

         $.ajax({
                url: deleteurl,
                method: "POST",
                data:{
                    "_token": "{{ csrf_token() }}"
                },
                dataType:'json',

                success: function (response) {
                 Swal.fire(
                  {
                    icon: "success",
                    title: 'Successfully Ended the listing!',
                    text: '',
                    confirmButtonClass: 'btn btn-success',
                  }
                )

                }
            });

      
    }
    });
});

//edit page
 var i = 0;
            var number1 = 0;
            var number2 = 0;

            $("body").on("click",'[data-add-item-specs]',function() {

                i++;
            

                var $trLast =  $($(this).parents('.card-body')).find('[data-item-specs-table] tr:last');
                $trNew = $trLast.clone();
              
                $trNew.find('td input:first').attr('type', 'text').val('').removeClass('itemspec_input');
                $trNew.find('td label').addClass('hidden');

                $trNew.find('td input:last').val('');
                $trNew.find('td select').addClass('hidden');

            

                var str1 = $trNew.find('td input:first').attr('name');
                var str2 = $trNew.find('td input:last').attr('name');


                var items1 = str1.split('_');
                var items2 = str2.split('_');


                if (number1 <= 0) {
                    number1 = parseInt(items1[4]) + 1;
                    number2 = parseInt(items2[4]) + 1;
                } else {
                    number1++;
                    number2++;
                }
                

                $trNew.find('td input:first').attr('name', items1[0]+'_'+parseInt(items1[1])+'_'+items1[2]+'_'+items1[3]+'_'+number1+'_Name');

                $trNew.find('td input:last').attr('name', items2[0]+'_'+parseInt(items2[1])+'_'+items2[2]+'_'+items2[3]+'_'+number2+'_Value');
                        
                $trLast.after($trNew);
                });

var rulesArr1 = '<select data-promo-input>'
  +'<option>5</option>'
  +'<option>10</option>'
  +'<option>15</option>'
  +'<option>20</option>'
  +'<option>25</option>'
  +'<option>30</option>'
  +'<option>35</option>'
  +'<option>40</option>'
  +'<option>45</option>'
  +'<option>49</option>'
  +'<option>50</option>'
  +'<option>55</option>'
  +'<option>59</option>'
  +'<option>60</option>'
  +'<option>65</option>'
  +'<option>69</option>'
  +'<option>70</option>'
  +'<option>75</option>'
  +'<option>79</option>'
  +'<option>80</option>'
  +'<option>85</option>'
  +'<option>89</option>'
  +'<option>90</option>'
  +'<option>95</option>'
  +'<option>99</option>'
  +'<option>100</option>'
  +'<option>110</option>'
  +'<option>120</option>'
  +'<option>125</option>'
  +'<option>149</option>'
  +'<option>150</option>'
  +'<option>175</option>'
  +'<option>199</option>'
  +'<option>249</option>'
  +'<option>250</option>'
  +'<option>299</option>'
  +'<option>300</option>'
  +'<option>350</option>'
  +'<option>399</option>'
  +'<option>400</option>'
  +'<option>450</option>'
  +'<option>499</option>'
  +'<option>500</option>'
  +'</select>';

var rulesArr2 = '<select data-promo-input>'
  +'<option>1</option>'
  +'<option>2</option>'
  +'<option>3</option>'
  +'<option>4</option>'
  +'<option>5</option>'
  +'<option>6</option>'
  +'<option>7</option>'
  +'<option>8</option>'
  +'<option>9</option>'
  +'<option>10</option>'
  +'<option>11</option>'
  +'<option>12</option>'
  +'<option>13</option>'
  +'<option>14</option>'
  +'<option>15</option>'
  +'<option>16</option>'
  +'<option>17</option>'
  +'<option>18</option>'
  +'<option>19</option>'
  +'<option>20</option>'
  +'<option>25</option>'
  +'<option>50</option>'
  +'<option>75</option>'
  +'<option>100</option>'
  +'</select>';

  var rulesArr3 = '<select data-promo-input>'
  +'<option>1</option>'
  +'<option>2</option>'
  +'<option>3</option>'
  +'<option>4</option>'
  +'<option>5</option>'
  +'<option>6</option>'
  +'<option>7</option>'
  +'<option>8</option>'
  +'<option>9</option>'
  +'<option>10</option>'
  +'</select>';

  var benArr1 = '<select data-promo-input>'
  +'<option>5</option>'
  +'<option>6</option>'
  +'<option>7</option>'
  +'<option>8</option>'
  +'<option>9</option>'
  +'<option>10</option>'
  +'<option>15</option>'
  +'<option>20</option>'
  +'<option>25</option>'
  +'<option>30</option>'
  +'<option>35</option>'
  +'<option>40</option>'
  +'<option>45</option>'
  +'<option>50</option>'
  +'<option>55</option>'
  +'<option>60</option>'
  +'<option>65</option>'
  +'<option>70</option>'
  +'<option>75</option>'
  +'<option>80</option>'
  +'<option>85</option>'
  +'<option>90</option>'
  +'<option>95</option>'
  +'<option>100</option>'
  +'<option>110</option>'
  +'<option>120</option>'
  +'<option>125</option>'
  +'<option>150</option>'
  +'<option>200</option>'
  +'<option>250</option>'




  +'</select>';

var rulescontent = '<table>'
+'<tr><th><input type="checkbox" name="rules_forEachAmount" value="forEachAmount">forEachAmount</th><td>'+rulesArr1+'</td></tr>'
+'<tr><th><input type="checkbox" name="rules_forEachQuantity" value="forEachQuantity">forEachQuantity</th><td>'+rulesArr2+'</td></tr>'
+'<tr><th><input type="checkbox" name="rules_minAmount" value="minAmount">minAmount</th><td>'+rulesArr1+'</td></tr>'
+'<tr><th><input type="checkbox" name="rules_minQuantity" value="minQuantity">minQuantity</th><td>'+rulesArr2+'</td></tr>'
+'<tr><th><input type="checkbox" name="rules_numberOfDiscountedItems" value="numberOfDiscountedItems">numberOfDiscountedItems</th><td>'+rulesArr3+'</td></tr>'
+'</table>';


var benefitcontent = '<table>'
+'<tr><th><input type="checkbox" name="ben_amountOffItem" value="amountOffItem">amountOffItem</th><td>'+benArr1+'</td></tr>'
+'<tr><th><input type="checkbox" name="ben_amountOffOrder" value="amountOffOrder">amountOffOrder</th><td>'+benArr1+'</td></tr>'
+'<tr><th><input type="checkbox" name="ben_percentageOffItem" value="percentageOffItem">percentageOffItem</th><td><input type="number" data-promo-input placeholder="min=5 max=80"></td></tr>'
+'<tr><th><input type="checkbox" name="ben_percentageOffOrder" value="percentageOffOrder">percentageOffOrder</th><td><input type="number" data-promo-input placeholder="min=5 max=80"></td></tr>'
+'</table>';

var htmlcontent = '<div class="form-group" id="promoform"><label>Name</label><input type="text" id="name"><label>Description</label><input type="text" id="description"><label>Start Date</label><input type="date" id="promo_start_date" class="form-control"><label class="form-control">End Date</label><input type="date" id="promo_end_date" class="form-control"></div><div class="form-group"><label>Rules</label>'+rulescontent+'</div><div class="form-group"><label>Benefit</label>'+benefitcontent+'</div>';


  //promotion
  $('[data-promo]').on('click', function (e) {
    e.preventDefault();

    var listingid  = $(this).attr('id');

    var formurl = "/ebay/<?php echo (isset($key)? $key:''); ?>/"+listingid+"/promotion/create";


       Swal.fire({
     title: '<strong>Create Promotion</u></strong>',
      html:htmlcontent,
      icon: 'info',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: '<i class="bx bx-like"></i>Create',
      confirmButtonClass: 'btn btn-primary',
      confirmButtonAriaLabel: 'Thumbs up, great!',
      cancelButtonClass: 'btn btn-danger ml-1',
      cancelButtonText:'<i class="bx bx-dislike"></i>Cancel',
      cancelButtonAriaLabel: 'Cancel',
      buttonsStyling: false,
    }).then(function (result) {
      if (result.value) {

    $('#promoform').css('background', 'yellow');
    
    //data
    var startDate = $('#promoform').find('#promo_start_date').val();
    var endDate = $('#promoform').find('#promo_end_date').val();

    var name = $('#promoform').find('#name').val();
    var description = $('#promoform').find('#description').val();


var promotion = [];
var benefit = [];

     $('input[name^=rules]').each(function(x, y) {
     
       if ($(this).prop('checked')) {
        promotion.push([$(this).val(), $(this).parents('tr').find('[data-promo-input]').val()]);

    }

    });

     $('input[name^=ben]').each(function(x, y) {
     
       if ($(this).prop('checked')) {
        
        benefit.push([$(this).val(), $(this).parents('tr').find('[data-promo-input]').val()]);

    }

    });


         $.ajax({
                url: formurl,
                method: "POST",
                data:{
                    "_token": csrfToken,
                    'startDate':startDate,
                    'endDate':endDate,
                    'rules':promotion,
                    'benefits':benefit,
                    'name':name,
                    'description':description

                },
                dataType:'json',

                success: function (response) {
                  console.log(response.errors);
                  if (typeof(response.errors) == 'undefined') {
                     Swal.fire(
                      {
                        icon: "success",
                        title: 'Successfully Created Promotion!',
                        text: '',
                        confirmButtonClass: 'btn btn-success',
                      }
                    )
                     return false;
                  } else {

                    if (response.errors.length > 0) {
                      for (var i = 0; i< response.errors.length; i++) {
                    
                        toastr['error'](response.errors[i].message, 'Error', {
                          positionClass: 'toast-top-right',
                          rtl: 'rtl',
                          timeOut: 0,
                        });
                      }
                    }
                  }

                   
                  
                

                


                }
            });

      
    }
    });
  });


  //shipment manual
            $('#smeItemSpecs').on('change', function() {

                    var data = $(this).val();
                  var smeID = data.split('-')[0];
                  var brandID = data.split('-')[1];

                    $.ajax({
                            url: "/ebay/config/package",
                            method: "POST",
                            data:{
                                "_token": csrfToken,
                                'sme':smeID,
                                'brand':brandID

                            },
                            dataType:'json',
                    
                            success: function (response) {
                                $('#product_list_total').html(response.length +' package(s) found');
                                $('#product_list').html('').removeClass('hidden');
                            var pkg_pro = '';
                            for (var i =0; i <= response.length; i++) {
                              if (typeof response[i].ispkg != 'undefined') {
                                if (response[i].ispkg.split('-')[0] == 'package') {
                                  pkg_pro = 'badge-light-success';
                                } else {
                                  pkg_pro = 'badge-light-info';

                                }
                              }
                                $('#product_list').append('<option class="badge '+pkg_pro+' badge-pill" value="'+response[i].ispkg+'_'+response[i].id+'">'+response[i].name+'</option>');
                              

                            }
                            


                            },
                        });

                });

            $('body').on('click', '#shipmentRequestFormSubmit', function(e) {
                e.preventDefault();
                var $inputs = $('#shipmentRequestForm').find('input');
                var values = {};
                    $inputs.each(function() {
                        values[this.name] = $(this).val();
                    });
                    $.ajax({
                        url: "/ebay/"+account+"/order/shipnow/",
                        method: "POST",
                        data:{
                            "_token": csrfToken,
                            'form':values
                        },
                        dataType:'html',
                
                        success: function (response) {
                           console.log(response);
                        },
                    });

            });

            $('body').on('change', '#product_list', function() {
            console.log('hiii');
            var data = $(this).val();

            var type = data.split('_')[0];
            if (typeof type == 'undefined') {
              type = 'product';
            }

            var id = data.split('_')[1];

                var account = $('#account').val();

                var orderID = $('#orderID').val();


                 $.ajax({
                        url: "/ebay/"+account+"/order/ship/"+orderID+'/143790221522/',
                        method: "POST",
                        data:{
                            "_token": csrfToken,
                            'packageID':id,
                            'type':type
                        },
                        dataType:'html',
                
                        success: function (response) {
                    
                          var pro_pkg_list = JSON.parse(response);
                         
                         
                          var template = '';
                          var qty = 1;
                          $('#hiddenInput').html('');
                          var itemID = $('#hiddenInput').parents('tr').attr('id');


                            $.each(pro_pkg_list, function (x, y) {
                             
                              for(var i=0; i< y.length; i++) {
                                if (typeof y[i].quantity != 'undefined') {
                                  qty = y[i].quantity;
                                }
                                template += '<tr data-item-container>'
                                  +'<td style="padding:0;"><span data-delete-item style="cursor:pointer;color:red;">X</span></td>'
                                             +'<td style="padding:0;">'
                                              +'<span class="bullet bullet-primary bullet-sm"></span>'
                                              +'<small class="text-muted">'+y[i].name+'</small>'
                                              +'<span class="badge badge-light-success badge-pill">'+qty+'</span>'
                                            +'</td>'
                                        +'</tr>';
                               

                                $.each(y[i], function (k, v) {
                                  $('#hiddenInput').append('<input type="hidden" name="data['+itemID+'][product]['+i+']['+k+']" value="'+v+'">');
                                });

                              }
                              });
                            $('#invoiceTemp').append(template);

                        


                        },
                    });
            });

            $('body').on('click', '[data-delete-item]', function() {
    
            $(this).parents('tr[data-item-container]').remove();
            });

            $('body').on('click', '[data-update-tracking]', function(e) {

              e.preventDefault();

                var account = $('#account').val();

            var trackingCode = $(this).find('span').data('tracking');
            var invoiceID = $(this).find('span').data('invoice');
            var orderID = $(this).find('span').data('order');
            var oline = $(this).find('span').data('oline');
            
            var itemID = $(this).find('span').data('item');





                 $.ajax({
                        url: "/ebay/"+account+"/order/trackingcode",
                        method: "POST",
                        data:{
                            "_token": csrfToken,
                            'trackingCode':trackingCode,
                            'invoiceID':invoiceID,
                            'orderID':orderID,
                            'oline':oline,
                            'itemID':itemID


                        },
                        dataType:'json',
                
                        success: function (response) {
                                toastr['success']('Successfully Updated', 'Success', {
                                  positionClass: 'toast-top-right',
                                  rtl: 'rtl',
                                  timeOut: 0,
                                });
                        },
                    });
            });


            $(document).keypress(function(event) {
                var keycode = event.keyCode || event.which;
                if(keycode == '13') {
                    var smeID = $('#sme').val();
                    var productname = $('#productname').val();
                   
                    $.ajax({
                            url: "/ebay/config/packageProducts",
                            method: "POST",
                            data:{
                                "_token": csrfToken,
                                'sme':smeID,
                                'productname':productname
                            },
                            dataType:'html',
                    
                            success: function (response) {
                       
                            
                            $('#product_list').html(response);
                            },
                        });

             
                }
            });

            $('[data-delete-itemspec]').on('click', function() {
           
              $(this).parents('tr').remove();
            });

            $('[data-listing-title-wrapper]').on('mouseover', function() {

              $(this).find('a').removeClass('hidden');
            });
            $('[data-listing-title-wrapper]').on('mouseout', function() {
              $(this).find('a').addClass('hidden');
            });

            // $('body').on('click', '[data-update-title]',function() {
            //   var itemID = $(this).parents('tr').attr('id');
            //   var title = $(this).parents('tr').find('[data-listing-title]').val();
            //   var price = $(this).parents('tr').find('[data-listing-price]').val();
              

            //   var account = $('#account').val();

            //   $.ajax({
            //     url: "/ebay/"+account+"/revise",
            //     method: "POST",
            //     data:{
            //         "_token": csrfToken,
                
            //         'itemID':itemID,
            //         'title':title,
            //         'price':price
            //     },
            //     dataType:'json',
        
            //     success: function (response) {
              
            //        toastr['success']('Successfully Updated', 'Success', {
            //               positionClass: 'toast-top-right',
            //               rtl: 'rtl',
            //               timeOut: 0,
            //             });
            //   }
            //   });


            // });
            // 
              $('body').on('click', '[data-update-title]',function() {
              var itemID = $(this).parents('tr').attr('id');
              var title = $(this).parents('tr').find('[data-listing-title]').val();
              var available = $(this).parents('tr').find('[data-listing-available]').val();
              var price = $(this).parents('tr').find('[data-listing-price]').val();
              

              var account = $(this).parents('tr').find('[current-account]').val();//$('#account').val();

              console.log(account);
              $.ajax({
                url: "/ebay/"+account+"/revise",
                method: "POST",
                data:{
                    "_token": csrfToken,
                
                    'itemID':itemID,
                    'title':title,
                    'available':available,
                    'price':price
                },
                dataType:'json',
        
                success: function (response) {
              
                   toastr['success']('Successfully Updated', 'Success', {
                          positionClass: 'toast-top-right',
                          rtl: 'rtl',
                          timeOut: 0,
                        });
              }
              });


            });


//price

   //setup before functions
var typingTimer;                //timer identifier
var doneTypingInterval = 5000;  //time in ms, 5 second for example


//on keyup, start the countdown
$("body").on('keyup', '[data-weight]', function() {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping, doneTypingInterval, this);
});

//on keydown, clear the countdown 
$("body").on('keydown', '[data-weight]', function() {
  clearTimeout(typingTimer);
});

//user is "finished typing," do something
function doneTyping (param) {

$(param).parents('tr').find('[data-spinner]').removeClass('hidden');
  //do something
  var weight = $(param).val();
  var totalProductCost = 0;
  var $totalCost = $($($(param).parents('.form-group')).next('.form-group')).find('[data-total-cost]');
  var $totalCostUsd = $($($(param).parents('td'))).find('[data-total-cost-usd]');


 if ($('[data-total-products-2]').text() != '') {
    totalProductCost = $('[data-total-products-2]').text();
 } else {
      $.each($(param).parents('table').find('tr[data-unit-product] td'), function(index, value) {
    if ($(value).find('[data-product-cost]').val()) {

        $(value).find('[data-product-cost]').css('background', 'yellow');
        totalProductCost+= parseFloat($(value).find('[data-product-cost]').val());

    }
  
  
});
 }



$.ajax({
    url: "/ebay/config/pricelist/totalcost",
    method: "POST",
    data:{
        "_token": csrfToken,
        'weight':weight,
        'productCost':totalProductCost
    },
    success: function (response) {

        $totalCost.val(response.rm);
        $totalCostUsd.val(response.usd);
$(param).parents('tr').find('[data-spinner]').addClass('hidden');


    },
});


}


//selling price

//on keyup, start the countdown
$("body").on('keyup', '[data-selling-price]', function() {
  clearTimeout(typingTimer);
  typingTimer = setTimeout(doneTyping2, doneTypingInterval, this);
});

//on keydown, clear the countdown 
$("body").on('keydown', '[data-selling-price]', function() {
  clearTimeout(typingTimer);
});

function doneTyping2 (param) {

$(param).parents('tr').find('[data-spinner]').removeClass('hidden');

                var sellingPrice = $(param).val();
            var totalCost = $(param).parents('td').find('[data-total-cost]').val();
        
            var $netProfit = $($($(param).parents('td'))).find('[data-netprofit]');



            // if (sellingPrice.length >= 3) {
                $.ajax({
                        url:"/ebay/config/pricelist/netprofit",
                        method: "POST",
                        data:{
                            "_token": csrfToken,
                            'sellingPrice':sellingPrice,
                            'totalCost':totalCost
                        },
                        success: function (response) {
                            $netProfit.val(response);
$(param).parents('tr').find('[data-spinner]').addClass('hidden');

                        },
                    });

}

$('[data-calculator]').on('click', function() {
  $(this).parents('td').find('[data-price-simulator]').toggleClass('hidden');
});


$('[data-calculator-config-submit]').on('click', function() {

 var $inputs = $('#calculatorForm').find('input');
  if ($inputs =='') {
      alert('no input found');
      return false;
  }

  var values = {};
  $inputs.each(function() {
      values[this.name] = $(this).val();
  });

  var store = $('#account').val();
  var calculator = $('#calculatorID').val();
console.log(calculator);
console.log(store);

  $.ajax({
      url: "/ebay/config/calculator/"+store+"/edit/"+calculator,
      method: "POST",
      data:{
          "_token": csrfToken,
          'input':values,
          'ajax':true
      },
      dataType:'json',

      success: function (response) {
        toastr['success']('Successfully edited the calculator', 'success', {
                                              positionClass: 'toast-top-right',
                                              rtl: 'rtl'
                                            });

      }
  });


});



$('#search').on('click', function() {
  $('.listings .card-body').html('<div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div>');
  var date = $('#start-end-date').val();
  var marketplace = $('#marketplace').val();
  var account = $('#account').val();



               $.ajax({
                url: "/"+marketplace+"/"+account+"/orders",
                method: "POST",
                data:{
                    "_token": csrfToken,
                    'startEndDate':date,
                    'internal':1,
                    'account':account
                },
                dataType:'html',
        
                success: function (response) {
                console.log(response);
                
                $('.listings .card-body').html(response);
                 // $('.portfolio-container .filter-Completed tbody').html('');
                 // $('.portfolio-container .filter-Cancelled tbody').html('');
                 // $('.portfolio-container .filter-Inactive tbody').html('');
                 // $('.portfolio-container .filter-Active tbody').html('');


                 // var template = '<div class="table-responsive">'
                 //                 +'<table class="table table-striped">'
                 //                      +'<thead>'
                 //                          +'<tr class="text-left">'
                 //                              +'<th>Order ID</th>'
                 //                              +'<th>Status</th>'
                                             
                 //                              +'<th>Subtotal</th>'

                 //                              +'<th>Total</th>'
                 //                              +'<th>Tax</th>'

                 //                              +'<th>Carrier</th>'
                 //                              +'<th>CreatedDate</th>'
                 //                              +'<th>Shipped Time</th>'

                 //                              +'<th>Action</th>'
                 //                          +'</tr>'
                 //                      +'</thead>'
                 //                      +'<tbody>';
                 var template = '';
                 var tax = 0;

                 // $.each(response, function (x, y) {


                 //  for(var i = 0; i<y.length;i++) {

                 //    template += '<tr>'
                 //    +'<td>'+y[i].OrderID+'</td>'
                 //    +'<td>'+y[i].OrderStatus+'</td>'
                 //    +'<td>'+y[i].Subtotal+'</td>'
                 //    +'<td>'+y[i].Total+'</td>';
                 //    if (typeof y[i].TransactionArray.Transaction.length == 'undefined') {
                 //      template +='<td>'+y[i].TransactionArray.Transaction.CreatedDate+'</td>';
                 //    } else {
                  
                     
                 //        // for(var j = 0; j<y[i].TransactionArray.Transaction.length;j++) {
                 //        //   console.log(y[i].TransactionArray.Transaction[j][0].CreatedDate);
                 //         template +='<td>'+y[i].TransactionArray.Transaction[0].CreatedDate+'</td>';
                      
                      
                 //        // }
                      
                 //    }
                    
                 //    template +='<td>'+y[i].ShippedTime+'</td>'
                 //    +'</tr>';

                 //  }

                 //   $('.portfolio-container .filter-'+x+' tbody').append(template);

                  
                 // });


              }
          });
});

//stores
    $('#stores').on('change', function() {
      var link = $(this).val();

      window.location.href = '/ebay/'+link+'/orders';
});

    // $('#portfolio-flters li:nth-child(1)').css('background', 'yellow').trigger('click');
    $('.portfolio-item:nth-child(1)').removeClass('hidden');
    $('#portfolio-flters li').on('click', function() {
    $('.portfolio-item').removeClass('hidden');
     
    });


    // $("input[name^=ship_]").on('change', function() {
    //   if ($(this).prop('checked')) {
    //     console.log('yes');
    //   } else {
    //     console.log('no');

    //   }
    // });

});



// $(function() {

//   var store = $('[data-store-id]');
//   var storeID = '';
//   for (var i =0; i < store.length; i++) {

//     var totalEntries = 0;
//         var totalAmount = 0;

//      storeID = $(store[i]).data('store-id');

//      //selling limit
//      $.post( "/ebay/availableSellingAmount/", { "_token": csrfToken, "store": storeID }, function(response) {
      
//           for (var key in response){
//           $('[data-store-id='+key+']').find('[data-available-limit]').html(response[key][0].Summary.AmountLimitRemaining);
          
//         }});

//      //order total volume
//      $.post( "/ebay/orderTotalVolume/", { "_token": csrfToken, "store": storeID }, function(response) {
        

//          if (typeof(response[key]) == 'undefined' || response == '') {
//               totalEntries = 0;
//               totalAmount = 0;
//               $('[data-store-id='+key+']').find('[data-order-total]').html(totalEntries);
//           $('[data-store-id='+key+']').find('[data-order-volume]').html(totalAmount)

//           } else {
//               totalEntries = response[key].totalEntries;
//               totalAmount = response[key].totalAmount;
//           }

//           for (var key in response){

//             $('[data-store-id='+key+']').find('[data-order-total]').html(totalEntries);
//             $('[data-store-id='+key+']').find('[data-order-volume]').html(totalAmount);
//        }});

//      //active listing
     
//          $.post( "/ebay/activeListing/", { "_token": csrfToken, "store": storeID }, function(response) {
          

//            var entry1 = 0;
//            var entry2 = 0;


//               if (typeof(response.listing[storeID]) == 'undefined') {

//                   entry1 = 0;
                 

//               } else {
//                   entry1 = response.listing[storeID];
                  
//               }

//                if (typeof(response.shipment[storeID]) == 'undefined' || response.shipment[storeID] == '') {

//                   entry2 = 0;

//               } else {
                  
//                   entry2 =response.shipment[storeID];
//               }

//       $('[data-store-id]').find('[data-active-listing]').html(entry1);
//            $('[data-store-id]').find('[data-pending-shipment]').html(entry2);

//       $('[data-active-listing]').parents('[data-dash-stats]').find('[data-spinner').addClass('hidden');
//       $('[data-pending-shipment]').parents('[data-dash-stats]').find('[data-spinner').addClass('hidden');

//           });


//   }
   

// });
