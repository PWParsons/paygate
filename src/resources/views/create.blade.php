<form action="{{ $url }}" method="POST" name="paygate-form">
    <input type="hidden" name="PAY_REQUEST_ID" value="{{ $request_id }}">
    <input type="hidden" name="CHECKSUM" value="{{ $checksum }}">
</form>

<script>
    window.onload = function(){
        document.forms['paygate-form'].submit();
    }
</script>