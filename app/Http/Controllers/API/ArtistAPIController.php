<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\API\CreateArtistAPIRequest;
use App\Http\Requests\API\UpdateArtistAPIRequest;
use App\Models\Artist;
use App\Repositories\ArtistRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController;
use InfyOm\Generator\Criteria\LimitOffsetCriteria;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;

/**
 * Class ArtistController
 * @package App\Http\Controllers\API
 */

class ArtistAPIController extends AppBaseController
{
    /** @var  ArtistRepository */
    private $artistRepository;

    public function __construct(ArtistRepository $artistRepo)
    {
        $this->artistRepository = $artistRepo;
    }

    /**
     * @param Request $request
     * @return Response
     *
     * @SWG\Get(
     *      path="/artists",
     *      summary="Get a listing of the Artists.",
     *      tags={"Artist"},
     *      description="Get all Artists",
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
     *                  @SWG\Items(ref="#/definitions/Artist")
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
        $this->artistRepository->pushCriteria(new RequestCriteria($request));
        $this->artistRepository->pushCriteria(new LimitOffsetCriteria($request));
        $artists = $this->artistRepository->all();

        return $this->sendResponse($artists->toArray(), 'Artists retrieved successfully');
    }

    /**
     * @param CreateArtistAPIRequest $request
     * @return Response
     *
     * @SWG\Post(
     *      path="/artists",
     *      summary="Store a newly created Artist in storage",
     *      tags={"Artist"},
     *      description="Store Artist",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Artist that should be stored",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Artist")
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
     *                  ref="#/definitions/Artist"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function store(CreateArtistAPIRequest $request)
    {
        $input = $request->all();

        $artists = $this->artistRepository->create($input);

        return $this->sendResponse($artists->toArray(), 'Artist saved successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Get(
     *      path="/artists/{id}",
     *      summary="Display the specified Artist",
     *      tags={"Artist"},
     *      description="Get Artist",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Artist",
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
     *                  ref="#/definitions/Artist"
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
        /** @var Artist $artist */
        $artist = $this->artistRepository->findWithoutFail($id);

        if (empty($artist)) {
            return $this->sendError('Artist not found');
        }

        return $this->sendResponse($artist->toArray(), 'Artist retrieved successfully');
    }

    /**
     * @param int $id
     * @param UpdateArtistAPIRequest $request
     * @return Response
     *
     * @SWG\Put(
     *      path="/artists/{id}",
     *      summary="Update the specified Artist in storage",
     *      tags={"Artist"},
     *      description="Update Artist",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Artist",
     *          type="integer",
     *          required=true,
     *          in="path"
     *      ),
     *      @SWG\Parameter(
     *          name="body",
     *          in="body",
     *          description="Artist that should be updated",
     *          required=false,
     *          @SWG\Schema(ref="#/definitions/Artist")
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
     *                  ref="#/definitions/Artist"
     *              ),
     *              @SWG\Property(
     *                  property="message",
     *                  type="string"
     *              )
     *          )
     *      )
     * )
     */
    public function update($id, UpdateArtistAPIRequest $request)
    {
        $input = $request->all();

        /** @var Artist $artist */
        $artist = $this->artistRepository->findWithoutFail($id);

        if (empty($artist)) {
            return $this->sendError('Artist not found');
        }

        $artist = $this->artistRepository->update($input, $id);

        return $this->sendResponse($artist->toArray(), 'Artist updated successfully');
    }

    /**
     * @param int $id
     * @return Response
     *
     * @SWG\Delete(
     *      path="/artists/{id}",
     *      summary="Remove the specified Artist from storage",
     *      tags={"Artist"},
     *      description="Delete Artist",
     *      produces={"application/json"},
     *      @SWG\Parameter(
     *          name="id",
     *          description="id of Artist",
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
        /** @var Artist $artist */
        $artist = $this->artistRepository->findWithoutFail($id);

        if (empty($artist)) {
            return $this->sendError('Artist not found');
        }

        $artist->delete();

        return $this->sendResponse($id, 'Artist deleted successfully');
    }
}
