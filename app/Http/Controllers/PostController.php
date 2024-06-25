<?php

namespace App\Http\Controllers;


use App\Http\Requests\PostCreateFormRequest;
use App\Http\Requests\PostUpdateFormRequest;
use App\Http\Resources\PostResource;
use App\Services\PostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\CustomResponseTraits;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class PostController extends Controller
{

    use CustomResponseTraits;

    public function __construct(protected PostService $postService)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
//        return $this->postService->all();
        return $this->successResponse(
            [
                'posts' => PostResource::collection($this->postService->all()),
                'links' => PostResource::collection($this->postService->all())->response()->getData()->links,
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostCreateFormRequest $request): JsonResponse
    {
        $input = array_merge($request->safe()->only(['title', 'content']), ['user_id' => $request->user()->id]);

        return $this->successResponse(
            array(PostResource::make($this->postService->create($input))),
            'Post Created Successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        return $this->successResponse(array(PostResource::make($this->postService->find($id))));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PostUpdateFormRequest $request, string $id): JsonResponse
    {
        if ($request->user()->posts()->find($id)) {
            $input = array_merge($request->safe()->only(['title', 'content']), ['user_id' => $request->user()->id]);
            return $this->successResponse(
                array(PostResource::make($this->postService->update($input, $id))),
                'Post Updated Successfully');
        }

        return $this->errorResponse(['message' => 'Forbidden'], code: HttpResponse::HTTP_FORBIDDEN);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        if ($post = $request->user()->posts()->find($id)) {
            $this->postService->delete($id);
            return $this->successResponse(['message' => $post->title . ' deleted successfully'], code: HttpResponse::HTTP_NO_CONTENT);
        }

        return $this->errorResponse(['message' => 'Forbidden'], code: HttpResponse::HTTP_FORBIDDEN);

    }


}
