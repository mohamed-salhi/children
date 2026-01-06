<?php

namespace App\Http\Controllers\Admin\Support;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class SupportController extends Controller
{

    public function index($id = null)
    {
        if ($id) {
            $chat = Admin::query()->where('id', $id)->with([
                'message'=> function($q){
                    $q->paginate(20);
                }
            ])->orderBy('created_at')->first();

            return view('admin.support.chat', compact('chat'))->render();

        } else {
            $admins = Admin::all();

            $msg = Admin::query()->has('message')->with([
                'message'=> function($q){
                    $q->paginate(20);
                }
            ])->orderBy('created_at')->first();

            return view('admin.support.index', compact('admins', 'msg'));

        }
    }

    public function message(Request $request)
    {
        $rules = [
            'message' => 'required|max:100',
            'user_uuid' => 'required|',
        ];
        $this->validate($request, $rules);

        $request->merge([
            'status' => 'admin',
            'type' => 1,
            'view_admin'=>date('Y-m-d H:i:s')

        ]);
        $msg = Message::create($request->only('message', 'user_uuid', 'status', 'type'));
        event(new \App\Events\Msg($request->message, $request->user_uuid, "admin", $request->user_uuid, $msg->user->image, 1, $msg->created_at,$msg->type_text));
//        event(new \App\Events\Chat($request->message, $request->user_uuid,  $request->user_uuid));
        return 'done';

    }

    public function readMore(Request $request, $id)
    {

        $chat = User::query()->where('uuid', $id)->with([
            'message'=> function($q){
            $q->paginate(5);
            }
        ])->orderBy('created_at')->first();
        $data = '';
        for ($i = count($chat->message) - 1; $i > 0; $i--) {
            if ($chat->message[$i]->status == 'admin') {
                if ($chat->message[$i]->type == \App\Models\Message::TEXT) {
                    $data .= '    <li class="clearfix">
                                <div class="message-data">
                                    <span class="message-data-time"> '.$chat->message[$i]->created_at->diffForHumans().'</span>
                                </div>
                                <div class="message my-message"> '.$chat->message[$i]->content.'</div>
                            </li>';
                } elseif ($chat->message[$i]->type == \App\Models\Message::IMAGE) {
                    $data .= '              <img id="flag"
                                 src="'.$chat->message[$i]->content.'"
                                 alt=""/>';
                } elseif ($chat->message[$i]->type == \App\Models\Message::VOICE) {
                    $data .= ' <audio controls>
                                <source src="'.$chat->message[$i]->content.'" type="audio/ogg">
                                <source src="'.$chat->message[$i]->content.'" type="audio/mpeg">
                    Your browser does not support the audio element.
                            </audio>';
                }
            }
            else {
                if ($chat->message[$i]->type == \App\Models\Message::TEXT) {
                    $data .= ' <li class="clearfix">
                            <div class="message-data text-right">
                                <span class="message-data-time">'.$chat->message[$i]->created_at->diffForHumans().'</span>
                                <img
                                    src="'.$chat->image.'"
                                    alt="avatar">
                            </div>
                            <div class="message-data text-right">


                                    <div class="message other-message float-right">
                                        '.$chat->message[$i]->message.'
                                    </div>
                                           </div>
                        </li>
';
                }elseif ($chat->message[$i]->type == \App\Models\Message::IMAGE){
                    $data .= ' <li class="clearfix">
                            <div class="message-data text-right">
                                <span class="message-data-time">'.$chat->message[$i]->created_at->diffForHumans().'</span>
                                <img
                                    src="'.$chat->image.'"
                                    alt="avatar">
                            </div>
                            <div class="message-data text-right">


                                    <div class="message other-message float-right">
   <img
                                        src = "'.$chat->message[$i]->content.'"
                                        height = "100" width = "200" >                                    </div>
                                           </div>
                        </li>
';
                }



                    elseif($chat->message[$i]->type == \App\Models\Message::VOICE){
                        $data .= ' <li class="clearfix">
                            <div class="message-data text-right">
                                <span class="message-data-time">'.$chat->message[$i]->created_at->diffForHumans().'</span>
                                <img
                                    src="'.$chat->image.'"
                                    alt="avatar">
                            </div>
                            <div class="message-data text-right">


                                    <div class="message other-message float-right">
         <audio controls>
                                        <source src = "'.$chat->message[$i]->content.'" type = "audio/ogg" >
                                        <source src = "'.$chat->message[$i]->content.'" type = "audio/mpeg" >
                    Your browser does not support the audio element .
                                    </audio >                               </div>
                                           </div>
                        </li>
';
                    }

            }


        }

        return $data;
    }
}
