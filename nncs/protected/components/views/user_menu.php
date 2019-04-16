<?php
$accArr = array(AppUrl::URL_ACCOUNT, AppUrl::URL_CASH_ACCOUNT);
$personalArr = array(AppUrl::URL_USER_PROFILE, AppUrl::URL_PASSWORD_CHANGE);
$orderArr = array(AppUrl::URL_PRODUCT_IN, AppUrl::URL_PRODUCT_IN_CREATE, AppUrl::URL_PRODUCT_IN_EDIT, AppUrl::URL_PRODUCT_IN_VIEW, AppUrl::URL_DELIVERY, AppUrl::URL_DELIVERY_CREATE, AppUrl::URL_DELIVERY_ITEM_LIST, AppUrl::URL_DELIVERY_REPORT, AppUrl::URL_DELIVERY_REPORT_DETAIL);
$loanArr = array(AppUrl::URL_LOAN_LIST, AppUrl::URL_LOAN_PAYMENT, AppUrl::URL_LOAN_RECEIVE, AppUrl::URL_LOAN_PENDING, AppUrl::URL_LOAN_SETTING, AppUrl::URL_LOAN_PAYMENT_ADVANCE_LIST);
$ledgerArr = array(AppUrl::URL_LEDGER, AppUrl::URL_LEDGER_INCOME, AppUrl::URL_LEDGER_EXPENSE, AppUrl::URL_LEDGER_BALANCE_SHEET, AppUrl::URL_PROFIT, AppUrl::URL_LEDGER_FINANCE_STATEMENT, AppUrl::URL_LEDGER_HEAD);
$payArr = array(AppUrl::URL_PAYMENT, AppUrl::URL_CUSTOMER_PAYMENT, AppUrl::URL_PAYMENT_LOADING, AppUrl::URL_PAYMENT_UNLOADING, AppUrl::URL_PAYMENT_PALLOT);
$pallotArr = array(AppUrl::URL_PALLOT, AppUrl::URL_PALLOT_CREATE, AppUrl::URL_PALLOT_EDIT, AppUrl::URL_PALLOT_VIEW);
$srinfoArr = array(AppUrl::URL_SR, AppUrl::URL_SR_VIEW);
if (isset(Yii::app()->user->role)):
    ?>
    <ul class="list-group">
        <?php if (in_array(Yii::app()->user->id, [1, 4])) : ?>
            <li class="list-group-item<?php if ($this->currentPage == AppUrl::URL_HISTORY) echo ' active'; ?>">
                <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_HISTORY); ?>"><?php echo Yii::t('strings', 'History'); ?></a>
            </li>
        <?php endif; ?>
        <li class="list-group-item<?php if ($this->currentPage == AppUrl::URL_DASHBOARD) echo ' active'; ?>">
            <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_DASHBOARD); ?>"><?php echo Yii::t('strings', 'Dashboard'); ?></a>
        </li>
    </ul>

    <div class="panel panel-default<?php if (in_array($this->currentPage, $personalArr)) echo ' in'; ?>">
        <div class="panel-heading" role="tab" id="heading1">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
                    <?php echo Yii::t('strings', 'Personal Info'); ?>&nbsp;<span class="caret"></span>
                </a>
            </h4>
        </div>
        <div id="collapse1" class="panel-collapse collapse<?php if (in_array($this->currentPage, $personalArr)) echo ' in'; ?>" role="tabpanel" aria-labelledby="heading1">
            <div class="panel-body no_pad">
                <ul class="list-group sidebar">
                    <li<?php if ($this->currentPage == AppUrl::URL_USER_PROFILE) echo ' class="active"'; ?>>
                        <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_USER_PROFILE); ?>"><?php echo Yii::t('strings', 'Profile'); ?></a>
                    </li>
                    <li<?php if ($this->currentPage == AppUrl::URL_PASSWORD_CHANGE) echo ' class="active"'; ?>>
                        <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_PASSWORD_CHANGE); ?>"><?php echo Yii::t('strings', 'Change Password'); ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="panel panel-default<?php if (in_array($this->currentPage, $accArr)) echo ' in'; ?>">
        <div class="panel-heading" role="tab" id="heading0">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse0" aria-expanded="true" aria-controls="collapse0">
                    <?php echo Yii::t('strings', 'Manage Accounts'); ?>&nbsp;<span class="caret"></span>
                </a>
            </h4>
        </div>
        <div id="collapse0" class="panel-collapse collapse<?php if (in_array($this->currentPage, $accArr)) echo ' in'; ?>" role="tabpanel" aria-labelledby="heading0">
            <div class="panel-body no_pad">
                <ul class="list-group sidebar">
                    <?php if ($this->hasUserAccess('account_list')): ?>
                        <li<?php if ($this->currentPage == AppUrl::URL_ACCOUNT) echo ' class="active"'; ?>>
                            <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_ACCOUNT); ?>"><?php echo Yii::t('strings', 'Bank Account'); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($this->hasUserAccess('cash_account_list')): ?>
                        <li<?php if ($this->currentPage == AppUrl::URL_CASH_ACCOUNT) echo ' class="active"'; ?>>
                            <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_CASH_ACCOUNT); ?>"><?php echo Yii::t('strings', 'Cash Account'); ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <ul class="list-group">
        <?php if ($this->hasUserAccess('product_stock')): ?>
            <li class="list-group-item<?php if ($this->currentPage == AppUrl::URL_STOCK) echo ' active'; ?>">
                <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_STOCK); ?>"><?php echo Yii::t('strings', 'Stocks'); ?></a>
            </li>
        <?php endif; ?>
        <li class="list-group-item<?php if (in_array($this->currentPage, $srinfoArr)) echo ' active'; ?>">
            <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_SR); ?>"><?php echo Yii::t('strings', 'SR Info'); ?></a>
        </li>
    </ul>

    <div class="panel panel-default<?php if (in_array($this->currentPage, $orderArr)) echo ' in'; ?>">
        <div class="panel-heading" role="tab" id="heading2">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="true" aria-controls="collapse2">
                    <?php echo Yii::t('strings', 'Manage Orders'); ?>&nbsp;<span class="caret"></span>
                </a>
            </h4>
        </div>
        <div id="collapse2" class="panel-collapse collapse<?php if (in_array($this->currentPage, $orderArr)) echo ' in'; ?>" role="tabpanel" aria-labelledby="heading2">
            <div class="panel-body no_pad">
                <ul class="list-group sidebar">
                    <?php if ($this->hasUserAccess('entry_list')): ?>
                        <li<?php if (in_array($this->currentPage, [AppUrl::URL_PRODUCT_IN, AppUrl::URL_PRODUCT_IN_CREATE, AppUrl::URL_PRODUCT_IN_EDIT, AppUrl::URL_PRODUCT_IN_VIEW])) echo ' class="active"'; ?>>
                            <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_PRODUCT_IN); ?>"><?php echo Yii::t('strings', 'Product In'); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($this->hasUserAccess('delivery_list')): ?>
                        <li<?php if (in_array($this->currentPage, [AppUrl::URL_DELIVERY, AppUrl::URL_DELIVERY_CREATE, AppUrl::URL_DELIVERY_EDIT])) echo ' class="active"'; ?>>
                            <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_DELIVERY); ?>"><?php echo Yii::t('strings', 'Product Out'); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($this->hasUserAccess('delivery_list')): ?>
                        <li<?php if ($this->currentPage == AppUrl::URL_DELIVERY_ITEM_LIST) echo ' class="active"'; ?>>
                            <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_DELIVERY_ITEM_LIST); ?>"><?php echo Yii::t('strings', 'Delivery Item List'); ?></a>
                        </li>
                    <?php endif; ?>
                    <li<?php if ($this->currentPage == AppUrl::URL_DELIVERY_REPORT) echo ' class="active"'; ?>>
                        <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_DELIVERY_REPORT); ?>"><?php echo Yii::t('strings', 'Delivery Report'); ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="panel panel-default<?php if (in_array($this->currentPage, $loanArr)) echo ' in'; ?>">
        <div class="panel-heading" role="tab" id="heading3">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="true" aria-controls="collapse3">
                    <?php echo Yii::t('strings', 'Manage Loan'); ?>&nbsp;<span class="caret"></span>
                </a>
            </h4>
        </div>
        <div id="collapse3" class="panel-collapse collapse<?php if (in_array($this->currentPage, $loanArr)) echo ' in'; ?>" role="tabpanel" aria-labelledby="heading3">
            <div class="panel-body no_pad">
                <ul class="list-group sidebar">
                    <?php if ($this->hasUserAccess('loan_payment_list')): ?>
                        <li<?php if ($this->currentPage == AppUrl::URL_LOAN_LIST) echo ' class="active"'; ?>>
                            <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LOAN_LIST); ?>"><?php echo Yii::t('strings', 'Loan List'); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($this->hasUserAccess('loan_receive_list')): ?>
                        <li<?php if ($this->currentPage == AppUrl::URL_LOAN_RECEIVE) echo ' class="active"'; ?>>
                            <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LOAN_RECEIVE); ?>_list"><?php echo Yii::t('strings', 'Loan Received List'); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($this->hasUserAccess('loan_payment_list')): ?>
                        <li<?php if ($this->currentPage == AppUrl::URL_LOAN_PAYMENT) echo ' class="active"'; ?>>
                            <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LOAN_PAYMENT); ?>"><?php echo Yii::t('strings', 'Loan Payment'); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($this->hasUserAccess('loan_receive_list')): ?>
                        <li<?php if ($this->currentPage == AppUrl::URL_LOAN_RECEIVE) echo ' class="active"'; ?>>
                            <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LOAN_RECEIVE); ?>"><?php echo Yii::t('strings', 'Loan Received'); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($this->hasUserAccess('loan_setting')): ?>
                        <li<?php if ($this->currentPage == AppUrl::URL_LOAN_SETTING) echo ' class="active"'; ?>>
                            <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LOAN_SETTING); ?>"><?php echo Yii::t('strings', 'Loan Setting'); ?></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>

    <div class="panel panel-default<?php if (in_array($this->currentPage, $ledgerArr)) echo ' in'; ?>">
        <div class="panel-heading" role="tab" id="heading4">
            <h4 class="panel-title">
                <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse4" aria-expanded="true" aria-controls="collapse4">
                    <?php echo Yii::t('strings', 'Ledger'); ?>&nbsp;<span class="caret"></span>
                </a>
            </h4>
        </div>
        <div id="collapse4" class="panel-collapse collapse<?php if (in_array($this->currentPage, $ledgerArr)) echo ' in'; ?>" role="tabpanel" aria-labelledby="heading4">
            <div class="panel-body no_pad">
                <ul class="list-group sidebar">
                    <?php if ($this->hasUserAccess('head_list')): ?>
                        <li class="list-group-item<?php if ($this->currentPage == AppUrl::URL_LEDGER_HEAD) echo ' active'; ?>">
                            <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LEDGER_HEAD); ?>"><?php echo Yii::t('strings', 'Account Heads'); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($this->hasUserAccess('expense_list')): ?>
                        <li class="list-group-item<?php if ($this->currentPage == AppUrl::URL_LEDGER_EXPENSE) echo ' active'; ?>">
                            <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LEDGER_EXPENSE); ?>"><?php echo Yii::t('strings', 'Expense'); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($this->hasUserAccess('balance_sheet')): ?>
                        <li class="list-group-item<?php if ($this->currentPage == AppUrl::URL_LEDGER_BALANCE_SHEET) echo ' active'; ?>">
                            <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LEDGER_BALANCE_SHEET); ?>"><?php echo Yii::t('strings', 'Balance Sheet'); ?></a>
                        </li>
                    <?php endif; ?>
                    <?php if ($this->hasUserAccess('income_list')): ?>
                        <li class="list-group-item<?php if ($this->currentPage == AppUrl::URL_LEDGER_INCOME) echo ' active'; ?>">
                            <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LEDGER_INCOME); ?>"><?php echo Yii::t('strings', 'Income Statement'); ?></a>
                        </li>
                    <?php endif; ?>
                    <li class="list-group-item<?php if ($this->currentPage == AppUrl::URL_LEDGER_FINANCE_STATEMENT) echo ' active'; ?>">
                        <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LEDGER_FINANCE_STATEMENT); ?>"><?php echo Yii::t('strings', 'Financial Statement'); ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <ul class="list-group">
        <?php if ($this->hasUserAccess('settings')): ?>
            <li class="list-group-item<?php if ($this->currentPage == AppUrl::URL_SETTINGS) echo ' active'; ?>">
                <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_SETTINGS); ?>"><?php echo Yii::t('strings', 'Settings'); ?></a>
            </li>
        <?php endif; ?>
        <?php if ($this->hasUserAccess('agent_list')): ?>
            <li class="list-group-item<?php if ($this->currentPage == AppUrl::URL_AGENT) echo ' active'; ?>">
                <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_AGENT); ?>"><?php echo Yii::t('strings', 'Agent List'); ?></a>
            </li>
        <?php endif; ?>
        <?php if ($this->hasUserAccess('customer_list')): ?>
            <li class="list-group-item<?php if ($this->currentPage == AppUrl::URL_CUSTOMER) echo ' active'; ?>">
                <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_CUSTOMER); ?>"><?php echo Yii::t('strings', 'Customer List'); ?></a>
            </li>
        <?php endif; ?>
        <?php if ($this->hasUserAccess('product_type_list')): ?>
            <li class="list-group-item<?php if ($this->currentPage == AppUrl::URL_PRODUCT_TYPE) echo ' active'; ?>">
                <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_PRODUCT_TYPE); ?>"><?php echo Yii::t('strings', 'Product Type List'); ?></a>
            </li>
        <?php endif; ?>
        <?php if ($this->hasUserAccess('location_list')): ?>
            <li class="list-group-item<?php if ($this->currentPage == AppUrl::URL_LOCATION) echo ' active'; ?>">
                <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_LOCATION); ?>"><?php echo Yii::t('strings', 'Stock Locations'); ?></a>
            </li>
        <?php endif; ?>
        <?php if ($this->hasUserAccess('user_list')): ?>
            <li class="list-group-item<?php if ($this->currentPage == AppUrl::URL_USERLIST) echo ' active'; ?>">
                <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_USERLIST); ?>"><?php echo Yii::t('strings', 'User List'); ?></a>
            </li>
        <?php endif; ?>
        <li class="list-group-item">
            <a href="<?php echo Yii::app()->createUrl(AppUrl::URL_USER_LOGOUT); ?>"><?php echo Yii::t('strings', 'Log Out'); ?></a>
        </li>
    </ul>
<?php endif; ?>