<?php
if (!function_exists('apiResponse')) {
    function apiResponse($status, $message, $data = null)
    {
        $responce = [
            'status' => $status,
            'message' => $message
        ];
        if ($data) {
            $responce['data'] = $data;
        }
        return response()->json(
            $responce
        , $status);
    }
}
