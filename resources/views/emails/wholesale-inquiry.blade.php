<!doctype html>
<html>
<body style="font-family: system-ui, -apple-system, Segoe UI, Roboto, 'Helvetica Neue', Arial, 'Noto Sans', 'Liberation Sans', sans-serif;">
<h2>New Wholesale Inquiry</h2>
<table cellpadding="6" cellspacing="0" border="0" style="background:#f8f9fa;border-radius:8px;">
@foreach($data as $k => $v)
@continue(in_array($k, ['id','created_at','updated_at']))
<tr>
<td style="font-weight:600;text-transform:capitalize;">{{ str_replace('_',' ', $k) }}</td>
<td>{{ $v }}</td>
</tr>
@endforeach
</table>
</body>
</html>