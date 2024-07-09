@if(!isset($paginatorAppends) || !is_array($paginatorAppends))
    @php
        $paginatorAppends = [];
    @endphp
@endif

@if(isset($includeSearch) && $includeSearch === true)
    {{ html()->form('GET', null)->attributes(['class' => 'form-inline m-b text-right'])->open() }}
    <div class="form-group">
        <label>Search:</label>
        {{ html()->text('q',null)->attributes(['class' => 'form-control']) }}
    </div>

    @if(count($paginatorAppends))
        @foreach($paginatorAppends as $key => $value)
            @if(is_array($value))
                @foreach($value as $item)
                    <input type="hidden" name="{{ $key }}[]" value="{{ $item }}">
                @endforeach
            @else
                {{ html()->hidden($key,$value) }}
            @endif
        @endforeach
    @endif
    {{ html()->submit('go')->attributes([ 'class' => 'btn btn-primary', 'style' => 'margin-bottom: 0']) }}
    {{ html()->form()->close() }}
@endif

<div class="clearfix">

    @if (!isset($paginatorOnly) || $paginatorOnly !== true)
        <label class="pull-left">
            Show <select id="pp-selector" class="form-control input-sm">
                <option @if ($pp === 10) selected @endif value="{{ updateCurrentUrl(['pp' => 10, 'page' => 1]) }}">10
                </option>
                <option @if ($pp === 25) selected @endif value="{{ updateCurrentUrl(['pp' => 25, 'page' => 1]) }}">25
                </option>
                <option @if ($pp === 50) selected @endif value="{{ updateCurrentUrl(['pp' => 50, 'page' => 1]) }}">50
                </option>
                <option @if ($pp === 100) selected @endif value="{{ updateCurrentUrl(['pp' => 100, 'page' => 1]) }}">
                    100
                </option>
            </select> entries
        </label>
    @endif

    <div class="paginate">
        {{ $paginator->appends(['sort' => $sort, 'asc' => $asc, 'pp' => $pp, 'q' => $q] + $paginatorAppends)->links() }}
    </div>

</div>

@if (!isset($paginatorOnly) || $paginatorOnly !== true)
    <div class="paginate-details">
        Showing {{ number_format(min(($paginator->currentPage() - 1) * $pp + 1, $paginator->total())) }}
        to {{ number_format(min($paginator->currentPage() * $pp, $paginator->total())) }}
        of {{ number_format($paginator->total()) }} entries
    </div>
@endif
