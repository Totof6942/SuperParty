<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Form\CommentType;
use Http\JsonResponse;
use Model\Entity\Comment;
use Model\Finder\CommentFinder;
use Model\Finder\LocationFinder;
use Model\DataMapper\CommentDataMapper;

class CommentController
{

    /**
     * Get all Comments for a Location
     *
     * @param Request     $request
     * @param Application $app
     * @param int         $location_id
     */
    public function getForLocationAction(Request $request, Application $app, $location_id)
    {
        if ('json' !== guessBestFormat()) {
            return $app->redirect($app['url_generator']->generate('location_get', array('id' => $location_id)));
        }

        $location = (new LocationFinder($app['db']))->findOneById($location_id);

        if (empty($location)) {
            if ('json' === guessBestFormat()) {
                return new JsonResponse('Location not found', 404);
            }
            
            return new Response('Location not found', 404);
        }

        $comments = (new CommentFinder($app['db']))->findAllForLocation($location);

        return new JsonResponse($comments);
    }

    /**
     * Post a Comment for a Location
     *
     * @param Request     $request
     * @param Application $app
     * @param int         $location_id
     */
    public function postAction(Request $request, Application $app, $location_id)
    {
        $location = (new LocationFinder($app['db']))->findOneById($location_id);

        if (empty($location)) {
            if ('json' === guessBestFormat()) {
                return new JsonResponse('Location not found', 404);
            }
            
            return new Response('Location not found', 404);
        }

        $comment = new Comment();
        $form = $app['form.factory']->create(new CommentType($comment), $comment);
        $form->bindRequest($request);

        if (!$form->isValid()) {
            $app['session']->setFlash('error', 'The comment has not been added. All fields are mandatory.');
        } else {
            $comment->setLocation($location);

            (new CommentDataMapper($app['db']))->persist($comment);

            $app['session']->setFlash('success', 'The comment has been added.');
        }

        if ('json' === guessBestFormat()) {
            return new JsonResponse($comment->getId(), 201);
        }

        return $app->redirect($app['url_generator']->generate('location_get', array('id' => $location->getId())));
    }

    /**
     * Admin get all Comments
     *
     * @param Request     $request
     * @param Application $app
     */
    public function adminIndexAction(Request $request, Application $app)
    {
        $comments = (new CommentFinder($app['db']))->findAll();

        return $app['twig']->render('admin_comments.html', array('comments' => $comments));
    }

    /**
     * Admin get a Comment for update
     *
     * @param Request     $request
     * @param Application $app
     * @param int         $id
     */
    public function adminGetForUpdateAction(Request $request, Application $app, $id)
    {
        $comment = (new CommentFinder($app['db']))->findOneById($id);

        if (empty($comment)) {
            return new Response('Comment not found', 404);
        }

        $form = $app['form.factory']->create(new CommentType($comment), $comment);

        return $app['twig']->render('admin_comment_update.html', array(
                'form'     => $form->createView(),
                'comment'  => $comment,
            ));
    }

    /**
     * Admin update a Comment
     *
     * @param Request     $request
     * @param Application $app
     * @param int         $id
     */
    public function adminUpdateAction(Request $request, Application $app, $id)
    {
        $comment = (new CommentFinder($app['db']))->findOneById($id);

        if (empty($comment)) {
            return new Response('Comment not found', 404);
        }

        $form = $app['form.factory']->create(new CommentType($comment), $comment);
        $form->bindRequest($request);

        if (!$form->isValid()) {
            $app['session']->setFlash('error', 'The comment has not been added.');
            return $app->redirect($app['url_generator']->generate('admin_comment_get', array('id' => $id)));
        }

        (new CommentDataMapper($app['db']))->persist($comment);

        $app['session']->setFlash('success', 'The comment has been updated.');

        return $app->redirect($app['url_generator']->generate('admin_comments_get'));
    }

    /**
     * Admin delete a Comment
     *
     * @param Request     $request
     * @param Application $app
     * @param int         $id
     */
    public function adminDeleteAction(Request $request, Application $app, $id)
    {
        $comment = (new CommentFinder($app['db']))->findOneById($id);

        if (empty($comment)) {
            return new Response('Comment not found', 404);
        }

        $mapper = new CommentDataMapper($app['db']);
        $mapper->remove($comment);

        $app['session']->setFlash('success', 'The comment has been deleted.');

        return $app->redirect($app['url_generator']->generate('admin_comments_get'));
    }

}
