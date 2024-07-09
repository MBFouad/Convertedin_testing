<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">{{trans('administrators.title')}} | #{{$admin->id}} - {{$admin->name}}</h4>
</div>
<div class="modal-body">
    <div class="form-body">
        <div class="row">
            <div class="col-md-3">{{trans('administrators.titles.user-name')}} : </div>
            <div class="col-md-9">{{$admin->name}}</div>
        </div>
        <div class="clearfix visible-xs-block"></div>
        <div class="row">
            <div class="col-md-3">{{trans('main.titles.email')}} : </div>
            <div class="col-md-9">{{$admin->email}}</div>
        </div>
        <div class="clearfix visible-xs-block"></div>
        <div class="row">
            <div class="col-md-3">{{trans('administrators.titles.user-type')}} :</div>
            <div class="col-md-9">{{$admin->user_type_translated}}</div>
        </div>
        <div class="clearfix visible-xs-block"></div>
        <div class="row">
            <div class="col-md-3">{{trans('main.titles.language')}} :</div>
            <div class="col-md-9">{{$admin->language->name ?? 'EN'}}</div>
        </div>
        <div class="clearfix visible-xs-block"></div>
        <div class="row">
            <div class="col-md-3">{{trans('administrators.titles.status')}} :</div>
            <div class="col-md-9">{{$admin->status_translated}}</div>
        </div>
        <div class="clearfix visible-xs-block"></div>
        <div class="row">
            <div class="col-md-3">{{trans('administrators.titles.idle-expiration')}} :</div>
            <div class="col-md-9">{{$admin->idle_expiration}} days</div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn default"
            name="cancel" data-dismiss="modal">{{ trans('main.labels.close') }}
    </button>
</div>