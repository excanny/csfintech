<?php

namespace App\Http\Controllers;

use App\Model\Dispute as TransactionDispute;
use App\Model\DisputeMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Dispute extends Controller
{
    /**
     * @var Dispute
     */
    private $dispute;

    /**
     * Dispute constructor.
     * @param TransactionDispute $dispute
     */
    public function __construct( TransactionDispute $dispute )
    {
        $this->dispute = $dispute;
    }

    public function index () {
        // Fetch all logged disputes
        $disputes = $this->dispute->orderBy('id', 'desc')
            ->get();

        // Return view
        return view('admin.disputes.viewDisputes', compact('disputes'));
    }

    public function messages ( $id ) {
        $dispute = $this->dispute->with(['messages'=> function ($query) {
            $query->with('admin');
        }, 'business'])->find($id);

        return view('admin.disputes.disputeMessages', compact('dispute'));
    }

    public function replyDispute( Request $request, $id )
    {

        // Get request data
        $message['text'] = $request->get('message');
        $dispute = $this->dispute->find($id);

        $message['type'] = DisputeMessage::$text;
        $message['admin_id'] = auth()->user()->id;

        // Check if message is an image
        if ( $request->hasFile('image') && $request->file('image')->isValid()) {
            $message['type'] = DisputeMessage::$image;

            // Validate image
            $validated = $request->validate([
                'image' => 'mimes:jpeg,png|max:1024'
            ]);

            $validated['name'] = md5(\Str::random(16). time());
            $extension = $request->image->extension();

            $image_url = $request->image->storeAs('/dispute-images', $validated['name'].".".$extension, ['disk' => 'public']);
            $message['image_url'] = $image_url;
        }

        $dispute->messages()->create($message);

        return redirect()->back();
    }

    public function closeDispute( $id )
    {
        $user = auth()->user();
        $dispute = $this->dispute->find($id);
        $dispute->update(['status' => \App\Model\Dispute::$closed ]);

        // Log Activity
        $user->activities()->create([
            "info" => "{$user->firstname} {$user->lastname} closed a dispute with ref: {$dispute->reference}"
        ]);
        return redirect()->back()->with('success', 'Dispute closed successfully');
    }
}
