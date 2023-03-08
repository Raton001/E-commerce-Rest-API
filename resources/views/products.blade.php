@extends('layouts.app')

@section('assets')

@endsection

@section('title')
   Products
@endsection


@section('content')
<!--sme-->
 <div class="row">
     <div class="col-4">
         <select>
            <?php 
            foreach ($sme as $key => $value) {
               ?>
               <option><?php echo $value->name;?></option>
               <?php
            }
            ?>
         </select>
     </div>
 </div>

<!--products-->
 <!--data table starts-->
<table id="target" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <!--standard columns-->
            <th>#</th>
            <th>
                <fieldset>
                    <div class="checkbox checkbox-info checkbox-glow">
                        <input type="checkbox" id="ship_all">
                        <label for="ship_all"></label>
                    </div>
                </fieldset>
            </th>
              <th>Name</th>
              <th>Price</th>
              <th>Brand</th>
              <th>SKU</th>
        
        </tr>
        
    </thead>
    <tbody>
        <?php
        $i=0;
        foreach ($data as $key => $product) {
            $i++;
         ?>
         <tr>
             <td><?php echo $i;?></td>
             <td>
                  <fieldset>
                    <div class="checkbox checkbox-info checkbox-glow">
                        <input type="checkbox" id="ship_<?php echo $i;?>">
                        <label for="ship_<?php echo $i;?>"></label>
                    </div>
                </fieldset>
             </td>
             <td><?php echo $product->name;?></td>
             <td><?php echo $product->selling_price;?></td>
             <td><?php echo $product->brand_name;?></td>
             <td><?php echo $product->sku;?></td>

         </tr>
         <?php
        }

        ?>
    </tbody>
</table>
<!--data table ends-->

@endsection

@section('footer-scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#target').DataTable();
    });
</script>
@endsection


