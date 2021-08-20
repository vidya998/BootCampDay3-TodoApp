<?php

namespace App\Http\Controllers;

use App\Services\TasksService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TodoController extends Controller
{
    protected $tasksService;

    public function __construct(TasksService $tasksService)
    {
        $this->tasksService = $tasksService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getAllTasks()
    {
        return  $this->tasksService->fetchAllTasks();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function insertTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'task_name' => 'required|max:255|min:3|unique:todos|regex:/^[a-zA-Z]+$/u',
            'task_description' => 'required|max:255',
            'task_status' => 'in:progress,pending'
        ]);
        if ($validator->fails()) {
            return response( $validator->errors());
        }
        return  $this->tasksService->insertTask($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function updateTask(Request $request)
    {
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:todos,id',
        'task_status'=> 'required|in:progress,completed'
        ]);
        if ($validator->fails()) {
            return response($validator->errors(),Response::HTTP_BAD_REQUEST);
        }
        return  $this->tasksService->updateTaskStatus($request);

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function deleteTask($id)
    {
        return  $this->tasksService->deleteTask($id);
    }
    /**
     * Fetch the specified resource from storage.
     *
     * @param $id
     * @return Response
     */
    public function fetchTask($id)
    {
        return  $this->tasksService->fetchById($id);
    }
}
