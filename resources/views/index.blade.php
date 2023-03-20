<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Expense Tracker</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    </head>
    <body>
        <h1>所有消費</h1>
        <table>
            <tr>
                <th>詳情</th>
                <th>數額</th>
                <th>日期</th>
            </tr>

            @foreach ( $allExpense as $key => $value )
                <tr>
                    <td></td>
                    <td></td>
                    <td>{{ $key }}</td>
                </tr>
                @foreach ($value as $eachExpense)
                    <tr>
                        <td>{{ $eachExpense->expense_details }}</td>
                        <td>{{ $eachExpense->expense_amount }}</td>
                        <td></td>
                    </tr>
                @endforeach
            @endforeach
        </table>
    </body>
</html>
