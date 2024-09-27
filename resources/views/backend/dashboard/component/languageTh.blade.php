@foreach($languages as $language) 
    @if (Session::get('locale') === $language->canonical)
        @continue;
    @endif
    <th class="text-center">
        <span class="image img-scaledown language-flag"><img src="{{ $language->image }}" alt=""></span>
    </th>
@endforeach