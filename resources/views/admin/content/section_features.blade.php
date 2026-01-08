@extends('admin.part.app')

@section('title')
    @lang('Section Our Features')
@endsection

@section('styles')
    <style>
        input[type="checkbox"] { transform: scale(1.5); }
    </style>
@endsection

@section('content')
@php
    $featuresObj = $features ?? null;
    $featureItems = $featuresObj->items ?? [];

    $normalize = function ($val) {
        if (is_array($val)) return $val[0] ?? '';
        return $val ?? '';
    };
@endphp

<div class="content-wrapper">
    <div class="content-header row">
        <div class="content-header-left col-md-9 col-12 mb-2">
            <div class="row breadcrumbs-top">
                <div class="col-12">
                    <h2 class="content-header-title float-left mb-0">@lang('Section Our Features')</h2>
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
                        {{-- Header --}}
                        <div class="card-header bg-white border-bottom">
                            <h4 class="mb-0 text-dark">
                                <i class="feather icon-grid mr-1"></i>
                                @lang('Section Our Features')
                            </h4>
                        </div>

                        <div class="card-body">
                            <form action="{{ route('content.postFeaturesSection') }}"
                                  method="POST" enctype="multipart/form-data">
                                @csrf

                                {{-- ================= Titles ================= --}}
                                <div class="mb-2">
                                    <h5 class="text-muted mb-0">@lang('title')</h5>
                                    <small class="text-muted">(AR / EN)</small>
                                </div>

                                <div class="row">
                                    @foreach (locales() as $key => $value)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">
                                                    @lang('title')
                                                    <span class="badge badge-light text-dark ml-1">{{ $value }}</span>
                                                </label>
                                                <input type="text" class="form-control"
                                                       name="title_{{ $key }}"
                                                       value="{{ $featuresObj ? $normalize($featuresObj->getTranslation('title', $key)) : '' }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <hr>

                                {{-- ================= Details ================= --}}
                                <div class="mb-2">
                                    <h5 class="text-muted mb-0">@lang('details')</h5>
                                    <small class="text-muted">(AR / EN)</small>
                                </div>

                                <div class="row">
                                    @foreach (locales() as $key => $value)
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="font-weight-bold">
                                                    @lang('details')
                                                    <span class="badge badge-light text-dark ml-1">{{ $value }}</span>
                                                </label>
                                                <input type="text" class="form-control"
                                                       name="details_{{ $key }}"
                                                       value="{{ $featuresObj ? $normalize($featuresObj->getTranslation('details', $key)) : '' }}">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <hr>

                                {{-- ================= Cover Image ================= --}}
                                <div class="form-group">
                                    <label class="font-weight-bold">@lang('Cover Image')</label>

                                    <div class="d-flex align-items-start">
                                        <div class="mr-3">
                                            <img id="edit_src_cover_image"
                                                 src="{{ $featuresObj->cover_image ?? asset('images/placeholder.png') }}"
                                                 class="img-thumbnail"
                                                 style="width:220px;height:160px;object-fit:cover;">
                                        </div>

                                        <div class="flex-grow-1">
                                            <div class="custom-file">
                                                <input type="file" name="cover_image"
                                                       class="custom-file-input" id="coverImage" accept="image/*">
                                                <label class="custom-file-label" for="coverImage">@lang('select_image')</label>
                                            </div>
                                            <small class="text-muted d-block mt-1">@lang('select_image')</small>
                                        </div>
                                    </div>
                                </div>

                                <hr>

                                {{-- ================= Items ================= --}}
                                <div class="card border">
                                    <div class="card-header bg-white border-bottom d-flex justify-content-between">
                                        <h5 class="mb-0">@lang('Items')</h5>
                                        <a class="btn btn-sm btn-primary add_row">
                                            <i class="feather icon-plus"></i> @lang('Add Row')
                                        </a>
                                    </div>

                                    <div class="card-body">
                                        <div class="row_data">

                                            {{-- ===== Existing Items ===== --}}
                                            @foreach($featureItems as $item)
                                                <div class="row mb-3 ss">
                                                    <div class="col-lg-11">
                                                        <div class="card border">
                                                            <div class="card-body">
                                                                <div class="row">

                                                                    {{-- item titles --}}
                                                                    @foreach (locales() as $key => $value)
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="font-weight-bold">
                                                                                    @lang('title')
                                                                                    <span class="badge badge-light text-dark ml-1">{{ $value }}</span>
                                                                                </label>
                                                                                <input type="text" class="form-control"
                                                                                       name="title_item_{{ $key }}[]"
                                                                                       value="{{ $normalize($item->getTranslation('title', $key)) }}">
                                                                            </div>
                                                                        </div>
                                                                    @endforeach

                                                                    {{-- item details --}}
                                                                    @foreach (locales() as $key => $value)
                                                                        <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label class="font-weight-bold">
                                                                                    @lang('details')
                                                                                    <span class="badge badge-light text-dark ml-1">{{ $value }}</span>
                                                                                </label>
                                                                                <input type="text" class="form-control"
                                                                                       name="details_item_{{ $key }}[]"
                                                                                       value="{{ $normalize($item->getTranslation('details', $key)) }}">
                                                                            </div>
                                                                        </div>
                                                                    @endforeach

                                                                    {{-- Icon Code --}}
                                                                    <div class="col-12">
                                                                        <div class="form-group mb-0">
                                                                            <label class="font-weight-bold">@lang('Icon Code')</label>
                                                                            <input type="text" class="form-control"
                                                                                   name="icon_item[]"
                                                                                   value="{{ $item->icon ?? '' }}"
                                                                                   placeholder="fa-solid fa-star">
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    {{-- delete (SMALL) --}}
                                                    <div class="col-lg-1 d-flex align-items-start">
                                                        <a class="btn btn-danger btn-sm remove_row">
                                                            <i data-feather="x"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>
                                </div>

                                <hr>

                                {{-- ================= Actions ================= --}}
                                <div class="d-flex justify-content-end">
                                    <button class="btn btn-primary mr-1" type="submit">
                                        <i class="feather icon-save mr-1"></i> @lang('save')
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        <i class="feather icon-x mr-1"></i> @lang('close')
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
    {{-- ✅ Feather Icons --}}
    <script src="https://unpkg.com/feather-icons"></script>

    <script>
        // ✅ Cover preview + label (Hero style)
        $(document).on('change', '#coverImage', function() {
            const file = this.files && this.files[0] ? this.files[0] : null;

            const fileName = file ? file.name : "@lang('select_image')";
            $(this).next('.custom-file-label').html(fileName);

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#edit_src_cover_image').attr('src', e.target.result);
                };
                reader.readAsDataURL(file);
            }
        });

        // ✅ Initial replace (for existing rows)
        $(document).ready(function () {
            feather.replace();
        });

        // ✅ Add row (IDENTICAL STRUCTURE)
        $(document).on('click', '.add_row', function (e) {
            e.preventDefault();

            const row = `
            <div class="row mb-3 ss">
                <div class="col-lg-11">
                    <div class="card border">
                        <div class="card-body">
                            <div class="row">

                                @foreach (locales() as $key => $value)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">
                                                @lang('title')
                                                <span class="badge badge-light text-dark ml-1">{{ $value }}</span>
                                            </label>
                                            <input type="text" class="form-control"
                                                   name="title_item_{{ $key }}[]">
                                        </div>
                                    </div>
                                @endforeach

                                @foreach (locales() as $key => $value)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="font-weight-bold">
                                                @lang('details')
                                                <span class="badge badge-light text-dark ml-1">{{ $value }}</span>
                                            </label>
                                            <input type="text" class="form-control"
                                                   name="details_item_{{ $key }}[]">
                                        </div>
                                    </div>
                                @endforeach

                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label class="font-weight-bold">@lang('Icon Code')</label>
                                        <input type="text" class="form-control"
                                               name="icon_item[]"
                                               placeholder="fa-solid fa-star">
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-1 d-flex align-items-start">
                    <a class="btn btn-danger btn-sm remove_row">
                        <i data-feather="x"></i>
                    </a>
                </div>
            </div>`;

            $('.row_data').append(row);

            // ✅ مهم حتى تظهر أيقونة Feather للصف المضاف
            feather.replace();
        });

        // ✅ Remove row
        $(document).on('click', '.remove_row', function (e) {
            e.preventDefault();
            $(this).closest('.ss').remove();
        });
    </script>
@endsection
