<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Beatchain')
<img src="/storage/assets/logo.png" class="logo" alt="Beatchain Logo">
@else
{{ $slot }}
@endif
</a>
</td>
</tr>
