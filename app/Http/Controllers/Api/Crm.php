<?php


namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Model\User;
use mysql_xdevapi\Exception;

class Crm extends Controller
{
    private $model;

    public function __construct( User $user )
    {
        $this->model = $user;
    }

    public function fetchUserdetails () {
        try {
            $required = 'email';
            $data = request()->all();
            $ip = request()->ip();

            if ($ip !== env('CRM_IP')) {
                return response()->json([
                    "success" => false,
                    "message" => "Invalid IP address"
                ]);
            }

            if ( !isset($data[$required]) ) {
                return response()->json(['success' => false, 'message' => 'Request is key: '.$required]);
            }

            $email = $data['email'];

            $user = $this->model->where('email', $email)->with(['business',
                'business.wallet' => function ($query) {
                    $query->select(['business_id','account_number', 'balance', 'commission','status']);
                },
                'business.wallet_transactions',
                'business.commissionTransactions',
                'business.transactions',
                'business.fee' => function ($query) {
                    $query->select(['business_id','products']);
                },
                'activities'
            ])->first();

            if (is_null($user)) {
                return response()->json(['success' => false, 'message' => 'User not found. Please check email and try again']);
            }

            return response()->json(['success' => true, 'message' => 'Success fetching user details', 'data' => $user]);
        }
        catch (\Exception $exception) {
            return response()->json(['success'=> false, 'message' => 'Error fetching user details']);
        }
    }
}
