<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllTasks()
    {
        Log::info('Fetching all the task');
        return Todo::orderBy('id','desc')->get(array('id','task_name','task_description','task_status'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws ValidationException
     */
    public function insertTask(Request $request)
    {
        Log::info('Creating a task: '.$request->task_name);
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'task_name' => 'required|max:255|min:3|unique:todos|regex:/^[a-zA-Z]+$/u',
            'task_description' => 'required|max:255',
            'task_status' =>'required|in:pending'
        ]);
        if ($validator->fails()) {
            echo $validator->errors();
            return response(["Message" => "Enter tasks properly"]);
        }
        try {
            Todo::insert($request->all());
        }
        catch (QueryException $e) {
            return response([$e,Response::HTTP_BAD_REQUEST]);
        }
        return response([
            'message' => "Task successfully created",Response::HTTP_OK]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|Response
     * @throws ValidationException
     */
    public function updateTask(Request $request)
    {
        Log::info('Updating the task: '.$request->task_name);
        $validator = Validator::make($request->all(), [
        'id' => 'required|exists:todos,id',
        'task_status'=> 'required|in:progress,completed'
    ]);
        if ($validator->fails()) {
            echo $validator->errors();
            return response(["Message" => "Enter tasks properly"],Response::HTTP_BAD_REQUEST);
        }
        try {
            $id = $request->input('id');
            $task_status = $request->input('task_status');
            $task = Todo::find($id);
            $task->update($request->all());
        }
        catch (QueryException $e) {
            return response([$e,Response::HTTP_BAD_REQUEST]);
        }
        return response([
            'message' => "Task $id $task->task_name sucessfully updated to $task_status ",200
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|Response
     * @throws ValidationException
     */
    public function deleteTask(Request $request)
    {
        $tasks = Todo::all();
        if($tasks->isEmpty()) {
            Log::info('Empty Table');
            return response(["Message" => "The Table is Empty",Response::HTTP_BAD_REQUEST]);
        }
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:todos,id'
        ]);
        if ($validator->fails()) {
            echo $validator->errors();
            return response()->json(["Message" => "Enter tasks properly"]);
        }
        $id =$request->input('id');
        $task = Todo::findOrFail($id);
        try {
            Log::info('Deleting Task ');
            $task->delete();
        }
        catch (QueryException $e) {
            return response([$e,Response::HTTP_BAD_REQUEST]);
        }
        return response([
            'message' => "Task $id $task->task_name successfully deleted "
        ]);
    }

    public function fetchTask(Request $request)
    {
        $tasks = Todo::all();
        if($tasks->isEmpty()) {
            Log::info('Empty Table');
            return response(["Message" => "The Table is Empty",Response::HTTP_BAD_REQUEST]);
        }
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:todos,id'
        ]);
        if ($validator->fails()) {
            echo $validator->errors();
            return response(["Message" => "Enter tasks properly"]);
        }
        $id =$request->input('id');
        $task = Todo::findOrFail($id);
        return response(["Message" => "The status for task $task->task_name is $task->task_status",Response::HTTP_OK]);
    }
}
