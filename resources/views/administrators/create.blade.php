@php
/** @var \App\Models\User $admin */
@endphp

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">{{trans('administrators.title')}}
        @if(isset($admin))
            edit
        @else
            Add
        @endif</h4>
</div>

@if(isset($admin))
    {{ html()->modelForm($admin, 'patch', route('administrators.update', $admin->id))->attributes(['role' => 'form', 'id' => 'formTest'])->open() }}
@else
    {{ html()->form('POST', route('administrators.store'))->attributes(['role'=>'form', 'id'=>'formTest'])->open() }}
@endif

<div class="modal-body">
    <div class="form-body">
        <div class="form-group"><label>{{trans('administrators.titles.user-name')}}</label>
            {{ html()->text('name',null)->attributes(['class'=>'form-control','placeholder'=>trans('main.titles.name')]) }}
        </div>
        <div class="form-group"><label>{{trans('main.titles.email')}}</label>
            {{ html()->text('email',null)->attributes(['class'=>'form-control','placeholder'=>trans('main.titles.email')]) }}

        </div>
        <div class="form-group">
            <label for="user-type">{{trans('main.titles.user-type')}}</label>
            <select class="form-control" name="user_type" id="user-type">
                <option value="">{{trans('main.titles.user-type')}}</option>
                <option value="{{\App\Utilities\Constants::USER_TYPE_ADMIN}}">{{ trans('administrators.types.admin') }}</option>
                <option value="{{\App\Utilities\Constants::USER_TYPE_TAD}}">{{ trans('administrators.types.tad') }}</option>
                <option value="{{\App\Utilities\Constants::USER_TYPE_HOT_LINE}}">{{ trans('administrators.types.hotline') }}</option>
                <option value="{{\App\Utilities\Constants::USER_TYPE_DISTRIBUTION_MANAGER}}">{{ trans('administrators.types.distribution-manager') }}</option>
                <option value="{{\App\Utilities\Constants::USER_TYPE_INDOOR_SERVICE}}">{{ trans('administrators.types.indoor-service') }}</option>

