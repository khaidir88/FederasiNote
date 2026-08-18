<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-6 bg-white shadow sm:rounded-lg">
                <div class="w-full">
                    @if (session('success'))
                    <div class="mb-4 p-4 rounded-lg bg-green-100 border border-green-300 text-green-700">
                        {{ session('success') }}
                    </div>
                    @endif
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 bg-white shadow sm:rounded-lg">
                <div class="w-full">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- 
            <div class="p-6 bg-white shadow sm:rounded-lg">
                <div class="w-full">
                    @include('profile.partials.delete-user-form')
                </div>
            </div> 
            -->
        </div>
    </div>
</x-app-layout>