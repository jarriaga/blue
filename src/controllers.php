<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

//Request::setTrustedProxies(array('127.0.0.1'));

//Read payload for json body
$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig', array());
})->bind('homepage');



/**  GET METHOD */
/** Route to get all the tasks in the database via JSON - API*/
$app->get('/api/tasks',function() use($app){
    //get entity manager instance
    $em = $app['doctrine'];
    //get all records from model
    $tasks = $em->getRepository('\models\Todo')->findBy(array(), array('position' => 'ASC'));
    //return response
    return $app->json($tasks,200);
});



/**  POST METHOD */
/** Route to set a task via parameter  */
$app->post('/api/task', function(Request $request) use($app){
    //get the parameter task
    $task = $request->get("task","");
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


/**  PATCH METHOD */
/** This Route has the function to update a task description via parameter */
$app->patch('/api/task/{id}',function($id,Request $request) use ($app){
    $em = $app['doctrine'];
    //find the model into the database
    $description = $request->request->get("description");
    $task =  $em->getRepository('\models\Todo')->find($id);
    //if not found return error
    if(!$task){
        return $app->json(["error"=>"task doesn't exists"],400);
    }
    //set new values
    $task->setDescription($description);
    $em->persist($task);
    $em->flush();
    //send response
    return $app->json($task,200);
});


/**  DELETE METHOD */
/***
 * This route has the code to delete an specific task
 */
$app->delete('/api/task/{id}',function($id) use($app){
    $em = $app['doctrine'];
    //find the model into the database
    $task =  $em->getRepository('\models\Todo')->find($id);
    //if not found return error
    if(!$task){
        return $app->json(["error"=>"task doesn't exists"],400);
    }
    //remove the task
    $em->remove($task);
    $em->flush();
    return $app->json(["success"=>"the task was deleted successfully"],200);
});

/**
 * Method post to sort the tasks
 */
$app->post('/api/task/sort', function(Request $request) use ($app){
    $tasks = $request->get("sort");
    $em = $app['doctrine'];
    foreach($tasks as $key=>$value){
        $task = $em->getRepository('\models\Todo')->find((int)$value);
        if($task){
            $task->setPosition($key);
            $em->persist($task);
        }
    }
    $em->flush();
    return $app->json(["success"=>"sorted successfully"],200);
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
