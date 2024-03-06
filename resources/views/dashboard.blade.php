<x-app-layout>

    <div class="flex justify-center">

        <div class="py-5">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h1 class="text-3xl mb-4">Playground: Guess the number of 4 unique digits</h1>

                        <div class="py-5 flex justify-center">
                            <form action="{{ route('gameSession.store') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-blue-500 text-white px-10 py-4 rounded uppercase">Start Game</button>
                            </form>
                        </div>

                        <div class="py-5 flex justify-center">
                            <form action="{{ route('guessSecretNumber') }}" method="POST">
                                @csrf
                                <div class="flex items-center w-full mb-5">
                                    <input type="text"
                                           name="guessNumber"
                                           class="block border-0 border-b-2 border-gray-300 focus:ring-0 mr-7 w-80"
                                           placeholder="Please enter a 4-digit number e.g:1234"
                                           maxlength="4"
                                           pattern="^(?!.*(.).*\1)[0-9]{1,4}$"
                                           required
                                    >
                                    <button type="submit" class="bg-green-500 text-white px-10 py-4 rounded uppercase">Guess</button>
                                </div>
                            </form>
                        </div>

                        @if (session('result'))
                            <div class="py-5 flex justify-center">
                                <table class="p-5 w-full bg-white shadow text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="px-6 py-3">Guess number</th>
                                            <th scope="col" class="px-6 py-3">Bulls</th>
                                            <th scope="col" class="px-6 py-3">Cows</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                            <td class="px-6 py-4">{{ session('result')['guessNumber'] }}</td>
                                            <td class="px-6 py-4">{{ session('result')['bulls'] }}</td>
                                            <td class="px-6 py-4">{{ session('result')['cows'] }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif

                        @if(session('successGameMessage'))
                            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                                <p class="font-bold uppercase text-center">{{ session('successGameMessage') }}</p>
                            </div>
                        @endif

                        @if(session('failureGameMessage'))
                            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4" role="alert">
                                <p class="font-bold uppercase text-center">{{ session('failureGameMessage') }}</p>
                            </div>
                        @endif

                        <div class="py-5 flex justify-center">
                            <form action="{{ route('quitGame') }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white px-10 py-4 rounded uppercase">Quit Game</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="py-5">
            <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h1 class="text-3xl mb-4 uppercase">Top 10</h1>
                        <table class="p-5 w-full bg-white shadow text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-200 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">Rank #</th>
                                <th scope="col" class="px-6 py-3">Name</th>
                                <th scope="col" class="px-6 py-3">Won Games</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $rank = 1;
                            @endphp

                            @foreach($top10ByHighScoreUsers as $user)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4">{{ $rank ++ }}</td>
                                    <td class="px-6 py-4 max-w-xs break-all">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->won_games }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>

</x-app-layout>
