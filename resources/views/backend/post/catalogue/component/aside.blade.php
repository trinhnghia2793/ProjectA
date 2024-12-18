<div class="ibox w">
    <div class="ibox-title">
        <h5>{{ __('messages.parent') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="text-danger notice">*{{ __('messages.parentNotice') }}</span>
                    <select name="parent_id" class="form-control setupSelect2" id="">
                        @foreach($dropdown as $key => $val)
                        <option 
                            {{ $key == old('parent_id', (isset($postCatalogue->parent_id)) ? $postCatalogue->parent_id : '') ? 'selected' : '' }}
                            value="{{ $key }}">{{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="ibox w">
    <div class="ibox-title">
        <h5>{{ __('messages.image') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <span class="image img-cover image-target"><img src="{{ (old('image', ($postCatalogue->image) ?? '') ? old('image', ($postCatalogue->image) ?? '') : 'backend/img/not-found.jpg') }}" alt=""></span>
                <input type="hidden" name="image" value="{{ old('image', ($postCatalogue->image) ?? '' ) }}">
            </div>
        </div>
    </div>
</div>

<div class="ibox w">
    <div class="ibox-title">
        <h5>{{ __('messages.advanced') }}</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="mb15">
                    {{-- Chọn trạng thái publish --}}
                    <select name="publish" class="form-control setupSelect2" id="">
                        @foreach (__('messages.publish') as $key => $val)
                            <option 
                                {{ $key == old('publish', (isset($postCatalogue->publish)) ? $postCatalogue->publish : '') ? 'selected' : '' }}
                                value="{{ $key }}"
                            >{{ $val }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Chọn trạng thái follow/unfollow --}}
                <select name="follow" class="form-control setupSelect2" id="">
                    @foreach (__('messages.follow') as $key => $val)
                        <option 
                            {{ $key == old('follow', (isset($postCatalogue->follow)) ? $postCatalogue->follow : '') ? 'selected' : '' }}
                            value="{{ $key }}"
                        >{{ $val }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>