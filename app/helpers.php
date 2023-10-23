<?php

/**
 * @return \App\Models\User|null
 *
 * @noinspection PhpReturnDocTypeMismatchInspection
 * @noinspection PhpMissingReturnTypeInspection
 * @noinspection PhpIncompatibleReturnTypeInspection
 */
function user()
{
    return auth()->user(); // @phpstan-ignore-line
}

function api($data, int $status = 200): Illuminate\Http\JsonResponse
{
    return response()->json($data, $status);
}
