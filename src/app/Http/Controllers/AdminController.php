<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\User;
use App\Models\Work;
use App\Models\Rest;
use App\Models\WorkCorrection;
use App\Models\RestCorrection;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Requests\CorrectionRequest;


class AdminController extends Controller
{
    public function showAttendanceList(Request $request) {
        if ($request->tab === null) {
            $day = Carbon::now();
            $work_day = $day->format('Y-m-d');
            $works = Work::where('date',$work_day)->get();
       
            return view('admin.attendance_list',compact('day','works'));
        } elseif ($request->tab === "previous") {
            $targetDay = Carbon::parse($request->date);
            $previousDay = $targetDay->copy()->subDay();
            $works = Work::whereDate('date',$previousDay->toDateString())->get();
            $day = $previousDay;
        
            return view('admin.attendance_list',compact('day','works'));
        } else {
            $targetDay = Carbon::parse($request->date);
            $nextDay = $targetDay->copy()->addDay();
            $works = Work::whereDate('date',$nextDay->toDateString())->get();
            $day = $nextDay;

            return view('admin.attendance_list',compact('day','works'));
        }
    }

    public function showStaffList() {
        $users = User::all();
        return view('admin.staff_list',compact('users'));
    }

    public function showStaffAttendance($id,Request $request) {
        $user = User::find($id);
        
        if ($request->tab === null) {
            $date = Carbon::now();
            $monthStart = $date->copy()->startOfMonth(); 
            $monthEnd = $date->copy()->endOfMonth();

            $dates = CarbonPeriod::create($monthStart, $monthEnd);

            $works = Work::where('user_id',$user->id)
                ->whereBetween('date', [$monthStart, $monthEnd])
                ->get()
                ->keyBy(fn($work) => Carbon::parse($work->date)->format('Y-m-d'));

            return view('admin.staff',compact('user','date','works','dates'));
        } elseif ($request->tab === "previous") {
            $request_date = $request->date;
            $targetDate = Carbon::parse($request_date);
            $startOfPreviousMonth = $targetDate->copy()->subMonth()->startOfMonth();
            $endOfPreviousMonth = $targetDate->copy()->subMonth()->endOfMonth();
            $dates = CarbonPeriod::create($startOfPreviousMonth, $endOfPreviousMonth);

            $works = Work::whereBetween('date',[$startOfPreviousMonth,$endOfPreviousMonth])
                ->where('user_id',$user->id)
                ->get()
                ->keyBy(fn($work) => Carbon::parse($work->date)
                ->format('Y-m-d'));

            $date = $startOfPreviousMonth;

            return view('admin.staff',compact('user','date','works','dates'));
        } else {
            $request_date = $request->date;
        
            $targetDate = Carbon::parse($request_date);
            $startOfNextMonth = $targetDate->copy()->addMonthNoOverflow()
                ->startOfMonth();
            $endOfNextMonth = $targetDate->copy()->addMonthNoOverflow() 
                ->endOfMonth();
            $dates = CarbonPeriod::create($startOfNextMonth, $endOfNextMonth);

            $works = Work::whereBetween('date',[$startOfNextMonth,$endOfNextMonth])
                ->where('user_id',$user->id)
                ->get()
                ->keyBy(fn($work) => Carbon::parse($work->date)->format('Y-m-d'));;

            $date = $startOfNextMonth;

            return view('admin.staff',compact('user','date','works','dates'));
        }
    }

    public function showCorrectionRequestApproval($attendance_correct_request) {
        $work_correction = WorkCorrection::find($attendance_correct_request);
        
        $rest_corrections = RestCorrection::with('work')
            ->whereHas('work',function($query) use ($work_correction){
            $query 
            -> where('work_id',$work_correction->work_id);
        })->get();
        
        return view('admin.approval',compact('work_correction','rest_corrections'));
    }

