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
        /* =========================
     * Validation
     * ========================= */

        $rules = [];
        for ($i = 1; $i <= 4; $i++) {
            $rules["image_$i"] = 'nullable|mimes:jpeg,jpg,png';
        }
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

        $journey = SectionJourney::updateOrCreate(
            ['id' => 1],
            $data
        );
        $journey->items()->delete();

        if ($request->items_en) {
            foreach ($request->items_en as $i => $item) {
                SectionJourneyItem::create([
                    'section_journey_id' => $journey->id,
                    'item' => [
                        'en' => [$request->items_en[$i] ?? ''],
                        'ar' => [$request->items_ar[$i] ?? ''],
                    ]
                ]);
            }
        }
        $imagesMap = [1 => 'imageOneJourney', 2 => 'imageTowJourney', 3 => 'imageThreeJourney', 4 => 'imageFourJourney',
        ];
        foreach ($imagesMap as $i => $column) {
            if ($request->has("image_$i")) {
                UploadImage($request->file("image_$i"), SectionJourney::PATH_IMAGE, SectionJourney::class, $journey->id, true, null, Upload::IMAGE, $column
                );
            }
        }

        return response()->json([
            'message' => 'تم تحديث الإعدادات بنجاح'
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
        $rules['cover_image'] = 'nullable|image';

        $request->validate($rules);

        $data = [];
        foreach (locales() as $key => $language) {
            $data['title'][$key] = $request->get('title_' . $key);
        }

        $feature = SectionFeatures::query()->updateOrCreate(
            ['id' => 1],   // مفتاح البحث
            $data
        );
        if ($request->has('cover_image')) {
            UploadImage($request->cover_image, SectionFeatures::PATH_IMAGE, SectionFeatures::class, $feature->id, true, null, Upload::IMAGE);

        }
        $feature->items()->delete();
        $count = count($request->title_item_en);


        for ($i = 0; $i < $count; $i++) {
            $item = SectionFeaturesItems::create([
                'section_feature_id' => 1,
                'title' => [
                    'en' => [$request->title_item_en[$i]],
                    'ar' => [$request->title_item_ar[$i]],
                ],
                'details' => [
                    'en' => [$request->details_item_en[$i]],
                    'ar' => [$request->details_item_ar[$i]],
                ],
                'icon' => $request->icon_item[$i]
            ]);
        }

        return response()->json([
            'تم تحديث الإعدادات بنجاح'
        ]);
    }

    public function getServicesSection()
    {

        $services = SectionService::query()->with('items')->first();

        return view('admin.content.section_services', compact('services'));
    }

    public function postServicesSection(Request $request)
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

        $service = SectionService::query()->updateOrCreate(
            ['id' => 1],   // مفتاح البحث
            $data
        );
        if ($request->has('image')) {
            UploadImage($request->image, SectionService::PATH_IMAGE, SectionService::class, $service->id, true, null, Upload::IMAGE);
        }

        $count = count($request->id);

        for ($i = 1; $i <= $count; $i++) {

            $item = SectionServiceItems::query()->where('id',$i)->update(
                [
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
                    ]]
            );
            if ($request->has("image_item$i")) {
                UploadImage($request->file("image_item$i"), SectionFeaturesItems::PATH_IMAGE, SectionServiceItems::class, $item->id, true, @$item->imageServiceitem->uuid, Upload::IMAGE,'image_'.$i);
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
