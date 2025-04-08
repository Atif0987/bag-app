<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <a href="{{ route('welcome') }}"
               class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Home
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-bold mb-4">Your Orders</h3>

                    @if ($orders->isEmpty())
                        <p>You haven't placed any orders yet.</p>
                    @else
                        <table class="w-full text-left text-sm">
                            <thead class="border-b border-gray-600">
                                <tr>
                                    <th class="py-2">Product</th>
                                    <th class="py-2">Plan</th>
                                    <th class="py-2">Amount</th>
                                    <th class="py-2">Start Date</th>
                                    <th class="py-2">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $order)
                                    <tr class="border-b border-gray-700">
                                        <td class="py-2">{{ $order->product_name }}</td>
                                        <td class="py-2">{{ $order->subscription_plan }}</td>
                                        <td class="py-2">${{ number_format($order->amount, 2) }}</td>
                                        <td class="py-2">{{ $order->created_at->format('d M Y') }}</td>
                                        <td class="py-2">
                                            <!-- Always show the swap button -->
                                            <a href="{{ route('welcome', $order->id) }}"
                                               class="py-2">
                                                Swap Bag (+$20)
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
