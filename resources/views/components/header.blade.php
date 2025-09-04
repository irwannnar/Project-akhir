<header class="flex justify-center items-center shadow-md bg-gray-100 w-full-auto border h-16">
    <div>
        <div>
            <h1 class="text-black text-2xl">iMon Jasa</h1>
        </div>
        <div class="flex absolute top-0 right-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="border border-red-600 px-2 py-1 rounded text-red-600 mt-2 mr-1
                     hover:bg-red-600 hover:text-white active:scale-95">
                    Logout</button>
            </form>
        </div>
    </div>
</header>
