<?php

namespace App\Http\Controllers;

use App\User;
use Dingo\Api\Routing\Helpers;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * JWT token request.
 *
 * @Resource("Auth", uri="/{model}")
 */
class AuthController extends Controller
{

    use Helpers;

    private $request;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    // HSA Authentication
    public function authenticate($model) {
        $this->validate($this->request, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Find the user by email
        $user = User::where('email', $this->request->input('email'))->first();

        if (!$user) {
            // User does not exist.
            return $this->response->errorNotFound('User does not exist.');
        }

        // Verify the password. Generate and return the token
        if (Hash::check($this->request->input('password'), $user->password)) {
            return response()->json([
                'token' => $this->jwt($user, $model)
            ], 200);
        }

        // Bad Request response
        return $this->response->errorNotFound('Email or Password is invalid.');
    }

    // RSA Authentication
    /*
    public function authenticate($model) {
        $this->validate($this->request, [
            'email' => 'required|email',
            'private_key' => 'required'
        ]);

        // Find the user by email
        $user = User::where('email', $this->request->input('email'))->first();

        if (!$user) {
            // User does not exist.
            return $this->response->errorNotFound('User does not exist.');
        }

        // Verify the private key with the public key of the User.
        // Generate and return the token
        if ($token = $this->jwt($user, $model, 'RS256', $this->request->private_key)) {
            return response()->json([
                'token' => $token
            ], 200);
        }

        // Bad Request response
        return $this->response->errorNotFound('Email or Private Key is invalid.');
    }
    */

    /**
     * Create a new token.
     *
     * @param  \App\User   $user
     * @return string
     */
    protected function jwt(User $user, $model, $alg = 'HS256', $private_key = null) {
        $payload = [
            'iss' => "pngmark",
            'sub' => $user->id,
            'cat' => $model,
            'iat' => date ('Y-m-d H:i:s'),
            'public_key' => $user->public_key, // For testing purposes only
        ];

        $secret_key = null;
        list($function, $algorithm) = JWT::$supported_algs[$alg];
        switch($function) {
            // If HSA Authentication, `JWT_SECRET` is the secret key
            case 'hash_hmac':
                $secret_key = env('JWT_SECRET');
                break;
            // If RSA Authentication, Private Key is the secret key
            case 'openssl':
                $secret_key = $private_key;
                break;
            default:
        }


        return JWT::encode($payload, $secret_key, $alg);
    }
}
