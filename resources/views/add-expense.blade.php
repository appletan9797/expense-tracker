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
        <div>
            <h1>Expense for {{ now()->format('Y/m/d') }}</h1>

            <!--details, category, amount, currency-->
            <form method="POST" action="{{ url('/add-expense') }}">
                @csrf
                <label for="category">分類 ｜ Expense Category: </label>
                <select id="category" name="category">
                    <option selected="true" disabled="disabled">請選擇一個類別</option>
                    @if(!@empty($categories))
                        @foreach ($categories as $categoryItem)
                            <option value="{{ $categoryItem->category_id }}">{{ $categoryItem->category_name_cn }}</option>
                        @endforeach
                    @endif
                </select>
                <br>

                <label for="details">詳情 ｜ Expense Details: </label>
                <input type="text" name="details" id="details" value="{{ old('details') }}">
                <br>

                <label for="amount">數額 ｜ Amount Spent: </label>
                <input type="number" name="amount" id="amount" step="0.01" value="{{ old('amount') }}">
                <br>

                <label for="currency">幣值 ｜ Expense Currency: </label>
                <select id="currency" name="currency">
                    @if(!@empty($currencies))
                        @foreach ($currencies as $currency)
                            <option value="{{ $currency->currency_id }}">{{ $currency->currency_name }}</option>
                        @endforeach
                    @endif
                </select>
                <br>

                <label for="date">日期 ｜ Date: </label>
                <input type="date" name="date" id="date" value="{{ now()->format('Y-m-d') }}">
                <br><br>

                <button type="submit" id='submit'>儲存</button>
            </form>
        </div>
    </body>
</html>
