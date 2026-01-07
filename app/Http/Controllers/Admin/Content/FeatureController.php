<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\Category;

use App\Models\SectionFeatures;
use App\Models\SectionService;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class FeatureController extends Controller
{

    public function index()
    {
       return $services = SectionService::query()->orderByDesc('created_at')->get();

        return view('admin.content.section_features');
    }


    public function update(Request $request)
    {

        $rules = [
            'image' => 'nullable|mimes:jpeg,jpg,png|max:2048',

        ];
        foreach (locales() as $key => $language) {
            $rules['title_' . $key] = 'required|string|max:255';
            $rules['details_' . $key] = 'required|string|max:255';

        }
        $request->validate($rules);

        foreach (locales() as $key => $language) {
            $data['title'][$key] = $request->get('title_' . $key);
            $data['details'][$key] = $request->get('details_' . $key);

        }
        $features = SectionFeatures::query()->findOrFail($request->id);
        $features->update($data);
        if ($request->has('image')) {
            UploadImage($request->image, SectionFeatures::PATH_IMAGE, SectionFeatures::class, $features->id, true, null, Upload::IMAGE);
        }
        return response()->json([
            'item_edited'
        ]);

    }


    public function indexTable(Request $request)
    {
        $features = SectionFeatures::query()->orderByDesc('created_at');

        return Datatables::of($features)
            ->addColumn('checkbox', function ($que) {
                return $que->id;
            })
            ->addColumn('action', function ($que) {
                $data_attr = '';
                $data_attr .= 'data-id="' . $que->id . '" ';
                $data_attr .= 'data-image="' . $que->image . '" ';
                foreach (locales() as $key => $value) {
                    $details = $que->getTranslation('details', $key);
                    $title   = $que->getTranslation('title', $key);

                    // إذا كانت array، حولها لنص
                    if (is_array($details))  $details = implode(', ', $details);
                    if (is_array($title))    $title   = implode(', ', $title);

                    // إضافة للـ data_attr
                    $data_attr .= 'data-details_' . $key . '="' . e($details) . '" ';
                    $data_attr .= 'data-title_' . $key . '="'   . e($title)   . '" ';
                }
                $string = '';
                $string .= '<button class="edit_btn btn btn-sm btn-outline-primary btn_edit" data-toggle="modal"
                    data-target="#edit_modal" ' . $data_attr . '>' . __('edit') . '</button>';

                return $string;
            })

            ->rawColumns(['action', 'status'])->toJson();
    }


}
