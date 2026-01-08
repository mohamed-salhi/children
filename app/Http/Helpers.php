<?php

use App\Models\FcmToken;
use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\Upload;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;


function rtl_assets()
{
    if (LaravelLocalization::getCurrentLocaleDirection() == 'rtl') {
        return '-rtl';
    }
    return '';
}
function setting($key, $default = null) {
    return cache('settings')[$key] ?? $default;
}
function locale()
{
    return Mcamara\LaravelLocalization\Facades\LaravelLocalization::getCurrentLocale();
}

function locales()
{
    $arr = [];
    foreach (LaravelLocalization::getSupportedLocales() as $key => $value) {
        $arr[$key] = __('' . $value['name']);
    }
    return $arr;
}

function languages()
{
    if (app()->getLocale() == 'en') {
        return ['ar' => 'arabic', 'en' => 'english'];
    } else {
        return ['ar' => 'العربية', 'en' => 'النجليزية'];
    }
}

function mainResponse($status, $msg, $items, $validator = [], $code = 200, $pages = null)
{
    $item_with_paginate = $items;
    if (gettype($items) == 'array') {
        if (count($items)) {
            $item_with_paginate = $items[array_key_first($items)];
        }
    }

    if (isset(json_decode(json_encode($item_with_paginate, true), true)['data'])) {
        $pagination = json_decode(json_encode($item_with_paginate, true), true);
        $new_items = $pagination['data'];
        $pages = [
            "current_page" => $pagination['current_page'],
            "first_page_url" => $pagination['first_page_url'],
            "from" => $pagination['from'],
            "last_page" => $pagination['last_page'],
            "last_page_url" => $pagination['last_page_url'],
            "next_page_url" => $pagination['next_page_url'],
            "path" => $pagination['path'],
            "per_page" => $pagination['per_page'],
            "prev_page_url" => $pagination['prev_page_url'],
            "to" => $pagination['to'],
            "total" => $pagination['total'],
        ];
    } else {
        $pages = [
            "current_page" => 0,
            "first_page_url" => '',
            "from" => 0,
            "last_page" => 0,
            "last_page_url" => '',
            "next_page_url" => null,
            "path" => '',
            "per_page" => 0,
            "prev_page_url" => null,
            "to" => 0,
            "total" => 0,
        ];
    }

    if (gettype($items) == 'array') {
        if (count($items)) {
            $new_items = [];
            foreach ($items as $key => $item) {
                if (isset(json_decode(json_encode($item, true), true)['data'])) {
                    $pagination = json_decode(json_encode($item, true), true);
                    $new_items[$key] = $pagination['data'];
                } else {
                    $new_items[$key] = $item;
                }

                $items = $new_items;
            }
        }
    } else {
        if (isset(json_decode(json_encode($item_with_paginate, true), true)['data'])) {
            $pagination = json_decode(json_encode($item_with_paginate, true), true);
            $items = $pagination['data'];
        }
    }

//    $items = $new_items;

    $aryErrors = [];
    foreach ($validator as $key => $value) {
        $aryErrors[] = ['field_name' => $key, 'messages' => $value];
    }
    /*    $aryErrors = array_map(function ($i) {
            return $i[0];
        }, $validator);*/

    $newData = ['status' => $status, 'message' => __($msg), 'data' => $items, 'pages' => $pages, 'errors' => $aryErrors];

    return response()->json($newData);
}

function paginate($items, $perPage = 15, $page = null, $options = [])
{
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $items = $items instanceof Collection ? $items : Collection::make($items);
    return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
}

function paginateOrder($items, $perPage = 15, $page = null, $options = [])
{
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $items = $items instanceof Collection ? $items : Collection::make($items);
    return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
}

function pageResource($data, $resource)
{
    $items = $data->getCollection();
    $items = $resource::collection($items);
    $data->setCollection(collect($items));
    return $data;
}

function latLngFormat($value)
{
    return number_format($value, 6, '.', '');
}

