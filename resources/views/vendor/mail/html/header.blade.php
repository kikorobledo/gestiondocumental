<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim($slot) === 'Laravel')
<img src="{{ asset('storage/img/logo.png') }}" class="logo" alt="Logo">
@else
<img src="{{ asset('storage/img/logo.png') }}" style="margin: auto; width:150px"  alt="Logo">
{{ $slot }}
@endif
</a>
</td>
</tr>
