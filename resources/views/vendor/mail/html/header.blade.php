<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Beatchain')
<img src="http://127.0.0.1:8000/storage/assets/logo.png" class="logo" alt="Beatchain Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
