<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tickets</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 min-h-screen p-6">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-lg">
        <h1 class="text-2xl font-bold mb-6 text-center">Your Tickets</h1>
        <div class="mb-4">
            <a href="{{ route('tickets.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Create New Ticket</a>
        </div>
        @if (session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-md mb-4">
            {{ session('success') }}
        </div>
        @endif
        @if ($tickets->isEmpty())
        <p class="text-gray-600 text-center">No tickets found.</p>
        @else
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 text-left">Title</th>
                    <th class="p-3 text-left">Status</th>
                    <th class="p-3 text-left">Priority</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tickets as $ticket)
                <tr class="border-b">
                    <td class="p-3">{{ $ticket->title }}</td>
                    <td class="p-3">{{ $ticket->status }}</td>
                    <td class="p-3">{{ $ticket->priority ?? 'None' }}</td>
                    <td class="p-3">
                        <a href="{{ route('tickets.show', $ticket) }}" class="text-blue-500 hover:underline">View</a>
                        <a href="{{ route('tickets.edit', $ticket) }}" class="text-yellow-500 hover:underline ml-2">Edit</a>
                        <form action="{{ route('tickets.destroy', $ticket) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</body>

</html>