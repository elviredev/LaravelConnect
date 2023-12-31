<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <title>
        @isset($docTitle) {{ $docTitle }} | EwsConnect
        @else EwsConnect
        @endisset
    </title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous" />
    <script defer src="https://use.fontawesome.com/releases/v5.5.0/js/all.js" integrity="sha384-GqVMZRt5Gn7tB9D9q7ONtcp4gtHIUEW/yG7h98J7IpE3kpi+srfFyyB/04OV6pG0" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet" />
    <link rel="icon" href="/favicon-32x32.png">
    @vite(['resources/css/app.css'])
    @vite(['resources/js/app.js'])
</head>
<body>
<header class="header-bar mb-3">
    <div class="container d-flex flex-column flex-md-row align-items-center p-3">
        <h4 class="my-0 mr-md-auto font-weight-normal d-flex  align-items-center">
            <img class="mr-2" src="/favicon-32x32.png" alt="logo ews">
            <a href="/" class="text-white">EwsConnect</a>
        </h4>

        @auth
        <!-- Si user authentifié -->
        <div class="flex-row my-3 my-md-0">
            <a href="#" class="text-white mr-2 header-search-icon" title="Rechercher" data-toggle="tooltip" data-placement="bottom">
                <i class="fas fa-search"></i>
            </a>
            <span class="text-white mr-2 header-chat-icon" title="Chat" data-toggle="tooltip" data-placement="bottom">
                <i class="fas fa-comment"></i>
            </span>
            <a href="/profile/{{ auth()->user()->username }}" class="mr-2">
                <img title="Mon Profil" data-toggle="tooltip" data-placement="bottom" style="width: 32px; height: 32px; border-radius: 16px" src="{{ auth()->user()->avatar }}" />
            </a>

            <a class="btn btn-sm btn-submit mr-2" href="/create-post">Créer article</a>

            <form action="/logout" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-sm btn-secondary">Quitter</button>
            </form>
        </div>
        @else
        <!-- Si user NON authentifié - Formulaire de LOGIN -->
        <form action="/login" method="POST" class="mb-0 pt-2 pt-md-0">
                @csrf
                <div class="row align-items-center">
                    <div class="col-md mr-0 pr-md-0 mb-3 mb-md-0">
                        <input name="loginusername" class="form-control form-control-sm input-dark" type="text" placeholder="Pseudo" autocomplete="off" />
                    </div>
                    <div class="col-md mr-0 pr-md-0 mb-3 mb-md-0">
                        <input name="loginpassword" class="form-control form-control-sm input-dark" type="password" placeholder="Mot de passe" />
                    </div>
                    <div class="col-md-auto">
                        <button class="btn btn-primary btn-sm">Se connecter</button>
                    </div>
                </div>
            </form>
        @endauth

    </div>
</header>
<!-- header ends here -->

<!-- flash message succès -->
@if(session()->has('success'))
    <div class="container container--narrow">
        <div class="alert alert-success-green text-center">
            {{ session('success') }}
        </div>
    </div>
@endif
<!-- flash message echec -->
@if(session()->has('failure'))
    <div class="container container--narrow">
        <div class="alert alert-danger-pink text-center">
            {{ session('failure') }}
        </div>
    </div>
@endif

{{ $slot }}

<!-- footer begins -->
<footer class="footer border-top text-center small">
    <div class="container">
        Copyright &copy; {{ date('Y') }}
        <a href="/" class="color-white">elvirewebsite.</a>
        Tous droits réservés.
    </div>
</footer>

{{-- Chat --}}
@auth
    <div
        data-username="{{ auth()->user()->username }}"
        data-avatar="{{ auth()->user()->avatar }}"
        id="chat-wrapper"
        class="chat-wrapper shadow border-top border-left border-right"
    ></div>
@endauth

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
<script>
    $('[data-toggle="tooltip"]').tooltip()
</script>
</body>
</html>

