@if (isset($page) && $page == "edit")
    {{
       Form::open([
           'route' => ['user-poll.update', $poll->id],
           'method' => 'PUT',
           'id' => 'form_update_poll',
           'role' => 'form',
       ])
    }}
@endif
@foreach ($data['viewData']['settings'] as $settingKey => $settingText)
    <div class="form-group">
        <label>
            <input type="checkbox" name="setting[{{ $settingKey }}]" {{ (isset($page) && ($page == 'edit' || $page == 'duplicate') && array_key_exists($settingKey, $setting)) ? "checked" : "" }}
            value="{{ $settingKey }}" onchange="settingAdvance('{{ $settingKey }}')"> {{ $settingText }}
        </label>
    </div>
    @if ($settingKey == config('settings.setting.custom_link'))
        <div class="form-group {{ (isset($page) && ($page == 'edit' || $page == 'duplicate') && array_key_exists($settingKey, $setting)) ? "" : "setting-advance" }}" id="setting-link">
            <div class="row">
                <div class="col-lg-7">
                    <div class="input-group">
                        <span class="input-group-addon">
                            {{ url('/') . config('settings.email.link_vote') }}
                        </span>
                        {{
                            Form::text('value[link]', (isset($page) && ($page == 'edit' || $page == 'duplicate') && array_key_exists($settingKey, $setting)) ? $setting[$settingKey] : str_random(config('settings.length_poll.link')), [
                                'class' => 'form-control',
                                'id' => 'link',
                                'placeholder' => trans('polls.label.setting.custom_link'),
                                'onkeyup' => 'checkLink()',
                            ])
                        }}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="error_link"></div>
        </div>
    @elseif ($settingKey == config('settings.setting.set_limit'))
        <div class="form-group {{ (isset($page) && ($page == 'edit' || $page == 'duplicate') && array_key_exists($settingKey, $setting)) ? "" : "setting-advance" }}" id="setting-limit">
            <div class="row">
                <div class="col-lg-3">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-list-ol" aria-hidden="true"></i>
                        </span>
                        {{
                           Form::number('value[limit]', (isset($page) && ($page == 'edit' || $page == 'duplicate') && array_key_exists($settingKey, $setting)) ? $setting[$settingKey] : null, [
                               'class' => 'form-control',
                               'id' => 'limit',
                               'min' => 1,
                               'max' => 99,
                               'placeholder' => trans('polls.label.setting.set_limit'),
                               'oninput' => "validity.valid||(value='1');",
                               'onkeyup' => 'checkLimit()',
                           ])
                        }}
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="error_limit"></div>
        </div>
    @elseif ($settingKey == config('settings.setting.set_password'))
        <div class="form-group {{ (isset($page) && ($page == 'edit' || $page == 'duplicate') && array_key_exists($settingKey, $setting)) ? "" : "setting-advance" }}" id="setting-password">
            <div class="row">
                <div class="col-lg-6">
                    <div class="input-group">
                        <span class="input-group-addon">
                            <i class="fa fa-key" aria-hidden="true"></i>
                        </span>
                        {{
                            Form::password('value[password]', [
                                'class' => 'form-control',
                                'id' => 'password',
                                'placeholder' => trans('polls.label.setting.set_password'),
                                'onkeyup' => 'checkPassword()',
                            ])
                        }}
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button" onclick="showAndHidePassword()">
                                <i class="fa fa-eye" aria-hidden="true"></i>
                            </button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <div class="error_password"></div>
        </div>
    @else
       @continue
    @endif
@endforeach
@if (isset($page) && $page == "edit")
    <input type="submit" class="btn btn-success" name="btn_edit" value="{{ trans('polls.button.save_setting') }}">
    {{ Form::close() }}
@endif
