
@php
    $service_id = $api->service_id;
    $password = $api->password;
    $ip_address = request()->ip();
    $currency_code = 'PHP';
    $payment_id = 'FUM' . date('Y') . date('d') . explode(" ", microtime())[1];
    $order_no = $temp->order_tracker_code;
    $page_timeout = '200';
    $merchantreturn = url('') . '/cart';
    $merchantcallback = url('') . '/checkout/callback';
    $merchantapprovalurl =  url('') . '/checkout/success/' . $temp->xtempcode;
    $merchantunapprovalurl =  url('') . '/checkout/failed';
    $amount = number_format($grand_total, 2, '.', '' );
    $customer_name = $temp->xfname . ' ' . $temp->xlname;
    $customer_email = $temp->xemail;
    $customer_phone = $temp->xmobile;
    $string = $password . $service_id . $payment_id . $merchantreturn . $merchantapprovalurl . $merchantunapprovalurl . $merchantcallback . $amount . $currency_code . $ip_address . $page_timeout;
    $hash = hash('sha256', $string);
@endphp
<form action="{{ $api->base_url }}" method="POST">
    <input name="TransactionType" value="SALE" type="hidden">
    <input name="PymtMethod" value="ANY" type="hidden">
    <input name="ServiceID" value="{{ $service_id }}" type="hidden">
    <input name="PaymentID" value="{{ $payment_id }}" type="hidden">
    <input name="OrderNumber" value="{{ $order_no }}" type="hidden">
    <input name="PaymentDesc" value="Fumaco Online Sale / Tracker Code: {{ $order_no }}" type="hidden">
    <input name="MerchantReturnURL" value="{{ $merchantreturn }}" type="hidden">
    <input name="MerchantCallbackURL" value="{{ $merchantcallback }}" type="hidden">
    <input name="MerchantApprovalURL" value="{{ $merchantapprovalurl }}" type="hidden">
    <input name="MerchantUnApprovalURL" value="{{ $merchantunapprovalurl }}" type="hidden">
    <input name="Amount" value="{{ $amount }}" type="hidden">
    <input name="CurrencyCode" value="{{ $currency_code }}" type="hidden">
    <input name="CustIP" value="{{ $ip_address }}" type="hidden">
    <input name="CustName" value="{{ $customer_name }}" type="hidden">
    <input name="CustEmail" value="{{ $customer_email }}" type="hidden">
    <input name="CustPhone" value="{{ $customer_phone }}" type="hidden">
    <input name="PageTimeout" value="{{ $page_timeout }}" type="hidden">
    <input name="HashValue" value="{{ $hash }}" type="hidden">
</form>