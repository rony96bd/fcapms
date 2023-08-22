<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blood;
use App\Models\City;
use App\Models\Agent;
use App\Models\Message;
use App\Models\Project;
use App\Models\Admin;
use App\Models\User;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ProjectController extends Controller
{

    public function index()
    {
        $pageTitle = "Manage Agent List";
        $emptyMessage = "No data found";
        $projects = Project::latest()->paginate(getPaginate());
        return view('admin.project.index', compact('pageTitle', 'emptyMessage', 'projects'));
    }

    public function pending()
    {
        $pageTitle = "Pending Agent List";
        $emptyMessage = "No data found";
        $bloods = Blood::where('status', 1)->select('id', 'name')->get();
        $agents = Agent::where('status', 0)->latest()->with('blood', 'location')->paginate(getPaginate());
        return view('admin.agent.index', compact('pageTitle', 'emptyMessage', 'agents', 'bloods'));
    }

    public function approved()
    {
        $pageTitle = "Approved Agent List";
        $emptyMessage = "No data found";
        $bloods = Blood::where('status', 1)->select('id', 'name')->get();
        $agents = Agent::where('status', 1)->latest()->with('blood', 'location')->paginate(getPaginate());
        return view('admin.agent.index', compact('pageTitle', 'emptyMessage', 'agents', 'bloods'));
    }

    public function banned()
    {
        $pageTitle = "Banned Agent List";
        $emptyMessage = "No data found";
        $bloods = Blood::where('status', 1)->select('id', 'name')->get();
        $agents = Agent::where('status', 2)->latest()->with('blood', 'location')->paginate(getPaginate());
        return view('admin.agent.index', compact('pageTitle', 'emptyMessage', 'agents', 'bloods'));
    }

    public function create()
    {
        $pageTitle = "Create Project";
        $cities = City::where('status', 1)->select('id', 'name')->with('location')->get();
        $bloods = Blood::where('status', 1)->select('id', 'name')->get();
        return view('admin.project.create', compact('pageTitle', 'cities', 'bloods'));
    }

    public function agentBloodSearch(Request $request)
    {
        $request->validate([
            'blood_id' => 'required|exists:bloods,id'
        ]);
        $bloodId = $request->blood_id;
        $blood = Blood::findOrFail($request->blood_id);
        $pageTitle = $blood->name . " Blood Group Students List";
        $emptyMessage = "No data found";
        $bloods = Blood::where('status', 1)->select('id', 'name')->get();
        $agents = Agent::where('blood_id', $request->blood_id)->latest()->with('blood', 'location')->paginate(getPaginate());
        return view('admin.agent.index', compact('pageTitle', 'emptyMessage', 'agents', 'bloods', 'bloodId'));
    }

    public function search(Request $request)
    {
        $pageTitle = "Students Search";
        $emptyMessage = "No data found";
        $search = $request->search;
        $agents = Agent::where('firstname', 'like', "%$search%")->latest()->with('firstname', 'lastname')->paginate(getPaginate());
        return view('admin.agent.index', compact('pageTitle', 'emptyMessage', 'agents', 'search'));
    }

    public function approvedStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:agents,id'
        ]);
        $agent = Agent::findOrFail($request->id);
        $agent->status = 1;
        $agent->save();
        $notify[] = ['success', 'Agent has been approved'];
        return back()->withNotify($notify);
    }

    public function bannedStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:agents,id'
        ]);
        $agent = Agent::findOrFail($request->id);
        $agent->status = 2;
        $agent->save();
        $notify[] = ['success', 'Students has been canceled'];
        return back()->withNotify($notify);
    }


    public function featuredInclude(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:agents,id'
        ]);
        $agent = Agent::findOrFail($request->id);
        $agent->featured = 1;
        $agent->save();
        $notify[] = ['success', 'Include this Students featured list'];
        return back()->withNotify($notify);
    }

    public function featuredNotInclude(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:agents,id'
        ]);
        $agent = Agent::findOrFail($request->id);
        $agent->featured = 0;
        $agent->save();
        $notify[] = ['success', 'Remove this Students featured list'];
        return back()->withNotify($notify);
    }

    public function message (Request $request)
    {
        $request->validate(['message' => 'required']);
        $message = new Message();

        $message->project_id = $request->project_id;
        $message->user_id = $request->user_id;
        $message->message = $request->message;
        $message->save();
        $notify[] = ['success', 'Message Send Successfully'];
        return back()->withNotify($notify);
    }

    public function store(Request $request)
    {
        $request->validate([
            'files' => 'required',
            'files.*' => 'required|mimes:jpg,pdf,xlx,csv|max:5048',
        ]);

        $project = new Project();
        $project->project_name = $request->project_name;
        $project->url = $request->url;
        $project->username = $request->username;
        $project->password = $request->password;
        $path = imagePath()['file']['path'];

        $filesNames = [];
        foreach($request->file('files') as $file){
            $ext = pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION);
            $onlyName = basename($file->getClientOriginalName(),".".$ext);

            $fileName = $onlyName . '_' . time() . '.' . $file->extension();
            $file->move($path, $fileName);
            $filesNames[] = $fileName;
        }

        $files = json_encode($filesNames);
        $project->files = $files;
        $project->save();
        $notify[] = ['success', 'New Project has been created'];
        return back()->withNotify($notify);
    }

    public function edit($id)
    {
        $pageTitle = "Project Update";
        $agent = Agent::findOrFail($id);
        $bloods = Blood::where('status', 1)->select('id', 'name')->get();
        $cities = City::where('status', 1)->select('id', 'name')->with('location')->get();
        return view('admin.project.edit', compact('pageTitle', 'cities', 'bloods', 'agent'));
    }

    public function view($id)
    {
        $pageTitle = "Project Details";
        $project = Project::findOrFail($id);
        $projects = Project::latest()->paginate(getPaginate());
        $users = User::latest()
        ->where('project_id', $id)
        ->paginate(getPaginate());
        $admins = Admin::latest()->paginate(getPaginate());
        $messages = Message::orderBy('id', 'desc')->get();
        return view('admin.project.view', compact('pageTitle', 'project', 'projects', 'users', 'admins', 'messages'));
    }

    public function delete($files)
    {
        $path = imagePath()['file']['path'];
        $deletefile = File::delete($path.$files);

        $notify[] = ['success', 'File Deleted Successfuly.'];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|max:80',
            'email' => 'required|email|max:60|unique:agents,email,' . $id,
            'phone' => 'required|max:40|unique:agents,phone,' . $id,
            'city' => 'required|exists:cities,id',
            'location' => 'required|exists:locations,id',
            'blood' => 'required|exists:bloods,id',
            'gender' => 'required|in:1,2',
            'religion' => 'required|max:40',
            'profession' => 'required|max:80',
            'donate' => 'required|integer',
            'address' => 'required|max:255',
            // 'details' => 'required',
            'birth_date' => 'required|date',
            // 'last_donate' =>'required|date',
            // 'facebook' => 'required',
            // 'twitter' => 'required',
            // 'linkedinIn' => 'required',
            // 'instagram' => 'required',
            'image' => ['nullable', 'image', new FileTypeValidate(['jpg', 'jpeg', 'png'])],
        ]);
        $agent = Agent::findOrFail($id);
        $agent->name = $request->name;
        $agent->email = $request->email;
        $agent->phone = $request->phone;
        $agent->city_id = $request->city;
        $agent->blood_id = $request->blood;
        $agent->location_id = $request->location;
        $agent->gender = $request->gender;
        $agent->religion = $request->religion;
        $agent->profession = $request->profession;
        $agent->address = $request->address;
        $agent->details = $request->details;
        $agent->total_donate = $request->donate;
        $agent->birth_date =  $request->birth_date;
        $agent->last_donate = $request->last_donate;
        $socialMedia = [
            'facebook' => $request->facebook,
            'twitter' => $request->twitter,
            'linkedinIn' => $request->linkedinIn,
            'instagram' => $request->instagram
        ];
        $agent->socialMedia = $socialMedia;
        $path = imagePath()['agent']['path'];
        $size = imagePath()['agent']['size'];
        if ($request->hasFile('image')) {
            try {
                $filename = uploadImage($request->image, $path, $size, $agent->image);
            } catch (\Exception $exp) {
                $notify[] = ['error', 'Image could not be uploaded.'];
                return back()->withNotify($notify);
            }
            $agent->image = $filename;
        }
        $agent->status = $request->status ? 1 : 2;
        $agent->save();
        $notify[] = ['success', 'Students has been updated'];
        return back()->withNotify($notify);
    }
}