    public function approve(Request $request) {
        $work = Work::find($request->work_id);
        $work_start = substr($work->work_start,0,5);
        $work_end = substr($work->work_end,0,5);
        $work_time = $request -> only(['work_start','work_end']);
        
        if ($work_start !== $work_time['work_start'] || $work_end !== $work_time['work_end']) {
            $work->update($work_time);
        }

        // 休憩において修正申請があった時
        if($work->restCorrections->isNotEmpty()) {
            $rest_time = $request->only('rest_id','rest_start','rest_end');
            $rest_id = $rest_time['rest_id'];
            $rest_start = $rest_time['rest_start'];
            $rest_end = $rest_time['rest_end'];

            // forで一つずつrestの修正データを取り出す
            // この時、rest_idがnullであるものも含んでいる
            for($i = 0; $i < count($rest_start); $i++){
                $rest_correction = [
                    'id' => $rest_id[$i],
                    'rest_start' => $rest_start[$i],
                    'rest_end' => $rest_end[$i],
                    'work_id' => $work->id,
                ];
                
                // データベース内にあるrestのデータを取得し、idを配列に入れていく
                $rests = Rest::where('work_id',$work->id)->get();
                $rest_ids=array();
                foreach($rests as $rest){
                    array_push($rest_ids,$rest->id);
                }
        
                // 修正しようとしているrestのデータがデータベース内にあるデータかどうかidを比較して判別する
                if (in_array($rest_correction['id'],$rest_ids)) {
                    Rest::where('id',$rest_correction['id'])->update($rest_correction);
                } elseif ($rest_correction['id'] === null && $rest_correction['rest_start'] !== null && $rest_correction['rest_end'] !== null) {
                    Rest::create($rest_correction);
                } 
            }

            $rest_correction_ids = array();
            foreach ($rest_id as $id){
                array_push($rest_correction_ids,$id);
            }
            
            foreach ($rests as $rest) {
                if (!in_array($rest->id,$rest_correction_ids)) {
                    Rest::find($rest->id)->delete();
                }
            }
        } elseif ($work->rests->isNotEmpty()) {
            $rests = Rest::where('work_id',$work->id)->get();
            foreach ($rests as $rest) {
                    $rest->delete();
            }
        }
        
        $work_correction = WorkCorrection::where('work_id','=',$work->id)->first();
        $work_correction->update(['status' => '2']);
    
        return redirect()->route(('request.approval'),['attendance_correct_request' => $work_correction->id]);
    }

    public function correct (CorrectionRequest $request) {
        $work = Work::find($request->work_id);
        $work_start = substr($work->work_start,0,5);
        $work_end = substr($work->work_end,0,5);
        $work_time = $request -> only(['work_start','work_end']);
        
        if ($work_start !== $work_time['work_start'] || $work_end !== $work_time['work_end']) {
            $work->update($work_time);
        }

        $rest_time = $request->only('rest_id','rest_start','rest_end');
        $rest_id = $rest_time['rest_id'];
        $rest_start = $rest_time['rest_start'];
        $rest_end = $rest_time['rest_end'];
        
        for($i = 0; $i < count($rest_start); $i++){
            $rest_correction = [
                'id' => $rest_id[$i],
                'rest_start' => $rest_start[$i],
                'rest_end' => $rest_end[$i],
                'work_id' => $work->id
                ];
                
            $rests = Rest::where('work_id',$work->id)->get();
            $rest_ids=array();
            foreach($rests as $rest){
                array_push($rest_ids,$rest->id);
            }
        
            if (in_array($rest_correction['id'],$rest_ids) &&$rest_correction['rest_start'] !== null && $rest_correction['rest_end'] !== null) {
                Rest::where('id',$rest_correction['id'])->update($rest_correction);
            } elseif (in_array($rest_correction['id'],$rest_ids) && $rest_correction['rest_start'] === null && $rest_correction['rest_end'] === null) {
                Rest::find($rest_correction['id'])->delete();
            } elseif ($rest_correction['id'] === null && $rest_correction['rest_start'] !== null && $rest_correction['rest_end'] !== null) {
                Rest::create($rest_correction);
            } 
        }

        $rest_correction_ids = array();
        foreach($rest_id as $id){
            array_push($rest_correction_ids,$id);
        }
        
        foreach ($rests as $rest) {
            if (!in_array($rest->id,$rest_correction_ids)) {
            Rest::find($rest->id)->delete();
            }
        }
        return redirect()->route('attendance.detail',['id' => $work->id]);  
    }

    public function downloadCsv (Request $request) {
        $data = $request->all();
        $length = count($data['date']);
        
        $csvData = [];
         for ($i = 0; $i < $length; $i++) {
            $array = [
                    'date' => Carbon::parse($data['date'][$i])->translatedFormat('m/d(D)'),
                    'work_start' => $data['work_start'][$i],
                    'work_end' => $data['work_end'][$i],
                    'totalRestTime' => $data['totalRestTime'][$i],
                    'totalWorkTime' => $data['totalWorkTime'][$i]
                ];
            array_push($csvData,$array);
        }
        
        $csvHeader = [
            'date','work_start','work_end','totalRestTime','totalWorkTime'
        ];

        $response = new StreamedResponse(function () use ($csvHeader, $csvData) {
            $createCsvFile = fopen('php://output', 'w');

            mb_convert_variables('SJIS-win', 'UTF-8', $csvHeader);

            fputcsv($createCsvFile, $csvHeader);

            foreach ($csvData as $csv) {
                
                fputcsv($createCsvFile, $csv);
            }
 
            fclose($createCsvFile);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="attendance.csv"',
        ]);

        return $response;
    }
}
