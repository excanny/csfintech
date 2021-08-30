<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Model\BeneficialOwner;
use App\Model\Director;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class Compliance extends Controller
{
    /**
     * @var Director
     */
    private $director;
    /**
     * @var BeneficialOwner
     */
    private $beneficialOwner;

    public function __construct(Director $director, BeneficialOwner $beneficialOwner)
    {
        $this->director = $director;
        $this->beneficialOwner = $beneficialOwner;
    }

    public function viewDocuments () {
        // Get authenticated user
        $user = auth()->user();

        // Get user's business
        $business = $user->business()
            ->select([
                'id',
                'certificate_of_incorporation',
                'articles_of_association',
                'cac_form',
                'other_document',
            ])->with(['directors', 'beneficial_owners'])
            ->first();

//        dd($business);

        // Return view
        return view('merchant.settings.documents', compact('business'));
    }


    public function addDocument ( Request $request ) {
        // Get authenticated user
        $user = auth()->user();

        // Get user's business
        $business = $user->business;

        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            // Validate request
            $request->validate([
                $key => 'required|mimes:pdf|max:2048'
            ]);

            // Check for file
            if($request->file()) {
                // Get file name
                $fileName = time().'_'.$request->$key->getClientOriginalName();

                // Create path and save file to path
                $filePath = $request->file($key)->storeAs('/document_uploads', $fileName, ['disk' => 'public']);

                // Save path in db
                $business->$key = $filePath;
                $business->save();

                // Reload
                return back()->with('success','File has been uploaded.');
            }
        }
        // Return error
        return back()->with('error','No File Attached!');
    }

    public function deleteDocument () {
        // Get request data
        $data = \request()->all();

        // Get business
        $business = auth()->user()->business()->select([$data['file']])->first();

        $file = $data['file'];

        // Update file_url to null
        auth()->user()->business()->update([
            $file => null
        ]);

        // Delete file from storage
        Storage::disk('public')->delete($business->$file);

        // Reload
        return back()->with('success', 'Successfully deleted');
    }

    public function addDirector ( Request $request ) {
        $data = $request->except('_token');

        $required = ['name', 'email', 'phone'];

        foreach ( $required as $req ) {
            if (!isset($data[$req]))
                return back()->with('error', "$req is required to add a director");
        }

        // Check if email exists
        if ($this->director->where('email', $data['email'])->exists()) {
            return redirect()->back()->with('error', 'Director with same email address already exist.');
        }

        $business = auth()->user()->business;
        $business->directors()->create($data);

        return back()->with('success', 'Director successfully created');
    }

    public function addBeneficialOwner ( Request $request ) {
        $data = $request->except('_token');

        $required = ['name', 'email', 'phone'];

        foreach ( $required as $req ) {
            if (!isset($data[$req]))
                return back()->with('error', "$req is required to add a beneficial owner");
        }

        // Check if email exists
        if ($this->beneficialOwner->where('email', $data['email'])->exists()) {
            return redirect()->back()->with('error', 'Beneficial Owner with same email address already exist.');
        }

        $business = auth()->user()->business;
        $business->beneficial_owners()->create($data);

        return back()->with('success', 'Beneficial Owner successfully created');
    }

    public function deleteCompliance () {
        $email = request()->get('email');
        $compliance = request()->get('compliance');

        $business = auth()->user()->business;

        if ($compliance == 'director') {
            $director = $business->directors()->where('email', $email)->first();

            if (is_null($director))
                return back()->with('error', 'Director not found');

            $director->delete();
        }
        elseif ($compliance == 'beneficial_owner') {

            $owner = $business->beneficial_owners()->where('email', $email)->first();

            if (is_null($owner))
                return back()->with('error', 'Beneficial Owner not found');

            $owner->delete();
        }

        return back()->with('success', 'Successfully Deleted');
    }
}
