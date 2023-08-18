<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Ticket;
use App\Models\Timetable;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    //

//STORE ZA SPREMANJE DOLASKA NA KONF!
    public function store_arrival(Request $request){
        try {

            $request->validate([
               'user_id'=>'required|exists:users,id',
               'timetable_id'=>'required|exists:timetables,id',
               'conference_role_id' => 'required|exists:conference_roles,id'
            ]);
            $timetable = Timetable::findOrFail($request->timetable_id);
            $conference_day = $timetable->conference_day_id;
            $ticket = Ticket::where('user_id', $request->user_id)
                ->where('conference_day_id', $conference_day)
                ->first();
            if(!$ticket){
                return response()->json("The ticket does not exist!", 404);
            }else {
                $arrival = now()->format('Y-m-d H:i:s');
                $attendance = Attendance::create([
                    'present' => true,
                    'arrival' => $arrival,
                    'user_id' => $request->user_id,
                    'timetable_id' => $request->timetable_id,
                    'conference_role_id' => $request->conference_role_id,

                ]);
                return response()->json($attendance, 201);
            }
        } catch (\Exception $e){
            return response()->json($e->getMessage());

        }
    }

    //funkcija za dodavanje odlaska sa predavanja
    public function add_departure($id){
        try {
            $attendance=Attendance::findOrFail($id);
            $departure = now()->format('Y-m-d H:i:s');
            $attendance->departure = $departure;
            $attendance->save();
            return response()->json($attendance, 201);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }
    }


    //funkcija koja mijenja rolu sudionika konferencije
    public function update_role($id, Request $request){
        try{
            $attendance=Attendance::findOrFail($id);
            $request->validate([
                'conference_role_id' => 'required|exists:conference_roles,id'
            ]);
            $attendance->conference_role_id = $request->conference_role_id;
            $attendance->save();
            return response()->json($attendance, 201);

        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }
    }

    //funkcija koja prikazuje sve prisutne
    public function showAttendanceForLecture($timetable_id){
        try{
            $attendance = Attendance::with('user', 'conference_role')
                ->where('timetable_id', $timetable_id)
                ->where('present', true)
                ->get();

            return response()->json($attendance, 200);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }
    }
    //funkcija koja prikazuje sve prisutnosti korisnika
    public function showUserAttendenceAll($user_id){
        try{
            $attendance = Attendance::with('timetable', 'conference_role')
                ->where('user_id', $user_id)
                ->get();

            return response()->json($attendance, 200);
        }catch (\Exception $e){
            return response()->json($e->getMessage());

        }
    }

    //funkcja koja prikazuje prisutnosti korisnika na jednom danu konferencije
    public function showUserAttendanceOfOneConferenceDay($user_id,$conference_day_id){
        try {
            $attendances = Attendance::where('user_id', $user_id)
                ->whereHas('timetable', function ($query) use ($conference_day_id) {
                    $query->where('conference_day_id', $conference_day_id);
                })
                ->get();

            return response()->json($attendances, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }



}
