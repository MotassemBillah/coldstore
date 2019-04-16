<?php

class AppUrl {

    //Error
    const URL_INSTALL = '/install';
    //Error
    const URL_ERROR = '/error';
    const URL_ERROR_MESSAGE = '/error/message';
    //URL Account
    const URL_ACCOUNT = '/account';
    const URL_ACCOUNT_CREATE = '/account/create';
    const URL_ACCOUNT_EDIT = '/account/edit';
    const URL_ACCOUNT_BALANCE = '/account/balance';
    const URL_ACCOUNT_BALANCE_DEPOSIT = '/account/balance_deposit';
    const URL_ACCOUNT_BALANCE_DEPOSIT_EDIT = '/account/balance_deposit_edit';
    const URL_ACCOUNT_BALANCE_WITHDRAW = '/account/balance_withdraw';
    const URL_ACCOUNT_BALANCE_WITHDRAW_EDIT = '/account/balance_withdraw_edit';
    const URL_ACCOUNT_DELETE = '/account/delete';
    const URL_ACCOUNT_DELETEALL = '/account/deleteall';
    //URL Cash Account
    const URL_CASH_ACCOUNT = '/cash_account';
    const URL_CASH_ACCOUNT_DEPOSIT = '/cash_account/deposit';
    const URL_CASH_ACCOUNT_DEPOSIT_EDIT = '/cash_account/deposit_edit';
    const URL_CASH_ACCOUNT_WITHDRAW = '/cash_account/withdraw';
    const URL_CASH_ACCOUNT_WITHDRAW_EDIT = '/cash_account/withdraw_edit';
    const URL_CASH_ACCOUNT_VOUCHER = '/cash_account/view';
    // Dashboard And Settings
    const URL_DASHBOARD = '/dashboard';
    const URL_VISITORS = '/dashboard/visitors';
    //URL Password
    const URL_PASSWORD_FORGET = '/password';
    const URL_PASSWORD_ACTIVATE = '/password/activate';
    const URL_PASSWORD_CHANGE = '/password/change';
    const URL_PASSWORD_RECOVER = '/password/recover';
    const URL_PASSWORD_RESET = '/password/reset';
    //URL Users
    const URL_LOGIN = '/login';
    const URL_USERLIST = '/user';
    const URL_USER_CREATE = '/user/create';
    const URL_USER_EDIT = '/user/edit';
    const URL_USER_ADMIN_EDIT = '/user/admin_edit';
    const URL_USER_DELETE = '/user/delete';
    const URL_USER_DELETEALL = '/user/deleteall';
    const URL_USER_ACTIVATE = '/user/activate';
    const URL_USER_DEACTIVATE = '/user/deactivate';
    const URL_USER_PROFILE = '/user/profile';
    const URL_USER_PERMISSION = '/user/permission';
    const URL_USER_DETAILS = '/user/details';
    const URL_USER_LOGOUT = '/user/logout';
    const URL_USER_ACTIVATION = '/user/activation';
    const URL_USER_REMOVE_LOGIN = '/user/remove_login';
    //URL Site
    const URL_SITE = '/site';
    const URL_SITE_MESSAGE = '/site/message';
    const URL_SITE_CONTACT = '/site/contact';
    const URL_CLEAR_CACHE = '/site/clear_cache';
    //URL Banks
    const URL_BANK = '/bank';
    const URL_BANK_CREATE = '/bank/create';
    const URL_BANK_EDIT = '/bank/edit';
    const URL_BANK_DELETE = '/bank/delete';
    const URL_BANK_DELETEALL = '/bank/deleteall';
    //URL Categories
    const URL_CATEGORIES = '/category';
    const URL_CATEGORIES_CREATE = '/category/create';
    const URL_CATEGORIES_EDIT = '/category/edit';
    const URL_CATEGORIES_DELETE = '/category/delete';
    const URL_CATEGORIES_DELETEALL = '/category/deleteall';
    // Dues
    const URL_DUES = '/dues';
    const URL_DUES_DELETE = '/dues/delete';
    const URL_DUES_DELETEALL = '/dues/deleteall';
    //URL Company
    const URL_COMPANY = '/company';
    const URL_COMPANY_CREATE = '/company/create';
    const URL_COMPANY_EDIT = '/company/edit';
    const URL_COMPANY_PAYMENT = '/company/payment';
    const URL_COMPANY_PAYMENT_CREATE = '/company/payment_create';
    const URL_COMPANY_PAYMENT_EDIT = '/company/payment_edit';
    const URL_COMPANY_DELETE = '/company/delete';
    const URL_COMPANY_DELETEALL = '/company/deleteall';
    const URL_COMPANY_ACTIVATE = '/company/activate';
    const URL_COMPANY_META_DELETE = '/company/deletemeta';
    //URL Agents
    const URL_AGENT = '/agent';
    const URL_AGENT_CREATE = '/agent/create';
    const URL_AGENT_EDIT = '/agent/edit';
    const URL_AGENT_LOAN = '/agent/loan';
    const URL_AGENT_LOAN_EDIT = '/agent/loan_edit';
    const URL_AGENT_LOAN_CREATE_ADV = '/agent/advance_loan_create';
    const URL_AGENT_LEDGER = '/agent/ledger';
    //URL Dealer
    const URL_CUSTOMER = '/customer';
    const URL_CUSTOMER_CREATE = '/customer/create';
    const URL_CUSTOMER_EDIT = '/customer/edit';
    const URL_CUSTOMER_DELETE = '/customer/delete';
    const URL_CUSTOMER_DELETEALL = '/customer/deleteall';
    const URL_CUSTOMER_LOAN = '/customer/loan';
    const URL_CUSTOMER_LOAN_CREATE = '/customer/loan_create';
    const URL_CUSTOMER_LOAN_CREATE_ADV = '/customer/advance_loan_create';
    const URL_CUSTOMER_PAYMENT = '/customer_payment';
    const URL_CUSTOMER_PAYMENT_CREATE = '/customer_payment/create';
    const URL_CUSTOMER_PAYMENT_CREATE_DELIVERY = '/customer_payment/create_delivery';
    const URL_CUSTOMER_PAYMENT_CREATE_DUE = '/customer_payment/create_due';
    const URL_CUSTOMER_PAYMENT_CREATE_LOAN = '/customer_payment/create_loan';
    const URL_CUSTOMER_PAYMENT_EDIT = '/customer_payment/edit';
    const URL_CUSTOMER_LEDGER = '/customer/ledger';
    //URL Permission
    const URL_PERMISSION = '/permission';
    const URL_PERMISSION_CREATE = '/permission/create';
    const URL_PERMISSION_EDIT = '/permission/edit';
    const URL_PERMISSION_DELETE = '/permission/delete';
    const URL_PERMISSION_DELETEALL = '/permission/deleteall';
    //URL Products
    const URL_STOCK = '/stock';
    const URL_PRODUCT_IN = '/product_in';
    const URL_PRODUCT_IN_CREATE = '/product_in/create';
    const URL_PRODUCT_IN_EDIT = '/product_in/edit';
    const URL_PRODUCT_IN_VIEW = '/product_in/view';
    const URL_PRODUCT_IN_INVOICE = '/product_in/invoice';
    const URL_PRODUCT_OUT = '/product_out';
    const URL_PRODUCT_OUT_CREATE = '/product_out/create';
    const URL_PRODUCT_OUT_EDIT = '/product_out/edit';
    const URL_PRODUCT_OUT_VIEW = '/product_out/view';
    const URL_PRODUCT_OUT_INVOICE = '/product_out/invoice';
    const URL_DELIVERY = '/delivery';
    const URL_DELIVERY_ITEM_LIST = '/delivery/item_list';
    const URL_DELIVERY_CREATE = '/delivery/create';
    const URL_DELIVERY_EDIT = '/delivery/edit';
    const URL_DELIVERY_VIEW = '/delivery/view';
    const URL_DELIVERY_INVOICE = '/delivery/invoice';
    const URL_DELIVERY_CREATE_SINGLE = '/delivery/single';
    const URL_DELIVERY_EDIT_SINGLE = '/delivery/single_edit';
    const URL_DELIVERY_VIEW_SINGLE = '/delivery/single_view';
    const URL_DELIVERY_REPORT = '/delivery/report';
    const URL_DELIVERY_REPORT_DETAIL = '/delivery/report_detail';
    //URL Products Sizess
    const URL_PRODUCT_TYPE = '/product_type';
    const URL_PRODUCT_TYPE_CREATE = '/product_type/create';
    const URL_PRODUCT_TYPE_EDIT = '/product_type/edit';
    //URL Products Location
    const URL_LOCATION = '/location';
    const URL_LOCATION_CREATE = '/location/create';
    const URL_LOCATION_CREATE_ROOM = '/location/create_room';
    const URL_LOCATION_CREATE_FLOOR = '/location/create_floor';
    const URL_LOCATION_CREATE_POCKET = '/location/create_pocket';
    const URL_LOCATION_EDIT = '/location/edit';
    const URL_LOCATION_EDIT_ROOM = '/location/edit_room';
    const URL_LOCATION_EDIT_FLOOR = '/location/edit_floor';
    const URL_LOCATION_EDIT_POCKET = '/location/edit_pocket';
    const URL_LOCATION_DELETE = '/location/delete';
    const URL_LOCATION_DELETEALL = '/location/deleteall';
    //URL Role
    const URL_ROLE = '/role';
    const URL_ROLE_CREATE = '/role/create';
    const URL_ROLE_EDIT = '/role/edit';
    const URL_ROLE_DELETE = '/role/delete';
    const URL_ROLE_DELETEALL = '/role/deleteall';
    const URL_ROLE_ACTIVATE = '/role/activate';
    //URL Purchases
    const URL_PURCHASE = '/purchase';
    const URL_PURCHASE_CREATE = '/purchase/create';
    const URL_PURCHASE_EDIT = '/purchase/edit';
    const URL_PURCHASE_DELETE = '/purchase/delete';
    const URL_PURCHASE_DELETE_ALL = '/purchase/deleteall';
    const URL_PURCHASE_PROCESS = '/purchase/process';
    const URL_PURCHASE_VIEW = '/purchase/view';
    const URL_PURCHASE_ITEMS = '/purchase/items';
    const URL_PURCHASE_PAYMENT = '/purchase/payment';
    const URL_PURCHASE_ITEM_STATUS = '/purchase/change_item_status';
    const URL_PURCHASE_TRUNCATE = '/purchase/truncate_data';
    const URL_PURCHASE_RESET = '/purchase/reset';
    //URL Sales
    const URL_SALE = '/sales';
    const URL_SALE_CREATE = '/sales/create';
    const URL_SALE_EDIT = '/sales/edit';
    const URL_SALE_DELETE = '/sales/delete';
    const URL_SALE_DELETE_ALL = '/sales/deleteall';
    const URL_SALE_PROCESS = '/sales/process';
    const URL_SALE_VIEW = '/sales/view';
    const URL_SALE_ITEMS = '/sales/items';
    const URL_SALE_PAYMENT = '/sales/payment';
    const URL_SALE_ITEM_STATUS = '/sales/change_item_status';
    const URL_SALE_CART = '/sales/cart';
    const URL_SALE_RESET = '/sales/reset';
    const URL_SALERETURN = '/sales_return';
    const URL_SALERETURN_CREATE = '/sales_return/create';
    const URL_SALERETURN_EDIT = '/sales_return/edit';
    const URL_SALERETURN_VIEW = '/sales_return/view';
    //URL Reports
    const URL_REPORT = '/report';
    //URL Payment
    const URL_PAYMENT = '/payments';
    const URL_PAYMENT_CREATE = '/payments/create';
    const URL_PAYMENT_EDIT = '/payments/edit';
    const URL_PAYMENT_DELETE = '/payments/delete';
    const URL_PAYMENT_DELETEALL = '/payments/deleteall';
    const URL_PAYMENT_OPTIONS = '/payments/options';
    const URL_PAYMENT_LOADING = '/payments/list_loading';
    const URL_PAYMENT_LOADING_NEW = '/payments/new_loading';
    const URL_PAYMENT_LOADING_EDIT = '/payments/edit_loading';
    const URL_PAYMENT_UNLOADING = '/payments/list_unloading';
    const URL_PAYMENT_UNLOADING_NEW = '/payments/new_unloading';
    const URL_PAYMENT_UNLOADING_EDIT = '/payments/edit_unloading';
    const URL_PAYMENT_PALLOT = '/payments/pallot';
    const URL_PAYMENT_PALLOT_NEW = '/payments/new_pallot';
    const URL_PAYMENT_PALLOT_EDIT = '/payments/edit_pallot';
    //URL Settings
    const URL_SETTINGS = '/settings';
    //URL Ledger
    const URL_LEDGER = '/ledger';
    const URL_LEDGER_HEAD = '/ledger/head';
    const URL_LEDGER_HEAD_CREATE = '/ledger/head/create';
    const URL_LEDGER_HEAD_EDIT = '/ledger/head/edit';
    const URL_LEDGER_HEAD_VIEW = '/ledger/head/view';
    const URL_LEDGER_INCOME = '/ledger/income';
    const URL_LEDGER_EXPENSE = '/ledger/expense';
    const URL_LEDGER_EXPENSE_CREATE = '/ledger/expense/create';
    const URL_LEDGER_EXPENSE_EDIT = '/ledger/expense/edit';
    const URL_LEDGER_EXPENSE_VIEW = '/ledger/expense/view';
    const URL_LEDGER_BALANCE_SHEET = '/ledger/balancesheet';
    const URL_LEDGER_BALANCE_SHEET_UPDATE = '/ledger/balancesheet/update';
    const URL_LEDGER_SETTINGS = '/ledger/settings';
    const URL_LEDGER_FINANCE_STATEMENT = '/ledger/statement';
    //URL Profit
    const URL_PROFIT = '/ledger/profit';
    const URL_PROFIT_CREATE = '/ledger/profit/create';
    const URL_PROFIT_EDIT = '/ledger/profit/edit';
    const URL_PROFIT_DELETEALL = '/ledger/profit/deleteall';
    // Cold Storage url
    const URL_LOAN = '/loan';
    const URL_LOAN_LIST = '/loan';
    const URL_LOAN_SETTING = '/loan/setting';
    const URL_LOAN_PAYMENT = '/loan/payment';
    const URL_LOAN_PAYMENT_CREATE = '/loan/payment_create';
    const URL_LOAN_PAYMENT_EDIT = '/loan/payment_edit';
    const URL_LOAN_PAYMENT_VIEW = '/loan/payment_view';
    const URL_LOAN_PAYMENT_ADVANCE_LIST = '/loan/payment_advance_list';
    const URL_LOAN_PAYMENT_ADVANCE_CREATE = '/loan/payment_advance_create';
    const URL_LOAN_PAYMENT_ADVANCE_EDIT = '/loan/payment_advance_edit';
    const URL_LOAN_PAYMENT_EDIT_SINGLE = '/loan/single_edit';
    const URL_LOAN_PAYMENT_VIEW_SINGLE = '/loan/single_view';
    const URL_LOAN_RECEIVE = '/loan/receive';
    const URL_LOAN_RECEIVE_CREATE = '/loan/receive_create';
    const URL_LOAN_RECEIVE_EDIT = '/loan/receive_edit';
    const URL_LOAN_RECEIVE_VIEW = '/loan/receive_view';
    const URL_LOAN_RECEIVE_VIEW_SINGLE = '/loan/receive_single_view';
    const URL_LOAN_PENDING = '/loan/pending';
//    const URL_LOAN = '/loan';
//    const URL_LOAN_LIST = '/loan/list';
//    const URL_LOAN_SETTING = '/loan/setting';
//    const URL_LOAN_PAYMENT = '/loan/payment';
//    const URL_LOAN_PAYMENT_CREATE = '/loan/payment/create';
//    const URL_LOAN_PAYMENT_EDIT = '/loan/payment/edit';
//    const URL_LOAN_PAYMENT_VIEW = '/loan/payment/view';
//    const URL_LOAN_PAYMENT_ADVANCE_LIST = '/loan/payment/advance_list';
//    const URL_LOAN_PAYMENT_ADVANCE_CREATE = '/loan/payment/advance_create';
//    const URL_LOAN_PAYMENT_ADVANCE_EDIT = '/loan/payment/advance_edit';
//    const URL_LOAN_PAYMENT_EDIT_SINGLE = '/loan/payment/single_edit';
//    const URL_LOAN_PAYMENT_VIEW_SINGLE = '/loan/payment/single_view';
//    const URL_LOAN_RECEIVE = '/loan/receive';
//    const URL_LOAN_RECEIVE_CREATE = '/loan/receive/create';
//    const URL_LOAN_RECEIVE_EDIT = '/loan/receive/edit';
//    const URL_LOAN_RECEIVE_VIEW = '/loan/receive/view';
//    const URL_LOAN_PENDING = '/loan/pending';
    // History url
    const URL_HISTORY = '/history';
    const URL_HISTORY_VIEW = '/history/view';
    const URL_HISTORY_CLEAR = '/history/clear';
    const URL_HISTORY_DELETE = '/history/delete';
    // Palot url
    const URL_PALLOT = '/pallot';
    const URL_PALLOT_CREATE = '/pallot/create';
    const URL_PALLOT_EDIT = '/pallot/edit';
    const URL_PALLOT_VIEW = '/pallot/view';
    const URL_PALLOT_DELETE = '/pallot/delete';
    // SR Controller
    const URL_SR = '/sr';
    const URL_SR_VIEW = '/sr/view';

}
