@extends('layouts.app')

@section('title', 'Pay Order')

@section('content')
<main class="h-full overflow-y-auto">
    <div class="container mx-auto">
        <div class="grid w-full gap-5 px-10 mx-auto md:grid-cols-12">
            <div class="col-span-12">
                <h2 class="mt-8 mb-1 text-2xl font-semibold text-gray-700">
                    Pay Order
                </h2>
            </div>
        </div>
    </div>

    <section class="container px-6 mx-auto mt-5">
        <div class="grid gap-5 md:grid-cols-12">
            <main class="col-span-12 p-4 md:pt-0">
                <div class="px-6 py-2 mt-2 bg-white rounded-xl">
                    <form id="payment-form" method="post" action="">
                        @csrf
                        <input type="hidden" name="result_data" id="result-data" value="">
                        <table class="w-full" aria-label="Table">
                            <thead>
                                <tr class="text-sm font-normal text-left text-gray-900 border-b border-b-gray-600">
                                    <th class="py-4" scope="">Freelancer Name</th>
                                    <th class="py-4" scope="">Service Details</th>
                                    <th class="py-4" scope="">Service Price</th>
                                    <th class="py-4" scope="">Service Deadline</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">
                                <tr class="mb-10 text-gray-700">
                                    <td class="px-1 py-5 text-sm w-2/8">
                                        <div class="flex items-center text-sm">
                                            <div class="relative w-10 h-10 mr-3 rounded-full md:block">
                                                @if ($order->user_freelancer->detail_user->photo != NULL)
                                                <img class="object-cover w-full h-full rounded-full" src="{{ url(Storage::url($order->user_freelancer->detail_user->photo)) }}" alt="photo freelancer" loading="lazy" />
                                                @else
                                                <svg class="object-cover w-full h-full rounded text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                @endif
                                                <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
                                            </div>
                                            <div>
                                                <p class="font-medium text-black">{{ $order->user_freelancer->name ?? '' }}</p>
                                                <p class="text-sm text-gray-400">{{ $order->user_freelancer->detail_user->role ?? '' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="w-2/6 px-1 py-5">
                                        <div class="flex items-center text-sm">
                                            <div class="relative w-10 h-10 mr-3 rounded-full md:block">
                                                @if ($order->service->thumbnail_service[0]->thumbnail != NULL)
                                                <img class="object-cover w-full h-full rounded" src="{{ url(Storage::url($order->service->thumbnail_service[0]->thumbnail)) }}" alt="photo freelancer" loading="lazy" />
                                                @else
                                                <svg class="object-cover w-full h-full rounded text-gray-300" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 20.993V24H0v-2.996A14.977 14.977 0 0112.004 15c4.904 0 9.26 2.354 11.996 5.993zM16.002 8.999a4 4 0 11-8 0 4 4 0 018 0z" />
                                                </svg>
                                                @endif
                                                <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
                                            </div>
                                            <div>
                                                <p class="font-medium text-black">
                                                    {{ $order->service->title ?? '' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-1 py-5 text-sm">
                                        {{ 'Rp '.number_format($order->service->price) ?? '' }}
                                    </td>
                                    <td class="px-1 py-5 text-xs text-red-500">
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" class="inline mb-1">
                                            <path d="M7.0002 12.8332C10.2219 12.8332 12.8335 10.2215 12.8335 6.99984C12.8335 3.77818 10.2219 1.1665 7.0002 1.1665C3.77854 1.1665 1.16687 3.77818 1.16687 6.99984C1.16687 10.2215 3.77854 12.8332 7.0002 12.8332Z" stroke="#F26E6E" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M7 3.5V7L9.33333 8.16667" stroke="#F26E6E" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        {{ (strtotime($order->expired) - strtotime(date('Y-m-d'))) / 86400 ?? '' }} days left
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <button id="pay-button" class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-green-600 border border-transparent rounded-lg shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Bayar Sekarang
                        </button>
                    </form>
                </div>
            </main>
        </div>
    </section>
</main>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function(event) {
        event.preventDefault();
        snap.pay('{{ $snapToken }}', {
            onSuccess: function(result) {
                alert("Payment successful!");
                window.location.href = `http://127.0.0.1:8000/member/request/{{ $order->id }}`;
            },
            onPending: function(result) {
                alert("Payment is pending!");
                window.location.href = `http://127.0.0.1:8000/member/request/{{ $order->id }}`;
            },
            onError: function(result) {
                alert("Payment failed!");
                window.location.href = `http://127.0.0.1:8000/member/request/{{ $order->id }}`;
            }
        });
    };
</script>

@endsection