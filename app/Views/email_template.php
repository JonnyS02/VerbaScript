<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Einladung</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: rgb(32, 39, 51);
            color: #ffffff;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .email-container {
            background-color: rgb(33, 37, 41);
            border: 1px solid #424549;
            padding: 20px;
            border-radius: 8px;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .email-title {
            font-size: 24px;
            color: #00bbff;
            margin-bottom: 10px;
            text-align: center;
        }

        .email-text {
            font-size: 16px;
            line-height: 1.5;
            color: #cccccc;
            margin-bottom: 20px;
            text-align: center;
        }

        .email-button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #0d6efb;
            color: #ffffff;
            text-decoration: none;
            font-size: 16px;
            text-align: center;
            border-radius: 20px;
            font-weight: bold;
        }

        .email-footer {
            font-size: 12px;
            color: #666666;
            text-align: center;
            margin-top: 20px;
        }

        .email-footer a {
            color: #00aaff;
            text-decoration: none;
        }
    </style>
</head>
<body>
<div class="email-container">
    <h1 class="email-title"><?= $headline ?></h1>
    <p class="email-text">
        <?= $message ?>
    </p>
    <div style="text-align: center;">
        <a href="<?= base_url(index_page()) . $link ?>" class="email-button"><span
                    style="color: white"><?= $action ?></span></a>
    </div>
    <div class="email-footer">
        <p>Wenn Sie diese E-Mail irrtümlich erhalten haben, ignorieren Sie sie bitte.</p>
        <p>© 2024 Jonathan Stengl | <a href="<?= base_url(index_page()) ?>">VerbaScript</a></p>
    </div>
</div>
</body>
</html>
