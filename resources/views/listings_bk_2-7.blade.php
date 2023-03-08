@extends('layouts.app')

@section('content')

<!-- DISPLAY ACTIVE UNSOLD LISTING -->
<div class="app-content content">
    <div class="content-overlay"></div>
    <div class="content-wrapper">
        <div class="content-body">
            <section id="active-unsold-table">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            <h4>LISTINGS</h4>
                        </div>
                        <div class="float-right">
                            <a href="/ebay/<?php echo $account;?>/myActivity" type="button" class="btn btn-primary glow invoice-create mr-2 mt-2">My Activity</a>
                        </div>
                    </div>
                    <div class="card-body card-dashboard">
                         <input type="hidden" id="account" value="<?php echo $account;?>">
                                    
                        <button class="btn btn-primary" data-bulk-delete>Bulk Delete</button>
                        <button class="btn btn-primary">Bulk Delete & Re-list</button>
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item  ml-3">
                                <a class="nav-link active" id="activesold-tab" data-toggle="tab" href="#activesold" aria-controls="activesold" role="tab" aria-selected="true">
                                    <i class="bx bx-home align-middle"></i>
                                    <span class="align-middle">Active</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="unsold-tab" data-toggle="tab" href="#unsold" aria-controls="unsold" role="tab" aria-selected="false">
                                    <i class="bx bx-user align-middle"></i>
                                    <span class="align-middle">Unsold</span>
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="activesold" aria-labelledby="activesold-tab" role="tabpanel">   
                                <div class="table-responsive">
                                    <table class="table mb-4 table-bordered table-striped table-hover">
                                <?php 
                                if (isset($selling)) {
                                    foreach ($selling as $key => $value){
                                        if(!isset($value['ActiveList'])){
                                ?>

                                <div class="mt-5 text-center">
                                    <h3>ACTIVE LISTING IS EMPTY</h3>
                                </div>

                                <?php 
                                        }
                                        else{
                                ?>
                                        <thead class="thead-dark">
                                            <col>
                                            <colgroup span="2"></colgroup>
                                            <colgroup span="2"></colgroup>
                                            <tr>
                                                <td rowspan="2"></td>
                                                <td rowspan="2">
                                                    <center>Image</center>
                                                </td>
                                                <td rowspan="2">
                                                    <center>Title</center>
                                                </td>
                                                <th colspan="2" scope="colgroup">
                                                    <center>Quantity</center>
                                                </th>
                                                <td rowspan="2">
                                                    <center>Price</center>
                                                </td>
                                                <td rowspan="2">
                                                    <center>Action</center>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="col">
                                                    <center>Available</center>
                                                </th>
                                                <th scope="col">
                                                    <center>Sold</center>
                                                </th>
                                            </tr>
                                        </thead>

                                <?php 
                                        }
                                    }
                                }
                                ?>
                                    
                                        <tbody>
                                            <?php
                                            if (isset($selling)) {
                                                $count = 0;
                                                foreach ($selling as $key => $value) {
                                                    if (isset($value['ActiveList'])) {
                                                        $data = $value['ActiveList']['ItemArray']['Item'];
                                                        $value2 = $value['ActiveList']['ItemArray']['Item'];
                                                        $entries = $value['ActiveList']['PaginationResult']['TotalNumberOfEntries'];
                                                        if ($entries == 1) {
                                                            $count++;

                                            ?>
                                                            <tr id="<?php echo $value2['ItemID']; ?>">

                                                                <td>
                                                                    <div class="checkbox">
                                                                        <input type="checkbox" class="checkbox-input" data-bulk-list id="mainCheckBox_<?php echo $value2['ItemID']; ?>">
                                                                        <label for="mainCheckBox_<?php echo $value2['ItemID']; ?>"></label>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <?php
                                                                    if (isset($value2['PictureDetails'])) {
                                                                    ?>
                                                                        <img src="<?php echo $value2['PictureDetails']['GalleryURL']; ?>" style='width: 60px;'>

                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </td>

                                                                <td data-listing-title-wrapper>
                                                                    <input data-listing-title type="text" name="title" value="<?php echo $value2['Title']; ?>" style="border:none; background:none;outline: none;width: 600px;"><br />
                                                                    <a href="#" class="hidden" data-update-title>Update</a>
                                                                    {{-- <?php
                                                                        if(in_array(,$package_id,TRUE)){
                                                                    ?>
                                                                        <div class="row float-right"><span class="badge badge-primary">CODED</span></div>
                                                                    <?php
                                                                        }
                                                                    ?> --}}
                                                                </td>

                                                                <td>
                                                                    <input style="border:none;background:none; outline: none;width: 50px;" type="number" name="quantity" value="<?php echo (isset($value2['QuantityAvailable']) ? $value2['QuantityAvailable'] : ''); ?>">
                                                                </td>

                                                                <td>
                                                                    <?php
                                                                    if (isset($value2['SellingStatus']['QuantitySold'])) {
                                                                        echo $value2['SellingStatus']['QuantitySold'];
                                                                    }
                                                                    ?>
                                                                </td>

                                                                <td>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">$</span>
                                                                        </div>
                                                                        <input data-listing-price data-selling-price type="text" name="sellingPrice" style="width: 80px;" value="<?php echo $value2['BuyItNowPrice']; ?>">
                                                                        <div class="input-group-append">
                                                                            <div data-calculator class="livicon-evo" data-options=" name: calculator.svg; style: solid; size: 30px;"></div>
                                                                            <div class="spinner-grow spinner-grow text-light hidden" role="status" data-spinner>
                                                                                <span class="sr-only">Loading...</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div data-price-simulator class="hidden">
                                                                        <div class='form-group'>
                                                                            <div class="input-group">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">g</span>
                                                                                </div>
                                                                                <input type='text' placeholder="weight" data-weight class='form-control' value='' style='border:1px solid black;'>
                                                                            </div>
                                                                        </div>

                                                                        <div class='form-group'>
                                                                            <div class="input-group">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">RM</span>
                                                                                </div>
                                                                                <input type='text' placeholder="total cost in RM" data-total-cost readonly value='' class='form-control'>
                                                                            </div>
                                                                        </div>

                                                                        <div class='form-group'>
                                                                            <div class="input-group">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">$</span>
                                                                                </div>
                                                                                <input type='text' placeholder="total cost in USD" data-total-cost-usd readonly value='' class='form-control'>
                                                                            </div>
                                                                        </div>

                                                                        <!--profit-->
                                                                        <div class='form-group'>
                                                                            <div class="input-group">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">RM</span>
                                                                                </div>
                                                                                <input type='text' placeholder="Net profit" data-netprofit value='' class='form-control'>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <table>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="dropdown my-auto">
                                                                                    <box-icon class="cursor-pointer dropdown-toggle nav-hide-arrow cursor-pointer" name='dots-vertical-rounded' id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></box-icon>
                                                                                    <span class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                                                        <a class="dropdown-item" href="/ebay/<?php echo $key; ?>/listing/<?php echo $value2['ItemID']; ?>" alt="view">
                                                                                            View
                                                                                        </a>
                                                                                        <a class="dropdown-item" href="/ebay/<?php echo $key; ?>/listing/edit/<?php echo $value2['ItemID']; ?>" alt="edit">
                                                                                            Edit
                                                                                        </a>
                                                                                        <a class="dropdown-item" href="JavaScript:void(0);">
                                                                                            Re-List
                                                                                        </a>
                                                                                        <a class="dropdown-item" href="JavaScript:void(0);">
                                                                                            Sell Similar
                                                                                        </a>
                                                                                        <a class="dropdown-item" data-promo id="<?php echo $value2['ItemID']; ?>" alt="Promotion">
                                                                                            Create Promotion
                                                                                        </a>
                                                                                        <a class="dropdown-item" data-delete-itemid href="/ebay/<?php echo $key; ?>/listing/end/<?php echo $value2['ItemID']; ?>" alt="Delete">
                                                                                            Delete
                                                                                        </a>
                                                                                    </span>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        } else {
                                                            foreach ($data as $key2 => $value2) {
                                                                $count++;
                                                            ?>
                                                                <tr id="<?php echo $value2['ItemID']; ?>">
                                                                    <td>
                                                                        <div class="checkbox">
                                                                            <input type="checkbox" class="checkbox-input" data-bulk-list id="mainCheckBox_<?php echo $value2['ItemID']; ?>">
                                                                            <label for="mainCheckBox_<?php echo $value2['ItemID']; ?>"></label>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <div class="d-flex justify-content-center">
                                                                            <?php
                                                                            if (isset($value2['PictureDetails'])) {
                                                                            ?>
                                                                                <img src="<?php echo $value2['PictureDetails']['GalleryURL']; ?>" style='width: 60px;'>

                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </div>

                                                                    </td>

                                                                    <td data-listing-title-wrapper>
                                                                        <input data-listing-title type="text" name="title" value="<?php echo $value2['Title']; ?>" style="border:none; background:none;outline: none;width: 600px;"><br />
                                                                        <a href="#" class="hidden" data-update-title>Update</a>
                                                                    </td>

                                                                    <td>
                                                                        <input style="border:none;background:none; outline: none;width: 50px;" type="number" name="quantity" value="<?php echo (isset($value2['QuantityAvailable']) ? $value2['QuantityAvailable'] : ''); ?>">
                                                                    </td>

                                                                    <td>
                                                                        <?php
                                                                        if (isset($value2['SellingStatus']['QuantitySold'])) {
                                                                            echo $value2['SellingStatus']['QuantitySold'];
                                                                        }
                                                                        ?>
                                                                    </td>

                                                                    <td>
                                                                        <div class="input-group">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text">$</span>
                                                                            </div>
                                                                            <input data-listing-price data-selling-price type="text" name="sellingPrice" style="width: 80px;" value="<?php echo $value2['BuyItNowPrice']; ?>">
                                                                            <div class="input-group-append">
                                                                                <div data-calculator class="livicon-evo" data-options=" name: calculator.svg; style: solid; size: 30px;"></div>
                                                                                <div class="spinner-grow spinner-grow text-light hidden" role="status" data-spinner>
                                                                                    <span class="sr-only">Loading...</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div data-price-simulator class="hidden">
                                                                            <div class='form-group'>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text">g</span>
                                                                                    </div>
                                                                                    <input type='text' placeholder="weight" data-weight class='form-control' value='' style='border:1px solid black;'>
                                                                                </div>
                                                                            </div>

                                                                            <div class='form-group'>

                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text">RM</span>
                                                                                    </div>
                                                                                    <input type='text' placeholder="total cost in RM" data-total-cost readonly value='' class='form-control'>
                                                                                </div>
                                                                            </div>

                                                                            <div class='form-group'>

                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text">$</span>
                                                                                    </div>
                                                                                    <input type='text' placeholder="total cost in USD" data-total-cost-usd readonly value='' class='form-control'>
                                                                                </div>
                                                                            </div>

                                                                            <!--profit-->
                                                                            <div class='form-group'>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text">RM</span>
                                                                                    </div>
                                                                                    <input type='text' placeholder="Net profit" data-netprofit value='' class='form-control'>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <table>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="dropdown my-auto">
                                                                                        <box-icon class="cursor-pointer dropdown-toggle nav-hide-arrow cursor-pointer" name='dots-vertical-rounded' id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></box-icon>
                                                                                        <span class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                                                            <a class="dropdown-item" href="/ebay/<?php echo $key; ?>/listing/<?php echo $value2['ItemID']; ?>" alt="view">
                                                                                                View
                                                                                            </a>
                                                                                            <a class="dropdown-item" href="/ebay/<?php echo $key; ?>/listing/edit/<?php echo $value2['ItemID']; ?>" alt="edit">
                                                                                                Edit
                                                                                            </a>
                                                                                            <a class="dropdown-item" href="JavaScript:void(0);">
                                                                                                Re-List
                                                                                            </a>
                                                                                            <a class="dropdown-item" href="JavaScript:void(0);">
                                                                                                Sell Similar
                                                                                            </a>
                                                                                            <a class="dropdown-item" data-promo id="<?php echo $value2['ItemID']; ?>" alt="Promotion">
                                                                                                Create Promotion
                                                                                            </a>
                                                                                            <a class="dropdown-item" data-delete-itemid href="/ebay/<?php echo $key; ?>/listing/end/<?php echo $value2['ItemID']; ?>" alt="Delete">
                                                                                                Delete
                                                                                            </a>
                                                                                        </span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane" id="unsold" aria-labelledby="unsold-tab" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table mb-4 table-bordered table-striped table-hover">
                                        <?php 
                                if (isset($selling)) {
                                    foreach ($selling as $key => $value){
                                        if(!isset($value['UnsoldList'])){
                                ?>

                                <div class="mt-5">
                                    <center><h3>UNSOLD LISTING EMPTY</h3></center>
                                </div>

                                <?php 
                                        }
                                        else{
                                ?>
                                        <thead class="thead-dark">
                                            <col>
                                            <colgroup span="2"></colgroup>
                                            <colgroup span="2"></colgroup>
                                            <tr>
                                                <td rowspan="2"></td>
                                                <td rowspan="2">
                                                    <center>Image</center>
                                                </td>
                                                <td rowspan="2">
                                                    <center>Title</center>
                                                </td>
                                                <th colspan="2" scope="colgroup">
                                                    <center>Quantity</center>
                                                </th>
                                                <td rowspan="2">
                                                    <center>Price</center>
                                                </td>
                                                <td rowspan="2">
                                                    <center>Action</center>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="col">
                                                    <center>Available</center>
                                                </th>
                                                <th scope="col">
                                                    <center>Sold</center>
                                                </th>
                                            </tr>
                                        </thead>

                                <?php 
                                        }
                                    }
                                }
                                ?>
                                        <tbody>
                                            <?php
                                            if (isset($selling)) {
                                               
                                                $count = 0;
                                                foreach ($selling as $key => $value) {
                                                    if (isset($value['UnsoldList'])) {
                                                        $data = $value['UnsoldList']['ItemArray']['Item'];
                                                        $value2 = $value['UnsoldList']['ItemArray']['Item'];
                                                        $entries = $value['UnsoldList']['PaginationResult']['TotalNumberOfEntries'];
                                                        if ($entries == 1) {
                                                            $count++;

                                            ?>
                                                            <tr id="<?php echo $value2['ItemID']; ?>">

                                                                <td>
                                                                    <div class="checkbox">
                                                                        <input type="checkbox" class="checkbox-input" data-bulk-list id="mainCheckBox_<?php echo $value2['ItemID']; ?>">
                                                                        <label for="mainCheckBox_<?php echo $value2['ItemID']; ?>"></label>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <?php
                                                                    if (isset($value2['PictureDetails'])) {
                                                                    ?>
                                                                        <img src="<?php echo $value2['PictureDetails']['GalleryURL']; ?>" style='width: 60px;'>

                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </td>

                                                                <td data-listing-title-wrapper>
                                                                    <input data-listing-title type="text" name="title" value="<?php echo $value2['Title']; ?>" style="border:none; background:none;outline: none;width: 600px;"><br />
                                                                    <a href="#" class="hidden" data-update-title>Update</a>
                                                                </td>

                                                                <td>
                                                                    <input style="border:none;background:none; outline: none;width: 50px;" type="number" name="quantity" value="<?php echo (isset($value2['QuantityAvailable']) ? $value2['QuantityAvailable'] : ''); ?>">
                                                                </td>

                                                                <td>
                                                                    <?php
                                                                    if (isset($value2['SellingStatus']['QuantitySold'])) {
                                                                        echo $value2['SellingStatus']['QuantitySold'];
                                                                    }
                                                                    ?>
                                                                </td>

                                                                <td>
                                                                    <div class="input-group">
                                                                        <div class="input-group-prepend">
                                                                            <span class="input-group-text">$</span>
                                                                        </div>
                                                                        <input data-listing-price data-selling-price type="text" name="sellingPrice" style="width: 80px;" value="<?php echo $value2['BuyItNowPrice']; ?>">
                                                                        <div class="input-group-append">
                                                                            <div data-calculator class="livicon-evo" data-options=" name: calculator.svg; style: solid; size: 30px;"></div>
                                                                            <div class="spinner-grow spinner-grow text-light hidden" role="status" data-spinner>
                                                                                <span class="sr-only">Loading...</span>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div data-price-simulator class="hidden">
                                                                        <div class='form-group'>
                                                                            <div class="input-group">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">g</span>
                                                                                </div>
                                                                                <input type='text' placeholder="weight" data-weight class='form-control' value='' style='border:1px solid black;'>
                                                                            </div>
                                                                        </div>

                                                                        <div class='form-group'>

                                                                            <div class="input-group">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">RM</span>
                                                                                </div>
                                                                                <input type='text' placeholder="total cost in RM" data-total-cost readonly value='' class='form-control'>
                                                                            </div>
                                                                        </div>

                                                                        <div class='form-group'>

                                                                            <div class="input-group">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">$</span>
                                                                                </div>
                                                                                <input type='text' placeholder="total cost in USD" data-total-cost-usd readonly value='' class='form-control'>
                                                                            </div>
                                                                        </div>

                                                                        <!--profit-->
                                                                        <div class='form-group'>
                                                                            <div class="input-group">
                                                                                <div class="input-group-prepend">
                                                                                    <span class="input-group-text">RM</span>
                                                                                </div>
                                                                                <input type='text' placeholder="Net profit" data-netprofit value='' class='form-control'>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>

                                                                <td>
                                                                    <table>
                                                                        <tr>
                                                                            <td>
                                                                                <div class="dropdown my-auto">
                                                                                    <box-icon class="cursor-pointer dropdown-toggle nav-hide-arrow cursor-pointer" name='dots-vertical-rounded' id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></box-icon>
                                                                                    <span class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                                                        <a class="dropdown-item" href="/ebay/<?php echo $key; ?>/listing/<?php echo $value2['ItemID']; ?>" alt="view">
                                                                                            View
                                                                                        </a>
                                                                                        <a class="dropdown-item" href="/ebay/<?php echo $key; ?>/listing/edit/<?php echo $value2['ItemID']; ?>" alt="edit">
                                                                                            Edit
                                                                                        </a>
                                                                                        <a class="dropdown-item" href="JavaScript:void(0);">
                                                                                            Re-List
                                                                                        </a>
                                                                                        <a class="dropdown-item" href="JavaScript:void(0);">
                                                                                            Sell Similar
                                                                                        </a>
                                                                                        <a class="dropdown-item" data-promo id="<?php echo $value2['ItemID']; ?>" alt="Promotion">
                                                                                            Create Promotion
                                                                                        </a>
                                                                                        <a class="dropdown-item" data-delete-itemid href="/ebay/<?php echo $key; ?>/listing/end/<?php echo $value2['ItemID']; ?>" alt="Delete">
                                                                                            Delete
                                                                                        </a>
                                                                                    </span>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                            <?php
                                                        } else {
                                                            foreach ($data as $key2 => $value2) {
                                                                $count++;
                                                            ?>
                                                                <tr id="<?php echo $value2['ItemID']; ?>">
                                                                    <td>
                                                                        <div class="checkbox">
                                                                            <input type="checkbox" class="checkbox-input" data-bulk-list id="mainCheckBox_<?php echo $value2['ItemID']; ?>">
                                                                            <label for="mainCheckBox_<?php echo $value2['ItemID']; ?>"></label>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <div class="d-flex justify-content-center">
                                                                            <?php
                                                                            if (isset($value2['PictureDetails'])) {
                                                                            ?>
                                                                                <img src="<?php echo $value2['PictureDetails']['GalleryURL']; ?>" style='width: 60px;'>

                                                                            <?php
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                    </td>

                                                                    <td data-listing-title-wrapper>
                                                                        <input data-listing-title type="text" name="title" value="<?php echo $value2['Title']; ?>" style="border:none; background:none;outline: none;width: 600px;"><br />
                                                                        <a href="#" class="hidden" data-update-title>Update</a>
                                                                    </td>

                                                                    <td>
                                                                        <input style="border:none;background:none; outline: none;width: 50px;" type="number" name="quantity" value="<?php echo (isset($value2['QuantityAvailable']) ? $value2['QuantityAvailable'] : ''); ?>">
                                                                    </td>

                                                                    <td>
                                                                        <?php
                                                                        if (isset($value2['SellingStatus']['QuantitySold'])) {
                                                                            echo $value2['SellingStatus']['QuantitySold'];
                                                                        }
                                                                        ?>
                                                                    </td>

                                                                    <td>
                                                                        <div class="input-group">
                                                                            <div class="input-group-prepend">
                                                                                <span class="input-group-text">$</span>
                                                                            </div>
                                                                            <input data-listing-price data-selling-price type="text" name="sellingPrice" style="width: 80px;" value="<?php echo $value2['BuyItNowPrice']; ?>">
                                                                            <div class="input-group-append">
                                                                                <div data-calculator class="livicon-evo" data-options=" name: calculator.svg; style: solid; size: 30px;"></div>
                                                                                <div class="spinner-grow spinner-grow text-light hidden" role="status" data-spinner>
                                                                                    <span class="sr-only">Loading...</span>
                                                                                </div>
                                                                            </div>
                                                                        </div>

                                                                        <div data-price-simulator class="hidden">
                                                                            <div class='form-group'>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text">g</span>
                                                                                    </div>
                                                                                    <input type='text' placeholder="weight" data-weight class='form-control' value='' style='border:1px solid black;'>
                                                                                </div>
                                                                            </div>

                                                                            <div class='form-group'>

                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text">RM</span>
                                                                                    </div>
                                                                                    <input type='text' placeholder="total cost in RM" data-total-cost readonly value='' class='form-control'>
                                                                                </div>
                                                                            </div>

                                                                            <div class='form-group'>

                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text">$</span>
                                                                                    </div>
                                                                                    <input type='text' placeholder="total cost in USD" data-total-cost-usd readonly value='' class='form-control'>
                                                                                </div>
                                                                            </div>

                                                                            <!--profit-->
                                                                            <div class='form-group'>
                                                                                <div class="input-group">
                                                                                    <div class="input-group-prepend">
                                                                                        <span class="input-group-text">RM</span>
                                                                                    </div>
                                                                                    <input type='text' placeholder="Net profit" data-netprofit value='' class='form-control'>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </td>

                                                                    <td>
                                                                        <table>
                                                                            <tr>
                                                                                <td>
                                                                                    <div class="dropdown my-auto">
                                                                                        <box-icon class="cursor-pointer dropdown-toggle nav-hide-arrow cursor-pointer" name='dots-vertical-rounded' id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" role="menu"></box-icon>
                                                                                        <span class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                                                                            <a class="dropdown-item" href="/ebay/<?php echo $key; ?>/listing/<?php echo $value2['ItemID']; ?>" alt="view">
                                                                                                View
                                                                                            </a>
                                                                                            <a class="dropdown-item" href="/ebay/<?php echo $key; ?>/listing/edit/<?php echo $value2['ItemID']; ?>" alt="edit">
                                                                                                Edit
                                                                                            </a>
                                                                                            <a class="dropdown-item" href="JavaScript:void(0);">
                                                                                                Re-List
                                                                                            </a>
                                                                                            <a class="dropdown-item" href="JavaScript:void(0);">
                                                                                                Sell Similar
                                                                                            </a>
                                                                                            <a class="dropdown-item" data-promo id="<?php echo $value2['ItemID']; ?>" alt="Promotion">
                                                                                                Create Promotion
                                                                                            </a>
                                                                                            <a class="dropdown-item" data-delete-itemid href="/ebay/<?php echo $key; ?>/listing/end/<?php echo $value2['ItemID']; ?>" alt="Delete">
                                                                                                Delete
                                                                                            </a>
                                                                                        </span>
                                                                                    </div>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                        <?php
                                                            }
                                                        }
                                                        ?>
                                            <?php
                                                    }
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</div>
<!-- END DISPLAY ACTIVE UNSOLD LISTING -->
<!--  DEMO CHAT    -->
<div class="widget-chat-demo">
    <!-- widget chat demo footer button start -->
    <button class="btn btn-primary chat-demo-button glow px-1"><i class="livicon-evo" data-options="name: calculator.svg; style: lines; size: 24px; strokeColor: #fff; autoPlay: true; repeat: loop;"></i></button>
    <!-- widget chat demo footer button ends -->

    <!-- widget chat demo start -->
    <div class="widget-chat widget-chat-demo d-none">
        <form class="d-flex" id="calculatorForm" action="javascript:void(0);">
            <div class="card mb-0">
                <div class="card-header border-bottom p-0">
                    <div class="media m-75">
                        <a href="JavaScript:void(0);">
                            <div class="avatar mr-75">
                                <img src="../../../app-assets/images/portrait/small/avatar-s-2.jpg" alt="avtar images" width="32" height="32">
                                <span class="avatar-status-online"></span>
                            </div>
                        </a>
                        <div class="media-body">
                            <h6 class="media-heading mb-0 pt-25"><a href="javaScript:void(0);">Calculator config</a></h6>
                            <span class="text-muted font-small-3">Active</span>
                        </div>
                    </div>
                    <div class="heading-elements">
                        <i class="bx bx-x widget-chat-close float-right my-auto cursor-pointer"></i>
                    </div>
                </div>
                <div class="card-body widget-chat-container widget-chat-demo-scroll" style="overflow-y: scroll;">
                    <div class="chat-content">
                        <div class="chat">
                            <div class="chat-body">
                                <input type="hidden" id="calculatorID" value="<?php echo $config['id']; ?>">
                                <!--calculator content starts-->
                                <?php
                                $url = "ebay/config/calculator/edit/" . $config['id'];
                                ?>
                                <!--  <form action="{{ url($url) }}" method="post" id="listingForm">

                        @csrf  -->

                                <table>

                                    <tr>
                                        <th>Fuel Surcharge</th>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="fuel_surcharge" class="form-control" value="<?php echo (isset($config['fuel_surcharge']) ? $config['fuel_surcharge'] : ''); ?>">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Conversion Rate</th>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="conversion_rate" class="form-control" value="<?php echo (isset($config['conversion_rate']) ? $config['conversion_rate'] : ''); ?>">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Foreign Currency Rate</th>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="foreign_currency_rate" class="form-control" value="<?php echo (isset($config['foreign_currency_rate']) ? $config['foreign_currency_rate'] : ''); ?>">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Listing Fees</th>
                                        <td>
                                            <table>

                                                <tr>
                                                    <td>
                                                        ebay no store
                                                        <div class="form-group">
                                                            <input type="text" name="ebay_no_store" class="form-control" class="form-control" value="<?php echo (isset($config['ebay_no_store']) ? $config['ebay_no_store'] : '0.35'); ?>">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        ebay starter
                                                        <div class="form-group">
                                                            <input type="text" name="ebay_starter" class="form-control" class="form-control" value="<?php echo (isset($config['ebay_starter']) ? $config['ebay_starter'] : '0.30'); ?>">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        ebay basic auction fixed price
                                                        <div class="form-group">
                                                            <input type="text" name="ebay_basic_auction_fixed_price" class="form-control" class="form-control" value="<?php echo (isset($config['ebay_basic_auction_fixed_price']) ? $config['ebay_basic_auction_fixed_price'] : '0.25'); ?>">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        ebay premium auction
                                                        <div class="form-group">
                                                            <input type="text" name="ebay_premium_auction" class="form-control" class="form-control" value="<?php echo (isset($config['ebay_premium_auction']) ? $config['ebay_premium_auction'] : '0.15'); ?>">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        ebay fixed price
                                                        <div class="form-group">
                                                            <input type="text" name="ebay_premium_fixed_price" class="form-control" class="form-control" value="<?php echo (isset($config['ebay_premium_fixed_price']) ? $config['ebay_premium_fixed_price'] : '0.05'); ?>">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        ebay anchor auction
                                                        <div class="form-group">
                                                            <input type="text" name="ebay_anchor_auction" class="form-control" class="form-control" value="<?php echo (isset($config['ebay_anchor_auction']) ? $config['ebay_anchor_auction'] : '0.10'); ?>">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>
                                                        ebay anchor fixed price
                                                        <div class="form-group">
                                                            <input type="text" name="ebay_anchor_fixed_price" class="form-control" class="form-control" value="<?php echo (isset($config['ebay_anchor_fixed_price']) ? $config['ebay_anchor_fixed_price'] : '0.05'); ?>">
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>

                                                    <td>
                                                        Paypal Off Ebay
                                                        <div class="form-group">
                                                            <input type="text" name="paypal_off_ebay" class="form-control" class="form-control" value="<?php echo (isset($config['paypal_off_ebay']) ? $config['paypal_off_ebay'] : '0.00'); ?>">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Referal Fees(%)</th>
                                        <td>
                                            <table>
                                                <tr>

                                                    <td>
                                                        ebay_referal_no_store
                                                        <div class="form-group">
                                                            <input type="text" name="ebay_referal_no_store" class="form-control" class="form-control" value="<?php echo (isset($config['ebay_referal_no_store']) ? $config['ebay_referal_no_store'] : '0.00'); ?>">
                                                        </div>
                                                    </td>
                                                </tr>

                                                <tr>

                                                    <td>
                                                        ebay_referal_any_store
                                                        <div class="form-group">
                                                            <input type="text" name="ebay_referal_any_store" class="form-control" class="form-control" value="<?php echo (isset($config['ebay_referal_any_store']) ? $config['ebay_referal_any_store'] : '0.00'); ?>">
                                                        </div>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>SST</th>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="sst" class="form-control" class="form-control" value="<?php echo (isset($config['sst']) ? $config['sst'] : ''); ?>">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Payment Gateway Fee</th>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="payment_gateway_fee" class="form-control" class="form-control" value="<?php echo (isset($config['payment_gateway_fee']) ? $config['payment_gateway_fee'] : ''); ?>">
                                            </div>
                                        </td>
                                    </tr>

                                    <tr>
                                        <th>Handling Fee</th>
                                        <td>
                                            <div class="form-group">
                                                <input type="text" name="handling_fee" class="form-control" class="form-control" value="<?php echo (isset($config['handling_fee']) ? $config['handling_fee'] : ''); ?>">
                                            </div>
                                        </td>
                                    </tr>


                                </table>
                                <!-- </form> -->
                            </div>
                        </div>
                        <!--calculator content ends-->
                    </div>
                </div>
                <div class="card-footer border-top p-1">


                    <button type="submit" class="btn btn-primary glow px-1" data-calculator-config-submit>Save</button>

                </div>
            </div>
        </form>
    </div>
    <!-- widget chat demo ends -->
</div>
