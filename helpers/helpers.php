<?php

function errorResponse($message = 'Something went wrong. Please try again.', $status = 0)
{
    return response()->json([
        'status'    => $status,
        'message'   => $message
    ]);
}