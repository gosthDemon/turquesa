<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta http-equiv="Expires" content="0" />
    <meta http-equiv="Last-Modified" content="0" />
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <link rel="stylesheet" href="{{ URL::asset('css/style.css') }} " />
    <link rel="stylesheet" href="{{ URL::asset('css/login.css') }} " />
    <title>Turquesa |</title>
</head>

<body>
    <section class="login-content-form">
        <div class="card-one">
            <div class="content">
                <header>
                    <div class="title-form">
                        <h1>Turquesa</h1>
                        <p class="small-paragraph">by Brenda Ayala</p>
                    </div>
                    <div class="logo-form">
                        <img src="" alt="" />
                    </div>
                </header>
                <div class="body">
                    <form>
                        <div class="row">
                            <!-- Email Input -->
                            <div class="form-group col-12">
                                <label for="email">Email:</label>
                                <input type="email" id="email" name="email" placeholder="Enter your email" />
                                <span class="error-message"></span>
                            </div>

                            <!-- Password Input -->
                            <div class="form-group col-12">
                                <label for="password">Password:</label>
                                <input type="password" id="password" name="password"
                                    placeholder="Enter your password" />
                                <span class="error-message"></span>
                            </div>

                            <div class="group-form">
                                <div class="error-form-input" id="error-form-input"></div>
                            </div>
                            <div class="group-form col-md-12">
                                <button type="button" class="btn btn-primary login-button" id="login-button">Log
                                    In</button>
                            </div>
                            <div class="group-form col-md-12">
                                <a href="#" class="small-paragraph" id="forgot_password"
                                    name="forgot_password">Forgot your password?</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-two"></div>
    </section>
    @vite('resources/ts/pages/auth/login.ts')
</body>

</html>
