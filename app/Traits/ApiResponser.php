<?php

namespace App\Traits;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Validator;

trait ApiResponser
{  
  private function successResponse($data, $code)
  {
    return response()->json([
      'response' => $data,
      'code'     => $code,
    ], $code);
  }

  protected function errorResponse($exception, $code)
  {
    if( $exception instanceof ValidationException ) {
      return response()->json([
          'error'             => 'Validation',
          'type'              => $exception->validator->errors()->getMessages(), 
          'error_description' => __('exception.validation'),
          'code'              => Response::HTTP_BAD_REQUEST
        ], Response::HTTP_BAD_REQUEST);
    }

    if( $exception instanceof ModelNotFoundException ) {
      $model = strtolower(class_basename($exception->getModel()));
      // CODIGO AGREGADO PARA AÑADIR LOG DE AUDITORIA      
      $content=collect([
        'error'             => 'Model',
        'type'              => ['model' => [ __('exception.model', ['attribute' => $model]) ]], 
        'error_description' => __('exception.attribute'),
        'code'              => Response::HTTP_BAD_REQUEST
      ]);
     

      return response()->json([
          'error'             => 'Model',
          'type'              => ['model' => [ __('exception.model', ['attribute' => $model]) ]], 
          'error_description' => __('exception.attribute'),
          'code'              => Response::HTTP_BAD_REQUEST
        ], Response::HTTP_BAD_REQUEST);
    }

    if( $exception instanceof AuthenticationException ) {
      return response()->json([
        'error'             => 'Authentication',
        'type'              => ['authentication' => [__('exception.authentication')]], 
        'error_description' => __('exception.authentication'),
        'code'              => Response::HTTP_UNAUTHORIZED
      ], Response::HTTP_UNAUTHORIZED);
    }

    if( $exception instanceof AuthorizationException ) {
      return response()->json([
        'error'             => 'Authorization',
        'type'              => ['authorization' => [__('exception.authorization')]],
        'error_description' => __('exception.authorization'), 
        'code'              => Response::HTTP_FORBIDDEN
      ], Response::HTTP_FORBIDDEN);
    }

    if ( $exception instanceof QueryException ) {
      $content = collect([
        'error'       => 'Query', 
        'code'        => Response::HTTP_CONFLICT, 
        'type'        => $exception->getMessage()
      ]);

      if ( env('APP_ENV') === 'local' ) {
        $content->put('error_description', __('exception.query'));
      }
       // CODIGO AGREGADO PARA AÑADIR LOG DE AUDITORIA      
      

      return response()->json($content->toArray(), Response::HTTP_CONFLICT);
    }

    if($code === Response::HTTP_INTERNAL_SERVER_ERROR){

      switch ($exception->getMessage()) {
        case 'Unauthenticated.':
            $error = __('exception.authentication');
            $code  = Response::HTTP_UNAUTHORIZED;
          break;
        
        default:
            $error = __('exception.interal_error');
          break;
      }
      
      $content = collect([
        'error'             => 'Internal error', 
        'type'              => $exception->getMessage(), 
        'code'              => $code,
        'error_description' => $error
      ]);
      
      if ( env('APP_ENV') === 'local' ) {
        $content->put('line', $exception->getLine());
        $content->put('file', $exception->getFile());
      }
      // CODIGO AGREGADO PARA AÑADIR LOG DE AUDITORIA     
      
      return response()->json($content->toArray(), $code);
    }

    if($code === Response::HTTP_NO_CONTENT){
      
      return response()->json([
        'response'    => [],
        'description' => ['not_found_data' => [__('exception.not_found_data')]],
        'code'        => Response::HTTP_NO_CONTENT,
      ], Response::HTTP_NO_CONTENT);
    }
     // CODIGO AGREGADO PARA AÑADIR LOG DE AUDITORIA
     $content=collect([
      'error'             => 'Internal error',
      'type'              => $exception, 
      'code'              => $code,
      'error_description' => $exception
    ]);    

    return response()->json([
        'error'             => 'Internal error',
        'type'              => $exception, 
        'code'              => $code,
        'error_description' => $exception
      ], $code);
  }

  protected function showAll(Collection $collection, $code = Response::HTTP_OK)
  {
    if ($collection->count() === 0) {
      $this->errorResponse(__('exception.not_found_data'), Response::HTTP_NO_CONTENT);
    }

    $collection = $this->sortBy($collection);
    $collection = $this->paginate($collection);

    return $this->successResponse($collection, $code);
  }

  protected function showOne(Model $instance, $code = Response::HTTP_OK)
  {
    return $this->successResponse($instance, $code);
  }

  protected function showMessage(string $message, $code = Response::HTTP_OK)
  {
    return $this->successResponse(['message' => $message], $code);
  }

  private function sortBy(Collection $collection)
  {
    if ( request()->has('sort_by') ) {
      if ( request()->has('desc') ) {
        $collection = $collection->sortByDesc( request()->sort_by )->values();
      } else {
        $collection = $collection->sortBy( request()->sort_by )->values();
      }
    }

    return $collection;
  }

  private function paginate(Collection $collection)
  {

    if ( request()->has('page') ) {
      $page    = LengthAwarePaginator::resolveCurrentPage();
      $perPage = ( request()->has('number') ) ? request()->number : 15;
      $results = $collection->slice( ($page - 1) * $perPage, $perPage)->values();

      $collection = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
          'path' => LengthAwarePaginator::resolveCurrentPage()
        ]);

      $collection->appends(request()->all());
    }
    
    return $collection;
  }

}