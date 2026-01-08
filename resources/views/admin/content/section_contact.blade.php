@extends('admin.part.app')
@section('title')
    @lang('Section Contact Us')
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
                        <h2 class="content-header-title float-left mb-0">@lang('Section Contact Us')</h2>
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

                                <form action="{{ route('content.posContactSection') }}" method="POST"id="add-mode-form" class="add-mode-form"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="uuid" id="uuid" class="form-control"/>
                                    <div class="modal-body">

                                        @foreach (locales() as $key => $value)
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label for="title_{{ $key }}">@lang('title') @lang($value)</label>
                                                    <input type="text" class="form-control"
                                                           placeholder="@lang('title') @lang($value)"
                                                           name="title_{{ $key }}" value="{{ optional($contact)->getTranslation('title', $key)[0] ?? '' }}"
                                                    >
                                                    <div class="invalid-feedback"></div>
                                                </div>
                                            </div>

                                        @endforeach
                                            @foreach (locales() as $key => $value)
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="details_{{ $key }}">@lang('details') @lang($value)</label>
                                                        <input type="text" class="form-control"
                                                               placeholder="@lang('details') @lang($value)"
                                                               name="details_{{ $key }}" value="{{ optional($contact)->getTranslation('details', $key)[0] ?? '' }}"
                                                        >
                                                        <div class="invalid-feedback"></div>
                                                    </div>
                                                </div>

                                            @endforeach

                                        <div class="col-12">
                                            <label for="icon">@lang('flag')</label>
                                            <div>
                                                <div class="fileinput fileinput-exists"
                                                     data-provides="fileinput">
                                                    <div class="fileinput-preview thumbnail"
                                                         data-trigger="fileinput"
                                                         style="width: 200px; height: 150px;">
                                                        <img id="edit_src_image"
                                                             src="{{@$contact->image}}"
                                                             alt=""/>
                                                    </div>
                                                    <div class="form-group">
                                                    <span class="btn btn-secondary btn-file">
                                                        <span class="fileinput-new"> @lang('select_image')</span>
                                                        <span class="fileinput-exists"> @lang('select_image')</span>
                                                        <input class="form-control" type="file" name="image">
                                                    </span>
                                                        <div class="invalid-feedback" style="display: block;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button  class="btn btn-primary done">@lang('save')</button>

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
    </script>
@endsection
