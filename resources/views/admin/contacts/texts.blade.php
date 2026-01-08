@extends('admin.part.app')
@section('title')
    @lang('texts')
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
                        <h2 class="content-header-title float-left mb-0">@lang('texts')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a
                                        href="{{ route('contacts.texts') }}">@lang('texts')</a>
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="content-body">
            <form action="{{ route('contacts.texts.post') }}" method="POST" id="add-mode-form" class="add-mode-form"
                  enctype="multipart/form-data">
                @csrf
                <div class="row">
                    @foreach (locales() as $key => $value)
                        <div class="col-12">
                            <div class="form-group">
                                <label
                                    for="page_contact_section_one_title_one_{{ $key }}">@lang('page_contact_section_one_title_one') @lang($value)</label>
                                <input type="text" class="form-control"
                                       placeholder="@lang('page_contact_section_one_title_one') @lang($value)"
                                       name="page_contact_section_one_title_one_{{ $key }}"
                                       value="{{ old('page_contact_section_one_title_one_'.$key, $settings->getTranslation('page_contact_section_one_title_one', $key) ?? '') }}"
                                >
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <div class="row">
                    @foreach (locales() as $key => $value)
                        <div class="col-12">
                            <div class="form-group">
                                <label
                                    for="page_contact_section_one_title_tow_{{ $key }}">@lang('page_contact_section_one_title_tow') @lang($value)</label>
                                <input type="text" class="form-control"
                                       placeholder="@lang('page_contact_section_one_title_tow') @lang($value)"
                                       name="page_contact_section_one_title_tow_{{ $key }}"
                                       value="{{ $settings->getTranslation('page_contact_section_one_title_tow', $key) ?? ''}}"
                                >
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                    @endforeach

                </div>
                <div class="col-3" style="margin-top: 20px">
                    <div class="modal-footer float-left">
                        <button type="submit" class="btn btn-primary">@lang('save')</button>
                    </div>
                </div>

            </form>

        </div>
    </div>
@endsection
@section('scripts')

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });




    </script>

@endsection
