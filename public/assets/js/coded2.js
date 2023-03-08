$(function() {
    

/**************manual shipment********************/


$('#getProducts').on('click', function() {

  var productname = $('#product').val();
      $.ajax({
              url: "/grab/products",
              method: "POST",
              data:{
                  "_token": csrfToken,
                  'productname':productname
              },
              dataType:'html',
      
              success: function (response) {
         
              
              $('#product_list').html(response);
              },
          });
});
/**dashboard required selling limit**/






$('[data-launch-listing]').on('click', function() {

var filter = $('.filter-active').data('filter');
filter = filter.substring(1, filter.length);


var store = $('.portfolio-container [data-'+filter+'] ').find('[data-store-id]').val();
var storename =  $('.portfolio-container [data-'+filter+'] ').find('[data-store-name]').val();


var marketplace = $('#marketplace').val();
var txt;


if (marketplace == 'shopee') {
txt = storename;
} else {
  txt = store;
}
var r = confirm("Do you want to launch listings to "+txt+'?');
if (r == true) {


  


        $.ajax({
          url: "/"+marketplace+"/addListing",
          method: "POST",
          data:{
              "_token": csrfToken,
              "marketplace":marketplace,
              'product':selectedListing,
              'store':store,
          },
          dataType:'json',

          success: function (response) {
   
          if (response == 1) {
            alert('Successfully Listed in '+ storename);
          } else {
            alert(response);
          }

          //  Swal.fire({
          //   icon: 'success',
          //   title: 'Default Marketplace Saved!',
          //   text: 'Select a store!',
          //   footer: '',
          //   confirmButtonClass: 'btn btn-secondary',
          //   buttonsStyling: false,
          // });

   }});

}

  

});
  /**************************************USER SETTING**********************************/
//dashboard product display

$('[data-list-switch]').on('click', function() {

  $('.item').addClass('list-group-item');
  $('.item').removeClass('col-xs-3 col-lg-3').addClass('col-xs-12 col-lg-12');
  $('.item [data-col-0]').removeClass('col-xs-12 col-lg-12').addClass('col-xs-4 col-lg-4');
  $('.item [data-col-1]').removeClass('col-xs-12 col-lg-12').addClass('col-xs-2 col-lg-2');
  $('.item [data-col-2]').removeClass('col-xs-12 col-lg-12').addClass('col-xs-2 col-lg-2');
  $('.item [data-col-3]').removeClass('col-xs-12 col-lg-12').addClass('col-xs-1 col-lg-1');


  // $(this).parents("[data-list-container]").find('[data-list-item]').css('background-color', 'yellow');
});

$('[data-grid-switch]').on('click', function() {
  
  $('.item').removeClass('list-group-item').addClass('grid-group-item');

    $('.item').removeClass('col-xs-12 col-lg-12').addClass('col-xs-3 col-lg-3');
  $('.item [data-col-0]').addClass('col-xs-12 col-lg-12').removeClass('col-xs-3 col-lg-3');
  $('.item [data-col-1]').addClass('col-xs-12 col-lg-12').removeClass('col-xs-2 col-lg-2');
  $('.item [data-col-2]').addClass('col-xs-12 col-lg-12').removeClass('col-xs-2 col-lg-2');
  $('.item [data-col-3]').addClass('col-xs-12 col-lg-12').removeClass('col-xs-2 col-lg-2');

  // $(this).parents("[data-list-container]").find('[data-list-item]').css('background-color', 'red');


});



$('.marketplace').on('change', function() {

  if ($(this).is(":checked")) {
 var marketplace = $(this).val();

      $.ajax({
          url: "/user/setting",
          method: "POST",
          data:{
              "_token": csrfToken,
              "default_marketplace":marketplace
          },
          dataType:'json',

          success: function (response) {
           Swal.fire({
            icon: 'success',
            title: 'Default Marketplace Saved!',
            text: 'Select a store!',
            footer: '',
            confirmButtonClass: 'btn btn-secondary',
            buttonsStyling: false,
          });

   }});
      
    }


});

    /***orders***/


/**************************************SETUP**********************************/
//trigger the next step
var pathname = window.location.pathname; 

  if (pathname.indexOf('ebay/setup/2') >= 0) {
    
      $(".actions [href='#next']").trigger('click');
  }

  if (pathname.indexOf('ebay/setup/3') >= 0) {
      $(".actions [href='#next']").trigger('click');
      $(".actions [href='#next']").trigger('click');
  }
  if (pathname.indexOf('shopee/setup/3') >= 0) {
    console.log('here');

      $(".actions [href='#next']").trigger('click');
      $(".actions [href='#next']").trigger('click');
  }

  if (pathname.indexOf('lazada/setup/3') >= 0) {
    
      $(".actions [href='#next']").trigger('click');
      $(".actions [href='#next']").trigger('click');
  }

  //shipment edit
    if (pathname.indexOf('/edit/shipment') >= 0) {
    
      $(".actions [href='#next']").trigger('click');
      $(".actions [href='#next']").trigger('click');
    }


});

  $('.tab-content .tab-pane:nth-child(1)').addClass('active');
    //setup page
    $('.nav-tabs a.nav-link').on('click', function(e) {

      e.preventDefault();
      // tab-pane
      var tab = $(this).attr('href');
      // console.log(tab.split('#')[1]);

      $('.tab-content .tab-pane').removeClass('active');

      $('.tab-content '+tab).addClass('active');
    });
/********************************Shopee Listing************************************/
  //launching starts
  $('[data-launch-shopee]').on('click', function() {
var launchpacks = [];
var selectedStore = 332671212;//[$('[data-selected-store]').val()];

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

          launchpacks.push([$(this).parents('tr').data('ebx_product_id'),$(this).attr('id').split('_')[1]]);
        }
    });

// console.log(launchpacks);
    // $('[data-launch-shopee]').attr('disabled', true);
    // $('[data-launch-shopee]').find('[data-spinner]').removeClass('hidden');
    // $('[data-spinner-text]').text('Verifying');

    $.ajax({
          url: "/shopee/addListing",
          method: "POST",
          data:{
              "_token": csrfToken,
              'id':launchpacks,
              'store':selectedStore
          },
          dataType:'json',

          success: function (response) {
       console.log(response);
   }});



});


  