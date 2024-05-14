<?php

namespace App\Base\Model;

use App\Helpers\DatabaseConnection;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];
    protected $connection = null;

    public function __construct(array $attributes = [])
    {

        parent::__construct($attributes);

        $this->connection = DatabaseConnection::setConnection();
    }
}
