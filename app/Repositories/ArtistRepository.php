<?php

namespace App\Repositories;

use App\Models\Artist;
use InfyOm\Generator\Common\BaseRepository;

/**
 * Class ArtistRepository
 * @package App\Repositories
 * @version July 10, 2019, 8:33 pm UTC
 *
 * @method Artist findWithoutFail($id, $columns = ['*'])
 * @method Artist find($id, $columns = ['*'])
 * @method Artist first($columns = ['*'])
*/
class ArtistRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'name',
        'twitter'
    ];

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Artist::class;
    }
}
