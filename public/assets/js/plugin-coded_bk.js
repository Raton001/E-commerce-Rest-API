(function ( $ ) {
 
    $.fn.datalist = function( options ) {
    var table = [];
    var checkboxid = 0;
    var selected = [];
    var selectedListing = [];
    var selectedListingData = [];
    var listingName = '';
    

    // Default options
    var settings = $.extend({
        url: 'John Doe',
        offset : 0,
        counter:0,
        target: 'target'


    }, options );

    var client = {

      fireJsonp: function(url, callback) {

        $.ajax({

          type: "GET",
          'url': url,
          dataType: 'jsonp',
          success: function (data) {

            callback(data);

          },
          error: function (msg, b, c) {

            console.debug('error');
            console.debug(msg);
            console.debug(b);
            console.debug(c);
          }
        });

      },

      fireAjax: function(offset, callback) {

       $.ajax({
         url: settings.url,
          method: "POST",
          data:{
              "_token": csrfToken,
              "offset":(offset? offset: settings.offset),
              'pagenumber':settings.counter,
              'brand_id':settings.brand_id,
              'sme_id':settings.sme_id
          },
          dataType:'json',
         error: function(msg, b, c) {

          console.debug('error');
          console.debug(msg);
          console.debug(b);
          console.debug(c);
        },
        dataType: 'json',
        success: function(result) {
  
         callback(result);
         return false;
       },
       type: 'POST'
      });

      },

      /**
       * target DOM element to append the response onto
       * @param  {[type]} response [response from ajax call to be appended]
       * @return {[type]}          [modified DOM]
       */
      target: function (response) {

        // $('#'+settings.target).find('thead').removeClass('hidden');
        $('thead').removeClass('hidden');

        $('[data-submit]').removeClass('hidden');
      
        //fix the brands
        if (response['brands']) {

       
        if (response['brands'].length > 0) {

          $('.portfolio-container').html('');

          $('#portfolio-flters').find('li').not(':first').remove();
          $.each(response['brands'], function(key, value) {
           $('#portfolio-flters').append('<li data-filter=".filter-'+value.id+'">'+value.name+'</li>');

           $('.portfolio-container').css('height', '100% !important');

           /**customize portfolio-item**/
                  var portfolio_item = '<div class="col-lg-12 col-md-6 portfolio-item filter-'+value.id+'" data-filter-'+value.id+'>'

                        +'<table id="target" class="table table-striped" style="width:100%">'
                            +'<thead class="hidden">'
                                +'<tr>'
                                    +'<th>#</th>'
                                    +'<th>'
                                        +'<fieldset>'
                                            +'<div class="checkbox checkbox-info checkbox-glow">'
                                                +'<input type="checkbox" id="ship_all">'
                                                +'<label for="ship_all"></label>'
                                            +'</div>'
                                        +'</fieldset>'
                                    +'</th>'
                                
                                  +'<th>SKU</th>'
                                  +'<th>name</th>'
                                  +'<th>selling price</th>'
                                  +'<th>brand</th>'

                                +'</tr>'
                            +'</thead>'
                            +'<tbody></tbody>'
                        +'</table>'
                        +'</div>'
           /**ends**/

           $('.portfolio-container').append(portfolio_item);
           $('.row.portfolio-container.aos-init.aos-animate').css('height', '100% !important');
          });
        } else {
          if (response['data'].length <= 0) {
           //no brands
            $('.portfolio-container').html('');

            $('#portfolio-flters').find('li').not(':first').remove();
          }
         
        }

         }
        
        //fix the products

        $.each(response['data'], function(key, value) {

         var table_rows = $.trim(value);

         if (!table[key]) {
               table[key] = $('.filter-'+key+' #'+settings.target).DataTable();
 
         }

            table[key].rows.add($(table_rows)).draw();
         
        });

        $('.loading-csc').css('display', 'none');

        settings.counter++;
         //recursion
        if(response['more']) {

          client.updateTable(response['total_data']);
        }

        //adjust the layout
        $('.filter-active').trigger('click');
      },

      /**
       * Calls to ajax to fetch data
       * @param  {[type]} offset [start point to fetch data]
       * @return {[type]}        [response data]
       */
      updateTable: function(offset) {
 
        client.fireAjax(offset, client[settings.target]);

      },

      updateCart: function($this) {

          checkboxid = $($this).attr('id');

          if ($($this).prop("checked")) {

              //check if added into the cart already
              if(jQuery.inArray(checkboxid, selectedListing) === -1) {


                  selected.push($($this).parents('[data-item-col]').find('[data-price]').val());
                  console.log($($this).parents('[data-item-col]').find('[data-price]').val());

                  selectedListing.push(checkboxid);
                  $($this).prop("checked", true);
                  $($this).parents('[data-item-col]').css('background-color', 'rgb(60, 179, 113, 0.5)');

                  listingName = $('#'+checkboxid).parents('[data-item-col]').find('[data-title]').text();

                  selectedListingData.push([{'id':checkboxid, 
                    'listing':listingName, 
                    'price':$('#'+checkboxid).parents('[data-item-col]').find('[data-price]').val()}]);
              }


          } else {
              selected.splice( $.inArray($($this).parents('[data-item-col]').find('[data-price]').val(), selected), 1 );
              selectedListing.splice( $.inArray(checkboxid, selectedListing), 1 );



              $($this).prop("checked", false);
              $($this).parents('[data-item-col]').css('background', 'none');


          }


          sum = 0;
          $.each(selected,function(){sum+=parseFloat(this) || 0;});
          $('[data-price-total]').text(sum.toFixed(2));

          $('[data-qty-total]').text(selectedListing.length);

      },

      init: function() {

        client.updateTable(0);
      }

      };

      /**
       * initialize the modules in the plugin
       */
      client.init();


        //update cart when listing checked
          $('body').on('change', 'input[type=checkbox]', function() {
              client.updateCart(this);

          });
        //by default check all orders
        $('#ship_all').on('change', function() {

           if ($(this).is(":checked")) {

             
                  $('input[type=checkbox]:not(:disabled)').prop('checked', true);
            
               
           } else {

               $('input[type=checkbox]').prop('checked', false);
           }
            
        });

        $('body').on('click', '#portfolio-flters li:not(:first-child)', function() {
          

          var str = $(this).data('filter');
          var id = str.split(".filter-")[1];

          $('.portfolio-item').css({'display': 'none'});
          
          $('.filter-'+id).css({'display': 'block'});
          $(this).addClass('filter-active');

          $('#portfolio-flters li:not(.filter-'+id+')').removeClass('filter-active');
          $('#portfolio-flters li[data-filter=".filter-'+id+'"]').addClass('filter-active');

       });


    };

 
}( jQuery ));