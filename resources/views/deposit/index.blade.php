<!-- resources/views/deposit/index.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Deposit</div>
                <div class="card-body">
                    <form method="POST" action="{{ url('/deposit') }}">
                        @csrf
                        <div class="form-group">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Deposit</button>
                    </form>
                    <h3 class="mt-4">Deposit History</h3>
                    <table class="table">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($deposits as $deposit)
                            <tr>
                                <td>{{ $deposit->id }}</td>
                                <td>{{ $deposit->amount }}</td>
                                <td>{{ $deposit->date }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
