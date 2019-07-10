<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateAlbumAPIRequest;
use App\Http\Requests\API\UpdateAlbumAPIRequest;
use App\Models\Album;
use App\Repositories\AlbumRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class AlbumController
 * @package App\Http\Controllers\API
 */

class AlbumAPIController extends AppBaseController
{
    /** @var  AlbumRepository */
    private $albumRepository;

    public function __construct(AlbumRepository $albumRepo)
    {
        $this->albumRepository = $albumRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/albums",
     *      summary="Get a listing of the Albums.",
     *      tags={"Album"},
     *      description="Get all Albums",
     *      produces={"application/json"},
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="array",
     *                  @SWG\Items(ref="#/definitions/Album")
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function index(Request $request)
    {
        $this->albumRepository->pushCriteria(new RequestCriteria($request));
        $this->albumRepository->pushCriteria(new LimitOffsetCriteria($request));
        $albums = $this->albumRepository->all();

        return $this->sendResponse($albums->toArray(), 'Albums retrieved successfully');
    }

    /**
     * @param CreateAlbumAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/albums",
     *      summary="Store a newly created Album in storage",
     *      tags={"Album"},
     *      description="Store Album",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Album that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Album")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Album"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateAlbumAPIRequest $request)
    {
        $input = $request->all();

        $albums = $this->albumRepository->create($input);

        return $this->sendResponse($albums->toArray(), 'Album saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/albums/{id}",
     *      summary="Display the specified Album",
     *      tags={"Album"},
     *      description="Get Album",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Album",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Album"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function show($id)
    {
        /** @var Album $album */
        $album = $this->albumRepository->findWithoutFail($id);

        if (empty($album)) {
            return $this->sendError('Album not found');
        }

        return $this->sendResponse($album->toArray(), 'Album retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateAlbumAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/albums/{id}",
     *      summary="Update the specified Album in storage",
     *      tags={"Album"},
     *      description="Update Album",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Album",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Album that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Album")
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  ref="#/definitions/Album"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateAlbumAPIRequest $request)
    {
        $input = $request->all();

        /** @var Album $album */
        $album = $this->albumRepository->findWithoutFail($id);

        if (empty($album)) {
            return $this->sendError('Album not found');
        }

        $album = $this->albumRepository->update($input, $id);

        return $this->sendResponse($album->toArray(), 'Album updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/albums/{id}",
     *      summary="Remove the specified Album from storage",
     *      tags={"Album"},
     *      description="Delete Album",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Album",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Response(
     *          response=200,
     *          description="successful operation",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="success",
     *                  type="boolean"
     *              ),
     *              @SWG\Property(
     *                  property="data",
     *                  type="string"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function destroy($id)
    {
        /** @var Album $album */
        $album = $this->albumRepository->findWithoutFail($id);

        if (empty($album)) {
            return $this->sendError('Album not found');
        }

        $album->delete();

        return $this->sendResponse($id, 'Album deleted successfully');
    }
}
