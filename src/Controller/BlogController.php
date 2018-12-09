<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog", name="blog")
 */
class BlogController extends BaseController
{
    private const POSTS = [
        [
            'id' => 1,
            'slug' => 'Hello-World-1',
            'title' => 'Hello World 1'
        ],
        [
            'id' => 2,
            'slug' => 'Hello-World-2',
            'title' => 'Hello-World-2'
        ],
        [
            'id' => 3,
            'slug' => 'Hello-World-3',
            'title' => 'Hello World 3'
        ],
    ];

    /**
     * @Route("/", name="blog_list")
     */
    public function list()
    {
        return new JsonResponse(self::POSTS);
    }

    /**
     * @Route("/{id}", name="blog_post", requirements={"id" = "\d+"})
     */
    public function post($id)
    {
        // array_search returns the index of the found element
        return new JsonResponse(
            self::POSTS[array_search($id, array_column(self::POSTS, 'id'))]
        );
    }

    /**
     * @Route("/{slug}", name="blog_post_by_slug")
     */
    public function postBySlug($slug)
    {
        // array_search returns the index of the found element
        return new JsonResponse(
            self::POSTS[array_search($slug, array_column(self::POSTS, 'slug'))]
        );
    }
}
