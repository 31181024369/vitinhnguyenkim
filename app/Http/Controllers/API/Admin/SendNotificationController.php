<?php

namespace App\Http\Controllers\API\Admin;

use Pusher\Pusher;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Notifications\TestNotification;

class SendNotificationController extends Controller
{

    public function sendNotifications(Request $request)
    {
        $selectedMemberIds = $request->input('mem_id');
        $message = $request->input('message');

        $members = Member::whereIn('mem_id', $selectedMemberIds)->get();

        foreach ($members as $member) {
            event(new SendNotificationController($member, $message));
        }
        

        return response()->json(['message' => 'Notifications sent successfully']);
    }


    public function create()
    {
        return view('notification');
    }

    public function store(Request $request)
    {
        $id = $request->mem_id;
        $user = Member::find($id); // id của user mình đã đăng kí ở trên, user này sẻ nhận được thông báo
        $data = $request->only([
            'title',
            'content',
        ]);
        $options = array(
            'cluster' => 'ap1',
            'encrypted' => true
        );

        $pusher = new Pusher(
            env('PUSHER_APP_KEY'),
            env('PUSHER_APP_SECRET'),
            env('PUSHER_APP_ID'),
            $options
        );

        $pusher->trigger('NotificationEvent', 'send-message', $data);

        $user->notify(new TestNotification($data));

        return view('notification');
    }
}
