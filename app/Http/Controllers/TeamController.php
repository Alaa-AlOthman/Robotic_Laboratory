<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
  public function getAllTeam()
  {
    $teams = Team::all();
    return response()->json($teams, 200);
  }

  public function getAllTeamWithPaginate()
  {
    $teams = Team::paginate(8);
    return response()->json($teams, 200);
  }

  public function getTeam(Request $request)
  {
    $team = Team::where(['id' => $request->team_id])->first();
    return response()->json($team, 200);
  }

  // ['team_name','team_members','teams_goal','team_leader']
  public function addTeam(Request $request)
{
  $validated = $request->validate([
    'team_name' => 'required|string|max:255',
    'team_members' => 'required|string',
    'teams_goal' => 'required|string',
    'team_leader' => 'required|string'
  ], [
    'team_name.required' => 'اسم الفريق مطلوب',
    'team_members.required' => 'أعضاء الفريق مطلوبون',
    'teams_goal.required' => 'هدف الفريق مطلوب',
    'team_leader.required' => 'قائد الفريق مطلوب'
  ]);

  try {
    $team = Team::create($validated);
    return response()->json([
      'message' => '✅ تم إنشاء الفريق بنجاح',
      'team' => $team
    ], 201);
  } catch (\Exception $e) {
    return response()->json(['error' => 'حدث خطأ أثناء إنشاء الفريق: ' . $e->getMessage()], 500);
  }
}

public function editTeam(Request $request)
{
  // التحقق من المدخلات المطلوبة
  $validated = $request->validate([
    'id' => 'required|exists:teams,id',
    'team_name' => 'nullable|string|max:255',
    'team_members' => 'nullable|string',
    'teams_goal' => 'nullable|string',
    'team_leader' => 'nullable|string'
  ], [
    'id.required' => '❌ رقم الفريق مطلوب',
    'id.exists' => '❌ الفريق غير موجود في قاعدة البيانات'
  ]);

  try {
    $team = Team::find($validated['id']);

    // التحديث الجزئي حسب المتوفر
    $team->update($request->only(['team_name', 'team_members', 'teams_goal', 'team_leader']));

    return response()->json([
      'message' => '✅ تم تعديل بيانات الفريق بنجاح',
      'team' => $team
    ], 200);

  } catch (\Exception $e) {
    return response()->json([
      'error' => '❌ حدث خطأ أثناء تعديل بيانات الفريق ' 
    ], 500);
  }
}


public function deleteTeam(Request $request)
{
  try {
    $team = Team::findOrFail($request->id);
    $team->delete();
    return response()->json(['message' => '✅ تم حذف الفريق بنجاح'], 200);
  } catch (\Exception $e) {
    return response()->json(['error' => '❌ تعذر حذف الفريق: ' . $e->getMessage()], 500);
  }
}

  // public function addTeam(Request $request)
  // {
  //   $team = Team::create([
  //     'team_name' => $request->team_name,
  //     'team_members' => $request->team_members,
  //     'teams_goal' => $request->teams_goal,
  //     'team_leader' => $request->team_leader
  //   ]);
  //   return response()->json($team, 200);
  // }

  // public function editTeam(Request $request)
  // {
  //   $team = Team::query()->where(['id' => $request->id])->first();
  //   if ($team) {
  //     if ($request->team_name)
  //       $newTeamName = $request->team_name;
  //     if ($request->team_members)
  //       $newTeamMembers = $request->team_members;
  //     if ($request->teams_goal)
  //       $newTeamGoal = $request->teams_goal;
  //     if ($request->team_leader)
  //       $newTeamLeader = $request->team_leader;

  //     $team->update([
  //       'team_name' => $newTeamName,
  //       'team_members' => $newTeamMembers,
  //       'teams_goal' => $newTeamGoal,
  //       'team_leader' => $newTeamLeader
  //     ]);
  //     return response()->json($team, 200);
  //   }
  // }

  // public function deleteTeam(Request $request)
  // {
  //   $team = Team::findOrFail($request->id);
  //   $team->delete();
  //   return response()->json($team, 200);
  // }
}
