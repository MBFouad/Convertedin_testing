<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">Tasks
        @if(isset($task))
            edit
        @else
            Add
        @endif</h4>
</div>
{{ html()->form('POST', route('task.store'))->attributes(['id'=>'formTest'])->open() }}
<div class="modal-body">
    <div class="form-body">
        <div class="form-group m-r-sm">
            <label for="country">Admin:</label>
            {{ html()->select(null, $admins, null)->attributes( ['name' => 'created_by', 'id' => 'created_by', 'class' => 'form-control, chosen-select']) }}
        </div>

        <div class="form-group"><label>{{trans('main.titles.title')}}</label>
            {{ html()->text('title',null)->attributes(['class'=>'form-control','placeholder'=>trans('main.titles.title')]) }}

        </div>
        <div class="form-group"><label> {{trans('main.titles.description')}} </label>
            {{     html()->textarea('description',(isset($request->description))?$request->description:null)
    ->attributes(['class' => 'form-control','size' => '30x5','placeholder'=>trans('main.titles.description')])  }}
        </div>
        <div class="form-group m-r-sm">
            <label for="country">Assign To:</label>
            {{ html()->select('assign_to', $users, null)->attributes( ['name' => 'assign_to', 'id' => 'assign_to', 'class' => 'form-control, chosen-select']) }}
        </div>

    </div>
</div>
<div class="modal-footer">
    <button type="submit" class="btn green" name="status" value="1"
            data-loading-text="{{ trans('main.labels.loading') }}..."
            id="save" data-style="expand-right">{{ trans('main.labels.save') }}
    </button>

    <button type="button" class="btn default"
            name="cancel" data-dismiss="modal">{{ trans('main.labels.cancel') }}
    </button>
</div>
@if(isset($device))
    {{ html()->closeModelForm() }}
@else
    {{ html()->form()->close() }}
@endif
<script>
    window.Chosen.init('.chosen-select', {width: '100%'});
</script>
