@extends('admin.part.app')

@section('title')
    @lang('section hero')
@endsection

@section('styles')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('Section Hero')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb"></ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="content-body">
            <section>
                <div class="row">
                    <div class="col-12">

                        <div class="card shadow-sm border-0">
                            {{-- ✅ Header أبيض --}}
                            <div
                                class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 text-dark">
                                    <i class="feather icon-layout mr-1"></i>
                                    @lang('Section Hero')
                                </h4>
                            </div>

                            {{-- ✅ Body في مكانه الصحيح --}}
                            <div class="card-body">
                                <form action="{{ route('content.postHeroSection') }}" method="POST" id="add-mode-form"
                                    class="add-mode-form" enctype="multipart/form-data">
                                    @csrf

                                    <input type="hidden" name="uuid" id="uuid" class="form-control"
                                        value="{{ optional($hero)->uuid ?? '' }}" />

                                    {{-- ===== Title ===== --}}
                                    <div class="mb-2">
                                        <h5 class="text-muted mb-0">@lang('title')</h5>
                                        <small class="text-muted">(@lang('Arabic') / @lang('English'))</small>
                                    </div>

                                    <div class="row">
                                        @foreach (locales() as $key => $value)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">
                                                        @lang('title')
                                                        <span
                                                            class="badge badge-light text-dark ml-1">{{ $value }}</span>
                                                    </label>
                                                    <input type="text" class="form-control"
                                                        placeholder="@lang('title') {{ $value }}"
                                                        name="title_{{ $key }}"
                                                        value="{{ optional($hero)->getTranslation('title', $key) ?? '' }}">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <hr>

                                    {{-- ===== Details ===== --}}
                                    <div class="mb-2">
                                        <h5 class="text-muted mb-0">@lang('details')</h5>
                                        <small class="text-muted">(@lang('Arabic') / @lang('English'))</small>
                                    </div>

                                    <div class="row">
                                        @foreach (locales() as $key => $value)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">
                                                        @lang('details')
                                                        <span
                                                            class="badge badge-light text-dark ml-1">{{ $value }}</span>
                                                    </label>
                                                    <input type="text" class="form-control"
                                                        placeholder="@lang('details') {{ $value }}"
                                                        name="details_{{ $key }}"
                                                        value="{{ optional($hero)->getTranslation('details', $key) ?? '' }}">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <hr>

                                    {{-- ===== Button ===== --}}
                                    <div class="mb-2">
                                        <h5 class="text-muted mb-0">@lang('button')</h5>
                                        <small class="text-muted">(@lang('Arabic') / @lang('English'))</small>
                                    </div>

                                    <div class="row">
                                        @foreach (locales() as $key => $value)
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">
                                                        @lang('button')
                                                        <span
                                                            class="badge badge-light text-dark ml-1">{{ $value }}</span>

                                                    </label>
                                                    <input type="text" class="form-control"
                                                        placeholder="@lang('button') {{ $value }}"
                                                        name="button_{{ $key }}"
                                                        value="{{ optional($hero)->getTranslation('button', $key) ?? '' }}">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <hr>

                                    {{-- ===== Image ===== --}}
                                    <div class="form-group">
                                        <label class="font-weight-bold">@lang('flag')</label>

                                        <div class="d-flex align-items-start">
                                            <div class="mr-3">
                                                <img id="edit_src_image"
                                                    src="{{ optional($hero)->image ?? asset('images/placeholder.png') }}"
                                                    alt="" class="img-thumbnail"
                                                    style="width: 220px; height: 160px; object-fit: cover;">
                                            </div>

                                            <div class="flex-grow-1">
                                                <div class="custom-file">
                                                    <input type="file" name="image" class="custom-file-input"
                                                        id="heroImage">
                                                    <label class="custom-file-label"
                                                        for="heroImage">@lang('select_image')</label>
                                                </div>
                                                <div class="invalid-feedback" style="display:block;"></div>
                                                <small class="text-muted d-block mt-1">@lang('select_image')</small>
                                            </div>
                                        </div>
                                    </div>

                                    <hr>

                                    {{-- ===== Buttons ===== --}}
                                    <div class="d-flex justify-content-end">
                                        <button class="btn btn-primary mr-1" type="submit">
                                            <i class="feather icon-save mr-1"></i>
                                            @lang('save')
                                        </button>

                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                            <i class="feather icon-x mr-1"></i>
                                            @lang('close')
                                        </button>
                                    </div>

                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.ckeditor.com/ckeditor5/25.0.0/classic/ckeditor.js"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        // ✅ Bootstrap custom-file label + image preview
        $(document).on('change', '#heroImage', function() {
            const file = this.files && this.files[0] ? this.files[0] : null;

            // update label
            const fileName = file ? file.name : "@lang('select_image')";
            $(this).next('.custom-file-label').html(fileName);

            // ✅ preview image
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#edit_src_image').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
