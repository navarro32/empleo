<?php

namespace App\Traits;

use Hashids\Hashids;

trait ApiHash
{
    /**
     * Singleton de la instancia de Hashids
     * @return objeto Hashids
     */
    public static function hash( $model_name )
    {
      $salt = sha1( env('APP_KEY').$model_name );
      return new Hashids( $salt, 10 );
    }

    /**
     * Añade el hash_id a cada modelo
     * @return string hash_id
     */
    public function getKeyAttribute($value)
    {
      //return $this->id; 
      return $this->hash_encode( $this->id, __CLASS__ );
    }
    
    public function setKeyAttribute($value)
    {
      //return $this->attributes['id'] = $value;  
      return $this->hash_decode( $value, __CLASS__ );
    }

    /**
     * Codifica los string para llaves foraneas
     * @param  string $value id a ofuscar
     * @return string        resultado del hash
     */
    public function hash_encode($value, $model_name)
    {
      return ApiHash::hash( $model_name )->encode($value);
    }

    /**
     * Decodifica los strings ofuscados
     * @param  string $value id a ofuscar
     * @return string        resultado del hash
     */
    public function hash_decode($value, $model_name)
    {   
      $decoded = collect( ApiHash::hash( $model_name )->decode($value) );
      return $decoded->first();
    }
}