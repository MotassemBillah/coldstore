<?php

class AppConstant {

    const MAIL_SENDER_EMAIL = 'rakibhasan880@gmail.com';
    const MAIL_MANDRILL_API = 'yaWRn4R15FlrlfYq9dZe0A';
    const PASSWORD_LENGTH = 6;
    const GENDER_MALE = "Male";
    const GENDER_FEMALE = "Female";
    const INITIAL_AMOUNT = 500;
    const INITIAL_BALANCE = 'Opening Balance';
    const CASH_IN = 'Debit';
    const CASH_OUT = 'Credit';
    const CASH_DEPOSIT = 'diposit';
    const CASH_WITHDRAW = 'withdraw';
    const PAYMENT_CASH = 'Cash Payment';
    const PAYMENT_CHECK = 'Cheque Payment';
    const PAYMENT_NO = 'No Payment';
    //Status
    const USER_STATUS_ACTIVE = 1;
    const USER_STATUS_INACTIVE = 0;
    const USER_STATUS_BLOCKED = 2;
    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    //Roles
    const ROLE_SUPERADMIN = 1;
    const ROLE_ADMIN = 2;
    const ROLE_CUSTOMER = 3;
    const BRANCH_INACTIVE = 0;
    const BRANCH_ACTIVE = 1;
    // String Type Values
    const TYPE_REGULAR = "Regular";
    const TYPE_ADVANCE = 'Advance';
    const TYPE_INVOICE = 'Invoice';
    const TYPE_DUE = 'Due';
    const TYPE_PREVIOUS_DUE = 'Previous due';
    const TYPE_DELIVERY_PAYMENT = 'Delivery Payment';
    const TYPE_DUE_PAYMENT = 'Due Payment';
    const TYPE_LOAN_PAYMENT = 'Loan Payment';
    const STOK_TYPE_PURCHASE = 'Purchase';
    const STOK_TYPE_PURCHASE_RETURN = 'Purchase Return';
    const STOK_TYPE_IN = 'Product In';
    const STOK_TYPE_OUT = 'Product Out';
    const ORDER_PAID = 'Paid';
    const ORDER_COMPLETE = 'Complete';
    const ORDER_DELIVERED = 'Delivered';
    const ORDER_PENDING = 'Pending';
    const ORDER_RETURNED = 'Returned';
    const ORDER_UPDATED = 'Updated';
    const PRODUCT_RETURN = 'Product Return';
    const YES = 'Yes';
    const NO = 'No';
    const LOAN_HEAD_ID = 81;
    const DELIVERY_HEAD_ID = 83;
    // Fixed Heads
    const HEAD_EMPTY_BAG = 50;
    const HEAD_LOAN = 81;
    const HEAD_INTEREST = 82;
    const HEAD_DELIVERY = 83;
    const HEAD_CARRYING = 86;
    const HEAD_FANNYING = 87;

}
