<?php

namespace App\Repositories;

use App\Models\Task;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use App\Interfaces\AppointmentInterface;

class AppointmentRepository implements AppointmentInterface
{
    public function listPatientAppointment() {
        $user_id = Auth::user()->id;
        $data = Appointment::join('users', 'users.id', '=', 'appointments.doctor_id')->where('patient_id', $user_id)->where('created_by', $user_id)->get(['users.name', 'appointments.date', 'appointments.time', 'appointments.status', 'appointments.id']);
        return $data;
    }

    public function getAddAppointment() {
        return User::where('role', 'doctor')->get(['id', 'name']);
    }

    public function createAppointment(array $aptDetails) {
        $user_id = Auth::user()->id;
        $val = Appointment::create([
            'patient_id' => $user_id,
            'doctor_id' => $aptDetails['doctor'],
            'date' => date('Y-m-d', strtotime($aptDetails['date'])),
            'time' => $aptDetails['time'],
            'status' => 0,
            'created_by' => $user_id,
            'updated_by' => $user_id,
          ]);
        return $val;
    }

    public function updateAppointmentPatient(array $newDetails, $aptId) {
        $record = Appointment::where('id', $aptId)->where('patient_id', Auth::user()->id)->first();
        if(isset($record)) {
            $record->update([
                'status' => $newDetails['request_id']
            ]);
            return true;
        }
        return false;
    }


    public function listDoctorAppointment() {
        $user_id = Auth::user()->id;
        $data = Appointment::join('users', 'users.id', '=', 'appointments.patient_id')->where('doctor_id', $user_id)->get(['users.name', 'appointments.date', 'appointments.time', 'appointments.status', 'appointments.id']);
        return $data;
    }

    public function getPostpnedAppointment($aptId) {
        $appointment = Appointment::find($aptId);
        $appointment->date = date('m-d-Y', strtotime($appointment->date));
        return $appointment;

    }

    public function savePostpnedAppointment(array $newDetails) {
        \Log::info('newDetails');
        \Log::info($newDetails);
        
        $record = Appointment::find($newDetails['id']);
        if($record) {
            $record->update([
                'date' =>  date('Y-m-d', strtotime($newDetails['date'])),
                'time' => $newDetails['time'],
                'status' => 3
            ]);
        }
    }

    public function updateAppointmentDoctor(array $newDetails, $aptId) {
        $record = Appointment::where('id', $aptId)->where('doctor_id', Auth::user()->id)->first();
        if(isset($record)) {
            $record->update([
                'status' => $newDetails['request_id']
            ]);
            return response()->json([
                'status' => true,
                'msg' => 'Status Updated'
            ], 200);
        }
    }


}
