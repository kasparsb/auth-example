<?php namespace App\Helpers;

use Str;
use Exception;

function createUniqueModel($modelClass, $fieldName='hash', $data=[], $length=48, $tries=0) {
    try {
        $model = $modelClass::create(
            array_merge(
                $data,
                [
                    $fieldName => Str::random($length)
                ]
            )
        );
    }
    catch (\Illuminate\Database\QueryException $e) {

        // In case of MySql use errorInfo[1] instead of getCode
        if ($e->errorInfo[1] === 1062) {
            if ($tries > 500) {
                throw new Exception('Can not generate unique model. Field name '.$fieldName);
            }
        }
        else {
            throw $e;
        }

        return createUniqueModel($modelClass, $data, $fieldName, $length, ++$tries);
    }

    return $model;
}
