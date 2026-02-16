<nav class="border-b border-border px-6">
    <div class="max-w-7xl mx-auto h-16 flex items-center justify-between">
        <div>
            <a href="">
                <img src="/images/logo.png" alt="idea" width="100" />
            </a>
        </div>

        <div class="flex gap-x-5 items-center">

            @auth
                <form action="/logout" method="POST">
                    @csrf
                    @method('DELETE')

                    <button type="submit" data-test="log-out-button">Logout</button>
                </form>
            @endauth

            @guest
                    <a href="/login">Sing in</a>
                    <a href="/register" class="btn">Register</a>
            @endguest


        </div>
    </div>
</nav>
