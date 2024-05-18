<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BankingController extends Controller
{
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'account_type' => 'required|string|in:Individual,Business',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = User::create([
            'name' => $request->name,
            'account_type' => $request->account_type,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'balance' => 0,
        ]);
        // Log in the user
        Auth::login($user);

        // Redirect to the home page after successful registration
        return redirect('/home');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return response()->json(Auth::user(), 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    public function showTransactions()
    {
        $transactions = Transaction::where('user_id', Auth::id())->get();
        $balance = Auth::user()->balance;

        return view('transactions.index', compact('transactions', 'balance'));
    }

    public function showDeposits()
    {
        $deposits = Transaction::where('user_id', Auth::id())
            ->where('transaction_type', 'deposit')
                ->get();

        return view('deposit.index', compact('deposits'));
    }

    public function deposit(Request $request)
    {
        $userId =  Auth::id();
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $user = User::findOrFail($userId);
        $user->balance += $request->amount;
        $user->save();

        Transaction::create([
            'user_id' => $user->id,
            'transaction_type' => 'deposit',
            'amount' => $request->amount,
            'date' => now(),
        ]);

        return redirect('/deposit')->with('status', 'Deposit successful!');
    }

    public function showWithdrawals()
    {
        $withdrawals = Transaction::where('user_id', Auth::id())
            ->where('transaction_type', 'withdrawal')
            ->get();

        return view('withdrawal.index', compact('withdrawals'));
    }

    public function withdrawal(Request $request)
    {
        $userId =  Auth::id();
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
        ]);

        $user = User::findOrFail($userId);
        $amount = $request->amount;

        // Free withdrawal conditions for Individual accounts
        if ($user->account_type == 'Individual') {
            $today = Carbon::now();
            $todayDate = $today->format('Y-m-d H:i:s');
            $firstOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d h:i:s');
            $withdrawalsThisMonth = Transaction::where('user_id', $user->id)
                ->where('transaction_type', 'withdrawal')
                ->whereBetween('date', [$firstOfMonth, $todayDate])
                ->sum('amount');
            $fee = 0;
            if ($today->isFriday() || $withdrawalsThisMonth + $amount <= 5000) {
                $fee = 0;
            } else {
                $fee = max(0, ($amount - 1000) * 0.015 / 100);
            }
        } else {
            // Business accounts
            $totalWithdrawals = Transaction::where('user_id', $user->id)
                ->where('transaction_type', 'withdrawal')
                ->sum('amount');

            if ($totalWithdrawals >= 50000) {
                $fee = $amount * 0.015 / 100;
            } else {
                $fee = $amount * 0.025 / 100;
            }
        }

        if ($user->balance < $amount + $fee) {
            return response()->json(['message' => 'Insufficient balance'], 400);
        }

        $user->balance -= ($amount + $fee);

        $user->save();

        Transaction::create([
            'user_id' => $user->id,
            'transaction_type' => 'withdrawal',
            'amount' => $amount,
            'fee' => $fee,
            'date' => now(),
        ]);

        return redirect('/withdrawal')->with('status', 'Withdrawal successful!');
    }
}
