  <ul class="list-group list-group-flush">
         <li class="list-group-item">
             <div class="row">
            <div class="col-1">

            </div>
            <div class="col-2">
                Name
            </div>
             <div class="col-3">
                Order
            </div>
             
            <div class="col-3"></div>
        </div>
        </li>
    <?php
    $i=0;
    
   
   if ($images) {


 foreach ($images as $key => $image) {


        $i++;
       
      ?>

      <li class="list-group-item">
         
        <div class="row">

           <div class="col-1">
            <div class="row">
                <div class="col-12">
                 <?php echo $i;?>
                    
                </div>
              
            </div>

            </div>
           <div class="col-3">
            
             <div class="user-profile-event">
                <!-- <img src="/assets/images/banner/banner-<?php echo $i;?>.jpg" class="img-fluid" alt="dummy"  style="object-fit: cover;"> -->
                <img src="/coded/public/<?php echo $image;?>" name="existing_images[]" class="img-fluid" alt="dummy"  style="object-fit: cover;">
               
            </div>
              

           </div>

          <div class="col-3">
            <input type="text" name="" class="form-control" value="">
          </div>
          <div class="col-md-2 col-12 form-group">
            <button class="btn btn-icon btn-danger rounded-circle" type="button" data-repeater-delete-attribute>
                <i class="bx bx-x"></i>
            </button>
        </div>
      </div>
      </li>
      <?php
    
    }

       }
    ?>
    </ul>

    <div class="col-12 form-group file-repeater">
    <div data-repeater-list="repeater-list">
        <div data-repeater-item>
            <div class="row mb-2">
                <div class="col-9 col-lg-8 mb-1">
                    <input type="file" name="images[]">
                </div>
                <div class="col-3 col-lg-4 text-lg-right">
                    <button class="btn btn-icon btn-danger" type="button" data-repeater-delete>
                        <i class="bx bx-x"></i>
                        <span class="d-lg-inline-block d-none">Delete</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="col form-group p-0">
        <button class="btn btn-primary" data-repeater-create type="button">
            <i class="bx bx-plus"></i>Add
        </button>
    </div>
</div>