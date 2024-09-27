<div class="ibox">
    <div class="ibox-title">
        <h5>{{ __('messages.seo') }}</h5>
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
                    (old('meta_title', ($model->meta_title) ?? '')) 
                    ? old('meta_title', ($model->meta_title) ?? '') : __('messages.seoTitle') 
                }}
            </div>

            {{-- Có thêm dấu '/' là do dùng 127.0.0.1 --}}
            {{-- 
                (1) Nếu như có giá trị từ old (lần submit trước đó) thì sẽ được hiển thị
                (2) Nếu rỗng, kiểm tra nếu tồn tại $post->canonical thì sẽ hiển thị (edit form)
                Nếu 1 trong 2 cái trên khác rỗng thì sẽ in ra theo định dạng
                Nếu cả hai cái trên rỗng thì sẽ in ra https://duong-dan-cua-ban.html
                (old(1, 2 ?? '')) ? [1 hoặc 2] : default
            --}}
            <div class="canonical">
                {{ (old('canonical', ($model->canonical) ?? '')) 
                ? config('app.url').old('canonical', ($model->canonical) ?? '') . config('apps.general.suffix') 
                :  __('messages.seoCanonical')  }}
            </div>

            {{-- Giống meta-title --}}
            <div class="meta-description">
                {{ 
                    (old('meta_description', ($model->meta_description) ?? '')) 
                    ? old('meta_description', ($model->meta_description) ?? '') : __('messages.seoDescription')  
                }}
            </div>
        </div>

        <div class="seo-wrapper">

            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <span>{{ __('messages.seoMetaTitle') }}</span>
                                <span class="count_meta-title">0 {{ __('messages.character') }}</span>
                            </div>
                        </label>
                        <input 
                            type="text"
                            name="meta_title"
                            value="{{ old('meta_title', ($model->meta_title) ?? '' ) }}"
                            class="form-control"
                            placeholder=""
                            autocomplete="off"
                            {{ (isset($disabled)) ? 'disabled' : '' }}
                        >
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <span>{{ __('messages.seoMetaKeyword') }}</span>
                        </label>
                        <input 
                            type="text"
                            name="meta_keyword"
                            value="{{ old('meta_keyword', ($model->meta_keyword) ?? '' ) }}"
                            class="form-control"
                            placeholder=""
                            autocomplete="off"
                            {{ (isset($disabled)) ? 'disabled' : '' }}
                        >
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <div class="uk-flex uk-flex-middle uk-flex-space-between">
                                <span>{{ __('messages.seoMetaDescription') }}</span>
                                <span class="count_meta-description">0 {{ __('messages.character') }}</span>
                            </div>
                        </label>
                        <textarea 
                            name="meta_description"
                            class="form-control"
                            placeholder=""
                            autocomplete="off"
                            {{ (isset($disabled)) ? 'disabled' : '' }}
                        >{{ old('meta_description', ($model->meta_description) ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="row mb15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <span>{{ __('messages.canonical') }} <span class="text-danger">*</span></span>
                        </label>
                       <div class="input-wrapper">
                            <input 
                                type="text"
                                name="canonical"
                                value="{{ old('canonical', ($model->canonical) ?? '' ) }}"
                                class="form-control seo-canonical"
                                placeholder=""
                                autocomplete="off"
                                {{ (isset($disabled)) ? 'disabled' : '' }}
                            >
                            <span class="baseUrl">{{ config('app.url') }}</span>
                       </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>