<?php

namespace App\Controller;

use App\Entity\BlogPost;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/blog")
 */
class BlogController extends BaseController
{
    /**
     * @Route("/list/{page}", name="blog_list", defaults={"page": 1}, requirements={"id" = "\d+"})
     */
    public function list($page, Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(BlogPost::class);
        $items = $repository->findAll();
        $limit = $request->get('limit', 10);

        return $this->json(
            [
                "page" => $page,
                "limit" => $limit,
                "items" => $items,
                "data" => array_map(function  (BlogPost $item) {
                    return $this->generateUrl('blog_post_by_id', ['id' => $item->getId()]);
                }, $items)
            ]
        );
    }

    /**
     * @Route("/post/{id}", name="blog_post_by_id", requirements={"id" = "\d+"}, methods={"GET"})
     */
    public function post(BlogPost $post)
    {
        // automatically gets the post by id - You can explicitly use the param converter like so:
        // @ParamConverter("post", class="App:BlogPost", option={"mapping": {"id": "id"}})
        // array_search returns the index of the found element
        return $this->json(
            $post
        );
    }


    /**
     * @Route("/post/{slug}", name="blog_post_by_slug", methods={"GET"})
     */
    public function postBySlug(BlogPost $post)
    {
        // array_search returns the index of the found element
        return $this->json(
            $post
        );
    }

    /**
     * @Route("/post/add", name="blog_add", methods={"POST"})
     */
    public function addBlog(Request $request)
    {
        /** @var Serializer $serializer */

        // Serialize means from object to array to json also known as (encode/normalise)
        // De-Serialize means from json to array to object (encoding/denormalisation)
        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();

        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost, 200, [], ["success" => true]);
    }

    /**
     * @Route("/post/delete/{id}", name="blog_post_by_id", requirements={"id" = "\d+"}, methods={"DELETE"})
     */
    public function delete(BlogPost $post)
    {
        $em = $this->getDoctrine()->getManager();

        $em->remove($post);
        $em->flush();

        return $this->json(
            'deleted',
            Response::HTTP_NO_CONTENT
        );
    }
}
