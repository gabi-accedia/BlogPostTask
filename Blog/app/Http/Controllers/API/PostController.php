<?php

namespace App\Http\Controllers\API;


use App\Models\Eloquent\TagsService as TagService;
use App\Models\Eloquent\PostsService as PostService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class PostController extends BaseController
{
    public function store(Request $request){

        $userId = auth()->guard('api')->user()->id;

        $isHeadline = $request->is_headline == 'true' ? true : false;
        $postDescription = $request->description;
        $postTitle = $request->title;

        //Validate headline
        $headLineValidator = PostService::headlineValidation($userId, $isHeadline);
        if (! $headLineValidator) {
            return $this->sendError('Validation Error.', "You can not create more than 2 headline posts in one day.");
        }

        //Post validation
        $input = $request->all();
        $validator = Validator::make($input, [
            'title' => 'required',
            'description' => 'required',
            'is_headline' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        try {
            DB::beginTransaction();

            //Create post
            $newPost = PostService::store($userId, $postDescription, $postTitle, $isHeadline);

            //Attach tags
            $relatedTags = $request->tags;

            $tagsIdCollection = TagService::store($relatedTags);
            $newPost->tags()->attach($tagsIdCollection);

            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->sendError('Validation Error.', $e->getMessage());
        }

        return  $this->sendResponse($newPost->toArray(), 'Post created successfully.');

    }

}
