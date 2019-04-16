<div class="content-panel">
    <div id="printDiv">
        <div class="form-group clearfix text-center mp_center media_print show_in_print mp_mt">
            <?php if (!empty($this->settings->title)): ?>
                <h1 style="font-size:22px;margin:0;"><?php echo $this->settings->title; ?></h1>
            <?php endif; ?>
            <?php if (!empty($this->settings->author_address)): ?>
                <?php echo $this->settings->author_address; ?><br>
            <?php endif; ?>
            <h4 class="inv_title"><u><?php echo Yii::t("strings", "Store Summary"); ?></u></h4>
        </div>
        <div class="row clearfix">
            <div class="col-md-6 col-sm-6">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Summary Of Stock</th>
                    </tr>
                    <tr>
                        <td>Total Agent Stock <span class="pull-right"><?php echo ProductIn::model()->sumTotalOfAgent(); ?></span></td>
                    </tr>
                    <tr>
                        <td>Total Office Stock <span class="pull-right"><?php echo ProductIn::model()->sumTotalOfice(); ?></span></td>
                    </tr>
                    <tr>
                        <td>Total Product Entry <span class="pull-right"><?php echo ProductIn::model()->sumTotal(); ?></span></td>
                    </tr>
                    <tr>
                        <td>Total Product Delivery <span class="pull-right"><?php echo DeliveryItem::model()->sumQty(); ?></span></td>
                    </tr>
                    <tr>
                        <td>Total Product Stock <span class="pull-right"><?php echo AppObject::sumStock(); ?></span></td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6 col-sm-6">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Summary Of Loan</th>
                    </tr>
                    <tr>
                        <td>Loan Given <u><?php echo LoanItem::model()->sumTotal(); ?></u> TK on quantity <u><?php echo LoanItem::model()->sumQty(); ?></u></td>
                    </tr>
                    <tr>
                        <td>Loan Received <u><?php echo LoanReceiveItem::model()->sumLoan(); ?></u> TK on quantity <u><?php echo LoanReceiveItem::model()->sumQty(); ?></u></td>
                    </tr>
                    <tr>
                        <td>Interest Received <u><?php echo LoanReceiveItem::model()->sumInterest(); ?></u> TK on quantity <u><?php echo LoanReceiveItem::model()->sumQty(); ?></u></td>
                    </tr>
                    <tr>
                        <td>Net Amount Received <u><?php echo LoanReceiveItem::model()->sumTotalAmount(); ?></u> TK on quantity <u><?php echo LoanReceiveItem::model()->sumQty(); ?></u></td>
                    </tr>
                    <tr>
                        <td>Discount <u><?php echo LoanReceiveItem::model()->sumDiscount(); ?></u> TK on quantity <u><?php echo LoanReceiveItem::model()->sumQty(); ?></u></td>
                    </tr>
                    <tr>
                        <td>Total Amount Received <u><?php echo LoanReceiveItem::model()->sumTotal(); ?></u> TK on quantity <u><?php echo LoanReceiveItem::model()->sumQty(); ?></u></td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6 col-sm-6">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Summary Of Delivery</th>
                    </tr>
                    <tr>
                        <td>Rent Received <u><?php echo DeliveryItem::model()->sumRent(); ?></u> TK on quantity <u><?php echo DeliveryItem::model()->sumQty(); ?></u></td>
                    </tr>
                    <tr>
                        <td>Fan Charge Received <u><?php echo DeliveryItem::model()->sumFanCharge(); ?></u> TK on quantity <u><?php echo DeliveryItem::model()->sumQty(); ?></u></td>
                    </tr>
                    <tr>
                        <td>Net Amount Received <u><?php echo DeliveryItem::model()->sumDeliveryTotal(); ?></u> TK on quantity <u><?php echo DeliveryItem::model()->sumQty(); ?></u></td>
                    </tr>
                    <tr>
                        <td>Discount <u><?php echo DeliveryItem::model()->sumDiscount(); ?></u> TK on quantity <u><?php echo DeliveryItem::model()->sumQty(); ?></u></td>
                    </tr>
                    <tr>
                        <td>Total Amount Received <u><?php echo DeliveryItem::model()->sumTotal(); ?></u> TK on quantity <u><?php echo DeliveryItem::model()->sumQty(); ?></u></td>
                    </tr>
                </table>
            </div>

            <div class="col-md-6 col-sm-6">
                <table class="table table-striped table-bordered">
                    <tr>
                        <th>Summary Of Customer, User and Others</th>
                    </tr>
                    <tr>
                        <td>Listed Agents <span class="pull-right"><?php echo Agent::model()->count(); ?></span></td>
                    </tr>
                    <tr>
                        <td>Listed Customers <span class="pull-right"><?php echo Customer::model()->count(); ?></span></td>
                    </tr>
                    <tr>
                        <td>Listed Product Types <span class="pull-right"><?php echo ProductType::model()->count(); ?></span></td>
                    </tr>
                    <tr>
                        <td>Listed Users <span class="pull-right"><?php echo User::model()->count(); ?></span></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="form-group text-center clearfix">
        <button type="button" class="btn btn-primary btn-xs" onclick="printDiv('printDiv')"><i class="fa fa-print"></i>&nbsp;<?php echo Yii::t("strings", "Print"); ?></button>
    </div>
</div>