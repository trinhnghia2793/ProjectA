<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>
                <input type="checkbox" value="" id="checkAll" class="input-checkbox">
            </th>
            <th>Tên nhóm thành viên</th>
            {{-- <th class="text-center">Tình trạng</th> --}}
            <th class="text-center">Thao tác</th>
        </tr>
    </thead>
    <tbody>
        @if (isset($userCatalogues) && is_object($userCatalogues))
            @foreach ($userCatalogues as $userCatalogue)
                <tr class="row-{{ $userCatalogue->id }}">
                    <td>
                        <input type="checkbox" value="{{ $userCatalogue->id }}" class="input-checkbox checkBoxItem">
                    </td>
                    <td>
                        {{ $userCatalogue->name }}
                    </td>
                    {{-- Publish chưa có --}}
                    {{-- <td class="text-center js-switch-{{ $userCatalogue->id }}"> 
                        <input type="checkbox" value="{{ $userCatalogue->publish }}" class="js-switch status" data-field="publish" data-model="User" {{ ($userCatalogue->publish == 1) ? 'checked' : '' }} data-modelId="{{ $userCatalogue->id }}" />
                    </td> --}}
                    <td class="text-center"> 
                        <a href="{{ route('user.edit', $userCatalogue->id) }}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                        <a href="{{ route('user.delete', $userCatalogue->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>        
            @endforeach
        @endif

    </tbody>
</table>

{{ $userCatalogues->links('pagination::bootstrap-4') }}