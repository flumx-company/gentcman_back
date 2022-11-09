<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>TeamProject</title>
    <base href="/">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0;
            max-width: 800px;
        }
        table {
            width: 100%;
            height: 700px;
        }
        table * {
            font-family: 'Inter', sans-serif;
        }
        thead {
            background: #FCFCFC;
        }
        thead img {
            width: 286px;
            height: 50px;
        }
        tfoot img {
            width: 183px;
            height: 32px;
        }
        tfoot {
            background: #445766;
        }
        thead td, tbody td {
            display: block;
            margin: 0 auto;
            width: fit-content;
        }
        tfoot td {
            padding: 30px;
            width: fit-content;
        }
        tbody td {
            width: 100%;
            max-width: 600px;
        }
        thead td:first-child {
            padding: 20px 0 5px;
        }
        thead td:last-child {
            padding: 5px 0 20px;
        }
        thead td:last-child span {
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            line-height: 15px;
            color: #677D8E;
        }
        tbody tr {
            max-width: 400px;
            margin: 0 auto;
        }
        tbody td:first-child {
            padding: 40px 0 20px;
        }
        tbody td:first-child h3 {
            font-weight: 600;
            font-size: 26px;
            line-height: 36px;
            color: #111D13;
            max-width: 300px;
            margin: 0;
        }
        tbody td:nth-child(2) {
            padding-bottom: 20px;
        }
        tbody td:nth-child(2) span{
            font-weight: 400;
            font-size: 14px;
            line-height: 21px;
            color: #111D13;
        }
        tbody td:nth-child(2) span span{
            text-decoration: underline;
        }
        tbody td:nth-child(3) {
            padding-bottom: 40px;
        }
        tbody td:nth-child(3) span{
            font-weight: 300;
            font-size: 13px;
            line-height: 15px;
            color: #677D8E;
            max-width: 200px;
        }
        tbody td:nth-child(4) {
            width: fit-content;
            margin: 0 auto;
            padding: 14px 20px;
            background: #677D8E;
            border-radius: 4px;
        }
        tbody td:nth-child(4) span{
            font-weight: 300;
            font-size: 16px;
            line-height: 20px;
            color: #FCFCFC;
            max-width: 200px;
        }
        tbody td:last-child {
            margin-top: 30px;
        }
        tbody td:last-child span{
            font-size: 14px;
            line-height: 21px;
            color: #677D8E;
        }
    </style>
</head>
<body class="mat-typography">
<table>
    <thead>
    <tr>
        <td>
            <img alt=""
                 src="https://backend.gentcman.ga/storage/uploads/images/logo_white.png" />
        </td>
        <td>
          <span>
            Натуральная кожа / Ручная работа / Гарантия качества
          </span>
        </td>
    </tr>
    </thead>

    <tbody>
    <tr>
        <td>
            <h3>
                Подтвердите действие в личном кабинете
            </h3>
        </td>
        <td>
          <span>
            Здравствуйте, {{ $content['name']}}!
            <br>Пароль к личному кабинету интернет-магазина
            <span>«Gentcman»</span>
              был изменен. Подвтердите совершенное действие, нажав на кнопку «Обновить пароль»
          </span>
        </td>
        <td>
          <span>
            Если вы не изменяли свои данные, то просто <br>проигнорируйте данное сообщение
          </span>
        </td>
        <td>
          <span>
             Обновить пароль
          </span>
        </td>
        <td>
            <a href="{{ $content['url'] }}">
                Обновить пароль
            </a>
        </td>
    </tr>
    </tbody>

    <tfoot>
    <tr>
        <td>
            <img alt=""
                 src="https://backend.gentcman.ga/storage/uploads/images/logo_white.png" />
        </td>
    </tr>
    </tfoot>
</table>
</body>
</html>
