<div class="alert alert-success" role="alert">
    <h4 class="alert-heading">{{ $title ?? __('You will receive money through the information below') }}</h4>
    <p>{!! __('Bank name: <strong>:name</strong>', ['name' => Arr::get($bankInfo, 'name')]) !!}</p>
    <p>{!! __('Bank number: <strong>:number</strong>', ['number' => Arr::get($bankInfo, 'number')]) !!}</p>
    <p>{!! __('Full name: <strong>:name</strong>', ['name' => Arr::get($bankInfo, 'full_name')]) !!}</p>
    <p>{{ __('Description: :description', ['description' => Arr::get($bankInfo, 'description')]) }}</p>
    @isset($link)
        <hr>
        <p class="mb-0">{!! __('You can update in <a href=":link">here</a>', ['link' => $link]) !!}</p>
    @endisset
</div>
