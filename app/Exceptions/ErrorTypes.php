<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class ErrorTypes
{
    public const BAD_REQUEST = 'BAD_REQUEST';
    public const ROUTE_NOT_FOUND = 'ROUTE_NOT_FOUND';
    public const METHOD_NOT_ALLOWED = 'METHOD_NOT_ALLOWED';
    public const INPUT_VALIDATION = 'INPUT_VALIDATION';
    public const UNAUTHENTICATED = 'UNAUTHENTICATED';
    public const FORBIDDEN = 'FORBIDDEN';
    public const TOKEN_MISMATCH = 'TOKEN_MISMATCH';
    public const ITEM_NOT_FOUND = 'ITEM_NOT_FOUND';
    public const UNKNOWN = 'UNKNOWN';
    public const PROVIDER_UNAUTHORIZED = 'PROVIDER_UNAUTHORIZED';
    public const EMAIL_NOT_VERIFIED = 'EMAIL_NOT_VERIFIED';
    public const FEATURE_NOT_ACCESSIBLE = 'FEATURE_NOT_ACCESSIBLE';
    public const INITIAL_PASSWORD_CHANGED = 'INITIAL_PASSWORD_CHANGED';

    private const STATUS_BY_TYPE = [
        self::BAD_REQUEST => Response::HTTP_BAD_REQUEST,
        self::UNAUTHENTICATED => Response::HTTP_UNAUTHORIZED,
        self::FORBIDDEN => Response::HTTP_FORBIDDEN,
        self::TOKEN_MISMATCH => 419,
        self::ITEM_NOT_FOUND => Response::HTTP_NOT_FOUND,
        self::ROUTE_NOT_FOUND => Response::HTTP_NOT_FOUND,
        self::METHOD_NOT_ALLOWED => Response::HTTP_METHOD_NOT_ALLOWED,
        self::INPUT_VALIDATION => Response::HTTP_UNPROCESSABLE_ENTITY,
        self::UNKNOWN => Response::HTTP_INTERNAL_SERVER_ERROR,
        self::PROVIDER_UNAUTHORIZED => Response::HTTP_FORBIDDEN,
        self::EMAIL_NOT_VERIFIED => Response::HTTP_FORBIDDEN,
        self::FEATURE_NOT_ACCESSIBLE => Response::HTTP_LOCKED,
        self::INITIAL_PASSWORD_CHANGED => Response::HTTP_LOCKED,
    ];

    public static function message(string $type, array $params = []): string
    {
        return trans('errors.'.$type, $params);
    }

    public static function status(string $type): int
    {
        return self::STATUS_BY_TYPE[$type];
    }
}
