@extends('admin.part.app')
@section('title')
    @lang('Section Our Service')
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
                        <h2 class="content-header-title float-left mb-0">@lang('Section Our Service')</h2>
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
                                <div class="head-label">
                                    <h4 class="card-title">@lang('Section Our Service')</h4>
                                </div>

                            </div>

                            <div class="table-responsive card-datatable" style="padding: 20px">
                                <table class="table" id="datatable">
                                    <thead>
                                    <tr>
                                        <th>@lang('title')</th>
                                        <th>@lang('details')</th>
                                        <th>@lang('button')</th>
                                        <th>@lang('image')</th>

                                        <th style="width: 225px;">@lang('actions')</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">@lang('edit')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('content.service.update') }}" method="POST" id="form_edit" class=""
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="id" class="form-control"/>
                    <div class="modal-body">
                        @foreach (locales() as $key => $value)
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="title_{{ $key }}">@lang('title') @lang($value)</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('title') @lang($value)"
                                           name="title_{{ $key }}" id="edit_title_{{ $key }}">
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
                                           name="details_{{ $key }}" id="edit_details_{{ $key }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                        @endforeach
                        @foreach (locales() as $key => $value)
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="button_{{ $key }}">@lang('button') @lang($value)</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('button') @lang($value)"
                                           name="button_{{ $key }}" id="edit_button_{{ $key }}">
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
                                             src="{{asset('dashboard/app-assets/images/placeholder.jpeg')}}"
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
                            <button class="btn btn-primary done">@lang('save')</button>

                            <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">@lang('close')</button>
                        </div>
                    </div>
                </form>
            </div>
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
        //bindTable
        var table = $('#datatable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            "bFilter": false,
            "oLanguage": {
                @if (app()->isLocale('ar'))
                "sEmptyTable": "ليست هناك بيانات متاحة في الجدول",
                "sLoadingRecords": "جارٍ التحميل...",
                "sProcessing": "جارٍ التحميل...",
                "sLengthMenu": "أظهر _MENU_ مدخلات",
                "sZeroRecords": "لم يعثر على أية سجلات",
                "sInfo": "إظهار _START_ إلى _END_ من أصل _TOTAL_ مدخل",
                "sInfoEmpty": "يعرض 0 إلى 0 من أصل 0 سجل",
                "sInfoFiltered": "(منتقاة من مجموع _MAX_ مُدخل)",
                "sInfoPostFix": "",
                "sSearch": "ابحث:",
                "oAria": {
                    "sSortAscending": ": تفعيل لترتيب العمود تصاعدياً",
                    "sSortDescending": ": تفعيل لترتيب العمود تنازلياً"
                },
                @endif // "oPaginate": {"sPrevious": '<-', "sNext": '->'},
            },
            ajax: {
                url: '{{ route('content.service.indexTable', app()->getLocale()) }}',

            },

            columns: [
                {
                    data: 'title_translate',
                    name: 'title',
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'details_translate',
                    name: 'details',
                    orderable: false,
                    searchable: false
                },  {
                    data: 'button_translate',
                    name: 'button',
                    orderable: false,
                    searchable: false
                },
                {
                    "data": 'image',
                    "name": 'image',
                    render: function (data, type, full, meta) {
                        return `<img src="${data}" style="width:100px;height:100px;"  class="img-fluid img-thumbnail">`;
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: true
                },
            ]

        });
        $(document).ready(function () {
            $(document).on('click', '.btn_edit', function (event) {
                $('input').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                event.preventDefault();
                var button = $(this)
                var id = button.data('id')
                $('#id').val(id);
                @foreach (locales() as $key => $value)
                $('#edit_title_{{ $key }}').val(button.data('title_{{ $key }}'))
                $('#edit_details_{{ $key }}').val(button.data('details_{{ $key }}'))
                $('#edit_button_{{ $key }}').val(button.data('button_{{ $key }}'))

                @endforeach

                $('#edit_src_image').attr('src', button.data('image'));


            });
        });
    </script>
@endsection
