<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="container p-5 my-10 mx-auto bg-white shadow rounded">
        <div
        class="flex justify-end"
        >
            <a
                href="{{ route('users.create') }}"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            >Add User</a>
        </div>

        <table
            class="table-auto w-full text-left whitespace-no-wrap">

            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Position</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ( $users as $user )
                    <tr
                        class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3">{{ $user->name }}</td>
                        <td class="py-3">{{ $user->email }}</td>
                        <td class="py-3">{{ $user->position ?? 'N/A' }}</td>
                        <td class="py-3 flex gap-2">
                            <a href="{{ route('users.edit', $user->id) }}"
                                class="text-blue-500 hover:text-blue-700">Edit</a>
                            <form
                                action="{{ route('users.destroy', $user->id) }}"
                                method="POST"
                                class="inline">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="text-red-500 hover:text-red-700">Delete</button>
                            </form>
                        </td>

                    </tr>    

                @endforeach
            </tbody>

        </table>

    </div>
</x-app-layout>