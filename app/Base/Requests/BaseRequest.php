<?php

namespace App\Base\Requests;

use App\Exceptions\BaseException;
use App\Modules\Customer\Models\Customer;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Lang;

class BaseRequest extends FormRequest
{
    /**
     * overriding failedValidation
     *
     * @param Validator $validator
     */
    protected function failedValidation(Validator $validator)
    {
        parent::failedValidation($validator);
        throw (new ValidationException($validator))
            ->errorBag($this->errorBag);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    public static function getLoggedCustomer(){
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
}
