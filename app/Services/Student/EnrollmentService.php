<?php
namespace App\Services\Student;

use App\Models\Course;
use App\Models\CourseMaterial;
use App\Models\Student as Model;
use App\Models\User;
use App\Services\SettingService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class EnrollmentService {


    public function newPassword($requestAll)
    {
        $user = Auth::user();
        $user->password = Hash::make($requestAll['password']);
        $user->save();
        return $user;
    }
    public function storeData($requestAll)
    {
        $setting = new Model();
        $setting->admission_id  = Auth::user()->admission->id;
        $setting->gender = $requestAll['gender'];
        $setting->dob = $requestAll['dob'];
        $setting->country_of_birth = $requestAll['country_of_birth'];
        $setting->mobile_no = $requestAll['mobile_no'];
        $setting->residential_address = $requestAll['residential_address'];
        $setting->state = $requestAll['state'];
        $setting->post_code = $requestAll['post_code'];
        $setting->commencement_date = $requestAll['commencement_date'];
        $setting->is_aus_permanent_resident = $requestAll['is_aus_permanent_resident'];
        $setting->is_living_in_aus = $requestAll['is_living_in_aus'];
        $setting->country_of_living = $requestAll['country_of_living'];
        $setting->visa_type = $requestAll['visa_type'];
        $setting->passport_number = $requestAll['passport_number'];
        $setting->passport_expiry_date = $requestAll['passport_expiry_date'];
        $setting->e_contact_name = $requestAll['e_contact_name'];
        $setting->relation = $requestAll['relation'];
        $setting->e_contact_no = $requestAll['e_contact_no'];
        $setting->term_and_condition = $requestAll['term_and_condition'];
        $setting->privacy = $requestAll['privacy'];
        $setting->signature = $requestAll['signature'];
        if($requestAll['image']){
            $directory = SettingService::makeDirectory(array_search('student',config('custom.image_folders')));
            $extension = $requestAll['image']->getClientOriginalExtension();
            $file_name = md5(rand(111,999).Auth::user()->admission->id).'.'.$extension;
            $path = $directory.$file_name;
            $requestAll['image']->move($directory,$file_name);
            $setting->image = $path;
        }
        if($setting->save()){
            $user = Auth::user();
            $user->name = $requestAll['name'];
            $user->email = $requestAll['email'];
            $user->save();
        }
        return $setting;
    }

    public function updateData($requestAll,$id)
    {
        $setting = Model::findOrFail($id);
        $user = $setting->admission->user;
        $user->name = $requestAll['name'];
        $user->email = $requestAll['email'];
        $user->save();
        $setting->gender = $requestAll['gender'];
        $setting->dob = $requestAll['dob'];
        $setting->country_of_birth = $requestAll['country_of_birth'];
        $setting->mobile_no = $requestAll['mobile_no'];
        $setting->residential_address = $requestAll['residential_address'];
        $setting->state = $requestAll['state'];
        $setting->post_code = $requestAll['post_code'];
        $setting->commencement_date = $requestAll['commencement_date'];
        $setting->is_aus_permanent_resident = $requestAll['is_aus_permanent_resident'];
        $setting->is_living_in_aus = $requestAll['is_living_in_aus'];
        $setting->country_of_living = $requestAll['country_of_living'];
        $setting->visa_type = $requestAll['visa_type'];
        $setting->passport_number = $requestAll['passport_number'];
        $setting->passport_expiry_date = $requestAll['passport_expiry_date'];
        $setting->e_contact_name = $requestAll['e_contact_name'];
        $setting->relation = $requestAll['relation'];
        $setting->e_contact_no = $requestAll['e_contact_no'];
        if(request('image')){
            if(is_file(public_path().'/'.$setting->image) && file_exists(public_path().'/'.$setting->image)){
                unlink($setting->image);
            }
            $directory = SettingService::makeDirectory(array_search('student',config('custom.image_folders')));
            $extension = $requestAll['image']->getClientOriginalExtension();
            if(Auth::user()->admission){
                $file_name = md5(rand(111,999).Auth::user()->admission->id).'.'.$extension;
            }else{
                $file_name = md5(rand(111,999).$setting->admission->id).'.'.$extension;
            }
            $path = $directory.$file_name;
            $requestAll['image']->move($directory,$file_name);
            $setting->image = $path;
        }
        $setting->save();
        return $setting;
    }

    public function getMaterials()
    {
        $settings = CourseMaterial::whereHas('batch_course_materials',function ($q){
                                 $q->where('batch_id',Auth::user()->admission->batch_id);
                    });
        if(request('name')){
            $key = \request('name');
            $settings = $settings->where('name','like','%'.$key.'%');
        }
        return $settings->paginate(config('custom.per_page'));
    }

}
