
<!DOCTYPE html>
<html>
<head>
<style>
    .row{margin:2em}
    .address{border-bottom:1px solid black;}
    .companyname{text-align: right; font-size:12px;">}
    #table-header{padding-bottom:10px;}
    #table-footer{padding-top:10px;}
    #table-product th{background-color:black;color:white;text-align:center;}
    #table-product tr:nth-child(odd) td{background-color:white;}
    #table-product tr:nth-child(even) td{background-color:whitesmoke;}
</style>
</head>
<body>

@if(!empty($invoicedata))

    <div class="row">
    <div class="add">
    <div class="card-body address" style="border-bottom:1px solid black;">
    <table id="table-header"width="100%" cellpadding="0" cellspacing="0">
    <tr rowspan="2">
        <td><img src="/assets/images/company_logo/{{$companydata->logo}}" height="80"></td>
        <td  class="companyname">{{$companydata->name}} {{$companydata->reg_no}}</b></td>
        </tr>
    <tr>
        <td></td>
        <td  class="companyname">{{$companydata->address1}} <br>{{$companydata->address2}}<br>
        {{$companydata->postcode}},{{$companydata->city}} {{$stateparam->label}}, Malaysia<br>
        (T) {{$companydata->phone_no}}  (F) {{$companydata->fax_no}}  <br></td>
    </tr>
    </table>
    </div>
    <br>
    <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
    <td width="50%" valign="top" style="">
        <br/><b> BILL TO</b>
            <table cellpadding="0" cellspacing="0">
                    <tr>
                    <td valign="top">Company Name</td>
                    <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                    <td valign="top">{{$invoicedata->customer_name}}</td>
                    </tr>
                    <tr>
                    <td valign="top">Address</td>
                    <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                    <td valign="top">{{$invoicedata->customer_address1}},{{$invoicedata->customer_address2}},{{$invoicedata->customer_postcode}},{{$invoicedata->customer_city}},{{$paramcustomer->label}}</td>
                    </tr>
                    <tr>
                    <td valign="top">Contact Person</td>
                    <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                    <td valign="top">{{$invoicedata->customer_pic}}</td>
                    </tr>
                    <tr>
                    <td valign="top">Contact No</td>
                    <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                    <td valign="top">{{$invoicedata->customer_phone}}</td>
                    </tr>
                    </table>
                </td>
                <td width="50%" valign="top" align="right">
                <table cellpadding="0" cellspacing="0" style="padding-right:1em;">
                <tr><td colspan="3" align="center"><h4><b>INVOICE</b></h4><br/><br/></td></tr>
                </table>
                <table cellpadding="0" cellspacing="0" style="padding-right:1em;">
                <tr>
                <td valign="top">Date<td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top" align="left">{{date('dS M Y', strtotime($invoicedata->doc_dt))}}</td>
                </tr>
                <tr>
                <td valign="top">Invoice No</td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top" align="left">{{$invoicedata->invoice_no}}</td>
                </tr>

            @if (!empty($invoicedata->invoice_no))
                <tr>
                <td valign="top">Customer PO ref</td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top" align="left">{{$invoicedata->po_ref}}</td>
                </tr>
            @endif

            @if(!empty($invoicedata->term))
                <tr>
                <td valign="top">Term</td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top" align="left">{{$daysparam->label}}</td>
                </tr>
                </table>
                </td>
                </tr>
            @endif

                <tr>
                <td valign="top">Issued by</td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top" align="left">{{$user->name}}</td>
                </tr>
                </table>
                </td>
                </tr>

                <tr>
                <td valign="top">Contact No</td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top" align="left">+601{{$user->phone}}</td>
                </tr>
                </table>
                </td>
                </tr>

                </table><br>
                <table width="100%" cellpadding="5" cellspacing="1" border="1" style="background-color:white;" id="table-product">
                <thead>
                <tr>
                    <th width="5%">No</th>
                    <th>Description</th>
                    <th width="10%">Qty</th>
                    <th width="10%">UoM</th>
                    <th width="10%">Unit Price</th>
                    <th width="10%">Discount</th>
                    <th width="15%">Total</th>
                </tr>
                </thead>
                <tbody>

               @if(!empty($items))

                @php
                    $subtotal = 0;
                    $grandtotal = 0;
                    $count =1;
                @endphp
                @foreach ($items as $item )

                 @php
                       $subtotal += $item->total;
                       $grandtotal += $item->total;
                       $products = DB::table('product')->where('id', $item->product_id)->get();
                 @endphp
                   <tr>
                    <td align="center">{{$count++}}</td>
                   @foreach ($products as $product)
                    <td align="center">{{$product->name}}</td>
                   @endforeach

                    <td align="center">{{$item->quantity}}</td>

                    <td align="center">{{$item->uom}}</td>
                    <td align="center">RM {{number_format((float) $item->unit_price, 2, '.', ',')}}</td>
                    <td align="center"> {{$item->discount}}</td>
                    <td align="center">RM {{number_format((float) $item->total, 2, '.', ',')}}</td>
                    </tr>
                @endforeach

              @else
                <tr>
                   <td colspan="7">No record found</td>
                </tr>
             @endif

                </tbody>
                <tfoot>
                 @if ( $invoicedata->tax != 0)
                   @php
                       $tax =  $invoicedata->tax / 100 *  $subtotal;
                       $grandtotal =  $tax + $subtotal;
                   @endphp
                      <tr>
                        <td colspan="6" align="right">Sub Total</td>
                        <td><b>RM  {{number_format((float) $subtotal, 2, '.', ',') }}</b></td>
                        </tr>
                        <tr>
                        <td colspan="6" align="right">Tax {{ $invoicedata->tax}}%</td>
                        <td><b>RM {{number_format((float) $tax, 2, '.', ',')}}</b></td>
                      </tr>
                 @endif
                 <tr>
                    <td colspan="6" align="right">Grand Total</td>
                    <td><b>RM {{number_format((float) $grandtotal, 2, '.', ',')}}</b></td>
                    </tr>

                   </tfoot>
                </table>

                @if (!empty( $invoicedata->remarks))
                <br>Remarks :  {{$invoicedata->remarks}} <br>
                @endif
               @php
                 $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
             @endphp
             <p style="font-size:20px" >{{$f->format($grandtotal)}}</p>
           <hr>

           <div style="font-size:20px;line-height:1.7em;">{!! $note !!}</div>

           <tr><td align="center" height="110"></td></tr>

           @if (!empty($companydata->stamp))
             <tr>
                 <td align="center"> <img src="/assets/images/company_logo/{{$companydata->stamp}}" height="100"></td>
                 <table width="30%">
                 <td style="border-top:1px solid black;" >Authorized Signature</td>
                 </table>
            </tr>
           @endif

           <table id="table-footer" border="0" width="100%" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center"  style="border-top:1px solid black;" style="font-size:15px;">
                    <b>{{$companydata->tagline}} </b><br>
                        {{$companydata->website}}
               </td>
            </tr>
            </table>

    </div>

    </div>
    </div>
@else
    <p> The Requested Page Does Not Exist</p>
@endif

</body>
</html>
