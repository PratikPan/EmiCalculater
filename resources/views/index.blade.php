<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.0/css/jquery.dataTables.css">

    <style type="text/css">
        body {
            background-color: lightgray;
        }

        .container {
            margin-top: 30px;
        }

        .box {
            background-color: white;
            padding: 30px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
            margin-top: 20px;
        }

    </style>
</head>

<body>
    <div class="container">
        <h1 class="text-center">Loan EMI Calculator</h1>
        <div class="box">
            <h3 class="text-center">Loan Details</h3>
            <form action="/" method="post">
                @csrf
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Loan Amount</label>
                            <input type="text" name="amount" class="form-control"
                                value="{{ isset($request['amount']) ? $request['amount'] : '' }}" required>
                        </div>
                        <div class="form-group">
                            <label>Interest (%)</label>
                            <input type="text" name="interest" class="form-control"
                                value="{{ isset($request['interest']) ? $request['interest'] : '' }}" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Period (Years)</label>
                            <input type="text" name="period" class="form-control"
                                value="{{ isset($request['period']) ? $request['period'] : '' }}" required>
                        </div>
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date"
                                value="{{ isset($request['start_date']) ? $request['start_date'] : '' }}"
                                class="form-control" required>
                        </div>
                    </div>
                </div>
                <input type="submit" class="btn btn-primary" value="Calculate">
            </form>
        </div>
        <div class="box">
            <div class="row">
                <div class="col-12">
                    <div class="alert alert-info" role="alert">
                        Monthly EMI Payment for the loan details amounts to
                        {{ isset($emi) ? number_format($emi, 2) : '*****' }}
                    </div>
                    <h3 class="text-center">EMI Schedule</h3>
                    <hr>
                    <table class="table table-striped table-bordered" id="emitable">
                        <thead>
                            <tr>
                                <th>SN</th>
                                <th>Payment Date</th>
                                <th>Monthly EMI</th>
                                <th>Interest Paid</th>
                                <th>Principal Paid</th>
                                <th>Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($emi))
                                @php
                                    $payment_date = $request['start_date'];
                                    $balance = $request['amount'];
                                @endphp
                                @for ($i = 1; $i <= $request['period'] * 12; $i++)
                                    @php
                                        // ---------------- count interest ------------------
                                        $interest = (($request['interest'] / 100) * $balance) / 12;
                                        // ----------- count paid principal amount -------------
                                        $principal = $emi - $interest;
                                        // ----------- count remaining amount -------------
                                        $balance = $balance - $principal;
                                        // ---- payment date --------
                                        $payment_date = date('d M-Y', strtotime('+1 month', strtotime($payment_date)));
                                    @endphp
                                    <tr>
                                        <td>{{ $i }}</td>
                                        <td>{{ $payment_date }}</td>
                                        <td>{{ number_format($emi, 2) }}</td>
                                        <td>{{ number_format($interest, 2) }}</td>
                                        <td>{{ number_format($principal, 2) }}</td>
                                        <td>{{ number_format($balance, 2) }}</td>
                                    </tr>
                                @endfor
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.0/js/jquery.dataTables.js"></script>
    <script>
        $(document).ready(function() {
            $('#emitable').DataTable();
        });
    </script>
</body>

</html>
