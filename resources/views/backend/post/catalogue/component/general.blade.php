<div class="row mb15">
    <div class="col-lg-12">
         
    </div>
</div>

<div class="row mb15">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">
                Mô tả ngắn
                <span class="text-danger">(*)</span>
            </label>
            <textarea type="text" name="description" value="{{ old('description', ($postCatalogue->description) ?? '' ) }}" class="form-control ck-editor" placeholder="" autocomplete="off" id="description"></textarea>
        </div>
    </div>
</div>

<div class="row mb15">
    <div class="col-lg-12">
        <div class="form-row">
            <label for="" class="control-label text-left">
                Nội dung
                <span class="text-danger">(*)</span>
            </label>
            <textarea type="text" name="content" value="{{ old('content', ($postCatalogue->content) ?? '' ) }}" class="form-control ck-editor" placeholder="" autocomplete="off" id="content"></textarea>
        </div>
    </div>
</div>