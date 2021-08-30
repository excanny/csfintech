<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Model\DisputeMessage;
use Illuminate\Http\Request;
use App\Model\Dispute as TransactionDispute;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

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
        // Get the authenticated user
        $user = auth()->user();

        // Get the business
        $business = $user->business;

        // Fetch all disputes for the business
        $disputes = $business->disputes;

        // Return view
        return view('merchant.disputes.viewDisputes', compact('disputes'));
    }


    public function logDispute ( Request $request) {
        // Get request data
        $data = $request->all();

        // Get authenticated user
        $user = auth()->user();

        // Get business
        $business = $user->business;

        // Check if subject exists
        if ( $this->dispute->where('subject', $data['subject'])->exists() )
            return back()->with('error', 'Dispute with subject already exists');

        // Get dispute reference
        $data['reference'] = str_replace('Dispute Log: ','', $data['subject']);

        // Create dispute
        $dispute = $business->disputes()->create($data);

        // Create dispute message
        $dispute->messages()->create([
            'text' => $data['text'],
            'type' => DisputeMessage::$text
        ]);

        // Log Activity
        $user->activities()->create([
            "info" => "{$user->firstname} {$user->lastname} raised a dispute on a transaction with ref: {$dispute->reference}"
        ]);

        return redirect()->route('merchant.disputes')->with('success', 'Dispute successfully created');
    }

    public function viewMessages ( $id ) {
        $dispute = $this->dispute->with(['messages', 'business'])->find($id);

        return view('merchant.disputes.disputeMessages', compact('dispute'));
    }

    public function replyDispute( Request $request, $id )
    {
        $message['text'] = $request->get('message');
//        dd($request->all());
        $dispute = $this->dispute->find($id);

        $message['type'] = DisputeMessage::$text;
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
}
