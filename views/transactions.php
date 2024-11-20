<!DOCTYPE html>
<html>
<head>
    <title>Transactions</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        table tr th, table tr td {
            padding: 5px;
            border: 1px #eee solid;
        }

        tfoot tr th, tfoot tr td {
            font-size: 20px;
        }

        tfoot tr th {
            text-align: right;
        }
    </style>
</head>
<body>
<table>
    <thead>
    <tr>
        <th>Date</th>
        <th>Check #</th>
        <th>Description</th>
        <th>Amount</th>
    </tr>
    </thead>
    <tbody>


    <?php foreach ($transactions as $transaction): ?>
        <tr>
            <td><?php echo htmlspecialchars(date('M d Y',strtotime( $transaction["date"]))); ?></td>
            <td><?php echo htmlspecialchars($transaction["checkNumber"]); ?></td>
            <td><?php echo htmlspecialchars($transaction["description"]); ?></td>
            <?php $color = ($transaction["amount"] < 0) ? "red" : "green";?>
            <td style="color:<?php echo $color ?>"><?php echo htmlspecialchars(str_replace('$-', '-$',"$".number_format($transaction["amount"],2))); ?></td>
        </tr>
    <?php endforeach ?>

    </tbody>
    <tfoot>
    <tr>
        <th colspan="3">Total Income:</th>
        <td><?php echo htmlspecialchars("$".$totals["totalIncome"]) ?></td>
    </tr>
    <tr>
        <th colspan="3">Total Expense:</th>
        <td><?php echo htmlspecialchars(str_replace('-', '-$',$totals["totalExpense"])) ?></td>
    </tr>
    <tr>
        <th colspan="3">Net Total:</th>
        <td><?php echo htmlspecialchars("$".$totals["netTotal"]) ?></td>
    </tr>
    </tfoot>
</table>
</body>
</html>
