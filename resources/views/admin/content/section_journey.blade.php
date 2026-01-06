@extends('admin.part.app')
@section('title')
    @lang('Section Our Journey')
@endsection
@section('styles')
    <style>
        input[type="checkbox"] {
            transform: scale(1.5);
        }
    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('Section Our Journey')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">

                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">

            <section id="">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">

                                <form action="{{ route('content.postJourneySection') }}" method="POST"
                                      id="add-mode-form" class="add-mode-form"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">

                                        @foreach (locales() as $key => $value)
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="title_{{ $key }}">@lang('title') @lang($value)</label>
                                                    <input type="text" class="form-control"
                                                           placeholder="@lang('title') @lang($value)"
                                                           name="title_{{ $key }}"
                                                           value="{{@$journey->getTranslation('title', $key) }}"
                                                    >
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>

                                        @endforeach
                                        @foreach (locales() as $key => $value)
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label
                                                        for="details_{{ $key }}">@lang('details') @lang($value)</label>
                                                    <input type="text" class="form-control"
                                                           placeholder="@lang('details') @lang($value)"
                                                           name="details_{{ $key }}"
                                                           value="{{@$journey->getTranslation('details', $key)  }}"
                                                    >
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>

                                        @endforeach

                                        <div class="add_images">
                                            <div class="col-12 edit_images">
                                                <div class="input-field">
                                                    <label class="active">@lang('Photos')</label>
                                                    <div class="input-images" style="padding-top: .5rem;"></div>
                                                </div>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>


                                        <div class="col-md-12">
                                            <div class="card">
                                                <div class="card-header bg-dark">
                                                    <h4 class="m-0" style="color: white">@lang('Items')</h4>
                                                </div>
                                                <div class="card-body">
                                                    <div class="text-right mt-3">
                                                        <a id="addRow"
                                                           class="add_row btn btn-sm btn-dark">@lang('Add Row')</a>
                                                    </div>
                                                    <br>
                                                    <br>
                                                    <div class="row_data">
                                                        @foreach($journey->items as $item)
                                                            <div class="row mb-3 ss">
                                                                <div class="col-md-11">
                                                                    <div class="row">
                                                                        @foreach (locales() as $key => $value)
                                                                            <div class="col-12">
                                                                                <div class="form-group">
                                                                                    <label for="items_{{ $key }}">@lang('items') @lang($value)</label>
                                                                                    <input type="text" class="form-control"
                                                                                           placeholder="@lang('items') @lang($value)"
                                                                                           name="items_{{ $key }}[]"
                                                                                           value="{{ $item->getTranslation('item', $key)[0] ?? '' }}"
                                                                                    >
                                                                                    <div class="invalid-feedback"></div>
                                                                                </div>
                                                                            </div>

                                                                        @endforeach



                                                                    </div>
                                                                </div>
                                                                <div class="col-md-1">
                                                                    <a class="btn btn-danger w-100 remove_row"><i class="fas fa-times"></i></a>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="modal-footer">
                                            <button class="btn btn-primary done">@lang('save')</button>

                                            <button type="button" class="btn btn-secondary"
                                                    data-dismiss="modal">@lang('close')</button>
                                        </div>
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
        $('.add_row').click(function (e) {
            e.preventDefault();
            console.log('ddd')
            const row = `<div class="row mb-3 ss">
                        <div class="col-md-11">
                            <div class="row">
                                  @foreach (locales() as $key => $value)
            <div class="col-12">
                <div class="form-group">
                    <label for="items_{{ $key }}">@lang('items') @lang($value)</label>
                                                        <input type="text" class="form-control"
                                                               placeholder="@lang('items') @lang($value)"
                                                               name="items_{{ $key }}[]"  >
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                </div>

                                            @endforeach



            </div>
        </div>
        <div class="col-md-1">
            <a class="btn btn-danger w-100 remove_row"><i class="fas fa-times"></i></a>
        </div>
    </div>`;

            $('.row_data').append(row);

        })
        $(document).ready(function () {
            $('body').on('click', '.remove_row', function (e) {
                e.preventDefault();
                $(this).parent().parent().remove();
            })
        })
        $('.input-images').imageUploader({
            preloaded: [
                    @foreach($journey->attachments as $item)
                {
                    id: "{{$item['uuid']}}", src: "{{$item['attachment']}}"
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
@endsection
