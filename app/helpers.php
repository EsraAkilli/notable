<?php

function api($data, int $status = 200): \Illuminate\Http\JsonResponse
{
    return response()->json($data, $status);
}
