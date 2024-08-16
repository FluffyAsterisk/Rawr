<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/base.css">
    <link rel="shortcut icon" href="data:image/x-icon;," type="image/x-icon"> 
    <title>Help desk</title>
</head>
<body>
    <div class="wrapper | text-end">
        <header class="bg-primary-400 pd-20">
            <div class="container">
                <ul>
                    <li><a href="#">Link</a></li>
                    <li><a href="#">Page</a></li>
                    <li><a href="#">Another Page</a></li>
                </ul>
            </div>
        </header>

        <main>

<section class="error">
    <div class="container | text-start pd-24">
        <h1 class="fs-800">Error <?php echo $error_code ?></h1>
        <p class="fs-400"><?php echo $error_message ?></p>
    </div>
</section>

        </main>

        <footer class="pd-20 | bg-primary-400">
            <div class="container">
                <ul>
                    <li><a href="#">Void</a></li>
                    <li><a href="#">Void</a></li>
                    <li><a href="#">Void</a></li>
                    <li><a href="#">Void</a></li>
                </ul>
            </div>
        </footer>
    </div>
</body>
</html>
