<?php

namespace Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

use Form\CommentType;
use Model\Entity\Comment;
use Model\Finder\CommentFinder;
use Model\DataMapper\CommentDataMapper;

class CommentController 
{

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

        $form = $app['form.factory']->create(new CommentType(), $comment);

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

        $form = $app['form.factory']->create(new CommentType());
        $form->bindRequest($request);
        $data = $form->getData();

        $comment->setUsername($data['username']);
        $comment->setBody($data['body']);

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

        // return 204
        $app['session']->setFlash('success', 'The comment has been deleted.');

        return $app->redirect($app['url_generator']->generate('admin_comments_get'));
    }

}