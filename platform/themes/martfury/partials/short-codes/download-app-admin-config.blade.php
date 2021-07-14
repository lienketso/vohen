<div class="form-group">
    <label class="control-label">{{ __('Title') }}</label>
    <input type="text" name="title" data-shortcode-attribute="title" class="form-control" placeholder="{{ __('Title') }}">
</div>

<div class="form-group">
    <label class="control-label">{{ __('Subtitle') }}</label>
    <textarea name="subtitle" data-shortcode-attribute="subtitle" class="form-control" placeholder="{{ __('Subtitle') }}" rows="3"></textarea>
</div>

<div class="form-group">
    <label class="control-label">{{ __('Screenshot') }}</label>
    {!! Form::mediaImage('screenshot', null, ['data-shortcode-attribute' => 'screenshot']) !!}
</div>

<div class="form-group">
    <label class="control-label">{{ __('Android app URL') }}</label>
    <input type="text" name="android_app_url" data-shortcode-attribute="android_app_url" class="form-control" placeholder="{{ __('Android app URL') }}">
</div>

<div class="form-group">
    <label class="control-label">{{ __('iOS app URL') }}</label>
    <input type="text" name="ios_app_url" data-shortcode-attribute="ios_app_url" class="form-control" placeholder="{{ __('iOS app URL') }}">
</div>
