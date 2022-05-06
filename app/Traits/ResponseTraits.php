<?php

namespace App\Traits;

use App\Exceptions\RoleAdminException;
use App\Http\Controllers\AuthController;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

trait ResponseTraits
{
    /**
     * Response after login
     *
     * @param $status
     * @param $message
     * @param $tokenResult
     * @return JsonResponse
     */
    public function responseAuth($status, $message, $tokenResult = null)
    {
        return response()->json([
            'status_code' => $status,
            'message' => $message,
            'access_token' => $tokenResult,
        ]);
    }

    /**
     * Get role auth
     *
     * @return mixed
     */
    public function getMyRole()
    {
        return Auth::user()->role->name;
    }

    /**
     * Check role manager
     *
     * @return void
     */
    public function checkRoleManager()
    {
        $auth = new AuthController();
        if (Auth::user()->role->name === $auth->manager) {
            return true;
        }
        Throw new RoleAdminException();
    }

    /**
     * Check role admin
     *
     * @return void
     */
    public function checkRoleAdmin()
    {
        $auth = new AuthController();
        if (Auth::user()->role->name === $auth->admin ||
            Auth::user()->role->name === $auth->manager) {
            return true;
        }
        Throw new RoleAdminException();
    }

    /**
     * Check role user
     *
     * @return bool
     */
    public function checkRoleUser()
    {
        if (Auth::user()->role->name === $this->user) {
            return true;
        } return false;
    }

    /**
     * Response data
     *
     * @param  mixed $status
     * @param  mixed $message
     * @param  mixed $data
     * @return void
     */
    public function responseData($status = null, $message = null, $data = null)
    {
        $response['status']    = $status;
        $response['message']   = $message;
        $response['data']      = $data;
        return $response;
    }
}
