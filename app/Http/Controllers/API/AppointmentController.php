<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\AppointmentInterface;
use Illuminate\Support\Facades\Redirect;
use App\Repositories\AppointmentRepository;

class AppointmentController extends Controller
{

    private $AppointmentRepository;

    public function __construct(AppointmentRepository $AppointmentRepository)
    {
        $this->AppointmentRepository = $AppointmentRepository;
    }

    public function addAppointment() {
        $doctors = $this->AppointmentRepository->getAddAppointment();
        return view('create', compact('doctors'));
    }

    public function index() {
        if(\request()->ajax()){
            // $user_id = Auth::user()->id;
            // $data = Appointment::join('users', 'users.id', '=', 'appointments.doctor_id')->where('patient_id', $user_id)->where('created_by', $user_id)->get(['users.name', 'appointments.date', 'appointments.time', 'appointments.status', 'appointments.id']);
            $data = $this->AppointmentRepository->listPatientAppointment();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $status = 'Pending';
                    if($row->status == 1) {
                        $status = 'Canceled';
                    } elseif($row->status == 2) {
                        $status = 'Rejected';
                    } elseif($row->status == 3) {
                        $status = 'Postponed';
                    } elseif($row->status == 4) {
                        $status = 'Approved';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" data-id='.$row->id.' request-id="1" class="edit btn btn-success btn-sm updateStatus">Cancel</a>
                    <a href="javascript:void(0)" data-id='.$row->id.' request-id="2" class="delete btn btn-danger btn-sm updateStatus">Reject</a>
                    <a href="'.route("postponed",[$row->id]).'" target="_blank" data-id='.$row->id.' request-id="3" class="delete btn btn-primary btn-sm postponeStatus">Postpone</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('dashboard.patient-dashboard');
    }

    public function create(Request $request) {
        $user_id = Auth::user()->id;
        $request->validate([
            'doctor' => 'required',
            'date' => 'required',
            'time' => 'required',
        ]);

        $check_if_exists = Appointment::where('doctor_id', $request->doctor)
        ->where('date', date('Y-m-d', strtotime($request->date)))
        ->where('time', $request->time)
        ->exists();
        if($check_if_exists) {
            return redirect()->back()->with('message', 'This slot is already taken by other patient');
        }
        $data = $this->AppointmentRepository->createAppointment($request->all());

        if($data) {
            return redirect('dashboard');
        }
    }

    public function update(Request $request, $id) {
        $data = $this->AppointmentRepository->updateAppointmentPatient($request->all(), $id);
        if($data) {
            return response()->json([
                'status' => true,
                'msg' => 'Status Updated'
            ], 200);
        }
        return response()->json('Ooops! No record found1', 404);

    }

    public function viewAppointment() {
        if(\request()->ajax()){
            $data = $this->AppointmentRepository->listDoctorAppointment();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $status = 'Pending';
                    if($row->status == 1) {
                        $status = 'Canceled';
                    } elseif($row->status == 2) {
                        $status = 'Rejected';
                    } elseif($row->status == 3) {
                        $status = 'Postponed';
                    } elseif($row->status == 4) {
                        $status = 'Approved';
                    }
                    return $status;
                })
                ->addColumn('action', function($row){
                    $actionBtn = '<a href="javascript:void(0)" data-id='.$row->id.' request-id="1" class="edit btn btn-success btn-sm updateStatus">Cancel</a>
                    <a href="javascript:void(0)" data-id='.$row->id.' request-id="2" class="delete btn btn-danger btn-sm updateStatus">Reject</a>
                    <a href="javascript:void(0)" data-id='.$row->id.' request-id="4" class="delete btn btn-primary btn-sm updateStatus">Approve</a>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'status'])
                ->make(true);
        }
        return view('dashboard.doctor-dashboard');
    }

    public function statusUpdate(Request $request, $id) {
        $data = $this->AppointmentRepository->updateAppointmentDoctor($request->all(), $id);
        if($data) {
            return response()->json([
                'status' => true,
                'msg' => 'Status Updated'
            ], 200);
        }
        return response()->json('Ooops! No record found2', 404);
    }

    public function postponed($id) {
        $appointment = $this->AppointmentRepository->getPostpnedAppointment($id);
        $doctors = User::where('role', 'doctor')->get(['id', 'name']);
        return view('postpone-appointment', compact('appointment', 'doctors'));
    }

    public function savePostponed(Request $request) {
        $request->validate([
            'date' => 'required',
            'time' => 'required',
        ]);
        $check_if_exists = Appointment::where('doctor_id', $request->doctor)
        ->where('date', date('Y-m-d', strtotime($request->date)))
        ->where('time', $request->time)
        ->exists();
        if($check_if_exists) {
            return redirect()->back()->with('message', 'This slot is already taken by other patient');
        }
        $this->AppointmentRepository->savePostpnedAppointment($request->all());
        return redirect('dashboard');
    }
}
