<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Seed Admin
        DB::table('admins')->insert([
            'username' => 'admin',
            'password' => Hash::make('password'),
            'email' => 'admin@bloodbank.com',
            'name' => 'System Administrator',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Seed Blood Groups
        $bloodGroups = [
            ['name' => 'A+', 'code' => 'A+', 'description' => 'A Positive - Can donate to A+, AB+. Can receive from A+, A-, O+, O-'],
            ['name' => 'A-', 'code' => 'A-', 'description' => 'A Negative - Can donate to A+, A-, AB+, AB-. Can receive from A-, O-'],
            ['name' => 'B+', 'code' => 'B+', 'description' => 'B Positive - Can donate to B+, AB+. Can receive from B+, B-, O+, O-'],
            ['name' => 'B-', 'code' => 'B-', 'description' => 'B Negative - Can donate to B+, B-, AB+, AB-. Can receive from B-, O-'],
            ['name' => 'AB+', 'code' => 'AB+', 'description' => 'AB Positive - Universal Recipient. Can receive from all types.'],
            ['name' => 'AB-', 'code' => 'AB-', 'description' => 'AB Negative - Can receive from AB-, A-, B-, O-'],
            ['name' => 'O+', 'code' => 'O+', 'description' => 'O Positive - Can donate to O+, A+, B+, AB+. Can receive from O+, O-'],
            ['name' => 'O-', 'code' => 'O-', 'description' => 'O Negative - Universal Donor. Can donate to all types.'],
        ];

        foreach ($bloodGroups as $bg) {
            DB::table('blood_groups')->insert([...$bg, 'created_at' => now(), 'updated_at' => now()]);
        }

        // Seed Doctors
        $doctors = [
            ['name' => 'Dr. Priya Sharma', 'address' => '123 Medical Center Road, Hyderabad', 'mobile' => '9876543201'],
            ['name' => 'Dr. Rajesh Kumar', 'address' => '456 Hospital Lane, Secunderabad', 'mobile' => '9876543202'],
            ['name' => 'Dr. Anjali Reddy', 'address' => '789 Health Plaza, Madhapur', 'mobile' => '9876543203'],
            ['name' => 'Dr. Suresh Babu', 'address' => '321 Clinic Street, Ameerpet', 'mobile' => '9876543204'],
            ['name' => 'Dr. Meena Devi', 'address' => '654 Wellness Center, Banjara Hills', 'mobile' => '9876543205'],
        ];

        foreach ($doctors as $doc) {
            DB::table('doctors')->insert([...$doc, 'created_at' => now(), 'updated_at' => now()]);
        }

        // Seed Donors (various blood groups, ages, eligibility statuses)
        $donors = [
            // Eligible donors
            ['fk_blood_group_id' => 1, 'name' => 'Rahul Verma', 'country' => 'India', 'mobile' => '9876543301', 'email' => 'rahul@email.com', 'password' => Hash::make('password'), 'birthdate' => '1995-05-15', 'address_line_1' => '101 MG Road', 'hemoglobin_level' => 14.5, 'systolic' => 120, 'diastolic' => 80, 'last_donation_date' => Carbon::now()->subMonths(4)],
            ['fk_blood_group_id' => 2, 'name' => 'Sneha Patel', 'country' => 'India', 'mobile' => '9876543302', 'email' => 'sneha@email.com', 'password' => Hash::make('password'), 'birthdate' => '1992-08-20', 'address_line_1' => '202 Nehru Nagar', 'hemoglobin_level' => 13.2, 'systolic' => 115, 'diastolic' => 75, 'last_donation_date' => Carbon::now()->subMonths(5)],
            ['fk_blood_group_id' => 3, 'name' => 'Amit Singh', 'country' => 'India', 'mobile' => '9876543303', 'email' => 'amit@email.com', 'password' => Hash::make('password'), 'birthdate' => '1988-12-10', 'address_line_1' => '303 Gandhi Street', 'hemoglobin_level' => 15.8, 'systolic' => 125, 'diastolic' => 82, 'last_donation_date' => Carbon::now()->subMonths(6)],
            ['fk_blood_group_id' => 4, 'name' => 'Kavitha Nair', 'country' => 'India', 'mobile' => '9876543304', 'email' => 'kavitha@email.com', 'password' => Hash::make('password'), 'birthdate' => '1990-03-25', 'address_line_1' => '404 Park Avenue', 'hemoglobin_level' => 12.8, 'systolic' => 110, 'diastolic' => 70, 'last_donation_date' => Carbon::now()->subMonths(4)],
            ['fk_blood_group_id' => 5, 'name' => 'Vikram Rao', 'country' => 'India', 'mobile' => '9876543305', 'email' => 'vikram@email.com', 'password' => Hash::make('password'), 'birthdate' => '1985-07-18', 'address_line_1' => '505 Lake View', 'hemoglobin_level' => 16.2, 'systolic' => 130, 'diastolic' => 85, 'last_donation_date' => Carbon::now()->subMonths(3)],
            ['fk_blood_group_id' => 8, 'name' => 'Deepak Gupta', 'country' => 'India', 'mobile' => '9876543306', 'email' => 'deepak@email.com', 'password' => Hash::make('password'), 'birthdate' => '1993-11-05', 'address_line_1' => '606 Hill Side', 'hemoglobin_level' => 14.8, 'systolic' => 118, 'diastolic' => 78, 'last_donation_date' => Carbon::now()->subMonths(5)],
            ['fk_blood_group_id' => 6, 'name' => 'Priyanka Joshi', 'country' => 'India', 'mobile' => '9876543307', 'email' => 'priyanka@email.com', 'password' => Hash::make('password'), 'birthdate' => '1991-06-30', 'address_line_1' => '707 Sunset Boulevard', 'hemoglobin_level' => 13.5, 'systolic' => 112, 'diastolic' => 72, 'last_donation_date' => Carbon::now()->subMonths(4)],
            ['fk_blood_group_id' => 7, 'name' => 'Karthik Menon', 'country' => 'India', 'mobile' => '9876543308', 'email' => 'karthik@email.com', 'password' => Hash::make('password'), 'birthdate' => '1987-09-12', 'address_line_1' => '808 River Road', 'hemoglobin_level' => 15.2, 'systolic' => 122, 'diastolic' => 80, 'last_donation_date' => Carbon::now()->subMonths(6)],

            // Not eligible donors (low hemoglobin or recent donation)
            ['fk_blood_group_id' => 1, 'name' => 'Sunita Devi', 'country' => 'India', 'mobile' => '9876543309', 'email' => 'sunita@email.com', 'password' => Hash::make('password'), 'birthdate' => '1998-02-14', 'address_line_1' => '909 Garden City', 'hemoglobin_level' => 11.2, 'systolic' => 105, 'diastolic' => 68, 'last_donation_date' => Carbon::now()->subMonths(4)],
            ['fk_blood_group_id' => 3, 'name' => 'Manoj Tiwari', 'country' => 'India', 'mobile' => '9876543310', 'email' => 'manoj@email.com', 'password' => Hash::make('password'), 'birthdate' => '1994-04-22', 'address_line_1' => '1010 Tech Park', 'hemoglobin_level' => 14.0, 'systolic' => 135, 'diastolic' => 88, 'last_donation_date' => Carbon::now()->subMonth()],

            // Young donors
            ['fk_blood_group_id' => 2, 'name' => 'Arjun Reddy', 'country' => 'India', 'mobile' => '9876543311', 'email' => 'arjun@email.com', 'password' => Hash::make('password'), 'birthdate' => '2005-01-10', 'address_line_1' => '1111 University Road', 'hemoglobin_level' => 13.8, 'systolic' => 118, 'diastolic' => 76, 'last_donation_date' => null],
            ['fk_blood_group_id' => 5, 'name' => 'Divya Sharma', 'country' => 'India', 'mobile' => '9876543312', 'email' => 'divya@email.com', 'password' => Hash::make('password'), 'birthdate' => '2006-06-15', 'address_line_1' => '1212 College Street', 'hemoglobin_level' => 12.5, 'systolic' => 108, 'diastolic' => 70, 'last_donation_date' => null],

            // Senior donors
            ['fk_blood_group_id' => 8, 'name' => 'Ramesh Chandra', 'country' => 'India', 'mobile' => '9876543313', 'email' => 'ramesh@email.com', 'password' => Hash::make('password'), 'birthdate' => '1960-08-20', 'address_line_1' => '1313 Senior Living', 'hemoglobin_level' => 13.0, 'systolic' => 140, 'diastolic' => 90, 'last_donation_date' => Carbon::now()->subMonths(7)],
        ];

        foreach ($donors as $donor) {
            DB::table('donors')->insert([...$donor, 'created_at' => now(), 'updated_at' => now()]);
        }

        // Seed Patients
        $patients = [
            ['id' => 1, 'name' => 'Ravi Shankar', 'email' => 'ravi@email.com', 'medical_history' => 'Thalassemia major, requires regular transfusions', 'address' => '100 Hospital Road', 'last_blood_taking_date' => Carbon::now()->subWeeks(3)],
            ['id' => 2, 'name' => 'Lakshmi Bai', 'email' => 'lakshmi@email.com', 'medical_history' => 'Post-surgery recovery, hysterectomy', 'address' => '200 Recovery Lane', 'last_blood_taking_date' => Carbon::now()->subMonths(2)],
            ['id' => 3, 'name' => 'Suresh Kumar', 'email' => 'suresh@email.com', 'medical_history' => 'Accident victim, severe blood loss', 'address' => '300 Emergency Ward', 'last_blood_taking_date' => null],
            ['id' => 4, 'name' => 'Anita Desai', 'email' => 'anita@email.com', 'medical_history' => 'Chemotherapy treatment, low blood count', 'address' => '400 Cancer Center', 'last_blood_taking_date' => Carbon::now()->subWeeks(2)],
            ['id' => 5, 'name' => 'Vijay Prasad', 'email' => 'vijay@email.com', 'medical_history' => 'Open heart surgery scheduled', 'address' => '500 Cardiac Wing', 'last_blood_taking_date' => null],
        ];

        foreach ($patients as $patient) {
            DB::table('patients')->insert([...$patient, 'created_at' => now(), 'updated_at' => now()]);
        }

        // Seed Blood Inventory
        $inventory = [
            ['fk_blood_group_id' => 1, 'fk_donor_id' => 1, 'quantity' => 5.0, 'collection_date' => Carbon::now()->subWeeks(2), 'expiry_date' => Carbon::now()->addWeeks(4)],
            ['fk_blood_group_id' => 2, 'fk_donor_id' => 2, 'quantity' => 3.5, 'collection_date' => Carbon::now()->subWeeks(3), 'expiry_date' => Carbon::now()->addWeeks(3)],
            ['fk_blood_group_id' => 3, 'fk_donor_id' => 3, 'quantity' => 4.0, 'collection_date' => Carbon::now()->subWeeks(1), 'expiry_date' => Carbon::now()->addWeeks(5)],
            ['fk_blood_group_id' => 4, 'fk_donor_id' => 4, 'quantity' => 2.0, 'collection_date' => Carbon::now()->subWeeks(4), 'expiry_date' => Carbon::now()->addWeeks(2)],
            ['fk_blood_group_id' => 5, 'fk_donor_id' => 5, 'quantity' => 6.0, 'collection_date' => Carbon::now()->subWeeks(2), 'expiry_date' => Carbon::now()->addWeeks(4)],
            ['fk_blood_group_id' => 8, 'fk_donor_id' => 6, 'quantity' => 8.0, 'collection_date' => Carbon::now()->subWeeks(1), 'expiry_date' => Carbon::now()->addWeeks(5)],
            ['fk_blood_group_id' => 6, 'fk_donor_id' => 7, 'quantity' => 1.5, 'collection_date' => Carbon::now()->subWeeks(5), 'expiry_date' => Carbon::now()->addWeek()],
            ['fk_blood_group_id' => 7, 'fk_donor_id' => 8, 'quantity' => 4.5, 'collection_date' => Carbon::now()->subWeeks(2), 'expiry_date' => Carbon::now()->addWeeks(4)],
        ];

        foreach ($inventory as $inv) {
            DB::table('blood_inventory')->insert([...$inv, 'created_at' => now(), 'updated_at' => now()]);
        }

        // Seed Appointments
        $appointments = [
            ['fk_donor_id' => 1, 'fk_doctor_id' => 1, 'appointment_time' => Carbon::now()->addDays(2)->setTime(10, 0), 'status' => 'Pending'],
            ['fk_donor_id' => 3, 'fk_doctor_id' => 2, 'appointment_time' => Carbon::now()->addDays(3)->setTime(14, 30), 'status' => 'Pending'],
            ['fk_donor_id' => 5, 'fk_doctor_id' => 1, 'appointment_time' => Carbon::now()->addDays(5)->setTime(9, 0), 'status' => 'Confirmed'],
            ['fk_donor_id' => 2, 'fk_doctor_id' => 3, 'appointment_time' => Carbon::now()->subDays(3)->setTime(11, 0), 'status' => 'Completed'],
            ['fk_donor_id' => 7, 'fk_doctor_id' => 2, 'appointment_time' => Carbon::now()->subDays(5)->setTime(15, 0), 'status' => 'Completed'],
            ['fk_donor_id' => null, 'fk_doctor_id' => 1, 'appointment_time' => Carbon::now()->addDays(7)->setTime(10, 0), 'status' => 'Pending'],
            ['fk_donor_id' => null, 'fk_doctor_id' => 4, 'appointment_time' => Carbon::now()->addDays(8)->setTime(14, 0), 'status' => 'Pending'],
        ];

        foreach ($appointments as $apt) {
            DB::table('appointments')->insert([...$apt, 'created_at' => now(), 'updated_at' => now()]);
        }

        // Seed Blood Distributions
        $distributions = [
            ['fk_patient_id' => 1, 'fk_blood_group_id' => 1, 'request_unit' => 2, 'approved_unit' => 2],
            ['fk_patient_id' => 2, 'fk_blood_group_id' => 5, 'request_unit' => 1, 'approved_unit' => 1],
            ['fk_patient_id' => 3, 'fk_blood_group_id' => 8, 'request_unit' => 3, 'approved_unit' => null],
            ['fk_patient_id' => 4, 'fk_blood_group_id' => 3, 'request_unit' => 2, 'approved_unit' => null],
            ['fk_patient_id' => 5, 'fk_blood_group_id' => 8, 'request_unit' => 4, 'approved_unit' => 2],
        ];

        foreach ($distributions as $dist) {
            DB::table('blood_distributions')->insert([...$dist, 'created_at' => now(), 'updated_at' => now()]);
        }

        $this->command->info('Demo data seeded successfully!');
        $this->command->info('Admin: admin / password');
        $this->command->info('Donors: Use any donor email with password "password"');
    }
}
