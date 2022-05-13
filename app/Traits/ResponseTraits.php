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
     * Check role manager
     *
     * @return bool
     * @throws RoleAdminException
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
     * @return bool
     * @throws RoleAdminException
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
     * @param $status
     * @param $message
     * @param $data
     * @return array
     */
    public function responseData($status = null, $message = null, $data = null)
    {
        $response['status']    = $status;
        $response['message']   = $message;
        $response['data']      = $data;
        return $response;
    }
}
