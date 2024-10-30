    <form method="POST" action="{{$data['QPUrl']}}" name="redirectForm">
        <input type="hidden" name="Action" id="Action" value="{{$data['Action']}}"/>
        <input type="hidden" name="Amount" id="Amount" value="{{$data['Amount']}}"/>
        <input type="hidden" name="BankID" id="BankID" value="{{$data['BankID']}}"/>
        <input type="hidden" name="CurrencyCode" id="CurrencyCode" value="{{$data['CurrencyCode']}}"/>
        <input type="hidden" name="Lang" id="Lang" value="{{$data['Lang']}}"/>
        <input type="hidden" name="MerchantID" id="MerchantID" value="{{$data['MerchantID']}}"/>
        <input type="hidden" name="MerchantModuleSessionID" id="MerchantModuleSessionID"
               value="{{$data['MerchantModuleSessionID']}}"/>
        <input type="hidden" name="NationalID" id="NationalID" value="{{$data['NationalID']}}"/>
        <input type="hidden" name="PUN" id="PUN" value="{{$data['Pun']}}"/>
        <input type="hidden" name="Quantity" id="Quantity" value="{{$data['Qantity']}}"/>
        <input type="hidden" name="PaymentDescription" id="PaymentDescription" value="{{$data['PaymentDescription']}}"/>
        <input type="hidden" name="TransactionRequestDate" id="TransactionRequestDate"
               value="{{$data['TransactionRequestDate']}}"/>
        <input type="hidden" name="SecureHash" id="SecureHash" value="{{$data['secureHash']}}"/>
    </form>


<script>
window.onload = function(){
  document.forms['redirectForm'].submit();
}
</script>
