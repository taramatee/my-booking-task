<?php

namespace App\Interfaces;

interface AppointmentInterface
{
    public function listPatientAppointment();
    public function getAddAppointment();
    public function createAppointment(array $data);
    public function updateAppointmentPatient(array $data, $aptId);

    public function listDoctorAppointment();
    public function getPostpnedAppointment($aptId);
    public function savePostpnedAppointment(array $data);
    public function updateAppointmentDoctor(array $data, $aptId);
}