{{--                <option value="{{\App\Utilities\Constants::USER_TYPES['Convertedin']}}">{{trans('main.titles.Convertedin-group')}}</option>--}}
{{--                <option value="{{\App\Utilities\Constants::USER_TYPES['Management']}}">{{trans('main.titles.Management')}}</option>--}}
            </select>
        </div>

        <div class="form-group">
            <label>{{trans('main.titles.distribution')}}</label>:
            <div>
                <strong>Whitelisted:</strong>
                {{ html()->select('distribution_ids[]', $selectedDistributions ?? [], null)->attributes( ['id' => 'whitelist', 'class'=>'form-control', 'multiple' => 'multiple']) }}
            </div>
            <div style="text-align: center; margin: 15px 0 0 0">
                <button type="button" id="add-to-whitelist"><i class="fa fa-arrow-up"></i></button>
                <button type="button" id="remove-from-whitelist"><i class="fa fa-arrow-down"></i></button>
            </div>
            <div>
                <strong>Blacklisted:</strong>
                {{ html()->select('blacklisted_distribution_ids[]', $distributions, null)->attributes( ['id' => 'blacklist', 'class'=>'form-control', 'multiple' => 'multiple']) }}
            </div>
        </div>

        <div class="form-group">
            <label for="languages_id">{{trans('main.titles.language')}}</label>
            {{ html()->select('languages_id', $languages, null)->attributes( ['class' => 'form-control']) }}
        </div>

        <div class="form-group" id="generate-key-checkbox" style="display:none;">
            <label class="i-checks">
                {{ html()->hidden('can_generate_key', 0) }}
                {{ html()->checkbox('can_generate_key', isset($admin) ? $admin->can_generate_key : false, 1)->attributes( []) }}
                {{ trans('main.titles.can_generate_key') }}
            </label>
        </div>

        <div class="form-group" id="generate-code-checkbox" style="display:none;">
            <label class="i-checks">
                {{ html()->hidden('can_generate_code', 0) }}
                {{ html()->checkbox('can_generate_code', isset($admin) ? $admin->can_generate_code : false, 1)->attributes( []) }}
                {{ trans('main.titles.can_generate_code') }}
            </label>
        </div>

        <div class="form-group">
            <label class="i-checks">
                {{ html()->hidden('can_see_device_map', 0) }}
                {{ html()->checkbox('can_see_device_map', isset($admin) ? $admin->can_see_device_map : false, 1)->attributes( []) }}
                {{ trans('main.titles.can_see_device_map') }}
            </label>
        </div>

        <div class="form-group">
            <label class="i-checks">
                {{ html()->hidden('can_see_service_manuals', 0) }}
                {{ html()->checkbox('can_see_service_manuals', isset($admin) ? $admin->can_see_service_manuals : false, 1)->attributes( []) }}
                {{ trans('main.titles.can_see_service_manuals') }}
            </label>
        </div>

        <div class="form-group">
            <label class="i-checks">
                {{ html()->hidden('can_see_spare_parts', 0) }}
                {{ html()->checkbox('can_see_spare_parts', isset($admin) ? $admin->can_see_spare_parts : false, 1)->attributes( []) }}
                {{ trans('main.titles.can_see_spare_parts') }}
            </label>
        </div>

        <div class="form-group">
            <label class="i-checks">
                {{ html()->hidden('can_see_tech_data', 0) }}
                {{ html()->checkbox('can_see_tech_data', isset($admin) ? $admin->can_see_tech_data : false, 1)->attributes( []) }}
                {{ trans('main.titles.can_see_tech_data') }}
            </label>
        </div>

        <div class="form-group">
            <label class="i-checks">
                {{ html()->hidden('can_receive_emails', 0) }}
                {{ html()->checkbox('can_receive_emails', isset($admin) ? $admin->can_receive_emails : true, 1)->attributes( []) }}
                {{ trans('main.titles.can_receive_emails') }}
            </label>
        </div>

        <div class="form-group">
            <label class="i-checks">
                {{ html()->hidden('can_receive_reg_emails', 0) }}
                {{ html()->checkbox('can_receive_reg_emails', isset($admin) ? $admin->can_receive_reg_emails : false, 1)->attributes( []) }}
                {{ trans('main.titles.can_receive_reg_emails') }}
            </label>
        </div>

        <div class="form-group" id="register-units-to-own-account-checkbox" style="display:none;">
            <label class="i-checks">
                {{ html()->hidden('can_register_units_to_own_account', 0) }}
                {{ html()->checkbox('can_register_units_to_own_account', isset($admin) ? $admin->can_register_units_to_own_account : false, 1)->attributes( ['id' => 'can_register_units_to_own_account']) }}
                {{ trans('main.titles.can_register_units_to_own_account') }}
            </label>
        </div>

        <div class="form-group" id="register-units-to-own-account-amount" style="display:none;">
            <label for="max_amount_units_own_account">{{ trans('main.labels.max_amount_units_own_account') }}</label>
            {{  html()->text('max_amount_units_own_account', null)->attributes( ['class' => 'form-control', 'placeholder' => trans('main.labels.max_amount_units_own_account') ]) }}
        </div>

        <div class="form-group" id="move-own-units-to-customer-checkbox" style="display:none;">
            <label class="i-checks">
                {{ html()->hidden('can_move_own_units_to_customer', 0) }}
                {{ html()->checkbox('can_move_own_units_to_customer', isset($admin) ? $admin->can_move_own_units_to_customer : false, 1)->attributes( []) }}
                {{ trans('main.titles.can_move_own_units_to_customer') }}
            </label>
        </div>

        <div class="form-group" id="move-units-to-customer-checkbox" style="display:none;">
            <label class="i-checks">
                {{ html()->hidden('can_move_units_to_customer', 0) }}
                {{ html()->checkbox('can_move_units_to_customer', isset($admin) ? $admin->can_move_units_to_customer : false, 1)->attributes( []) }}
                {{ trans('main.titles.can_move_units_to_customer') }}
            </label>
        </div>

        <div class="form-group">
            <label for="idle_expiration">{{ trans('main.labels.idle-expiration') }}</label>
            {{  html()->text('idle_expiration', null)->attributes( ['class' => 'form-control', 'placeholder' => trans('main.labels.idle-expiration') ]) }}
        </div>

        <div class="form-group">
            <label for="password">{{ trans('register.password') }}</label>
            {{  html()->password('password')->attributes(['id' => 'password', 'class' => 'form-control', 'placeholder' => trans('register.password') ]) }}
        </div>
        <div class="form-group">
            <label for="password_confirmation">{{ trans('register.password-confirmation') }}</label>
            {{  html()->password('password_confirmation')->attributes(['id' => 'password_confirmation', 'class' => 'form-control', 'placeholder' => trans('register.password-confirmation') ]) }}
        </div>
    </div>
</div>

