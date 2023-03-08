<?php

if ($source == 'shopee') {

  $orderStatus = array_keys($data);
  $view = 'shopee.orders';

} else if ($source == 'lazada'){
  $orderStatus = ['completed', 'pending', 'canceled', 'inactive', 'shipped'];
  $view = 'lazada.orders';

} else {
  $orderStatus = ['Completed', 'Active', 'Cancelled', 'Inactive'];
  $view = 'ebay.orders';

}

?>

<?php

  foreach ($orderStatus as $key => $value) {

  ?>

  @include($view, ['data'=>(isset($data[$value])? $data[$value]: ''), 'title'=>$value, 'account'=>$account])


  <?php
  }
?>

