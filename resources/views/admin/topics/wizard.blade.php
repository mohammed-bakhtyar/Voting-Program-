@extends('layouts.main')

@section('content')
<div class="max-w-6xl mx-auto py-8 px-4 sm:px-6 lg:px-8" x-data="topicWizard()">
    <!-- Header Area -->
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Create New Poll</h1>
        <p class="mt-2 text-sm text-gray-600">Configure your new voting topic and its options.</p>
    </div>

    <div class="bg-white/80 backdrop-blur-xl shadow-xl shadow-blue-900/5 ring-1 ring-gray-900/5 rounded-2xl overflow-hidden flex flex-col md:flex-row min-h-[550px]">
        
        <!-- Left Sidebar (Steps Menu) -->
        <div class="w-full md:w-72 bg-gradient-to-b from-gray-50 to-white border-r border-gray-100 p-8 flex-shrink-0">
            <nav class="space-y-6">
                <template x-for="(title, idx) in stepTitles" :key="idx">
                    <div class="relative flex items-start group cursor-pointer" @click="if(idx <= Math.max(step, highestStepReached)) step = parseInt(idx)">
                        <!-- Decorative line connecting steps -->
                        <div x-show="parseInt(idx) < 4" class="absolute top-8 left-4 -ml-px h-full w-0.5" 
                             :class="step > parseInt(idx) ? 'bg-blue-600' : 'bg-gray-200'" aria-hidden="true"></div>
                        
                        <div class="relative flex items-center justify-center flex-shrink-0 w-8 h-8 rounded-full shadow-sm ring-4 ring-white transition-all duration-300"
                             :class="{
                                 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white transform scale-110': step === parseInt(idx),
                                 'bg-blue-600 text-white': step > parseInt(idx),
                                 'bg-gray-100 border border-gray-300 text-gray-500': step < parseInt(idx)
                             }">
                            <span x-show="step <= parseInt(idx)" class="text-sm font-bold" x-text="idx"></span>
                            <!-- Checkmark SVG -->
                            <svg x-show="step > parseInt(idx)" class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                            </svg>
                        </div>
                        <div class="ml-4 min-w-0 flex-1 pt-1.5">
                            <span class="text-sm font-semibold transition-colors duration-200"
                                  :class="step === parseInt(idx) ? 'text-blue-700' : (step > parseInt(idx) ? 'text-gray-900' : 'text-gray-500')"
                                  x-text="title"></span>
                        </div>
                    </div>
                </template>
            </nav>
        </div>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col bg-white">
            <div class="p-8 md:p-10 flex-1">
                
                <!-- STEP 1: Basic Info -->
                <div x-show="step === 1" x-transition.opacity.duration.300ms>
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900">Topic Details</h3>
                        <p class="text-sm text-gray-500 mt-1">Provide the fundamental information for your poll.</p>
                    </div>
                    <div class="space-y-6 max-w-2xl">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Topic Title <span class="text-red-500">*</span></label>
                            <input type="text" x-model="formData.title" class="block w-full rounded-xl border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-shadow" placeholder="e.g. Which framework do you prefer?">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                            <textarea x-model="formData.description" rows="4" class="block w-full rounded-xl border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6 transition-shadow" placeholder="Enter detailed description..."></textarea>
                        </div>

                        <div class="bg-gray-50 p-5 rounded-xl border border-gray-100 space-y-4">
                            <label class="flex items-start cursor-pointer group">
                                <div class="flex h-6 items-center">
                                    <input type="checkbox" x-model="formData.allow_multiple_votes" class="h-5 w-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-600 transition-colors">
                                </div>
                                <div class="ml-3 text-sm leading-6">
                                    <span class="font-medium text-gray-900 group-hover:text-blue-700 transition-colors">Allow multiple votes</span>
                                    <p class="text-gray-500">Users can select more than one option.</p>
                                </div>
                            </label>

                            <label class="flex items-start cursor-pointer group">
                                <div class="flex h-6 items-center">
                                    <input type="checkbox" x-model="formData.show_results_before_voting" class="h-5 w-5 rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-600 transition-colors">
                                </div>
                                <div class="ml-3 text-sm leading-6">
                                    <span class="font-medium text-gray-900 group-hover:text-blue-700 transition-colors">Show results upfront</span>
                                    <p class="text-gray-500">Display current percentages before the user votes.</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- STEP 2: Options -->
                <div x-show="step === 2" x-transition.opacity.duration.300ms style="display: none;">
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900">Voting Options</h3>
                        <p class="text-sm text-gray-500 mt-1">Add the choices users can vote for.</p>
                    </div>
                    <div class="space-y-4 max-w-2xl">
                        <template x-for="(option, index) in formData.options" :key="index">
                            <div class="flex items-center space-x-3 group">
                                <div class="flex-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-400 font-semibold text-sm" x-text="index + 1"></span>
                                    </div>
                                    <input type="text" x-model="option.text" class="block w-full rounded-xl border-0 py-3 pl-8 pr-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm transition-shadow" placeholder="Option text...">
                                </div>
                                <button @click="removeOption(index)" type="button" class="p-3 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-xl transition-colors focus:outline-none" x-show="formData.options.length > 2" title="Remove option">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </div>
                        </template>
                        
                        <button @click="addOption()" type="button" class="mt-6 flex items-center justify-center w-full py-3 border-2 border-dashed border-gray-300 rounded-xl text-sm font-semibold text-gray-600 hover:border-blue-500 hover:text-blue-600 hover:bg-blue-50/50 transition-all focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" x-show="formData.options.length < 10">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                            Add Another Option
                        </button>
                    </div>
                </div>

                <!-- STEP 3: Review -->
                <div x-show="step === 3" x-transition.opacity.duration.300ms style="display: none;">
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-gray-900">Review & Confirm</h3>
                        <p class="text-sm text-gray-500 mt-1">Double check your poll settings before publishing.</p>
                    </div>
                    
                    <div class="bg-gray-50 rounded-2xl p-6 ring-1 ring-inset ring-gray-200">
                        <dl class="divide-y divide-gray-200">
                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Topic Title</dt>
                                <dd class="mt-1 text-sm font-semibold text-gray-900 sm:col-span-2 sm:mt-0" x-text="formData.title || 'Not set'"></dd>
                            </div>
                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Description</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0" x-text="formData.description || 'No description'"></dd>
                            </div>
                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Options Count</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 font-medium bg-blue-100 text-blue-700 px-2 py-0.5 rounded inline-block" x-text="formData.options.length"></dd>
                            </div>
                            <div class="py-4 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Settings</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:col-span-2 sm:mt-0 space-y-2">
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="formData.allow_multiple_votes"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        <svg class="w-4 h-4 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!formData.allow_multiple_votes"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        Multiple Votes
                                    </div>
                                    <div class="flex items-center text-gray-600">
                                        <svg class="w-4 h-4 mr-2 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="formData.show_results_before_voting"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                        <svg class="w-4 h-4 mr-2 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!formData.show_results_before_voting"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                        Show Results Upfront
                                    </div>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- STEP 4: Publish -->
                <div x-show="step === 4" x-transition.opacity.duration.300ms style="display: none;">
                    <div class="text-center py-16">
                        <template x-if="isPublishing">
                            <div class="flex flex-col items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-12 w-12 text-blue-600 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <div class="text-gray-900 font-semibold text-xl">Publishing your poll...</div>
                                <p class="text-gray-500 mt-2 text-sm">Please do not close this window.</p>
                            </div>
                        </template>
                        <template x-if="!isPublishing && publishSuccess">
                            <div>
                                <div class="mx-auto flex h-24 w-24 items-center justify-center rounded-full bg-green-100 mb-6">
                                    <svg class="h-12 w-12 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                    </svg>
                                </div>
                                <h3 class="text-3xl font-extrabold text-gray-900">Poll is live!</h3>
                                <p class="text-gray-500 mt-2 text-lg">Your topic has been successfully published.</p>
                                <div class="mt-10 flex items-center justify-center space-x-4">
                                    <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 bg-white text-gray-700 font-medium rounded-xl shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors">Return to Dashboard</a>
                                    <a :href="publishedUrl" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium rounded-xl shadow-lg shadow-blue-500/30 hover:opacity-90 transition-opacity">View Poll</a>
                                </div>
                            </div>
                        </template>
                        <template x-if="!isPublishing && !publishSuccess">
                            <div>
                                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-red-100 mb-6">
                                    <svg class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div class="text-gray-900 font-bold mb-2 text-2xl">Publishing Failed</div>
                                <div x-text="errorMessage" class="text-sm text-red-600 mb-8 bg-red-50 py-3 px-4 rounded-xl inline-block font-medium"></div>
                                <br>
                                <button @click="submitPoll()" class="px-8 py-3 bg-gray-900 text-white rounded-xl font-medium hover:bg-gray-800 transition-colors shadow-lg">Try Again</button>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Navigation Footer -->
            <div class="bg-gray-50 border-t border-gray-100 p-6 flex justify-between items-center" x-show="step < 4">
                <button type="button" @click="step--" class="px-6 py-2.5 text-sm font-semibold text-gray-700 hover:text-gray-900 transition-colors" :class="{ 'invisible': step === 1 }">
                    Back
                </button>
                <div class="flex space-x-3">
                    <button type="button" @click="saveDraft()" class="px-6 py-2.5 bg-white text-gray-700 font-semibold text-sm rounded-xl shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 transition-colors" x-show="step < 3">
                        Save Draft
                    </button>
                    <button type="button" @click="nextStep()" class="px-8 py-2.5 bg-gray-900 text-white font-semibold text-sm rounded-xl shadow-md hover:bg-gray-800 transition-all active:scale-95" x-show="step < 3">
                        Continue
                    </button>
                    <button type="button" @click="submitPoll()" class="px-8 py-2.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/30 hover:opacity-90 transition-all active:scale-95" x-show="step === 3">
                        Publish Poll
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function topicWizard() {
    return {
        step: 1,
        highestStepReached: 1,
        stepTitles: {
            1: 'Basic Information',
            2: 'Voting Options',
            3: 'Review & Confirm',
            4: 'Publish Poll'
        },
        formData: {
            title: '',
            description: '',
            allow_multiple_votes: false,
            show_results_before_voting: true,
            status: 'active',
            options: [
                { text: '' },
                { text: '' }
            ]
        },
        isPublishing: false,
        publishSuccess: false,
        publishedUrl: '#',
        errorMessage: '',

        addOption() {
            if (this.formData.options.length < 10) {
                this.formData.options.push({ text: '' });
            }
        },
        removeOption(index) {
            if (this.formData.options.length > 2) {
                this.formData.options.splice(index, 1);
            }
        },
        nextStep() {
            if (this.step < 4) {
                // Validation before moving to next step
                if (this.step === 1 && !this.formData.title) {
                    alert('Please enter a Topic Title.');
                    return;
                }
                if (this.step === 2) {
                    const validOptions = this.formData.options.filter(o => o.text.trim() !== '');
                    if (validOptions.length < 2) {
                        alert('Please provide at least two valid options.');
                        return;
                    }
                }

                this.step++;
                if (this.step > this.highestStepReached) {
                    this.highestStepReached = this.step;
                }
            }
        },
        async saveDraft() {
            this.formData.status = 'draft';
            await this.submitPoll(true);
        },
        async submitPoll(isDraft = false) {
            if (!isDraft) {
                this.step = 4;
            }
            this.isPublishing = true;
            this.publishSuccess = false;
            
            const validOptions = this.formData.options.filter(o => o.text.trim() !== '').map(o => o.text);
            
            try {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                const response = await fetch('/admin/topics', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        title: this.formData.title,
                        description: this.formData.description,
                        allow_multiple_votes: this.formData.allow_multiple_votes,
                        show_results_before_voting: this.formData.show_results_before_voting,
                        status: this.formData.status,
                        options: validOptions,
                        opens_at: new Date().toISOString(),
                        closes_at: new Date(Date.now() + 365*24*60*60*1000).toISOString() // Default close 1 year
                    })
                });

                if (response.ok) {
                    const data = await response.json();
                    if (!isDraft) {
                        this.publishSuccess = true;
                        this.publishedUrl = `/topics/${data.id}`;
                    } else {
                        alert('Draft saved successfully!');
                        this.isPublishing = false;
                    }
                } else {
                    const errorData = await response.json();
                    this.errorMessage = errorData.message || 'Validation failed';
                    this.publishSuccess = false;
                }
            } catch (e) {
                this.errorMessage = e.message;
                this.publishSuccess = false;
            }
            this.isPublishing = false;
        }
    }
}
</script>
@endsection