<div class="modal-footer">
    <button id="submit-btn" type="submit" class="btn green" name="status" value="1"
        data-loading-text="{{ trans('main.labels.loading') }}..."
        id="save" data-style="expand-right">{{ trans('main.labels.save') }}
    </button>

    <button type="button" class="btn default"
        name="cancel" data-dismiss="modal">{{ trans('main.labels.cancel') }}
    </button>
</div>
@if(isset($admin))
    {{ html()->closeModelForm() }}
@else
    {{ html()->form()->close() }}
@endif


<script src="{{asset('js/plugins/iCheck/icheck.min.js')}}"></script>

<script>
    /* global $*/
    $(function () {
        $('#add-to-whitelist').on('click', function() {
            return !$('#blacklist option:selected').remove().appendTo('#whitelist').prop("selected", false);
        });

        $('#blacklist').on('dblclick', function () {
            return !$('#blacklist option:selected').remove().appendTo('#whitelist').prop("selected", false);
        })

        $('#remove-from-whitelist').on('click', function() {
            return !$('#whitelist option:selected').remove().appendTo('#blacklist').prop("selected", false);
        });

        $('#whitelist').on('dblclick', function () {
            return !$('#whitelist option:selected').remove().appendTo('#blacklist').prop("selected", false);
        })

        $('#submit-btn').on('click', function () {
            $('#whitelist option').each(function (key, item) {
                $(item).prop("selected", true);
            })
        });

        @if(isset($admin))
            var isAddNew = false;
        @else
            var isAddNew = true;
        @endif

        // wrappers
        $generateKey = $('#generate-key-checkbox');
        $generateCode = $('#generate-code-checkbox')
        $registerUnitsToOwnAccount = $('#register-units-to-own-account-checkbox');
        $registerUnitsToOwnAccountAmount = $('#register-units-to-own-account-amount');
        $maxAmountUnitsOwnAccount = $registerUnitsToOwnAccountAmount.find('input[name="max_amount_units_own_account"]');
        $moveOwnUnitsToCustomer = $('#move-own-units-to-customer-checkbox');
        $moveUnitsToCustomer = $('#move-units-to-customer-checkbox');

        $('#user-type').on('change', function (event) {

            // hide all dependency set defaults values
            $generateKey.hide();
            $generateCode.hide();
            $registerUnitsToOwnAccount.hide();
            $registerUnitsToOwnAccount.iCheck('uncheck');
            $registerUnitsToOwnAccount.iCheck('update');
            $registerUnitsToOwnAccountAmount.hide();
            $maxAmountUnitsOwnAccount.val('');
            @if(isset($admin) && $admin->can_register_units_to_own_account)
                $maxAmountUnitsOwnAccount.val('{{$admin->max_amount_units_own_account}}');
            @endif

            $moveOwnUnitsToCustomer.hide();
            $moveUnitsToCustomer.hide();

            // show TAD fields
            if ($(event.target).val() === '{{ \App\Utilities\Constants::USER_TYPE_TAD }}'){
                $generateKey.show();
                $registerUnitsToOwnAccount.show();

                @if(!isset($admin))
                    $registerUnitsToOwnAccount.iCheck('check');
                    $registerUnitsToOwnAccount.iCheck('update');
                    $moveOwnUnitsToCustomer.iCheck('check');
                    $moveOwnUnitsToCustomer.iCheck('update');
                @endif

                @if(isset($admin) && $admin->can_register_units_to_own_account)
                    $registerUnitsToOwnAccount.iCheck('check');
                    $registerUnitsToOwnAccount.iCheck('update');
                @endif

                if ($maxAmountUnitsOwnAccount.val().length === 0 || $maxAmountUnitsOwnAccount.val() === '0'){
                    $maxAmountUnitsOwnAccount.val(10);
                }
            }

            // show HotLine fields
            if ($(event.target).val() == {{ \App\Utilities\Constants::USER_TYPE_HOT_LINE }}){
                $generateKey.show();
                $generateCode.show();
                $moveUnitsToCustomer.show();
            }
        });

        @if (isset($admin))
            $('#user-type').val({{ $admin->user_type }});
            $('#user-type').trigger('change');
        @endif

        $('.i-checks').iCheck({
            checkboxClass: 'icheckbox_square-green',
            radioClass: 'iradio_square-green',
        });

        $('#can_register_units_to_own_account')
            .on('ifToggled', function (event) {
                if (event.target.checked) {
                    $('#register-units-to-own-account-amount').show();
                    $('#move-own-units-to-customer-checkbox').show();
                } else {
                    $('#register-units-to-own-account-amount').hide();
                    $('#move-own-units-to-customer-checkbox').hide();
                }
            })
            .trigger('ifToggled');
    });
</script>