function UploadImage($file, $path = null, $model, $imageable_id, $update = false, $id = null, $type, $name = null)
{
    $path = $file->store($path, 'public');
    if (!$update) {
        return Upload::create([
            'filename' => $path,
            'path' => $path,
            'imageable_id' => $imageable_id,
            'imageable_type' => $model,
            'type' => $type,
            'name' => $name
        ]);
    } else {
        if ($name) {
            $image = Upload::query()->where('imageable_id', $imageable_id)->where('imageable_type', $model)->where('name', $name)->first();
            if ($image) {
                Storage::delete('public/' . @$image->path);
                return $image->update(
                    [
                        'filename' => $path,
                        'path' => $path,
                        'imageable_id' => $imageable_id,
                        'imageable_type' => $model,
                        'type' => $type,
                        'name' => $name
                    ]
                );
            } else {
                return Upload::create([
                    'filename' => $path,
                    'path' => $path,
                    'imageable_id' => $imageable_id,
                    'imageable_type' => $model,
                    'type' => $type,
                    'name' => $name
                ]);
            }
        } else {
            $image = Upload::where('imageable_id', $imageable_id)->where('imageable_type', $model)->where('type', $type)->first();
            if ($id) {
                $image = Upload::where('uuid', $id)->first();
            }
            if ($image) {
                Storage::delete('public/' . @$image->path);
                $image->update(
                    [
                        'filename' => $path,
                        'path' => $path,
                        'imageable_id' => $imageable_id,
                        'imageable_type' => $model,
                        'type' => $type,
                        'name' => $name
                    ]
                );
                return $path;
            } else {
                return Upload::create([
                    'filename' => $path,
                    'path' => $path,
                    'imageable_id' => $imageable_id,
                    'imageable_type' => $model,
                    'type' => $type,
                    'name' => $name
                ]);
            }
        }
    }

}

function fcmNotification($token, $id, $title, $content, $type, $reference_id, $reference_type,$device, $icon = null)
{
//    dump($device, $token);
    if (count($token) < 1)
        return null;

    $msg = [
        'uuid' => $id,
        'title' => $title,
        'content' => $content,
        'body' => $content, //default for ios
        'type' => $type,
        'reference_uuid' => $reference_id,
        'reference_type' => $reference_type,

        'icon' => $icon,
        'sound' => url('/') . '/sound.mp3',
    ];


    if ($device == 'ios') {
        $fields = [
            'registration_ids' => $token,
            'notification' => $msg,
        ];
    } else {
        $fields = [
            'registration_ids' => $token,
            'data' => $msg,
        ];
    }

    $headers = [
        'Authorization: key=' . 'AAAAYWlMH_w:APA91bHU0-C5Dt72_AoUUwPCKb5jzGS79dcjEzPodkxuf7bvPlBaewuex7d34A7MNESffPKygFpZ-U8KAs5qUb1T_40AlkbUmIXqEmdXAQX8_zEXilVVCM5XApYRQtmlMNWmzIIYfHMC',
        'Content-Type: application/json'
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
    curl_close($ch);
//    dump($result);
    return $result;
}

function generateLocalizedSlugs(array $names, $separator = '-', $modelClass = null): array
{
    $slugs = [];

    foreach ($names as $locale => $value) {
        $slug = trim($value);
        $slug = mb_strtolower($slug, 'UTF-8');
        $slug = preg_replace('/[^\p{Arabic}a-zA-Z0-9\s\-]+/u', '', $slug);
        $slug = preg_replace('/[\s\-]+/u', $separator, $slug);
        $slug = trim($slug, $separator);

        $originalSlug = $slug;
        $counter = 1;

        if ($modelClass) {
            while (
                $modelClass::where("slug->{$locale}", $slug)->exists()
            ) {
                $slug = $originalSlug . $separator . $counter;
                $counter++;
            }
        }

        $slugs[$locale] = $slug;
    }

    return $slugs;
}

//function notfication($receiver_uuid, $sender, $type = null, $msg = null, $name = null, $request = null)
//{
//    if ($msg) {
//        $content = [
//            'ar' => $name . __($msg, [], 'ar'),
//            'en' => $name . $msg
//        ];
//    } else {
//        $content = [
//            'ar' => $request->get('content_ar'),
//            'en' => $request->get('content_en')
//        ];
//    }
//
//    $ios_tokens = FcmToken::query()
//        ->whereIn("user_uuid", $receiver_uuid)
//        ->where('fcm_device', 'ios')
//        ->pluck('fcm_token')->toArray();
//    $android_tokens = FcmToken::query()
//        ->whereIn("user_uuid", $receiver_uuid)
//        ->where('fcm_device', 'android')
//        ->pluck('fcm_token')->toArray();
//
//    $icon = null;
//    if ($sender != 'admin') {
//        $icon = $sender->image;
//        $sender = $sender->uuid;
//
//    }
//    $title = null;
//    if ($request) {
//        $title = ['en' => $request->get('title_en'), 'ar' => $request->get('title_en')];
//    }
//
//    $notification = Notification::query()->create([
//        'sender' => $sender,
//        'icon' => $icon,
//        'content' => $content,
//        'type' => $type,
//        'title' => $title
//    ]);
//
//    foreach ($receiver_uuid as $uuid) {
//        NotificationUser::query()->create([
//            'receiver_uuid' => $uuid,
//            'notification_uuid' => $notification->uuid
//        ]);
//    }
//    if ($ios_tokens) {
//        sendFCM($msg, $ios_tokens, "ios");
//    }
//    if ($android_tokens) {
//        sendFCM($msg, $android_tokens, "android");
//    }
//}

?>




