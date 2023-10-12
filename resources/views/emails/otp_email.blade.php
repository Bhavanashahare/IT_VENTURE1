{{-- @component('mail::message') --}}
# Hello {{$data['name']}},

{{$data['message']}}

{{-- @component('mail::button', ['url'=>'javascript:void(0)', 'color' => 'success']) --}}
{{ $data['otp'] }}
{{-- @endcomponent --}}

Thanks,<br>
{{ config('app.name') }}
{{-- @endcomponent --}}
