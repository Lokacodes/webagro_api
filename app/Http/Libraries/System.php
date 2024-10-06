<?php

namespace App\Http\Libraries;

class System
{
    public static function response($statusCode, $payload)
    {
        return response()->json($payload, $statusCode);
    }

    public static function badRequest($validator)
    {
        $getErrors = $validator->errors()->messages();
        $indexArr = array_keys((array) $getErrors);

        $messageError = [];
        foreach ($indexArr as $key) {
            $messageData = $validator->errors()->get($key);
            $messageError[] = [
                'field' => $key,
                'error' => @$messageData[0]
            ];
        }

        return response()->json([
            'statusCode' => 400,
            'message' => 'Mohon isi form dengan benar !',
            'data' => [
                'error' => $messageError
            ]
        ], 400);
    }
}
