<?php

namespace App\Http\Controllers\Api;

use App\Models\Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\EventResource;
use App\Http\Traits\CanLoadRelationships;
class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use CanLoadRelationships;
    private array $relations = ['user', 'attendees'];
    public function index()
    {   
        $query=  $this->loadRelationships(Event::query());

        return EventResource::collection($query->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $event =  Event::create([
            ...$request->validate([
                "name" => "required|string|max:250",
                "description" => "nullable|string",
                "start_time" => "required|date",
                "end_time" => "required|date|after:start_time",
                "user_id" => "required|integer|exists:users,id"
            ]),
        ]);
        return $event;
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        foreach($this->relations as $relation ){
            if($this->shouldIncludeRelation($relation)){
                $event->load($relation);
            }
        }
        return new EventResource($event);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $event->update([
            ...$request->validate([
                "name" => "sometimes|string|max:250",
                "description" => "sometimes|nullable|string",
                "start_time" => "sometimes|date",
                "end_time" => "sometimes|date|after:start_time",
                "user_id" => "sometimes|integer|exists:users,id"
            ]),
        ]);
        return $event;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        $is_delete = $event->delete();
        if ($is_delete) {
            return [
                "message" => "delete successfully",
            ];
        } else {
            return [
                "message" => "delete failed",
            ];
        }
    }

}
