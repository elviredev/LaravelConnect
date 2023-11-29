<x-layout>
   <div class="container py-md-5">
        <div class="row align-items-center">
            <div class="col-lg-7 py-3 py-md-5">
                <h1 class="display-3"><span class="color-primary">Souvenez-vous</span> de l'écriture ?</h1>
                <p class="lead text-muted">Vous en avez assez des tweets courts et des messages &ldquo;partagés&rdquo; impersonnels qui rappellent les transferts d’e-mails de la fin des années 90 ? Nous pensons que revenir à l’écriture est la clé pour profiter à nouveau d’Internet.</p>
            </div>
            <div class="col-lg-5 pl-lg-5 pb-3 py-lg-5">
                <form action="/register" method="POST" id="registration-form">
                    @csrf
                    <div class="form-group">
                        <label for="username-register" class="text-muted mb-1"><small>Nom d'utilisateur</small></label>
                        <input value="{{ old('username') }}" name="username" id="username-register" class="form-control" type="text" placeholder="Choisir un pseudo" autocomplete="off" />
                        @error('username')
                        <p class="m-0 alert alert-danger-pink shadow-sm">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="email-register" class="text-muted mb-1"><small>Email</small></label>
                        <input value="{{ old('email') }}" name="email" id="email-register" class="form-control" type="text" placeholder="vous@exemple.com" autocomplete="off" />
                        @error('email')
                        <p class="m-0 alert alert-danger-pink shadow-sm">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password-register" class="text-muted mb-1"><small>Mot de passe</small></label>
                        <input name="password" id="password-register" class="form-control" type="password" placeholder="Créer un mot de passe" />
                        @error('password')
                        <p class="m-0 alert alert-danger-pink shadow-sm">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password-register-confirm" class="text-muted mb-1"><small>Confirmer le mot de passe</small></label>
                        <input name="password_confirmation" id="password-register-confirm" class="form-control" type="password" placeholder="Même mot de passe" />
                        @error('password_confirmation')
                        <p class="m-0 alert alert-danger-pink shadow-sm">
                            {{ $message }}
                        </p>
                        @enderror
                    </div>

                    <button type="submit" class="py-3 mt-4 btn btn-lg btn-submit btn-block">Inscrivez-vous à EwsConnect</button>
                </form>
            </div>
        </div>
    </div>
</x-layout>
