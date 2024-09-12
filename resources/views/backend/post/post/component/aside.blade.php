<div class="ibox w">
    <div class="ibox-title">
        <h5>Chọn danh mục cha</h5>
    </div>
    <div class="ibox-content">

        <div class="row mb15">
            <div class="col-lg-12">
                <div class="form-row">
                    <span class="text-danger notice">*Chọn Root nếu không có danh mục cha</span>
                    <select name="post_catalogue_id" class="form-control setupSelect2" id="">
                        @foreach($dropdown as $key => $val)
                        <option 
                            {{ $key == old('post_catalogue_id', (isset($post->post_catalogue_id)) ? $post->post_catalogue_id : '') ? 'selected' : '' }}
                            value="{{ $key }}">{{ $val }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        @php
        $catalogue = [];
        if(isset($post)) {
            foreach ($post->post_catalogues as $key => $val) {
                $catalogue[] = $val->id;
            }
        }
        @endphp
        <div class="row">
            <div class="col-lg-12">
                <div class="form-row">
                    <label class="control-label">Danh mục phụ</label>
                    <select multiple name="catalogue[]" class="form-control setupSelect2" id="">
                        @foreach($dropdown as $key => $val)
                        <option 
                            @if(is_array(old('catalogue', (isset($catalogue) && count($catalogue)) ? $catalogue : [])) 
                            && in_array($key, old('catalogue', (isset($catalogue)) ? $catalogue : [])) )
                            selected
                            @endif value="{{ $key }}">{{ $val }}</option>
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
        <h5>Chọn ảnh đại diện</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <span class="image img-cover image-target"><img src="{{ (old('image', ($post->image) ?? '') ? old('image', ($post->image) ?? '') : 'backend/img/not-found.jpg') }}" alt=""></span>
                <input type="hidden" name="image" value="{{ old('image', ($post->image) ?? '' ) }}">
            </div>
        </div>
    </div>
</div>

<div class="ibox w">
    <div class="ibox-title">
        <h5>Cấu hình nâng cao</h5>
    </div>
    <div class="ibox-content">
        <div class="row">
            <div class="col-lg-12">
                <div class="mb15">
                    {{-- Chọn trạng thái publish --}}
                    <select name="publish" class="form-control setupSelect2" id="">
                        @foreach (config('apps.general.publish') as $key => $val)
                            <option 
                                {{ $key == old('publish', (isset($post->publish)) ? $post->publish : '') ? 'selected' : '' }}
                                value="{{ $key }}"
                            >{{ $val }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Chọn trạng thái follow/unfollow --}}
                <select name="follow" class="form-control setupSelect2" id="">
                    @foreach (config('apps.general.follow') as $key => $val)
                        <option 
                            {{ $key == old('follow', (isset($post->follow)) ? $post->follow : '') ? 'selected' : '' }}
                            value="{{ $key }}"
                        >{{ $val }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>