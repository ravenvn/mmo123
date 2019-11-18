<?php

namespace App\Http\Controllers;

use Auth;
use App\Account;
use Illuminate\Http\Request;

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
        $accounts = Auth::user()->accounts;

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
                $existAccount = Auth::user()->accounts()->whereEmail($account[0])->first();
                if (!$existAccount) {
                    Auth::user()->accounts()->create([
                        'email' => $account[0],
                        'password' => $account[1],
                        'recovery_email' => $account[2],
                        'notes' => count($account) >= 4 ? $account[3] : $request->notes,
                    ]);
                    $numberInserted++;
                }
                
            }

            return response()->json(['status' => 'success', 'numberInserted' => $numberInserted]);
        } catch (\Exception $ex) {
            return response()->json(['status' => 'error', 'message' => $ex->getMessage()]);
        }
    }
}
