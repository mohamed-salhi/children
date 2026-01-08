@extends('admin.part.app')

@section('title')
    @lang('Section Our Journey')
@endsection

@section('styles')
@endsection

@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('Section Our Journey')</h2>
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

                            {{-- ✅ Header أبيض (نفس Hero) --}}
                            <div
                                class="card-header bg-white border-bottom d-flex align-items-center justify-content-between">
                                <h4 class="mb-0 text-dark">
                                    <i class="feather icon-map mr-1"></i>
                                    @lang('Section Our Journey')
                                </h4>
                            </div>

                            <div class="card-body">
                                <form action="{{ route('content.postJourneySection') }}" method="POST" id="add-mode-form"
                                    class="add-mode-form" enctype="multipart/form-data">
                                    @csrf

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
                                                        value="{{ @$journey->getTranslation('title', $key) }}">
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
                                                        value="{{ @$journey->getTranslation('details', $key) }}">
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>

                                    <hr>

                                    {{-- ===== Photos ===== --}}
                                    <div class="mb-2">
                                        <h5 class="text-muted mb-0">@lang('Photos')</h5>
                                        <small class="text-muted">@lang('Upload images')</small>
                                    </div>

                                    {{-- ===== Photos (4 separate inputs) ===== --}}
                                    <div class="mb-2">
                                        <h5 class="text-muted mb-0">@lang('Photos')</h5>
                                        <small class="text-muted">@lang('Upload 4 images (each field is separate)')</small>
                                    </div>
                                    @php
                                        use Illuminate\Support\Facades\Storage;

                                        $journeyImages = \App\Models\Upload::where(
                                            'imageable_type',
                                            \App\Models\SectionJourney::class,
                                        )
                                            ->where('imageable_id', 1)
                                            ->where('type', \App\Models\Upload::IMAGE)
                                            ->get()
                                            ->keyBy('name'); // image_1 .. image_4
                                    @endphp



                                    <div class="row">
                                        @for ($i = 1; $i <= 4; $i++)
                                            @php
                                                $key = 'image_' . $i;
                                            @endphp

                                            <div class="col-12 col-md-6 mb-2">
                                                <div class="card border">
                                                    <div class="card-body">
                                                        <label class="font-weight-bold d-block mb-1">
                                                            @lang('Photo') {{ $i }}
                                                        </label>

                                                        <img id="preview_img_{{ $i }}"
                                                            src="{{ isset($journeyImages[$key]) ? Storage::url($journeyImages[$key]->filename) : asset('images/placeholder.png') }}"
                                                            class="img-thumbnail mb-1"
                                                            style="width:100%; height:180px; object-fit:cover;">

                                                        <div class="custom-file">
                                                            <input type="file" name="{{ $key }}"
                                                                class="custom-file-input img-input"
                                                                id="img_{{ $i }}" accept="image/*"
                                                                data-preview="preview_img_{{ $i }}">

                                                            <label class="custom-file-label" for="img_{{ $i }}">
                                                                @lang('select_image')
                                                            </label>
                                                        </div>

                                                        <div class="invalid-feedback d-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endfor
                                    </div>



                                    <hr>

                                    {{-- ===== Items ===== --}}
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <div>
                                            <h5 class="text-muted mb-0">@lang('Items')</h5>
                                            <small class="text-muted">@lang('Add multiple items')</small>
                                        </div>

                                        <a id="addRow" class="add_row btn btn-sm btn-dark">
                                            <i class="feather icon-plus mr-50"></i> @lang('Add Row')
                                        </a>
                                    </div>

                                    <div class="row_data">
                                        @foreach ($journey->items as $item)
                                            <div class="row mb-2 ss align-items-start">
                                                <div class="col-md-11">
                                                    <div class="row">
                                                        @foreach (locales() as $key => $value)
                                                            <div class="col-12 col-md-6">
                                                                <div class="form-group">
                                                                    <label class="font-weight-bold">
                                                                        @lang('items')
                                                                        <span
                                                                            class="badge badge-light text-dark ml-1">{{ $value }}</span>
                                                                    </label>
                                                                    <input type="text" class="form-control"
                                                                        placeholder="@lang('items') {{ $value }}"
                                                                        name="items_{{ $key }}[]"
                                                                        value="{{ $item->getTranslation('item', $key)[0] ?? '' }}">
                                                                    <div class="invalid-feedback"></div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <div class="col-md-1 d-flex">
                                                    <a class="btn btn-danger btn-sm w-100 remove_row"
                                                        title="@lang('delete')">
                                                        <i class="fas fa-times"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endforeach
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

        // ✅ Add Row (نفس ستايل الحقول: كل لغتين جنب بعض)
        $('body').on('click', '.add_row', function(e) {
            e.preventDefault();

            const row = `
                <div class="row mb-2 ss align-items-start">
                    <div class="col-md-11">
                        <div class="row">
                            @foreach (locales() as $key => $value)
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold">
                                            @lang('items')
                                            <span class="badge badge-light text-dark ml-1">{{ $value }}</span>
                                        </label>
                                        <input type="text" class="form-control"
                                               placeholder="@lang('items') {{ $value }}"
                                               name="items_{{ $key }}[]">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="col-md-1 d-flex">
                        <a class="btn btn-danger btn-sm w-100 remove_row" title="@lang('delete')">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            `;

            $('.row_data').append(row);
        });

        // ✅ Remove Row
        $('body').on('click', '.remove_row', function(e) {
            e.preventDefault();
            $(this).closest('.ss').remove();
        });

        // ✅ Image Uploader (كما هو)
        $('.input-images').imageUploader({
            preloaded: [
                @foreach ($journey->attachments as $item)
                    {
                        id: "{{ $item['uuid'] }}",
                        src: "{{ $item['attachment'] }}"
                    },
                @endforeach
            ],
            imagesInputName: 'images[]',
            preloadedInputName: 'delete_images',
            maxSize: 2 * 1024 * 1024,
            maxFiles: 20,
            with: 100
        });
    </script>
   <script>
    $(document).on('change', '.img-input', function () {
        const file = this.files && this.files[0] ? this.files[0] : null;

        // label
        const fileName = file ? file.name : "@lang('select_image')";
        $(this).next('.custom-file-label').html(fileName);

        // preview
        const previewId = $(this).data('preview');
        if (file && previewId) {
            const reader = new FileReader();
            reader.onload = e => $('#' + previewId).attr('src', e.target.result);
            reader.readAsDataURL(file);
        }
    });
</script>

@endsection
