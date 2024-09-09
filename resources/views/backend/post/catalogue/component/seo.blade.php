<div class="ibox">
    <div class="ibox-title">
        <h5>Cấu hình SEO</h5>
    </div>
    <div class="ibox-content">
        <div class="seo-container">

            {{--
                (1) Kiểm tra giá trị old('meta-title') từ lần submit trước, có thì sẽ hiển thị
                (2) Nếu rỗng, kiểm tra nếu tồn tại $postCatalouge->meta_title thì sẽ hiển thị (edit form)
                Nếu 1 trong 2 cái khác rỗng thì sẽ được in ra
                Nếu cả hai đều rỗng thì in ra 'Bạn chưa có tiêu đề SEO'
            --}}
            <div class="meta-title"> 
                {{ 
                    (old('meta_title', ($postCatalogue->meta_title) ?? '')) 
                    ? old('meta_title', ($postCatalogue->meta_title) ?? '') : 'Bạn chưa có tiêu đề SEO' 
                }}
            </div>

            {{-- Có thêm dấu '/' là do dùng 127.0.0.1 --}}
            {{-- 
                (1) Nếu như có giá trị từ old (lần submit trước đó) thì sẽ được hiển thị
                (2) Nếu rỗng, kiểm tra nếu tồn tại $postCatalogue->canonical thì sẽ hiển thị (edit form)
                Nếu 1 trong 2 cái trên khác rỗng thì sẽ in ra theo định dạng
                Nếu cả hai cái trên rỗng thì sẽ in ra https://duong-dan-cua-ban.html
                (old(1, 2 ?? '')) ? [1 hoặc 2] : default
            --}}
            <div class="canonical"> 
                {{ 
                    (old('canonical', ($postCatalogue->canonical) ?? '')) 
                    ? config('app.url') . '/' . old('canonical', ($postCatalogue->canonical) ?? '') . config('apps.general.suffix') 
                    : 'https://duong-dan-cua-ban.html' 
                }} 
            </div>

            {{-- Giống meta-title --}}
            <div class="meta-description">
                {{ 
                    (old('meta_description', ($postCatalogue->meta_description) ?? '')) 
                    ? old('meta_description', ($postCatalogue->meta_description) ?? '') : 'Bạn chưa có mô tả SEO' 
                }}
            </div>

        </div>
        <div class="seo-wrapper">

            <div class="row mb-15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <span>Mô tả SEO</span>
                                <span class="count_meta-title">0 ký tự</span>
                            </div>
                        </label>
                        <input type="text" name="meta_title" value="{{ old('meta_title', ($postCatalogue->meta_title) ?? '' ) }}" class="form-control" placeholder="" autocomplete="off">
                    </div>
                </div>
            </div>

            <div class="row mb-15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <span>Từ khóa SEO</span>
                        </label>
                        <input type="text" name="meta_keyword" value="{{ old('meta_keyword', ($postCatalogue->meta_keyword) ?? '' ) }}" class="form-control" placeholder="" autocomplete="off">
                    </div>
                </div>
            </div>

            <div class="row mb-15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <span>Mô tả SEO</span>
                                <span class="count_meta-description">0 ký tự</span>
                            </div>
                        </label>
                        <textarea 
                            type="text" 
                            name="meta_description" 
                            class="form-control" 
                            placeholder="" 
                            autocomplete="off"
                        >{{ old('meta_description', ($postCatalogue->meta_description) ?? '' ) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="row mb-15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <span>Đường dẫn <span class="text-danger">(*)</span></span>
                        </label>
                        <div class="input-wrapper">
                            <input 
                                type="text" 
                                name="canonical" 
                                value="{{ old('canonical', ($postCatalogue->canonical) ?? '' ) }}" 
                                class="form-control" 
                                placeholder="" 
                                autocomplete="off"
                            >
                            <span class="baseUrl">{{ config('app.url') . '/' }}</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>