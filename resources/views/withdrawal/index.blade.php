<!-- resources/views/withdrawal/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Withdrawal</div>
                <div class="card-body">
                    <form method="POST" action="{{ url('/withdrawal') }}">
                        @csrf
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Withdraw</button>
                    </form>
                    <h3 class="mt-4">Withdrawal History</h3>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Amount</th>
                            <th>Fee</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($withdrawals as $withdrawal)
                            <tr>
                                <td>{{ $withdrawal->id }}</td>
                                <td>{{ $withdrawal->amount }}</td>
                                <td>{{ $withdrawal->fee }}</td>
                                <td>{{ $withdrawal->date }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
