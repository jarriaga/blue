<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));


$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig', array());
})->bind('homepage');




/** Route to get all the tasks in the database via JSON - API*/
$app->get('/api/tasks',function() use($app){
    //get entity manager instance
    $em = $app['doctrine'];
    //get all records from model
    $tasks = $em->getRepository('\models\Todo')->findAll();
    //return response
    return $app->json(["tasks"=>$tasks],200);
});




/** Route to set a task via parameter  */
$app->post('/api/task', function(Request $request) use($app){
    //get the parameter task
    $task = $request->get("task");
    //count the total tasks
    $em = $app['doctrine'];
    $nextPos =count($em->getRepository('\models\Todo')->findAll())+1;
    //create a new model and insert
    $todo = new \models\Todo();
    $todo->setDescription($task);
    $todo->setPosition($nextPos);
    //save the model to the database
    $em->persist($todo);
    $em->flush();
    //send response
    return $app->json(["task"=>$todo],200);
});


/***
 * This route has the code to delete an specific task
 */
$app->delete('/api/task',function(Request $request) use($app){
    //get the id of the task
    $id = $request->get("id");
    $em = $app['doctrine'];
    //find the model into the database
    $task =  $em->getRepository('\models\Todo',$id);
    //if not found return error
    if(!$task){
        return $app->json(["error"=>"task doesn't exists"],400);
    }
    //remove the task
    $em->remove($task);
    $em->flush();
    //Reorder all tasks
    \models\Todo::sort($em);

    return $app->json(["success"=>"the task was deleted successfully"],200);
});





$app->error(function (\Exception $e, Request $request, $code) use ($app) {
    if ($app['debug']) {
        return;
    }

    // 404.html, or 40x.html, or 4xx.html, or error.html
    $templates = array(
        'errors/'.$code.'.html.twig',
        'errors/'.substr($code, 0, 2).'x.html.twig',
        'errors/'.substr($code, 0, 1).'xx.html.twig',
        'errors/default.html.twig',
    );

    return new Response($app['twig']->resolveTemplate($templates)->render(array('code' => $code)), $code);
});
