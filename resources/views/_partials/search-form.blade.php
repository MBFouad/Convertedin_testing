@php
    $route = !empty($route) ? $route : null;
    $id = !empty($id) ? $id : 'search-form';
@endphp

{{ html()->form('get', route($route))->attributes(['id' => $id, 'class' => 'form-inline m-b text-right'])->open() }}
<div class="form-group">
    <label>Search:</label>
    {{  html()->text('q', null)->attributes( ['class' => 'form-control'])  }}
</div>

@if(isset($formAppends) && count($formAppends))
    @foreach($formAppends as $key => $value)
        @if(is_array($value))
            @foreach($value as $item)
                <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
            @endforeach
        @else
            {{ html()->hidden($key, $value) }}
        @endif
    @endforeach
@endif
{{ html()->submit('go')->attributes(['class' => 'btn btn-primary', 'style' => 'margin-bottom: 0']) }}

{{ html()->form()->close() }}
