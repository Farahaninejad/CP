<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-store" />
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdn.rtlcss.com/bootstrap/v4.0.0/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.0.0/css/bootstrap.min.css" integrity="sha384-P4uhUIGk/q1gaD/NdgkBIl3a6QywJjlsFJFk7SPRdruoGddvRVSwv5qFnvZ73cpz" crossorigin="anonymous">
    <link rel="stylesheet" href="css/style.css">
    <title>محاسبه سود مرکب</title>
    <script>
        function Number(Number) {
            Number+= '';
            Number= Number.replace(',', ''); Number= Number.replace(',', ''); Number= Number.replace(',', '');
            Number= Number.replace(',', ''); Number= Number.replace(',', ''); Number= Number.replace(',', '');
            x = Number.split('.');
            y = x[0];
            z= x.length > 1 ? '.' + x[1] : '';
            var rgx = /(\d+)(\d{3})/;
            while (rgx.test(y))
                y= y.replace(rgx, '$1' + ',' + '$2');
            return y+ z;
        }
    </script>
</head>
<body>
    <h1 class="header">محاسبه سود مرکب</h1>
    <div class="box-form">
    <form action="index.php" method="POST">
        <div class="input-group">
            <input type="text" required name="Amount" min="0" onkeyup="this.value=Number(this.value);" placeholder="مبلغ را به تومان وارد کنید" class="form-control input-border border-fix">
            <div class="input-group-prepend">
                <span class="input-group-text input-border">موجودی اولیه</span>
            </div>
        </div>
        <div class="input-group" style="margin-top: 10px">
            <input type="number" required name="InterestRate" style="width: 30%; margin-right: 0" placeholder="نرخ را بصورت اعشار یا صحیح وارد کنید" class="form-control input-border" min="0" max="100">
            <div class="input-group-append">
                <span class="input-group-text input-border" style="border-left: 0">%</span>
            </div>
            <select name="InterestRateType" class="custom-select input-border border-fix" style="margin-left: 5px" id="inputGroupSelect01">
                <option selected value="30">ماهانه</option>
                <option value="1">روزانه</option>
                <option value="365">سالانه</option>
            </select>
            <div class="input-group-prepend">
                <label class="input-group-text input-border" for="inputGroupSelect01">نزخ سود</label>
            </div>
        </div>
        <div class="input-group" style="margin-top: 10px">
            <input type="text" name="CapitalIncrease" style="width: 40%;" placeholder="مبلغ را به تومان وارد کنید" onkeyup="this.value=Number(this.value);" class="form-control input-border">
            <select name="CapitalIncreaseType" class="custom-select input-border border-fix" style="text-align: center; margin-left: 5px" aria-hidden="true" id="inputGroupSelect01">
                <option selected value="30">ماهانه</option>
                <option value="1">روزانه</option>
                <option value="365">سالانه</option>
            </select>
            <div class="input-group-prepend">
                <label class="input-group-text input-border" for="inputGroupSelect01">افزایش سرمایه</label>
            </div>
        </div>
        <div class="input-group" style="margin-top: 10px">
            <input type="number" name="AnnualInflation" style="width: 50%; margin-right: 0" placeholder="نرخ را بصورت اعشار یا صحیح وارد کنید" class="form-control input-border" min="0">
            <div class="input-group-append">
                <span class="input-group-text input-border" style="border-left: 0">%</span>
            </div>
            <div class="input-group-prepend">
                <span class="input-group-text input-border" style="margin-left: 5px">نرخ تورم سالیانه</span>
            </div>
        </div>
        <div class="input-group" style="margin-top: 10px">
            <select name="TimeType" class="custom-select input-border" style="margin-right: 5px" id="inputGroupSelect01">
                <option selected value="30">ماه</option>
                <option value="1">روز</option>
                <option value="365">سال</option>
            </select>
            <input type="number" required name="Time" style="width: 35%;" placeholder="به عدد وارد کنید" class="form-control input-border border-fix" min="0">
            <div class="input-group-prepend">
                <label class="input-group-text input-border" for="inputGroupSelect01">مدت زمان</label>
            </div>
        </div>
        <div style="margin-top:10px">
            <button type="reset" class="btn btn-danger">ریست</button>
            <button type="submit" class="btn btn-success">محاسبه</button>
        </div>
    </form>
    </div>
    <div class="box-form" style="margin-bottom: 50px; border: 1rem">
        <table class="table" style="border-collapse: unset">
            <thead>
            <tr>
                <th scope="col">موجودی</th>
                <th scope="col">زمان</th>
            </tr>
            </thead>
            <tbody>
                <?php
                error_reporting(E_ERROR | E_PARSE);
                require 'function.php';
                    $Amount = intval(filter($_POST['Amount']));
                    $InterestRate = $_POST['InterestRate'];
                    $InterestRateType = $_POST['InterestRateType'];
                    $CapitalIncrease = intval(filter($_POST['CapitalIncrease']));
                    $CapitalIncreaseType = $_POST['CapitalIncreaseType'];
                    $AnnualInflation = $_POST['AnnualInflation'];
                    $TimeType = $_POST['TimeType'];
                    $Time = $_POST['Time'];
                    $Balance = $Amount;
                    $TimeOver = null;
                    $Fund = null;
                    if ($Amount != null && $InterestRate != null && $Time != null) {
                        for ($i = 0; $i < intval(($TimeType * $Time) / $InterestRateType); $i++) {
                            if ($CapitalIncreaseType == 1 && $InterestRateType == 1) {
                                $Fund = "روزانه";
                                $Balance = intval($Balance + ($Balance * $InterestRate / 100) + $CapitalIncrease);
                            } elseif ($CapitalIncreaseType == 30 && $InterestRateType == 30) {
                                $Fund = "ماهیانه";
                                $Balance = intval($Balance + ($Balance * $InterestRate / 100) + $CapitalIncrease);
                            } elseif ($CapitalIncreaseType == 365 && $InterestRateType == 365) {
                                $Fund = "سالیانه";
                                $Balance = intval($Balance + ($Balance * $InterestRate / 100) + $CapitalIncrease);
                            } elseif ($CapitalIncreaseType == 1 && $InterestRateType == 30) {
                                $Fund = "روزانه";
                                $Balance = intval($Balance + ($Balance * $InterestRate / 100) + ($CapitalIncrease * 30));
                            } elseif ($CapitalIncreaseType == 1 && $InterestRateType == 365) {
                                $Fund = "روزانه";
                                $Balance = intval($Balance + ($Balance * $InterestRate / 100) + ($CapitalIncrease * 365));
                            } elseif ($CapitalIncreaseType == 30 && $InterestRateType == 1) {
                                $Fund = "ماهیانه";
                                $Balance = intval($Balance + ($Balance * $InterestRate / 100) + ($CapitalIncrease / 30));
                            } elseif ($CapitalIncreaseType == 30 && $InterestRateType == 365) {
                                $Fund = "ماهیانه";
                                $Balance = intval($Balance + ($Balance * $InterestRate / 100) + ($CapitalIncrease * 12));
                            } elseif ($CapitalIncreaseType == 365 && $InterestRateType == 1) {
                                $Fund = "سالیانه";
                                $Balance = intval($Balance + ($Balance * $InterestRate / 100) + ($CapitalIncrease / 365));
                            } elseif ($CapitalIncreaseType == 365 && $InterestRateType == 30) {
                                $Fund = "سالیانه";
                                $Balance = intval($Balance + ($Balance * $InterestRate / 100) + ($CapitalIncrease / 12));
                            }
                            $Count = $i + 1;
                            if ($InterestRateType == 1) {
                                $TimeOver = "روزانه";
                                $result = number_format(round($Balance - ($Balance * ($AnnualInflation / 365) / 100)));
                                echo FontNumaber("<tr><td>$result</td><td>بعد از $Count روز</td></tr>");
                            } elseif ($InterestRateType == 30) {
                                $TimeOver = "ماهیانه";
                                $result = number_format(round($Balance - ($Balance * ($AnnualInflation / 12) / 100)));
                                echo FontNumaber("<tr><td>$result</td><td>بعد از $Count ماه</td></tr>");
                            } elseif ($InterestRateType == 365) {
                                $TimeOver = "سالیانه";
                                $result = number_format(round($Balance - ($Balance * $AnnualInflation / 100)));
                                echo FontNumaber("<tr><td>$result</td><td>بعد از $Count سال</td></tr>");
                            }
                        }
                        $Final = null;
                        if ($TimeType == 1) {
                            $Final = "روز";
                        } elseif ($TimeType == 30) {
                            $Final = "ماه";
                        } elseif ($TimeType == 365) {
                            $Final = "سال";
                        }
                        if ($AnnualInflation == null)
                            $AnnualInflation = 0;
                        if ($CapitalIncrease == null)
                            $CapitalIncrease = 0;
                        echo FontNumaber("</tbody></table><div class=\"alert alert-dark\" role=\"alert\">نتیجه محاسبه با موجودی اولیه <strong>$Amount</strong> تومان با سود $TimeOver <strong>$InterestRate</strong>% و افزایش سرمایه $Fund <strong>$CapitalIncrease</strong> تومان با نرخ تورم سالیانه <strong>$AnnualInflation</strong>% به مدت <strong>$Time</strong> $Final</div>");
                    }

        ?>
    </div>
</body>
</html>
