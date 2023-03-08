<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use App\Jobs\SendOrderEmail;
use App\Jobs\DashboardOrder;
use App\Jobs\DashboardListing;
use \App\Http\Controllers\Controller;
use \App\Http\Controllers\ListingController as Listing;
use App\Events\OrdersEvent;


use Illuminate\Support\Facades\Mail;
use App\Mail\OrderShipped;
use App\Mylaunchpack;
use App\myOldlisting;
use App\Order;
use Log;

class ReportController extends Controller
{
    use \App\Http\Controllers\HelperController;
    use \App\Http\Controllers\AuthMarketplaceController;
    use \App\Http\Controllers\CalculatorController;

    public function __construct()
    {
     
    }

    public function getInvoiceReport(Request $request)
    {
      
        $column = [];
        $input = $request->all();
        
        if ($request->route('account')) {
          $account = $request->route('account');
        }
        if ($request->route('marketplace')) {
          $marketplace = $request->route('marketplace');
        }
      
        $offsetStr = '';
        if (isset($input['offset'])) {
          $offsetStr = '&offset='.$input["offset"];
        }
        
         $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, 'https://axisnet.asia/memberv2/API/getinvoice.php?marketplace=shopee'.$offsetStr);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          $invoiceStatus = curl_exec($ch);
          curl_close($ch);
         
          $invoiceStatus = json_decode($invoiceStatus,true);
         

          $count = 0;
          $status = array_unique(array_column($invoiceStatus["data"], 'shipment_status'));

            static $count = 0;
         
            foreach ($invoiceStatus["data"] as $key => $value) {
              $count++;
      
              $column[$value['shipment_status']][] = $this->setRowInvoice(
                $value, $count);
            }
            return ['invoice'=>$column,'more'=>$invoiceStatus["more"],'offset'=>$invoiceStatus["offset"]];
      
 }

  public function setRowInvoice($value, $count)
        {
          $invoiceId = [];
          $isCoded = $this->isCoded($invoiceId);
          $action='<a target="_blank" href="/order/ship/'.$value["order_id"].'" data-item-id="'.$value["order_id"].'"><small class="text-muted"><i class="bx bx-show-alt"></i></span></a>';
          ob_start();
      
            ?>
            <tr>
              <td><?php echo $count;?></td>
              <td>
                <fieldset>
                    <div class="checkbox checkbox-info checkbox-glow">
      
                        <input type="checkbox" name="invoice[]" id="ship_<?php echo $value["id"]?>" value="<?php echo $value['id']?>" >
                            <label for="ship_<?php echo $value["id"]?>"></label>
      
                    </div>
                </fieldset>
              </td>
              <td><?php echo $value["id"]?></td>
              <td><?php echo $value["order_id"]?></td>
              
              <td><?php echo $value["shipment_date"]?></td>
              <td><?php echo $value["shipment_mode"]?></td>
              <td><?php echo $value["shipment_status"]?></td>
              <td><?php echo $value["shipping_mode"]?></td>
              <td><?php echo $value["tracking_code"]?></td>
              <td><?php echo $value["shipping_fee"]?></td>
              <td><?php echo $value["final_selling_price"]?></td>
              <td>
        
        <?php echo (isset($value["airwaybill_url"]) ? '<span class="bullet bullet-success bullet-sm"></span>': '<span class="bullet bullet-danger bullet-sm"></span>');?>
       <a target="_blank" href="<?php echo (isset($value["airwaybill_url"]) ? $value["airwaybill_url"]: '');?>">
       <small class="text-muted">
            <?php echo ($value["airwaybill_url"] !='' ? 'View': '-');?>
          </small>
        </a>
      </td>
              <td><?php
                if($isCoded){
                  echo "yes";
                  }else{
                  echo "no";
                  }
                ?>
              </td>
              <td><?php echo $action ?></td>
  
            </tr>
              <?php
           
            $html = ob_get_contents();
            ob_end_clean();
            return $this->minify_html($html);
        }

        Public function isCoded($invoiceId)
        {
          if (!\App\shippedOrder::where(['invoice_id'=> $invoiceId])->exists()) {
            return false;
          }
          return true;
          
        }

         private function minify_html($html)
  {
     $search = array(
      '/(\n|^)(\x20+|\t)/',
      '/(\n|^)\/\/(.*?)(\n|$)/',
      '/\n/',
      '/\<\!--.*?-->/',
      '/(\x20+|\t)/', # Delete multispace (Without \n)
      '/\>\s+\</', # strip whitespaces between tags
      '/(\"|\')\s+\>/', # strip whitespaces between quotation ("') and end tags
      '/=\s+(\"|\')/'); # strip whitespaces between = "'

     $replace = array(
      "\n",
      "\n",
      " ",
      "",
      " ",
      "><",
      "$1>",
      "=$1");

      $html = preg_replace($search,$replace,$html);
      return $html;
  }
}
