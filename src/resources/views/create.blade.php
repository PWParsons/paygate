<form action="{{ $paygate['url'] }}" method="POST" name="paygate-form">
    <input type="hidden" name="PAY_REQUEST_ID" value="{{ $paygate['request_id'] }}">
    <input type="hidden" name="CHECKSUM" value="{{ $paygate['checksum'] }}">
</form>

<script>
    window.onload = () => document.forms['paygate-form'].submit();
</script>
