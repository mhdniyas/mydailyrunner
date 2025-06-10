@if(Auth::check())
    <div class="bg-white p-4 m-4 rounded shadow">
        <h3 class="text-lg font-bold mb-4">Debug Information</h3>
        
        <div class="space-y-2">
            <p><strong>User ID:</strong> {{ Auth::user()->id }}</p>
            <p><strong>User Name:</strong> {{ Auth::user()->name }}</p>
            <p><strong>User Email:</strong> {{ Auth::user()->email }}</p>
            
            <div class="mt-4">
                <p><strong>User Shop Roles:</strong></p>
                @if(Auth::user()->shopUsers->count() > 0)
                    <ul class="list-disc list-inside ml-4">
                    @foreach(Auth::user()->shopUsers as $shopUser)
                        <li>Shop: {{ $shopUser->shop->name }} - Role: {{ $shopUser->role }}</li>
                    @endforeach
                    </ul>
                @else
                    <p class="text-red-500">No shop roles found</p>
                @endif
            </div>
            
            <div class="mt-4">
                <p><strong>Is Admin Check:</strong> 
                    @if(Auth::user()->isAdmin())
                        <span class="text-green-600">✓ YES</span>
                    @else
                        <span class="text-red-600">✗ NO</span>
                    @endif
                </p>
            </div>
            
            <div class="mt-4">
                <p><strong>Can Approve Subscriptions:</strong> 
                    @if(Auth::user()->canApproveSubscriptions())
                        <span class="text-green-600">✓ YES</span>
                    @else
                        <span class="text-red-600">✗ NO</span>
                    @endif
                </p>
            </div>
            
            <div class="mt-4">
                <p><strong>Subscription Menu Should Show:</strong> 
                    @if(Auth::user()->isAdmin())
                        <span class="text-green-600">✓ YES - Menu should be visible</span>
                    @else
                        <span class="text-red-600">✗ NO - Menu will be hidden</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
@else
    <div class="bg-red-100 p-4 m-4 rounded">
        <p>User not authenticated</p>
    </div>
@endif
