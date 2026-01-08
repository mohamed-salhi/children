<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Models\SectionContact;
use App\Models\SectionFeatures;
use App\Models\SectionFeaturesItems;
use App\Models\SectionHero;
use App\Models\SectionJourney;
use App\Models\SectionJourneyItem;
use App\Models\SectionService;
use App\Models\SectionServiceItems;
use App\Models\Setting;
use App\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    public function getHeroSection()
    {
        $hero = SectionHero::query()->first();
        return view('admin.content.section_hero', compact('hero'));
    }

    public function postHeroSection(Request $request)
    {

        $rules = [];
        foreach (locales() as $key => $language) {
            $rules['title_' . $key] = 'required|string|max:45';
            $rules['details_' . $key] = 'required|string|max:45';
            $rules['button_' . $key] = 'required|string|max:45';
        }
        $rules['image'] = 'nullable|image';

        $request->validate($rules);

        $data = [];
        foreach (locales() as $key => $language) {
            $data['title'][$key] = $request->get('title_' . $key);
            $data['details'][$key] = $request->get('details_' . $key);
            $data['button'][$key] = $request->get('button_' . $key);
        }

        $hero = SectionHero::query()->updateOrCreate(
            ['id' => 1],   // مفتاح البحث
            $data
        );
        if ($request->has('image')) {
            UploadImage($request->image, SectionHero::PATH_IMAGE, SectionHero::class, $hero->id, true, null, Upload::IMAGE);
        }
        return response()->json([
            'تم تحديث الإعدادات بنجاح'
        ]);
    }

    public function getjourneySection()
    {
        $journey = SectionJourney::query()->first();
        return view('admin.content.section_journey', compact('journey'));
    }

    public function postjourneySection(Request $request)
    {
        $rules = [
            'images' => 'nullable',
            'images.*' => 'required|mimes:jpeg,jpg,png|max:2048',

        ];
        foreach (locales() as $key => $language) {
            $rules['title_' . $key] = 'required|string|max:45';
            $rules['details_' . $key] = 'required|string|max:45';
        }

        $request->validate($rules);

        $data = [];
        foreach (locales() as $key => $language) {
            $data['title'][$key] = $request->get('title_' . $key);
            $data['details'][$key] = $request->get('details_' . $key);
        }

        $journey = SectionJourney::query()->updateOrCreate(
            ['id' => 1],   // مفتاح البحث
            $data
        );
        $journey->items()->delete();
        $count = count($request->items_en);

        for ($i = 0; $i < $count; $i++) {
            SectionJourneyItem::create([
                'section_journey_id' => 1,
                'item' => [
                    'en' => [$request->items_en[$i]],
                    'ar' => [$request->items_ar[$i]],
                ]
            ]);
        }
        if (isset($request->delete_images)) {
            $images = Upload::query()->where('imageable_type', SectionJourney::class)->where('imageable_id', $journey->id)->whereNotIn('uuid', $request->delete_images)->get();

            foreach ($images as $item) {
                File::delete(public_path(SectionJourney::PATH_IMAGE . $item->filename));
                $item->delete();
            }
        }
        if ($request->hasFile('images')) {
            foreach ($request->images as $item) {
                UploadImage($item, SectionJourney::PATH_IMAGE, SectionJourney::class, $journey->id, false, null, Upload::IMAGE); // one يعني انو هذه الصورة تابعة لمعرض الاعمال الي من نوع الفيديوهات

            }
        }
        return response()->json([
            'تم تحديث الإعدادات بنجاح'
        ]);
    }


    public function getFeaturesSection()
    {
        $features = SectionFeatures::query()->with('items')->first();

        return view('admin.content.section_features', compact('features'));
    }

    public function postFeaturesSection(Request $request)
    {

        foreach (locales() as $key => $language) {
            $rules['title_' . $key] = 'required|string|max:45';
        }
        $rules['image'] = 'nullable|image';

        $request->validate($rules);

        $data = [];
        foreach (locales() as $key => $language) {
            $data['title'][$key] = $request->get('title_' . $key);
        }

        $feature = SectionFeatures::query()->updateOrCreate(
            ['id' => 1],   // مفتاح البحث
            $data
        );
        if ($request->has('image')) {
            UploadImage($request->image, SectionFeatures::PATH_IMAGE, SectionFeatures::class, $feature->id, true, null, Upload::IMAGE);
        }

        $feature->items()->delete();
        $count = count($request->title_item_en);

        for ($i = 0; $i < $count; $i++) {
         $item=   SectionFeaturesItems::create([
                'section_feature_id' => 1,
                'title' => [
                    'en' => [$request->title_item_en[$i]],
                    'ar' => [$request->title_item_ar[$i]],
                ],
                'details' => [
                    'en' => [$request->details_item_en[$i]],
                    'ar' => [$request->details_item_ar[$i]],
                ]
            ]);
            if (isset($request->image_item[$i])) {
                UploadImage($request->image_item[$i], SectionFeaturesItems::PATH_IMAGE, SectionFeaturesItems::class, $item->id, true, null, Upload::IMAGE);
            }

        }

        return response()->json([
            'تم تحديث الإعدادات بنجاح'
        ]);
    }

    public function getServicesSection()
    {
        $services= SectionService::query()->with('items')->first();

        return view('admin.content.section_services', compact('services'));
    }

    public function postServicesSection(Request $request)
    {

        foreach (locales() as $key => $language) {
            $rules['title_' . $key] = 'required|string|max:45';
        }
        $rules['image'] = 'nullable|image';
        foreach (locales() as $key => $lang) {
            $rules["title_item_$key"]   = 'required|array';
            $rules["details_item_$key"] = 'required|array';
            $rules["button_item_$key"]  = 'required|array';

            $rules["title_item_$key.*"]   = 'required|string|max:255';
            $rules["details_item_$key.*"] = 'required|string|max:500';
            $rules["button_item_$key.*"]  = 'required|string|max:255';
        }

        $rules['image_item']   = 'nullable|array';
        $rules['image_item.*'] = 'nullable|image|mimes:jpg,png,jpeg,webp|max:2048';

        $request->validate($rules);

        $data = [];
        foreach (locales() as $key => $language) {
            $data['title'][$key] = $request->get('title_' . $key);
        }

        $service = SectionService::query()->updateOrCreate(
            ['id' => 1],   // مفتاح البحث
            $data
        );
        if ($request->has('image')) {
            UploadImage($request->image, SectionService::PATH_IMAGE, SectionService::class, $service->id, true, null, Upload::IMAGE);
        }

        $service->items()->delete();
        $count = count($request->title_item_en);

        for ($i = 0; $i < $count; $i++) {
            $item=   SectionServiceItems::create([
                'section_service_id' => 1,
                'title' => [
                    'en' => [$request->title_item_en[$i]],
                    'ar' => [$request->title_item_ar[$i]],
                ],
                'details' => [
                    'en' => [$request->details_item_en[$i]],
                    'ar' => [$request->details_item_ar[$i]],
                ],
                  'button' => [
                'en' => [$request->button_item_en[$i]],
                'ar' => [$request->button_item_ar[$i]],
            ]
            ]);
            if (@$request->image_item[$i]) {
                UploadImage($request->image_item[$i], SectionFeaturesItems::PATH_IMAGE, SectionServiceItems::class, $item->id, true, null, Upload::IMAGE);
            }

        }

        return response()->json([
            'تم تحديث الإعدادات بنجاح'
        ]);
    }

    public function getContactSection()
    {
        $contact = SectionContact::query()->first();
        return view('admin.content.section_contact', compact('contact'));
    }

    public function posContactSection(Request $request)
    {

        $rules = [];
        foreach (locales() as $key => $language) {
            $rules['title_' . $key] = 'required|string|max:45';
            $rules['details_' . $key] = 'required|string|max:45';
        }
        $rules['image'] = 'nullable|image';

        $request->validate($rules);

        $data = [];
        foreach (locales() as $key => $language) {
            $data['title'][$key] = $request->get('title_' . $key);
            $data['details'][$key] = $request->get('details_' . $key);
        }

        $contact = SectionContact::query()->updateOrCreate(
            ['id' => 1],   // مفتاح البحث
            $data
        );
        if ($request->has('image')) {
            UploadImage($request->image, SectionContact::PATH_IMAGE, SectionContact::class, $contact->id, true, null, Upload::IMAGE);
        }
        return response()->json([
            'تم تحديث الإعدادات بنجاح'
        ]);
    }

}
