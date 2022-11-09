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
            max-width: 600px;
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
        tfoot .logo{
            display: inline-block;
            width: 183px;
            height: 32px;
        }
        tfoot {
            background: #445766;
        }
        tfoot .icons {
            display: inline-block;
            float: right;
            margin-top: 4px;
        }
        tfoot .icons div {
            display: inline-block;
            margin: 0 5px;
        }
        thead td, .firstbody td {
            display: block;
            margin: 0 auto;
            width: fit-content;
        }
        tfoot td {
            padding: 30px;
            width: fit-content;
        }
        .firstbody td {
            width: 100%;
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
            margin: 0 12px;
            position: relative;
        }
        thead td:last-child span:not(:last-child):after {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #677D8E;
            position: absolute;
            top: 5px;
            right: -12px;
        }
        .firstbody tr {
            max-width: 400px;
        }
        .firstbody td:first-child:not(.second) {
            margin: 20px auto 0;
            padding: 20px 0;
            text-align: center;
            border-top: 1px solid #899BA9;
            border-bottom: 1px solid #899BA9;
        }
        .firstbody td:first-child h3 {
            font-weight: 600;
            font-size: 26px;
            line-height: 36px;
            color: #111D13;
            margin: 0;
            position: relative;
text-align: center;
        }
        .firstbody td:first-child img{
            width: 24px;
            height: 24px;
            position: absolute;
            top: 5px;
            right: 17%;
            cursor: pointer;
        }
        .firstbody td:nth-child(2),.firstbody td:nth-child(3) {
            padding: 20px 0;
            margin: 0 auto;
            max-width: 400px;
        }
        .firstbody td:nth-child(2) {
            text-align: center;
        }
        .firstbody td:nth-child(2) span{
            font-weight: 400;
            display: inline-block;
            font-size: 16px;
            line-height: 24px;
            color: #111D13;
        }
        img {
            width: 100%;
            height: 100%;
        }
        .secondbody {
            height: 100px;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .secondbody:not(:last-child) {
            border-bottom: 2px dashed #899BA9;
        }
        .secondbody:last-child .description{
            padding-bottom: 10px;
            border-bottom: 1px solid #899BA9;
        }
        .secondbody .img {
            display: inline-block;
            width: 100px;
            height: 100px;
        }
        .secondbody .description {
            width: 280px;
            float: right;
            display: inline-block;
        }
        .secondbody h3 {
            margin: 0 0 10px;
            font-size: 16px;
            line-height: 20px;
        }
        .secondbody .count, .secondbody .price {
            margin: 5px 0;
        }
        .secondbody .count span:last-child,.secondbody .price span:last-child {
            float: right;
        }
        .secondbody span  {
            color: #677D8E;
        }
        .secondbody .price span:last-child {
            font-weight: 600;
            font-size: 14px;
            line-height: 16px;
            color: #111D13;
        }
        .thirdbody{
            padding-bottom: 40px;
        }
        .thirdbody .summa{
            width: 280px;
            float: right;
        }
        .thirdbody .summa div {
            display: inline-block;
            font-weight: 600;
            font-size: 20px;
            line-height: 24px;
        }
        .thirdbody .summa div:last-child {
            float: right;
        }
    </style>
</head>
<body class="mat-typography">
<table>
    <thead>
    <tr>
        <td>
            <img alt=""
              src="https://backend.gentcman.ga/storage/uploads/images/logo_black.png" />
        </td>
        <td>
          <span>
            Натуральная кожа
          </span>
            <span>
            Ручная работа
          </span>
            <span>
            Гарантия качества
          </span>
        </td>
    </tr>
    </thead>

    <tbody class="firstbody">
    <tr>
        <td>
            <h3>
                Ваш заказ {{$content['id'] }} <br>зарегистрирован!
                <img alt=""
                     src="https://backend.gentcman.ga/storage/uploads/images/copy_icon.png" />
            </h3>
        </td>
        <td>
          <span>
            Вы сделали заказ, мы с вами свяжемся в самые короткие сроки, ожидайте!
          </span>
        </td>
        <td>
            <h4>Вы заказали:</h4>
            @foreach ($content['products'] as $item)
                <div class="secondbody">
                    <div class="img">
                        <img alt=""
                             src="{{ $item['product']['banner_image'] }}"/>
                    </div>
                    <div class="description">
                        <h3>{{ $item['product']['name'] }}</h3>
                        <div class="summary">
                            <div class="count">
                                <span>Кол-во:</span>
                                <span>{{ $item['qty'] }} шт.</span>
                            </div>
                            <div class="price">
                                <span>Цена:</span>
                                <span>{{ $item['cost'] }} грн</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            <div class="thirdbody">
                <div class="summa">
                    <div class="text">
                        Итого к оплате:
                    </div>
                    <div class="sum">
                        {{ $content['total'] }} грн
                    </div>
                </div>
            </div>
        </td>
    </tr>
    </tbody>

    <tfoot>
    <tr>
        <td>
            <div class="logo">
                <img alt=""
                   src="https://backend.gentcman.ga/storage/uploads/images/logo_white.png" />
            </div>
            <div class="icons">
                <div class="tg">
                    <img alt="" src="https://backend.gentcman.ga/storage/uploads/images/Telegram.png" />
                </div>
                <div class="vb">
                    <img alt=""
                    src="https://backend.gentcman.ga/storage/uploads/images/Viber.png" />
                </div>
                <div class="fc">
		    <img alt="" src="https://backend.gentcman.ga/storage/uploads/images/Facebook.png" />
                </div>
                <div class="ig">
                    <img alt=""
                    src="https://backend.gentcman.ga/storage/uploads/images/Instagram.png" />
                </div>
            </div>
        </td>
    </tr>
    </tfoot>
</table>
</body>
</html>
