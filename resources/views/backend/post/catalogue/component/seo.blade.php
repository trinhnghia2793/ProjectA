<div class="ibox">
    <div class="ibox-title">
        <h5>Cấu hình SEO</h5>
    </div>
    <div class="ibox-content">
        <div class="seo-container">
            <div class="meta-title">meta title</div>
            <div class="canonical">http://laravel.com/tu-hoc-laravel.html</div>
            <div class="meta-description">meta description</div>
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
                        <textarea type="text" name="meta_description" value="{{ old('meta_description', ($postCatalogue->meta_description) ?? '' ) }}" class="form-control" placeholder="" autocomplete="off"></textarea>
                    </div>
                </div>
            </div>

            <div class="row mb-15">
                <div class="col-lg-12">
                    <div class="form-row">
                        <label for="" class="control-label text-left">
                            <span>Đường dẫn</span>
                        </label>
                        <input type="text" name="canonical" value="{{ old('canonical', ($postCatalogue->canonical) ?? '' ) }}" class="form-control" placeholder="" autocomplete="off">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>