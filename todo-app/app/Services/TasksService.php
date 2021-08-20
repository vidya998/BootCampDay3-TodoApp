<?php

namespace App\Services;

use App\Models\Todo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class TasksService
{
    public function fetchAllTasks(){
        try{
            Log::info('Fetching all the task');
            $tasks = Todo::orderBy('id','desc')->get(array('id','task_name','task_description','task_status'));
        }catch (QueryException $e){
            return response()->json($e->errorInfo , Response::HTTP_BAD_REQUEST);
        }
        if (count ($tasks) == 0){
            return response()->json(["message"=>"Empty To-Do List"],Response::HTTP_BAD_REQUEST);
        }
        return response()->json(["tasks"=>$tasks],Response::HTTP_OK);
    }
    public function insertTask(Request $request){
        Log::info('Creating a task: '.$request->task_name);
        try {
            Todo::insert($request->all());
        }
        catch (QueryException $e) {
            return response([$e,Response::HTTP_BAD_REQUEST]);
        }
        return response([
            'message' => "Task successfully created ",Response::HTTP_OK]);
    }
    public function deleteTask($id){
        $tasks = Todo::all();
        if($tasks->isEmpty()) {
            Log::info('Empty Table');
            return response(["Message" => "The Table is Empty",Response::HTTP_BAD_REQUEST]);
        }
        try{
            $task = Todo::findOrFail($id);
            $task->delete();
        }
        catch (QueryException $e){
            Log::info('rrrr');
            return response()->json($e->errorInfo , Response::HTTP_BAD_REQUEST);
        }
        return response([
            'message' => "Task $id $task->task_name successfully deleted ",Response::HTTP_OK
        ]);
    }
    public function fetchById($id)
    {
        $tasks = Todo::all();
        if($tasks->isEmpty()) {
            Log::info('Empty Table');
            return response(["Message" => "The Table is Empty",Response::HTTP_BAD_REQUEST]);
        }
        try{
            $task = Todo::findOrFail($id);
        }
        catch (QueryException $e){
            return response()->json($e->errorInfo , Response::HTTP_BAD_REQUEST);
        }
        return response()->json($task,Response::HTTP_OK);
    }
    public function updateTaskStatus(Request $request)
    {
        Log::info('Updating the task: '.$request->task_name);
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
}
