<?php
namespace App\Services;

use App\Models\Admission as Model;
use App\Models\AdmissionEmailInfo;
use App\Models\Batch;
use App\Models\Count;
use App\Models\Discount;
use App\Models\Finance;
use App\Models\StudentPassword;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdmissionService
{


    public function storeData($requestAll)
    {
//        $batch = Batch::findOrFail($requestAll['batch_id']);
//        dd($batch->batch_installments->where('installment_type',1)->first());
        try{
            DB::beginTransaction();
            // Create User Account
            $user = new User();
            $user->name = $requestAll['name'];
            $user->email = $requestAll['email'];
            $random_password = $this->randString(6);
            $user->password = Hash::make($random_password);
            $user->status = array_search('Active', config('custom.status')); //active
            $user->user_type = array_search('Student', config('custom.user_types'));
            $user->save();
            //save student password
            $student_password = new StudentPassword();
            $student_password->user_id = $user->id;
            $student_password->password = $random_password;
            $student_password->save();
            // Create User admission
            $batch = Batch::findOrFail($requestAll['batch_id']);
            $setting = new Model();
            $setting->user_id = $user->id;
            $setting->created_by = Auth::user()->id;
            $setting->batch_id = $batch->id;
            $setting->date = date('Y-m-d');
            $setting->student_id = $this->getStudentUserId();
            $setting->payable_amount = $batch->fee - request('discount');
            $setting->save();

            // Create income from student
            foreach ($batch->batch_installments as $batch_installment){
                if(request('amount') == $setting->payable_amount){
                    if($batch_installment->installment_type == 1){
                        $finance = Finance::firstOrNew(['admission_id' => $setting->id,'batch_installment_id'=> $batch_installment->id]);
                        $finance->created_by = Auth::user()->id;
                        $finance->amount = $requestAll['amount'];
                        $finance->date = $requestAll['date'];
//                        $finance->status = $requestAll['payment_status'];
                        $finance->status = array_search('Paid',config('custom.payment_status'));
                        $finance->transaction_no = $requestAll['transaction_no'];
                        $finance->bank_status = $requestAll['bank_status'];
                        $finance->remark = $requestAll['remark'];
                        $finance->save();
                    }else{
                        $finance = Finance::firstOrNew(['admission_id' => $setting->id,'batch_installment_id'=> $batch_installment->id]);
                        $finance->created_by = Auth::user()->id;
                        $finance->amount = 0.0;
                        $finance->date = $requestAll['date'];
                        $finance->status = array_search('Paid',config('custom.payment_status'));
                        $finance->transaction_no = '';
                        $finance->bank_status = $requestAll['bank_status'];
                        $finance->remark = '';
                        $finance->save();
                    }
                }else{
                    if($batch_installment->installment_type == 1){
                        $finance = Finance::firstOrNew(['admission_id' => $setting->id,'batch_installment_id'=> $batch_installment->id]);
                        $finance->created_by = Auth::user()->id;
                        $finance->amount = $requestAll['amount'];
                        $finance->date = $requestAll['date'];
                        $finance->status = $requestAll['payment_status'];
                        $finance->transaction_no = $requestAll['transaction_no'];
                        $finance->bank_status = $requestAll['bank_status'];
                        $finance->remark = $requestAll['remark'];
                        $finance->save();
                    }else{
                        $finance = Finance::firstOrNew(['admission_id' => $setting->id,'batch_installment_id'=> $batch_installment->id]);
                        $finance->created_by = Auth::user()->id;
                        $finance->amount = 0.0;
                        $finance->date = $requestAll['date'];
                        $finance->status = array_search('Unpaid',config('custom.payment_status')); // unpaid
                        $finance->transaction_no = '';
                        $finance->bank_status = 2; //unverified
                        $finance->remark = '';
                        $finance->save();
                    }
                }
            }
            //create discount for student
            $discount = new Discount();
            $discount->created_by = Auth::user()->id;
            $discount->admission_id = $setting->id;
            $discount->amount = $requestAll['discount'];
            $discount->date = $requestAll['date'];
            $discount->save();
            DB::commit();
            return $setting;
        }
        catch(\Exception $e){
            DB::rollback();
            throw $e;
        }
    }
    public function getStudentUserId()
    {
        $count = Count::getCount();
        $student_id = 'ET' . date('Y') . date('m') . $count;
        return $student_id;
    }

    //to generate random password
    public function randString( $length ) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars),0,$length);
    }

    //save the admission email information
    public function storeAdmissionEmailInfo($admission)
    {
      $admission_email = new AdmissionEmailInfo();
      $admission_email->admission_id = $admission->id;
      $admission_email->content = '<p>Thank You for making the enrollment payment. We are excited to have you onboard and wish you have an amazing learning experience in <strong>&nbsp;Extratech</strong>.</p> <p>We would like to kindly request you to change your temporary password to complete the enrollment form. Then download Skype and send a message to Binod Kunwar at Skype ID: binod.kunwar56 to be added in the learning group.</p> <p> <strong>Your AMS Login Details</strong> </p><p> <strong>Username :</strong> '.$admission->user->email.'</p><p> <strong>Password :</strong>'.$admission->user->student_password.'</p>';
      $admission_email->save();
      return $admission_email;
    }

    public function updateData($requestAll, $id)
    {
        try{
            DB::beginTransaction();
            // Create User Account
            $setting = Model::findOrFail($id);
            $user = $setting->user;
            $user->name = $requestAll['name'];
            $user->email = $requestAll['email'];
            $user->password = Hash::make('password');
            $user->status = array_search('Active', config('custom.status')); //active
            $user->user_type = array_search('Student', config('custom.user_types'));
            $user->save();
            // Create User admission
            $batch = Batch::findOrFail($requestAll['batch_id']);
            $setting->user_id = $user->id;
            $setting->created_by = Auth::user()->id;
            $setting->batch_id = $batch->id;
            $setting->date = date('Y-m-d');
            $setting->student_id = $this->getStudentUserId();
            $setting->payable_amount = $batch->fee - request('discount');
            $setting->save();

            // Create income from student
            $finance = $setting->finances[0];
            $finance->amount = $requestAll['amount'];
            $finance->date = $requestAll['date'];
            $finance->status = $requestAll['payment_status'];
            $finance->transaction_no = $requestAll['transaction_no'];
            $finance->bank_status = $requestAll['bank_status'];
            $finance->remark = $requestAll['remark'];
            $finance->save();
            //create discount for student
            $discount = $setting->discount;
            $discount->created_by = Auth::user()->id;
            $discount->admission_id = $setting->id;
            $discount->amount = $requestAll['discount'];
            $discount->date = $requestAll['date'];
            $discount->save();
            DB::commit();
            return $setting;
        }
        catch(\Exception $e){
            DB::rollback();
            throw $e;
        }
    }
}
