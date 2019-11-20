<?php

namespace App\Http\Controllers;

use Auth;
use App\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getAccounts()
    {
        $accounts = Auth::user()->accounts()->latest()->get();

        return response()->json(['accounts' => $accounts]);
    }

    public function store(Request $request)
    {
        try {
            $accounts = explode("\n", $request->accounts);
            $validAccounts = [];
            foreach ($accounts as $account) {
                $accountDetails = preg_split('/[\s-|;:,]+/', $account);
                if (count($accountDetails) >= 3
                    && isValidEmail($accountDetails[0])
                    && isValidEmail($accountDetails[2])) {
                    $validAccounts[] = $accountDetails;
                }
            }

            $numberInserted = 0;
            foreach ($validAccounts as $account) {
                $existAccount = Auth::user()->accounts()->withTrashed()->whereEmail($account[0])->first();
                if (!$existAccount) {
                    Auth::user()->accounts()->create([
                        'email' => $account[0],
                        'password' => $account[1],
                        'recovery_email' => $account[2],
                        'phone_number' => count($account) >= 4 ? $account[3] : null,
                        'notes' => $request->notes,
                    ]);
                    $numberInserted++;
                } elseif ($existAccount->trashed()) {
                    $existAccount->restore();
                    $existAccount->update([
                        'password' => $account[1],
                        'recovery_email' => $account[2],
                        'phone_number' => count($account) >= 4 ? $account[3] : null,
                        'notes' => $request->notes,
                    ]);
                }
            }

            return response()->json(['status' => 'success', 'numberInserted' => $numberInserted]);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage()]);
        }
    }
    
    public function update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
                'recovery_email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()->first()]);
            } 

            $existAccount = Auth::user()->accounts()->withTrashed()->where('id', '!=', $request->id)->whereEmail($request->email)->first();
            if ($existAccount) {
                if ($existAccount->trashed()) {
                    $existAccount->forceDelete();
                } else {
                    return response()->json(['status' => 'error', 'message' => 'Đã tồn tại tài khoản này trên hệ thống']);
                }
            }

            $account = Auth::user()->accounts()->find($request->id);
            $account->update([
                'email' => $request->email,
                'password' => $request->password,
                'recovery_email' => $request->recovery_email,
                'phone_number' => $request->phone_number,
                'notes' => $request->notes,
            ]);

            return response()->json(['status' => 'success']);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage()]);
        }
    }

    public function delete(Request $request)
    {
        try {
            $account = Auth::user()->accounts()->find($request->id);
            $account->delete();

            return response()->json(['status' => 'success']);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage()]);
        }
    }
    
    public function massDelete(Request $request)
    {
        try {

            $accounts = Auth::user()->accounts()->whereIn('id', $request->ids);
            $accounts->delete();

            return response()->json(['status' => 'success']);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage()]);
        }
    }

    public function updateStatus(Request $request)
    {
        try {
            $account = Auth::user()->accounts()->find($request->id);
            
            $account->update([
                'status' => $request->status,
                'detail_reason' => $request->detail_reason,
                'cookie' => $request->cookie,
            ]);

            if ($request->channel_name) {
                $account->update([
                    'channel_name' => $request->channel_name,
                    'channel_link' => $request->channel_link
                ]);
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage()]);
        }
    }
}
