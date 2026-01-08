@extends('admin.part.app')
@section('title')
    @lang('products')
@endsection
@section('styles')
    <style>
        input[type="checkbox"] {
            transform: scale(1.5);
        }

        /*.input-images-2 .image-uploader .uploaded .uploaded-image img:hover  {*/
        /*    transform: scale(2.2); !* تكبير الصورة عند تحويم المؤشر *!*/

        /*}*/
        #map {
            height: 400px;
            width: 100%;
        }

        #edit_map {
            height: 400px;
            width: 100%;
        }

    </style>
@endsection
@section('content')
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-left mb-0">@lang('products')</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">

                                <li class="breadcrumb-item"><a
                                        href="{{ route('products.index') }}">@lang('products')</a>
                                </li>
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
                                    <h4 class="card-title">@lang('products')</h4>
                                </div>
                                {{--                                @can('place-create')--}}
                                <div class="text-right">
                                    <div class="form-group">
                                        <button class="btn btn-outline-primary button_modal" type="button"
                                                data-toggle="modal" id=""
                                                data-target="#full-modal-stem"><span><i
                                                    class="fa fa-plus"></i>@lang('add')</span>
                                        </button>
                                        <button

                                            class="btn_delete_all btn btn-outline-danger " type="button">
                                            <span><i aria-hidden="true"></i> @lang('delete')</span>
                                        </button>
                                        <button
                                            data-status="1" class="btn_status btn btn-outline-success " type="button">
                                            <span><i aria-hidden="true"></i> @lang('activate')</span>
                                        </button>
                                        <button
                                            data-status="0" class="btn_status btn btn-outline-warning " type="button">
                                            <span><i aria-hidden="true"></i> @lang('deactivate')</span>
                                        </button>
                                    </div>
                                </div>
                                {{--                                @endcan--}}
                            </div>
                            <div class="card-body">
                                <form id="search_form">
                                    <div class="row">
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_name">@lang('product name')</label>
                                                <input id="s_name" type="text"
                                                       class="search_input form-control"
                                                       placeholder="@lang('product name')">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_price">@lang('price')</label>
                                                <input id="s_price" type="text"
                                                       class="search_input form-control"
                                                       placeholder="@lang('price')">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_number">@lang('number')</label>
                                                <input id="s_number" type="text"
                                                       class="search_input form-control"
                                                       placeholder="@lang('number')">
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <div class="form-group">
                                                <label for="s_category_uuid">@lang('categories')</label>
                                                <select name="category_uuid" id="s_category_uuid"
                                                        class="search_input form-control">
                                                    <option selected
                                                            disabled>@lang('select')  @lang('categories')</option>
                                                    @foreach ($categories as $item)
                                                        <option value="{{ $item->uuid }}"> {{ $item->name }} </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="s_status">@lang('status')</label>
                                                <select name="s_status" id="s_status" class="search_input form-control">
                                                    <option selected disabled>@lang('select') @lang('status')</option>
                                                    <option value="1"> @lang('active') </option>
                                                    <option value="2"> @lang('inactive') </option>
                                                </select>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                        </div>

                                        <div class="col-3" style="margin-top: 20px">
                                            <button id="search_btn" class="btn btn-outline-info" type="submit">
                                                <span><i class="fa fa-search"></i> @lang('search')</span>
                                            </button>
                                            <button id="clear_btn" class="btn btn-outline-secondary" type="submit">
                                                <span><i class="fa fa-undo"></i> @lang('reset')</span>
                                            </button>


                                            <div class="col-3" style="margin-top: 20px">

                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="table-responsive card-datatable" style="padding: 20px">
                                <table class="table" id="datatable">
                                    <thead>
                                    <tr>
                                        <th><input name="select_all" id="example-select-all" type="checkbox"
                                                   onclick="CheckAll('box1', this)"/></th>
                                        <th>@lang('product name')</th>
                                        <th>@lang('price')</th>
                                        <th>@lang('category')</th>
                                        <th>@lang('number')</th>
                                        <th>@lang('status')</th>
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
    <div class="modal fade" class="full-modal-stem" id="full-modal-stem" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">@lang('add')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('products.store') }}" method="POST" id="add-mode-form"
                      class="add-mode-form"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row">

                            @foreach (locales() as $key => $value)
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="name_{{ $key }}">@lang('name') @lang($value)</label>
                                        <input type="text" class="form-control"
                                               placeholder="@lang('name') @lang($value)" name="name_{{ $key }}"
                                               id="name_{{ $key }}">
                                        <div class="invalid-feedback"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="price">@lang('price') </label>
                                <input type="number" class="form-control"
                                       placeholder="@lang('price')" name="price"
                                       id="price">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label for="">@lang('categories')</label>
                                <select name="category_uuid" id="category_uuid" class="select form-control"
                                        data-select2-id="select2-data-1-bgy2" tabindex="-1" aria-hidden="true">
                                    <option selected disabled>@lang('select') @lang('categories')</option>
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->uuid }}"> {{ $item->name }} </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        @foreach (locales() as $key => $value)
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="details_{{ $key }}">@lang('details') @lang($value)</label>
                                    <textarea type="text" class="form-control"
                                              placeholder="@lang('details') @lang($value)" name="details_{{ $key }}"
                                              id="details_{{ $key }}"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        @endforeach


                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-dark">
                                    <h4 class="m-0" style="color: white">@lang('specifications')</h4>
                                </div>
                                <div class="card-body">
                                    <div class="text-right mt-3">
                                        <button class="add_row btn btn-sm btn-dark">@lang('Add Row')</button>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="row_data">
                                        <div class="row mb-3">
                                            <div class="col-md-11">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <input type="text" name="fsize[]" class="form-control"
                                                               placeholder="@lang('size')" required>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <input type="text" name="fquantity[]" class="form-control"
                                                               placeholder="@lang('quantity')" required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <button class="btn btn-danger w-100 remove_row"><i
                                                        class="fas fa-times"></i></button>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="input-field">
                                <label class="active">@lang('Photos')</label>
                                <div class="input-images" style="padding-top: .5rem;"></div>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-primary done">@lang('save')</button>

                        <button type="button" class="btn btn-secondary"
                                data-dismiss="modal">@lang('close')</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="edit_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
         aria-hidden="true">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">@lang('edit')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('products.update') }}" method="POST" id="form_edit" class=""
                      enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="uuid" id="uuid" class="form-control"/>
                    <div class="modal-body">

                        @foreach (locales() as $key => $value)
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="name_{{ $key }}">@lang('name') @lang($value)</label>
                                    <input type="text" class="form-control"
                                           placeholder="@lang('name') @lang($value)"
                                           name="name_{{ $key }}" id="edit_name_{{ $key }}">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>

                        @endforeach
                        <div class="col-6">
                            <div class="form-group">
                                <label for="price">@lang('price')</label>
                                <input type="number" class="form-control"
                                       placeholder="@lang('price')"
                                       name="price" id="edit_price">
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="edit_category_uuid">@lang('categories')</label>
                                <select class="form-control" id="edit_category_uuid" name="category_uuid" required>
                                    <option value="">@lang('select') @lang('categories')</option>
                                    @foreach ($categories as $item)
                                        <option value="{{ $item->uuid }}"> {{ $item->name }} </option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                        </div>
                        @foreach (locales() as $key => $value)
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="details_{{ $key }}">@lang('details') @lang($value)</label>
                                    <textarea type="text" class="form-control"
                                              placeholder="@lang('details') @lang($value)"
                                              name="details_{{ $key }}" id="edit_details_{{ $key }}"></textarea>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                        @endforeach


                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header bg-dark">
                                    <h4 class="m-0" style="color: white">@lang('specifications')</h4>
                                </div>
                                <div class="card-body">
                                    <div class="text-right mt-3">
                                        <a id="addRow" class="add_row btn btn-sm btn-dark">@lang('Add Row')</a>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="row_data">

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="add_images">
                            <div class="col-12 edit_images">
                                <div class="input-field">
                                    <label class="active">@lang('Photos')</label>
                                    <div class="input-images-2" style="padding-top: .5rem;"></div>
                                </div>
                                <div class="invalid-feedback"></div>
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
                url: '{{ route('products.indexTable', app()->getLocale()) }}',
                data: function (d) {
                    d.status = $('#s_status').val();
                    d.category_uuid = $('#s_category_uuid').val();
                    d.user_name = $('#s_number').val();
                    d.price = $('#s_price').val();
                    d.name = $('#s_name').val();
                }
            },
            dom: '<"row"<"col-md-12"<"row"<"col-md-6"B><"col-md-6"f> > ><"col-md-12"rt> <"col-md-12"<"row"<"col-md-5"i><"col-md-7"p>>> >',
            "buttons": [
                {

                    "extend": 'excel',
                    text: '<span class="fa fa-file-excel-o"></span> @lang('Excel Export')',
                    "titleAttr": 'Excel',
                    "action": newexportaction,
                    "exportOptions": {
                        columns: ':not(:last-child)',
                    },
                    "filename": function () {
                        var d = new Date();
                        var l = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
                        var n = d.getHours() + "-" + d.getMinutes() + "-" + d.getSeconds();
                        return 'List_' + l + ' ' + n;
                    },
                },
            ],
            columns: [{
                "render": function (data, type, full, meta) {
                    return `<td><input type="checkbox" onclick="checkClickFunc()" value="${data}" class="box1" ></td>
`;
                },
                name: 'checkbox',
                data: 'checkbox',
                orderable: false,
                searchable: false
            },

                {
                    data: 'name_translate',
                    name: 'name'
                },
                {
                    data: 'price',
                    name: 'price'
                },
                {
                    data: 'category_name',
                    name: 'category_name',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'number',
                    name: 'number',
                    orderable: false,
                    searchable: false,
                },
                {
                    data: 'status',
                    name: 'status'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: true
                },
                {{--                @endif--}}
            ]

        });

        $(document).ready(function () {
            $(document).on('click', '.btn_edit', function (event) {
                $('.ss').remove();
                $('.edit_images').remove()
                $('.add_images').append(` <div class="col-12 edit_images">
                        <div class="input-field">
                            <label class="active">@lang('Photos')</label>
                            <div class="input-images-2" style="padding-top: .5rem;"></div>
                        </div>
                        <div class="invalid-feedback"></div>
                    </div>`)
                $('input').removeClass('is-invalid');
                $('.invalid-feedback').text('');
                event.preventDefault();
                var button = $(this)
                var uuid = button.data('uuid')
                $('#uuid').val(uuid);


                console.log(button.data('name'))
                $('#edit_price').val(button.data('price'))
                @foreach (locales() as $key => $value)
                $('#edit_name_{{ $key }}').val(button.data('name_{{ $key }}'))
                $('#edit_details_{{ $key }}').val(button.data('details_{{ $key }}'))

                @endforeach

                $('#edit_category_uuid').val(button.data('category_uuid')).trigger('change');
                console.log(button.data('images_uuid').split(','))
                let fileArray = button.data('images').split(',');
                let fileArrayUuids = button.data('images_uuid').split(',');
                var preloaded = []; // Empty array
                $.each(fileArray, function (index, fileName) {
                    var object = {
                        id: fileArrayUuids[index],
                        src: '{{ url('/') }}/storage/' + fileName
                    };


                    preloaded.push(object)
                })
                console.log(preloaded)
                $('.input-images-2').imageUploader({
                    preloaded: preloaded,
                    imagesInputName: 'images[]',
                    preloadedInputName: 'delete_images',
                    maxSize: 2 * 1024 * 1024,
                    maxFiles: 20,
                    with: 100
                });

                var fileArrayKey = button.data('size') + '';
                var fileArrayVlaue = button.data('quantity') + '';
                console .log(fileArrayKey)

                if (fileArrayKey.indexOf(',') >= 0) {
                     fileArrayVlaue = button.data('quantity').split(',');
                     fileArrayKey = button.data('size').split(',');
                    if (fileArrayKey.length >= 0) {
                        $.each(fileArrayKey, function (index, fileName) {
                            console.log(fileName)
                            console.log(index)
                            $('.row_data').append(`<div class="row mb-3 ss">
                        <div class="col-md-11">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="fsize[]" value="${fileArrayKey[index]}" class="form-control" placeholder="{{__('size')}}" required>
                                </div>

                                <div class="col-md-4">
                                    <input type="text" name="fquantity[]" value="${fileArrayVlaue[index]}" class="form-control" placeholder="{{__('quantity')}}" required>
                                </div>



                            </div>
                        </div>
                        <div class="col-md-1">
                            <a class="btn btn-danger w-100 remove_row"><i class="fas fa-times"></i></a>
                        </div>
                    </div>`);
                        })

                    }
                }else{
                    $('.row_data').append(`<div class="row mb-3 ss">
                        <div class="col-md-11">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="fsize[]" value="${fileArrayKey}" class="form-control" placeholder="{{__('size')}}" required>
                                </div>

                                <div class="col-md-4">
                                    <input type="text" name="fquantity[]" value="${fileArrayVlaue}" class="form-control" placeholder="{{__('quantity')}}" required>
                                </div>



                            </div>
                        </div>
                        <div class="col-md-1">
                            <a class="btn btn-danger w-100 remove_row"><i class="fas fa-times"></i></a>
                        </div>
                    </div>`);


                }

            });
        });


        $('.add_row').click(function (e) {
            e.preventDefault();
            console.log('ddd')
            const row = `<div class="row mb-3 ss">
                        <div class="col-md-11">
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" name="fsize[]" class="form-control" placeholder="{{__('key')}}" required>
                                </div>

                                <div class="col-md-4">
                                    <input type="text" name="fquantity[]" class="form-control" placeholder="{{__('value')}}" required>
                                </div>



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
    </script>
    <script async
            src="https://maps.googleapis.com/maps/api/js?key={{ GOOGLE_API_KEY }}&callback=initMap">
    </script>
@endsection
