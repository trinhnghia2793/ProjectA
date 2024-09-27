@include('backend.dashboard.component.breadcrumb', ['title' => $config['seo']['create']['title']])
@include('backend.dashboard.component.formError')

<form action="" method="post">
    @csrf
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">

            <div class="col-lg-6">

                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('messages.tableHeading') }}</h5>
                    </div>
                    <div class="ibox-content">
                        @include('backend.dashboard.component.content', ['model' => ($object) ?? null])
                    </div>
                </div>
                @include('backend.dashboard.component.seo', ['model' => ($object) ?? null])

            </div>

            <div class="col-lg-6">
                <div class="ibox">
                    <div class="ibox-title">
                        <h5>{{ __('messages.tableHeading') }}</h5>
                    </div>
                    <div class="ibox-content">
                        @include('backend.dashboard.component.translate', ['model' => ($objectTranslate) ?? null])
                    </div>
                </div>
                @include('backend.dashboard.component.seoTranslate', ['model' => ($objectTranslate) ?? null])
            </div>

        </div>
        @include('backend.dashboard.component.button')
   </div>
</form>