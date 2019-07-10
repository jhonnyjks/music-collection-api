<?php

namespace App\Repositories;

use App\Models\Album;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class AlbumRepository
 * @package App\Repositories
 * @version July 10, 2019, 8:33 pm UTC
 *
 * @method Album findWithoutFail($id, $columns = ['*'])
 * @method Album find($id, $columns = ['*'])
 * @method Album first($columns = ['*'])
*/
class AlbumRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'artist_id',
        'name',
        'year'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Album::class;
    }
}
