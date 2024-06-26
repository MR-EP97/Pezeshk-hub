<?php

namespace App\Http\Controllers;


use App\Http\Requests\PostCreateFormRequest;
use App\Http\Requests\PostUpdateFormRequest;
use App\Http\Resources\PostResource;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\CustomResponseTraits;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Response as HttpResponse;


class PostController extends Controller
{

    use CustomResponseTraits;

    public function __construct(protected PostService $postService)
    {
    }

    /**
     *
     * @OA\Get(
     *     path="/api/posts",
     *     tags={"Post"},
     *     summary="Get list of posts",
     *     description="Return all posts",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *          name="page",
     *          in="query",
     *          description="Filter posts by category",
     *          required=false,
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *          @OA\SecurityScheme(
     *          securityScheme="bearerAuth",
     *          type="http",
     *          scheme="bearer",
     *          bearerFormat="JWT"
     *      ),
     *     @OA\Response(
     *         response="200",
     *         description="success",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/PostResource")
     *         )
     *     )
     * )
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
//        return $this->postService->all();
        return $this->successResponse(
            data: [
                'posts' => PostResource::collection($this->postService->all()),
                'links' => PostResource::collection($this->postService->all())->response()->getData()->links,
            ]
        );
    }


    /**
     * @OA\Get(
     *      path="/api/posts/{id}",
     *      tags={"Post"},
     *      summary="Get a specific post",
     *      description="Retrieve the details of a specific post by ID",
     *      security={{"bearerAuth":{}}},
     *          @OA\SecurityScheme(
     *          securityScheme="bearerAuth",
     *          type="http",
     *          scheme="bearer",
     *          bearerFormat="token"
     *      ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the post",
     *          required=true,
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/PostResource")
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Post not found"
     *      )
     *  )
     *
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            return $this->successResponse(data: ['post' => PostResource::make($this->postService->find($id))]);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * /**
     * @OA\Post(
     *      path="/api/posts",
     *      tags={"Post"},
     *      summary="Create a new post",
     *      description="Create a new post with title and content",
     *      security={{"bearerAuth":{}}},
     *           @OA\SecurityScheme(
     *           securityScheme="bearerAuth",
     *           type="http",
     *           scheme="bearer",
     *           bearerFormat="token"
     *       ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"title", "content"},
     *              @OA\Property(property="title", type="string", example="Sample Title"),
     *              @OA\Property(property="content", type="string", example="Sample Content")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Post created successfully",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/PostResource")
     *
     *          )
     *      ),
     *  )
     * Store a newly created resource in storage.
     * @param PostCreateFormRequest $request
     * @return JsonResponse
     */
    public function store(PostCreateFormRequest $request): JsonResponse
    {
        $input = array_merge($request->safe()->only(['title', 'content']), ['user_id' => $request->user()->id]);

        return $this->successResponse(
            'Post Created Successfully',
            ['post' => PostResource::make($this->postService->create($input))],
            HttpResponse::HTTP_CREATED
        );
    }

    /**
     *
     * /**
     * @OA\Put(
     *      path="/api/posts/{id}",
     *      tags={"Post"},
     *      summary="Update a post",
     *      description="Update a post by ID",
     *      security={{"bearerAuth":{}}},
     *      @OA\SecurityScheme(
     *           securityScheme="bearerAuth",
     *           type="http",
     *           scheme="bearer",
     *           bearerFormat="token"
     *       ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the post to be updated",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"title", "content"},
     *              @OA\Property(property="title", type="string",maxLength=50),
     *              @OA\Property(property="content", type="string",minLength=50)
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Post updated successfully",
     *          response="200",
     *          description="success",
     *          @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/PostResource")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Post not found"
     *      )
     *  )
     * /
     * Update the specified resource in storage.
     * @param PostUpdateFormRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(PostUpdateFormRequest $request, string $id): JsonResponse
    {
//        Each user can only update their own post.
        if ($request->user()->posts()->find($id)) {
            $input = array_merge($request->safe()->only(['title', 'content']), ['user_id' => $request->user()->id]);
            return $this->successResponse(
                'Post Updated Successfully',
                ['post' => PostResource::make($this->postService->update($input, $id))],
            );
        }

        return $this->errorResponse('Forbidden', code: HttpResponse::HTTP_FORBIDDEN);
    }


    /**
     * @OA\Delete(
     *      path="/api/posts/{id}",
     *      tags={"Post"},
     *      summary="Delete a post",
     *      description="Delete a post by ID",
     *      security={{"bearerAuth":{}}},
     *           @OA\SecurityScheme(
     *           securityScheme="bearerAuth",
     *           type="http",
     *           scheme="bearer",
     *           bearerFormat="token"
     *       ),
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          description="ID of the post to be deleted",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Post deleted successfully",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(
     *                  property="message",
     *                  type="string",
     *                  example="Post deleted successfully"
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Post not found"
     *      ),
     *     @OA\Response(
     *           response=403,
     *           description="Forbidden"
     *       ),
     *  )
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     *
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
//        Each user can only delete their own post.
        if ($this->postService->find($id) && $request->user()->posts()->find($id)) {
            $this->postService->delete($id);
            return $this->successResponse('Post deleted successfully', code: HttpResponse::HTTP_NO_CONTENT);
        }
        return $this->errorResponse('Forbidden', code: HttpResponse::HTTP_FORBIDDEN);
    }


}
