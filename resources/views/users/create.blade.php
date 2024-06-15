<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="container mx-auto p-5 my-10 bg-white shadow">

        <form action="{{ route('users.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" id="name" autocomplete="name" class="mt-1 focus
                    :ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm
                    border-gray-300 rounded-md">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" autocomplete="email" class="mt-1 focus
                    :ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm
                    border-gray-300 rounded-md">
                </div>
                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                    <input type="text" name="position" id="position" autocomplete="position" class="mt-1 focus
                    :ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm
                    border-gray-300 rounded-md">
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" autocomplete="password" class="mt-1 focus
                    :ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm
                    border-gray-300 rounded-md">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" autocomplete="password_confirmation" class="mt-1 focus
                    :ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm
                    border-gray-300 rounded-md">
                </div>
                <div>
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Add User</button>
                </div>


    </div>

</x-app-layout>
