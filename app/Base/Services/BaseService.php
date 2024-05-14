<?php

namespace App\Base\Services;

use App\Exceptions\BaseException;
use App\Modules\Customer\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;

class BaseService
{
    public function __construct()
    {

    }


    static function getLoggedCustomer(){
        try{
            $request = request();
            $currentCustomer = Customer::where('remote_customer_id', $request->get('customer_token'))->first();

            if($currentCustomer){
                return $currentCustomer;
            }
            else{
                throw new BaseException(1008, ['error' => Lang::get('appcodes.1008')]);
            }
        }
        catch(\Exception $e){
            throw new BaseException(1008, ['error' => Lang::get('appcodes.1008')]);
        }
    }

    static function sendMail(string $to = "", string $subject = "", string $view = "", array $data){
        try{
            Mail::send($view, $data, function ($message) use ($to, $subject) {
                $message->from(getenv('MAIL_FROM'), 'www.test.lk');
                $message->to($to);
                $message->subject($subject);
            });
        }
        catch(\Exception $e){
            throw new BaseException(1006, ["email" => [$e->getMessage()]]);
        }
    }
}
