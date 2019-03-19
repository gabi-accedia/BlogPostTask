<?php

namespace App\Models\Eloquent;


use App\Tag as Tag;

class TagsService
{
    public static function store($relatedTags){
        $tags = explode(',', $relatedTags);
        $tagsIdCollection = array();

        foreach($tags AS $tag) {
            $tagName = trim($tag);
            if(empty($tagName)) {
                continue;
            }

            $tag = Tag::firstOrCreate(array('name' => $tagName));

            $tagsIdCollection[] = $tag->id;
        }

        return $tagsIdCollection;
    }
}
