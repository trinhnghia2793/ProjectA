<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th style="width: 50px">
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>{{ __('messages.tableName') }}</th>
            @foreach($languages as $language) 
                @if (Session::get('locale') === $language->canonical)
                    @continue;
                @endif
                <th class="text-center">
                    <span class="image img-scaledown language-flag"><img src="{{ $language->image }}" alt=""></span>
                </th>
            @endforeach
            <th class="text-center" style="width: 100px">{{ __('messages.tableStatus') }}</th>
            <th class="text-center" style="width: 100px">{{ __('messages.tableAction') }}</th>
        </tr>
    </thead>
    <tbody>
        @if(isset($postCatalogues) && is_object($postCatalogues))
            @foreach ($postCatalogues as $postCatalogue)
                <tr>
                    <td>
                        <input type="checkbox" value="{{ $postCatalogue->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        {{-- Với mỗi level > 0 sẽ cài thêm cái '|----' vào để phân cấp bậc --}}
                        {{ str_repeat('|----', ( ($postCatalogue->level > 0) ? ($postCatalogue->level - 1) : 0 )) . $postCatalogue->name }}
                    </td>
                    @foreach($languages as $language) 
                        @if (Session::get('locale') === $language->canonical)
                            @continue;
                        @endif
                        <td class="text-center">
                            <a href="{{ route('language.translate', ['id' => $postCatalogue->id, 'languageId' => $language->id, 'model' => 'PostCatalogue']) }}">Chưa dịch</a>
                        </td>
                    @endforeach
                    <td class="text-center js-switch-{{ $postCatalogue->id }}"> 
                        <input type="checkbox" value="{{ $postCatalogue->publish }}" class="js-switch status" data-field="publish" data-model="{{ $config['model'] }}" {{ ($postCatalogue->publish == 2) ? 'checked' : '' }} data-modelId="{{ $postCatalogue->id }}" />
                    </td>
                    <td class="text-center"> 
                        <a href="{{ route('post.catalogue.edit', $postCatalogue->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('post.catalogue.delete', $postCatalogue->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>        
            @endforeach
        @endif

    </tbody>
</table>

{{ $postCatalogues->links('pagination::bootstrap-4') }}