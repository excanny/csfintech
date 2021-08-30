<?php
/**
 * Created by Canaan Etai.
 * Date: 5/12/19
 * Time: 6:42 PM
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Model\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Users extends Controller
{
    /**
     * @var User
     */
    private $model;

    /**
     * User constructor.
     * @param User $user
     */
    public function __construct( User $user )
    {
        $this->model = $user;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function authorizeMerchant( Request $request)
    {
        try {
            if (!auth()->attempt(['email' => request('email'), 'password' => request('password')])) {
                return response()->json([
                    'success' => false,
                    'message' => 'These business credentials do not match our records. Please check and try again'
                ]);
            }

            $user = auth()->user();

            if ( !$user->hasRole('MERCHANT') ) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ]);
            }


            // check if business is active
            if ( $user->business->status != 'ACTIVE' ) {

                return response()->json([
                    'success' => false,
                    'message' => "Business is {$user->business->status}"
                ]);
            }

            // create token for this user.
            $tokenObject = $user->createToken("{$user->business->name} personal token", ['*']);

            $token = $tokenObject->token;
            $token->save(); // save user token

            $data = collect([]);
            $data['business_name'] = $user->business->name;
//            $data['business_name'] = $user->business->name;

            $data['token'] = [
                'access_token'  => $tokenObject->accessToken,
                'token_type'    => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenObject->token->expires_at
                )->toDateTimeString()
            ];

            $payload = [
                'success'       => true,
                'data'          => $data
            ];

            return response()->json($payload);

        } catch (\Exception $exception ) {
            if ( auth()->check() ) {
                auth()->logout();
            }

           return response()->json(['success' => false, 'error' => true, 'message' => $exception->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Error logging you into the application']);
        }
    }


    /**
     * Logout a user from the application
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        try {
            request()->user()->touch();
            request()->user()->token()->revoke();

            return response()->json([
                'success'     => true,
                'message'   => 'user logged out Successfully'
            ]);
        }
        catch ( \Exception $exception ) {
            return response()->json([
                'success'     => false,
                'message'   => 'Error with sign out'
            ]);
        }
    }
}
