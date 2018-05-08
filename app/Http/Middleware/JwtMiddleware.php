<?php

namespace App\Http\Middleware;

use Closure;
use Dingo\Api\Routing\Helpers;
use Exception;
use App\User;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Log;

class JwtMiddleware
{
    use Helpers;

    // HSA Authentication

    public function handle($request, Closure $next, $guard = null)
    {
        $token = str_replace("Bearer ", "", $request->header('authorization'));

        if(!$token) {
            // Unauthorized response if token not there
            return $this->response->errorUnauthorized('Token not provided.');
        }

        try {
            $credentials = JWT::decode($token, env('JWT_SECRET'), ['HS256']);
        } catch(UnexpectedValueException $e) {
            return $this->response->errorBadRequest($e);
        } catch(SignatureInvalidException $e) {
            return $this->response->errorBadRequest($e);
        } catch(BeforeValidException $e) {
            return $this->response->errorBadRequest($e);
        } catch(ExpiredException $e) {
            return $this->response->errorBadRequest('Provided token is expired.');
        } catch(Exception $e) {
            return $this->response->errorBadRequest('Decoding token failed.');
        }

        $user = User::find($credentials->sub);

        // Now let's put the user in the request class so that you can grab it from there
        $request->auth = $user;

        return $next($request);
    }


    // RSA Authentication
    /*
    public function handle($request, Closure $next, $guard = null)
    {
        $jwt = str_replace("Bearer ", "", $request->header('authorization'));

        if(!$jwt) {
            // Unauthorized response if token not there
            return $this->response->errorUnauthorized('Token not provided.');
        }

        // Decoding Payload
        $tks = explode('.', $jwt);
        if (count($tks) != 3) {
            throw new UnexpectedValueException('Wrong number of segments');
        }
        list($headb64, $bodyb64, $cryptob64) = $tks;
        if (null === $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64))) {
            throw new UnexpectedValueException('Invalid claims encoding');
        }
        // End Decoding Payload

        try {
            $credentials = JWT::decode($jwt, $payload->public_key, ['RS256']);
        } catch(UnexpectedValueException $e) {
            return $this->response->errorBadRequest($e);
        } catch(SignatureInvalidException $e) {
            return $this->response->errorBadRequest($e);
        } catch(BeforeValidException $e) {
            return $this->response->errorBadRequest($e);
        } catch(ExpiredException $e) {
            return $this->response->errorBadRequest('Provided token is expired.');
        } catch(Exception $e) {
            return $this->response->errorBadRequest('Decoding token failed.');
        }

        $user = User::find($credentials->sub);

        // Now let's put the user in the request class so that you can grab it from there
        $request->auth = $user;

        return $next($request);
    }
    */

}
